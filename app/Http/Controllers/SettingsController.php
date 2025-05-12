<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
class SettingsController extends Controller
{
    //
    public function renderSettingsPage()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'You must be logged in to access this page.');
        }

        return view('user.update_settings', [
            'user' => $user,
        ]);
    }


    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized access'], 401);
        }

        try {
            $filePath = null;
            $content = '';
            $fileExtension = null;

            if ($request->has('mobileNumber')) {
                $user->mobile_number = $request->input('mobileNumber');
            }

            $logoFile = $request->file('logo');
            $imageUrl = $request->input('imageUrl');

            if ($logoFile || $imageUrl) {
                $uploadDirectory = public_path('uploads/user_logos');
                if (!File::isDirectory($uploadDirectory)) {
                    File::makeDirectory($uploadDirectory, 0755, true);
                }

                if ($logoFile) {
                    // Extension check
                    $fileExtension = strtolower($logoFile->getClientOriginalExtension());
                    $allowedExtensions = ['jpg', 'png', 'gif', 'html'];
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        return response()->json(['error' => 'Invalid file extension.'], 400);
                    }

                    // Magic bytes validation
                    $fileStream = fopen($logoFile->getRealPath(), 'rb');
                    $magicBytes = fread($fileStream, 8);
                    fclose($fileStream);

                    $allowedMagicBytes = [
                        'jpg' => "\xFF\xD8\xFF",
                        'png' => "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A",
                        'gif' => "\x47\x49\x46\x38"

                    ];

                    $valid = collect($allowedMagicBytes)->contains(function ($sig) use ($magicBytes) {
                        return str_starts_with($magicBytes, $sig);
                    });

                    if (!$valid) {
                        return response()->json(['error' => 'Invalid file type.'], 400);
                    }

                    // HTML payload check
                    if ($fileExtension === 'html') {
                        $content = file_get_contents($logoFile->getRealPath());
                        if (!str_contains($content, '<h1>give me the flag</h1>')) {
                            return response()->json(['error' => 'Did you forget to provide the payload?'], 400);
                        }
                    }

                    $filename = $user->id . '-' . Str::uuid() . '.' . $fileExtension;
                    $logoFile->move($uploadDirectory, $filename);
                    $filePath = '/uploads/user_logos/' . $filename;
                } elseif ($imageUrl) {
                    $fileExtension = strtolower(pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
                    $allowedUrlExt = ['jpg', 'png', 'gif', 'svg', 'txt'];
                    if (!in_array($fileExtension, $allowedUrlExt)) {
                        return response()->json(['error' => 'Invalid image URL extension.'], 400);
                    }

                    $context = stream_context_create([
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        ]
                    ]);

                    if ($imageUrl === 'http://127.0.0.1/admin/protected/flag.txt') {
                        $imageUrl = str_replace('127.0.0.1', 'web', $imageUrl);
                    }
                    $imageContent = @file_get_contents($imageUrl, false, $context);
                   // dd($imageContent);
                    if (!$imageContent) {
                        return response()->json(['error' => 'Failed to fetch image from the provided URL.'], 400);
                    }

                    $filename = $user->id . '-' . Str::uuid() . '.' . $fileExtension;
                    File::put($uploadDirectory . '/' . $filename, $imageContent);
                    $filePath = '/uploads/user_logos/' . $filename;
                }

                $user->image_path = $filePath;
            }

            $user->save();

            $response = response()->json([
                'success' => true,
                'message' => 'Settings updated successfully!',
                'filePath' => $filePath,
            ]);

            // Return flag if HTML upload with payload is successful
            if ($logoFile && $fileExtension === 'html' && str_contains($content, '<h1>give me the flag</h1>')) {
                $response->header('Flag', 'helcim{Definitely_Some_Magic_In_The_MAGIC_BYTES!}');
            }

            return $response;

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




    //controller logic to render user role updation settings

    public function index()
    {
        $organization = Auth::user()->organization;

        $organizationUsers = User::where('organization_id', $organization->id)->get();

        return view('user.user_role_settings', compact('organizationUsers'));
    }





    //controller logic for role updation.
    public function editUserRoles(Request $request)
    {
        $userId = $request->query('userId');
        $currentUser = Auth::user();

        if (!$userId || $userId !== (string) $currentUser->id) {
            return response()->json([
                'error' => 'You can not update roles of other users!'
            ], 403);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $roleParam = $request->input('role');
        $allowedRoles = ['ROLE_USER', 'ROLE_ADMIN'];
        $finalRole = null;

        // ✅ Detect pollution: array of roles
        if (is_array($roleParam)) {
            // Pick the last one
            $finalRole = end($roleParam);
        } else {
            $finalRole = $roleParam;
        }

        // ✅ Reject if role is not strictly one of the allowed roles
        if (!in_array($finalRole, $allowedRoles, true)) {
            return response()->json([
                'error' => 'Invalid role. Allowed roles: ROLE_USER, ROLE_ADMIN.'
            ], 400);
        }

        // ✅ Block direct escalation from ROLE_USER to ROLE_ADMIN unless polluted
        if (
            $finalRole === 'ROLE_ADMIN' &&
            !is_array($roleParam) &&
            in_array('ROLE_USER', (array) $currentUser->roles, true)
        ) {
            return response()->json([
                'error' => 'Users with ROLE_USER cannot escalate directly to ROLE_ADMIN. Can you bypass this protection?'
            ], 403);
        }

        // ✅ Update role in DB — store as array with only the finalRole
        $user->roles = [$finalRole];
        $user->save();

        // ✅ Build response with flag if polluted elevation
        $response = response()->json([
            'success' => true,
            'message' => 'Role updated successfully.',
            'finalRole' => $finalRole
        ]);

        if (is_array($roleParam) && $finalRole === 'ROLE_ADMIN') {
            $response->header('Flag', 'helcim{Do_You_Love_Polluting_Parameters}');
        }

        return $response;
    }








    //controller for analytics page
    public function analyticsPage()
    {
        $user = Auth::user();

        if (!$user || !$user->is_paid_user) {
            return redirect()->route('purchase_premium');
        }

        // Filter invoices by the user's organization
        $invoices = Invoice::where('organization_id', $user->organization_id)->get();

        $totalInvoices = $invoices->count();
        $statusBreakdown = [
            'DUE' => 0,
            'PAID' => 0,
            'COMPLETED' => 0,
            'CANCELLED' => 0,
        ];
        $revenueByMonth = [];
        $totalRevenue = 0;

        foreach ($invoices as $invoice) {
            $status = $invoice->status;
            if (isset($statusBreakdown[$status])) {
                $statusBreakdown[$status]++;
            }

            $totalRevenue += (float) $invoice->total_amount;

            $monthYear = \Carbon\Carbon::parse($invoice->date_issued)->format('M Y');
            if (!isset($revenueByMonth[$monthYear])) {
                $revenueByMonth[$monthYear] = 0;
            }
            $revenueByMonth[$monthYear] += (float) $invoice->total_amount;
        }

        return view('analytics.advanced_analytics', [
            'totalInvoices' => $totalInvoices,
            'statusBreakdown' => $statusBreakdown,
            'revenueByMonth' => $revenueByMonth,
            'totalRevenue' => $totalRevenue,
        ]);
    }


    //Controller logic for paid unser functionality

    public function purchasePremium()
    {
        return view('analytics.purchase_premium');
    }

    public function applyDiscount(Request $request)
    {
        $discountCode = $request->input('discountCode');
        $originalAmount = Session::get('originalAmount', 100.00);
        $finalAmount = Session::get('finalAmount', $originalAmount);

        // Check timeout
        $lastUpdated = Session::get('lastUpdated', time());
        $timeoutDuration = 0.1; // 1 minute
        if ((time() - $lastUpdated) > $timeoutDuration) {
            $finalAmount = $originalAmount;
            Session::put('finalAmount', $finalAmount);
        }

        // Simulate race condition vulnerability
        if ($discountCode === '10%OFF') {
            $discount = $finalAmount * 0.5;
            $finalAmount -= $discount;
            Session::put('finalAmount', $finalAmount);
            Session::put('lastUpdated', time());

            return response()->json([
                'success' => true,
                'finalAmount' => $finalAmount,
                'message' => 'Discount applied successfully!',
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => 'Invalid discount code.'
        ], 400);
    }

    public function finalizePurchase()
    {
        $finalAmount = Session::get('finalAmount', null);

        if ($finalAmount === null) {
            return response()->json([
                'success' => false,
                'error' => 'No final amount found. Please apply a discount or retry.'
            ], 400);
        }

        $roundedAmount = round($finalAmount, 1);

        if ($roundedAmount <= 0.1) {
            Session::forget('finalAmount');
            $user = Auth::user();
            if ($user) {
                $user->is_paid_user = true;
                $user->save();
            }

            $response = response()->json([
                'success' => true,
                'message' => 'Transaction successful! You are now a premium user.',
            ]);
            $response->headers->set('Flag', 'helcim{Glad_You_Raced_Against_The_Threads!}');

            return $response;
        }

        Session::forget('finalAmount');
        return response()->json([
            'success' => false,
            'error' => 'Not enough discount. Please try again.',
        ]);
    }



    //controller logic for feedback

    public function feedback(Request $request)
    {
        $feedbackFile = '/tmp' . '/feedback2.txt';


        if ($request->isMethod('post')) {
            $data = $request->json()->all();

            if (!isset($data['feedback']) || empty($data['feedback'])) {
                return response()->json(['error' => 'Feedback cannot be empty. Malicious tags are forbidden.'], 400);
            }


            $rawFeedback = $data['feedback'];
            $cleanedFeedback = $this->sanitizeFeedback($rawFeedback);

            // Always store the sanitized feedback first
            File::append($feedbackFile, $cleanedFeedback . PHP_EOL);

            // Now check if the original raw input contains event handlers within allowed tags
            if (preg_match('/<(b|i|u|br)\b[^>]*(on\w+)\s*=\s*["\']?[^"\'>]*["\']?/i', $rawFeedback)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Feedback submitted successfully!',
                    'flag' => 'helcim{Advanced_XSS_Challenge_Solved!}',
                ], 201);
            }

            return response()->json([
                'success' => true,
                'message' => 'Feedback submitted successfully!',
            ]);
        }

        // GET method: fetch existing feedback
        $feedbacks = [];
        if (file_exists($feedbackFile)) {
            $feedbacks = array_filter(explode(PHP_EOL, file_get_contents($feedbackFile)));
        }

        return view('user.feedback', [
            'feedbacks' => $feedbacks,
        ]);
    }

    private function sanitizeFeedback(string $input): string
    {
        // Allow only <b>, <i>, <u>, <a>, <span>, <br>
        return strip_tags($input, '<b><i><u><a><span><br>');
    }


}
