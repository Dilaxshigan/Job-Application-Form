<x-mail::message>
# Hello, {{ $applicantName }}!

We wanted to inform you that your CV is currently under review. Our team will get back to you soon.

Thank you for applying!

<x-mail::button :url="'#'">
View Application Status
</x-mail::button>

Best regards,<br>
The Hiring Team
</x-mail::message>
