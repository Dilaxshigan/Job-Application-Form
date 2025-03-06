<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser;

class JobApplicationController extends Controller
{
    public function showForm()
    {
        return view('job_form');
    }
    public function submitForm(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:applicants',
            'phone' => 'required|string',
            'cv' => 'required|file|mimes:pdf,docx|max:2048',
        ]);

        $data = new Applicant;

        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;

        if ($request->hasFile("cv")) {

            $cvFileName = time() . '_' . $request->file("cv")->getClientOriginalName();

            $cvPath = $request->file("cv")->storeAs(
                'uploads', 
                $cvFileName, 
                [
                    "disk" => "s3",
                    "visibility" => "public" 
                ]
            );

            $cvPublicUrl = Storage::disk('s3')->url($cvPath);

            $data->cv_url = $cvPublicUrl;

            $cvText = $this->parseCv($request->file("cv"));
            $sections = $this->extractSections($cvText);

            $data->education = $sections['education'];
            $data->qualifications = $sections['qualifications'];
            $data->projects = $sections['projects'];
            $data->personal_info = $sections['personal_info'];

            $this->appendToGoogleSheet([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'cv_url' => $cvPublicUrl,
                'education' => $sections['education'],
                'qualifications' => $sections['qualifications'],
                'projects' => $sections['projects'],
                'personal_info' => $sections['personal_info'],
            ]);
        }

        $data->save();

        return redirect()->route('cv.data', ['id' => $data->id])->with('success', 'Your application has been submitted successfully!');
    }
    private function parsePdf($filePath)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        return $pdf->getText();
    }
    private function parseDocx($filePath)
    {
        $phpWord = IOFactory::load($filePath);
        $text = '';
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . ' ';
                }
            }
        }
        return $text;
    }
    private function parseCv($file)
    {
        if ($file->extension() === 'pdf') {
            return $this->parsePdf($file->path());
        } elseif ($file->extension() === 'docx') {
            return $this->parseDocx($file->path());
        }
        return '';
    }
    private function extractSections($text)
    {
        $sections = [
            'education' => '',
            'qualifications' => '',
            'projects' => '',
            'personal_info' => '',
        ];

        // Extract Education (customize based on your CV format)
        if (preg_match('/Education:(.*?)(Qualifications:|Projects:|$)/s', $text, $matches)) {
            $sections['education'] = trim($matches[1]);
        }

        // Extract Qualifications
        if (preg_match('/Qualifications:(.*?)(Education:|Projects:|$)/s', $text, $matches)) {
            $sections['qualifications'] = trim($matches[1]);
        }

        // Extract Projects
        if (preg_match('/Projects:(.*?)(Education:|Qualifications:|$)/s', $text, $matches)) {
            $sections['projects'] = trim($matches[1]);
        }

        // Extract Personal Info (Name, Contact Details)
        if (preg_match('/(Name|Contact):(.*?)(Education:|Qualifications:|Projects:|$)/s', $text, $matches)) {
            $sections['personal_info'] = trim($matches[2]);
        }

        return $sections;
    }
    private function appendToGoogleSheet($data)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
    
        $spreadsheetId = '1A90CV2deupitiLZ7cUUU5CMuzmOwBAgZrc48g4Chlik'; 
        $range = 'Sheet1'; 
    
        $values = [
            [
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['cv_url'],
                $data['education'],
                $data['qualifications'],
                $data['projects'],
                $data['personal_info'],
            ],
        ];
    
        // Append the data to the sheet
        $body = new Sheets\ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'RAW'];
        $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
    }
    public function showCvData($id)
    {
        $cvData = Applicant::findOrFail($id);
        return view('cv_data', compact('cvData'));
    }

}
