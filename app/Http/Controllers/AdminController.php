<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Organization;

class AdminController extends Controller
{
    //
    public function protectedFlag(Request $request)
    {
        $clientIp = $request->ip();
        $serverIp = $request->server('SERVER_ADDR') ?: gethostbyname(gethostname());

        // Hostname used in the request (e.g., "web", "localhost", "nginx")
        $hostHeader = $request->getHost();

        // Hostname of the container/server
        $serverHostName = gethostname();

        // Allow-list of hostnames that are considered internal
        $allowedHostnames = [
            'web',          // Docker alias for nginx
            'nginx',        // Possible alias
            'localhost',
            $serverHostName // current container's hostname
        ];

        // Optional debug output
        // dd(compact('clientIp', 'serverIp', 'hostHeader', 'serverHostName'));

        // If hostname is not in allowed list, block access
        if (!in_array($hostHeader, $allowedHostnames, true)) {
            return response('Access denied. Only internal running services can access this file.', 200)
                ->header('Content-Type', 'text/plain');
        }

        // Allow access
        return response('helcim{Only_Local_Access_Allowed}', 200)
            ->header('Content-Type', 'text/plain');
    }




    public function protectedFlag1(Request $request)
    {

        // Return the flag
        $flag = 'helcim{Only_Local_Access_Allowed}';
        return response($flag, 200)->header('Content-Type', 'text/plain');
    }




    //Read admin controller logic

    public function adminPanel(Request $request): Response
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !preg_match('/^Bearer\s+(.+)$/', $authHeader, $matches)) {
            return response()->view('admin.access_denied', [
                'message' => 'Access Denied: JWT token is required.',
            ]);
        }

        $jwtToken = $matches[1];
        if (!$this->validateJwtToken($jwtToken)) {
            return response()->view('admin.access_denied', [
                'message' => 'Access Denied: Invalid JWT token.',
            ]);
        }

        $organizations = Organization::with('customers.invoices')->get();

        $response = response()->view('admin.admin', ['data' => $organizations]);
        $response->header('Flag', 'helcim{Wohooo_Admin_Access_Granted}');
        return $response;
    }

    private function validateJwtToken(string $jwtToken): bool
    {
        $secret = 'your-secret-key';
        $parts = explode('.', $jwtToken);
        if (count($parts) !== 3)
            return false;

        [$header, $payload, $signature] = $parts;

        $decodedHeader = json_decode(base64_decode($header), true);
        $decodedPayload = json_decode(base64_decode($payload), true);

        if (!$decodedHeader || !$decodedPayload)
            return false;

        $expectedSig = hash_hmac('sha256', "$header.$payload", $secret, true);
        $encodedSig = rtrim(strtr(base64_encode($expectedSig), '+/', '-_'), '=');

        if (!hash_equals($encodedSig, $signature))
            return false;
        if (isset($decodedPayload['exp']) && time() > $decodedPayload['exp'])
            return false;
        if (!isset($decodedPayload['role']) || $decodedPayload['role'] !== 'SuperAdmin')
            return false;

        return true;
    }

















}
