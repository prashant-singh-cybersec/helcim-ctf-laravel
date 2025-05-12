<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeatureRequest;
use Illuminate\Support\Facades\Auth;

class FeatureRequestController extends Controller
{
    public function create()
{
    $requests = FeatureRequest::orderBy('created_at')->get();

    // Flag detection for CSP bypass via callback in details
    $flagTriggered = false;

    foreach ($requests as $req) {
        if ($req->details && preg_match('/<script\s+src=["\']https:\/\/accounts\.google\.com\/o\/oauth2\/revoke\?callback=.*?["\']><\/script>/i', $req->details)) {
            $flagTriggered = true;
            break;
        }
    }

    $response = response()
        ->view('feature_request.create', compact('requests'))
        ->header('Content-Security-Policy', "default-src 'none'; script-src https://*.google.com https://cdn.tailwindcss.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline'; img-src 'self'; connect-src 'none'; frame-ancestors 'none';");

    if ($flagTriggered) {
        $response->header('X-CTF-Flag', 'helcim{csp_bypass_google_callback}');
    }

    return $response;
}

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'details' => 'nullable|string',
        'attachment' => 'nullable|file|mimes:txt|max:2048', // 2MB max
    ]);

    $data = [
        'user_id' => auth()->id(),
        'title' => $request->input('title'),
        'details' => $request->input('details'),
    ];

    if ($request->hasFile('attachment')) {
        $file = $request->file('attachment');
        $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path('attachments');

        // Ensure the directory exists
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $filename);

        // Save relative path for use in Blade
        $data['attachment'] = 'attachments/' . $filename;
    }

    FeatureRequest::create($data);

    return back()->with('success', '🎉 Feature request submitted successfully!');
}


public function download(Request $request)
{
    // 1. Mimic native PHP param resolution
    $downloadFileName = isset($_REQUEST['filename']) ? $_REQUEST['filename'] : ($_GET['fileName'] ?? null);
    $downloadFileName = urldecode($downloadFileName); // Handle spaces

    $token = $request->get('token');
    if (!$token || !$downloadFileName) {
        echo "Missing token or filename.";
        exit;
    }

    // 2. Try to find file based on token from attachments
    $attachmentsDir = public_path('attachments');
    $matchingFiles = glob($attachmentsDir . '/' . $token . '.*');
    $fullPath = (string) ($matchingFiles[0] ?? $token);


    if (!file_exists($fullPath)) {
        echo "Invalid token or file not found.";
        exit;
    }


    // 3. Copy file contents into /tmp (mimicking staging or caching)
    $fileContents = file_get_contents($fullPath);
    $tmpPath = '/tmp/' . $token;
    //dd($fullPath);

    file_put_contents($tmpPath, $fileContents);

    // 4. Send headers and stream file
    header('Content-Disposition: attachment; filename="' . $downloadFileName . '"');
    header('Content-Length: ' . filesize($tmpPath));
    readfile($tmpPath);
  //  unlink($tmpPath); // Clean up temp file

    exit;
}










}
