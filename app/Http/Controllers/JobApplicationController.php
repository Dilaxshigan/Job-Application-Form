<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    public function showForm()
    {
        return view('job_form');
    }
    public function submitForm(Request $request)
    {
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
        }
    
        $data->save();
    
        return redirect()->back()->with('success', 'Your application has been submitted successfully!')->with('cv_url', $cvPublicUrl);
    }
}
