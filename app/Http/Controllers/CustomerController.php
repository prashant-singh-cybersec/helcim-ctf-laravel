<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\JsonResponse;

class CustomerController extends BaseController
{
    /**
     * Edit a customer's name by ID, stripping <script> tags only.
     * Intentionally keeps other XSS vectors as this is for a CTF challenge.
     */
    public function editCustomer(Request $request, $id)
    {
        // Find the customer by ID
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        $newCustName = $request->input('CustName');

        if (!$newCustName) {
            return response()->json(['error' => 'Customer name is required'], 400);
        }

        // Intentionally vulnerable sanitization (CTF-style): only remove <script> tags
        $sanitizedCustName = preg_replace('/<\/?script.*?>/i', '', $newCustName);

        // Update and save
        $customer->cust_name = $sanitizedCustName;
        $customer->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'customer' => [
                'id' => $customer->id,
                'CustName' => $customer->cust_name,
            ],
        ], 200);
    }








    public function dashboard()
    {
        $customers = Customer::all();

        foreach ($customers as $customer) {
            if ($this->isPotentiallyMalicious($customer->cust_name)) {
                return response()
                    ->view('dashboard.dashboard', ['controller_name' => 'CustomerController'])
                    ->header('Flag', 'helcim{Detected_XSS_In_Customer_Name!}');
            }
        }

        return view('dashboard.dashboard', ['controller_name' => 'HomeController']);
    }

    /**
     * Check for potentially malicious XSS payloads.
     */
    private function isPotentiallyMalicious(string $input): bool
    {
        $pattern = '/(?i)(<\w+\b[^>]*\s+on\w+\s*=\s*["\']?\s*(alert|prompt|confirm)\s*\([^)]*\)["\']?[^>]*>)|(<script\b[^>]*>[\s\S]*?<\/script>)|((javascript|data):[^>]*>)/';

        return preg_match($pattern, $input) === 1;
    }






    public function createCustomer(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Access denied. You must be logged in to perform this action.'], 403);
        }
    
        $data = $request->json()->all();
    
        // Step 1: Ensure csrf_token key exists
        if (!array_key_exists('csrf_token', $data)) {
            return response()->json(['error' => 'Missing CSRF token'], 403);
        }
    
      //  dd($data['csrf_token']);
        // Step 2: If token is empty string "", skip CSRF validation and return flag
        if ($data['csrf_token'] === null) {
            
            return $this->handleBypassCustomerCreation($data, true);
        }
    
        // Step 3: Normal validation if token is provided and non-empty
        $providedToken = $data['csrf_token'];
        $sessionToken = Session::token();
    
        if (!is_string($providedToken) || !is_string($sessionToken) || !hash_equals($sessionToken, $providedToken)) {
            return response()->json(['error' => 'CSRF validation failed.'], 403);
        }
    
        return $this->handleBypassCustomerCreation($data, false);
    }


    private function handleBypassCustomerCreation(array $data, bool $bypass = false): JsonResponse
    {
        if (!isset($data['CustName'], $data['email'], $data['organization_id'])) {
            return response()->json(['error' => 'Invalid data'], 400);
        }
    
        $existingCustomer = Customer::where('email', $data['email'])->first();
        if ($existingCustomer) {
            return response()->json(['error' => 'Email already exists'], 500);
        }
    
        $organization = Organization::find($data['organization_id']);
        if (!$organization) {
            return response()->json(['error' => 'Organization not found'], 404);
        }
    
        // Assign ID manually for CTF use-case
        $newId = (Customer::max('id') ?? 0) + 1;
    
        $customer = new Customer();
        $customer->id = $newId;
        $customer->cust_name = $data['CustName'];
        $customer->email = $data['email'];
        $customer->organization_id = $organization->id;
        $customer->save();
    
        $response = response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'customer' => [
                'id' => $customer->id,
                'CustName' => $customer->cust_name,
                'email' => $customer->email,
                'organization_id' => $organization->id,
            ],
        ], 201);
    
        if ($bypass) {
            $response->headers->set('X-CTF-Flag', 'helcim{Bingo_For_The_CSRF_Bypass!}');
        }
    
        return $response;
    }
    



    //Simple IDOR

    public function getCustomer(Request $request): JsonResponse
{
    $customerId = $request->query('customerid');

    if (!$customerId) {
        return response()->json(['error' => 'customerid query parameter is required'], 400);
    }

    // Retrieve the Customer by ID
    $customer = Customer::find($customerId);

    if (!$customer) {
        return response()->json(['error' => 'Customer not found'], 404);
    }

    $organization = $customer->organization; // assuming Eloquent relationship

    // Set default headers
    $headers = ['Content-Type' => 'application/json'];

    // Add CTF flag header if customerid is 1337 (IDOR simulation)
    if ((int) $customerId === 1337) {
        $flagContent = 'Idor_Exposed_Customer1337';
        $headers['X-CTF-Flag'] = "helcim{{$flagContent}}";
    }

    return response()->json([
        'customer' => [
            'id' => $customer->id,
            'CustName' => $customer->cust_name,
            'email' => $customer->email,
        ],
        'organization' => [
            'id' => $organization->id,
            'OrgId' => $organization->org_id,
            'OrgName' => $organization->org_name,
        ],
    ], 200, $headers);
}

}
