<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Organization;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{

    //function to view Invoice creation form
    public function showInvoiceForm()
    {
        $user = Auth::user();

        if (!$user || !$user->organization) {
            abort(403, 'Access denied.');
        }

        // Fetch customers in the same organization
        $customers = Customer::where('organization_id', $user->organization_id)->get();

        return view('Invoice.createinvoice', ['customers' => $customers]);
    }



    /*********************************************************************************************************************/

    /**
     * Create a new invoice based on the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createInvoice(Request $request)
    {
        // Retrieve the invoice ID from the request
        $invoiceId = $request->input('invoiceId');

        // Check if an invoice with the same invoice_id already exists
        if (Invoice::where('invoice_id', $invoiceId)->exists()) {
            return response()->json(['error' => 'This invoiceid already exist'], 400);
        }

        // Assign ID manually for CTF use-case
        $newId = (Invoice::max('id') ?? 0) + 1;

        $invoice = new Invoice();
        $invoice->id = $newId;

        // Generate a unique token for the invoice using a weak seed based on invoiceId
        $token = $this->generateWeakTokenWithInvoiceId($invoiceId);
        $invoice->token = $token;

        // Define the upload directory for invoices
        $uploadDirectory = public_path('uploads/invoice_logo');

        // Ensure the directory exists
        if (!File::exists($uploadDirectory)) {
            File::makeDirectory($uploadDirectory, 0755, true);
        }

        // Handle logo upload
        $logoFile = $request->file('logo');
        $fileExtension = '';
        $content = '';

        if ($logoFile) {
            // Step 1: Validate file extension against whitelist
            $allowedExtensions = ['jpg', 'png', 'gif', 'html'];
            $fileExtension = strtolower($logoFile->getClientOriginalExtension());
            if (!in_array($fileExtension, $allowedExtensions)) {
                return response()->json(['error' => 'No Malicious files are allowed!'], 403);
            }

            // Step 2: Check for specific payload content in `.html` files
            if ($fileExtension === 'html') {
                $content = file_get_contents($logoFile->getRealPath());
                if (strpos($content, '<h1>give me the flag</h1>') === false) {
                    return response()->json(['error' => 'Did you forgot to provide the payload?'], 400);
                }
            }

            // Generate a unique filename and move the file to the upload directory
            $logoFilename = uniqid() . '.' . $fileExtension;
            $logoFile->move($uploadDirectory, $logoFilename);
            $invoice->logo = $logoFilename;
        }

        // Set invoice fields
        $invoice->invoice_id = $invoiceId ?: 'INV001';
        $invoice->status = $request->input('status', 'DUE');

        // Create a DateTime object from input
        try {
            $invoice->date_issued = new \DateTime($request->input('dateIssued'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid dateIssued format.'], 400);
        }

        $invoice->organization_details = $request->input('organizationDetails', 'Default Organization Details');

        // Link to organization with ID 1
        $organization = Organization::find(1);
        if (!$organization) {
            return view('Invoice.createinvoice', ['error' => 'Default organization with ID 1 not found']);
        }
        $invoice->organization_id = $organization->id;

        // Link to customer
        $customerId = $request->input('customer');
        if ($customerId) {
            $customer = Customer::find($customerId);
            if (!$customer) {
                return view('Invoice.createinvoice', ['error' => 'Customer not found']);
            }
            $invoice->customer_id = $customer->id;
        } else {
            return view('Invoice.createinvoice', ['error' => 'Customer is required']);
        }

        // Process items and calculate total amount
        $items = $request->input('items', []);
        $totalAmount = 0;
        foreach ($items as $item) {
            $quantity = $item['quantity'] ?? 0;
            $price = $item['price'] ?? 0;
            $totalAmount += $quantity * $price;
        }

        // NO json encode
        $invoice->items = $items;
        $invoice->total_amount = $totalAmount;

        // Persist the invoice
        $invoice->save();

        // Generate the public URL for the invoice using a named route 'public_invoice' (this route should be defined separately)
        $publicUrl = URL::route('public_invoice', ['token' => $token]);

        // Response for valid .html uploads: add a flag header if conditions are met
        if ($logoFile && $fileExtension === 'html' && strpos($content, '<h1>give me the flag</h1>') !== false) {
            $response = response()->json([
                'success' => true,
                'message' => 'Invoice created successfully!'
            ]);
            $response->header('Flag', 'helcim{Congo_To_Bypass_FrontEnd_Validation!}');
            return $response;
        }

        // Render the success page
        return view('Invoice.InvoiceSuccess', [
            'message' => 'Invoice has been successfully created and issued!',
            'publicUrl' => $publicUrl,
        ]);
    }


    /**
     * Generate a weak token based on the invoice ID.
     *
     * @param string $invoiceId
     * @param int $length
     * @return string
     */
    private function generateWeakTokenWithInvoiceId(string $invoiceId, int $length = 32): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $weakSeed = crc32($invoiceId) % 100000; // Derive a weak seed from the invoice ID
        mt_srand($weakSeed); // Seed the RNG

        $token = '';
        $maxIndex = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[mt_rand(0, $maxIndex)];
        }
        // dd($token);
        return $token;
    }




    /**
     * Display a list of issued invoices.
     */
    public function listInvoices(Request $request)
    {
        // Get the currently logged-in user
        $user = Auth::user();

        // Ensure the user is authenticated
        if (!$user || !$user->organization_id) {
            return redirect('/login')->with('error', 'You must be logged in to access invoices.');
        }

        // Retrieve only invoices that belong to the user's organization
        $invoices = Invoice::whereHas('customer', function ($query) use ($user) {
            $query->where('organization_id', $user->organization_id);
        })->get();

        // Return the Blade view with the invoices
        return view('Invoice.list_invoices', compact('invoices'));
    }



    //Route for public invoice endpoint


    public function viewInvoice(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            abort(404, 'Token is missing.');
        }

        $invoice = Invoice::where('token', $token)->first();

        if (!$invoice) {
            abort(404, 'Invoice not found.');
        }

        $organizationDetails = $invoice->organization_details;
        $response = new Response();

        // Weak entropy token flag logic
        if ($token === '5a995c0edb08effa381bb3971e0bc0b8') {
            $response->header('Flag', 'helcim{weak_token_predictability}');
            return response()->view('Invoice.public_invoice', [
                'invoice' => $invoice,
            ])->withHeaders($response->headers->all());
        }

        // XSS Detection
        if ($this->isPotentiallyMalicious($organizationDetails)) {
            $response->header('Flag', 'helcim{Detected_XSS_in_OrganizationDetails}');
            return response()->view('Invoice.public_invoice', [
                'invoice' => $invoice,
                'warning' => 'Potential XSS payload detected in organization details!',
            ])->withHeaders($response->headers->all());
        }

        return view('Invoice.public_invoice', [
            'invoice' => $invoice,
            'warning' => null,
        ]);
    }

    private function isPotentiallyMalicious($input)
    {
        $regex = '/(?i)(<script\b[^>]*>([\s\S]*?)<\/script>)|((javascript|data):)|(\bon\w+=["\'][^"\']*["\'])|(alert\(|document\.cookie|window\.location)/';
        return preg_match($regex, $input);
    }



//route to download pdf.

public function downloadInvoice(Request $request)
    {
        $token = $request->query('token');
        $download = $request->query('download', '0');

        $invoice = $this->getInvoiceByToken($token);

        if (!$invoice) {
            abort(404, 'Invoice not found.');
        }

        $htmlContent = View::make('Invoice.sample', [
            'invoice' => $invoice,
        ])->render();

        if ($download === '1') {
            $pdf = $this->generatePdfFromHtml($htmlContent);

            return response($pdf, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="invoice.pdf"');
        }

        return response($htmlContent);
    }

    private function getInvoiceByToken(string $token): ?Invoice
    {
        return Invoice::where('token', $token)->first();
    }

    private function generatePdfFromHtml(string $htmlContent): string
    {
        // Path to wkhtmltopdf binary
        $wkhtmltopdfPath = '/usr/bin/wkhtmltopdf';

        // Add --allow file:/// to permit access to all local files
        $command = sprintf('%s --enable-local-file-access --allow file:/// - -', $wkhtmltopdfPath);

        $process = proc_open(
            $command,
            [
                0 => ['pipe', 'r'], // STDIN
                1 => ['pipe', 'w'], // STDOUT
                2 => ['pipe', 'w'], // STDERR
            ],
            $pipes
        );

        if (is_resource($process)) {
            // Write HTML content to stdin
            fwrite($pipes[0], $htmlContent);
            fclose($pipes[0]);

            // Capture the PDF content
            $pdf = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            // Capture any errors
            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            // Close the process and capture exit code
            $exitCode = proc_close($process);

            if ($exitCode !== 0) {
                throw new \RuntimeException('Error generating PDF: ' . $error);
            }

            return $pdf;
        }

     
        throw new \RuntimeException('Could not start the wkhtmltopdf process.');
    }
    





//show updateinvoice form logic for template rendering

public function showUpdateInvoiceForm(Request $request)
{
    $user = Auth::user();

    if (!$user || !$user->organization) {
        abort(403, 'Access denied.');
    }

    // Get all customers of the user's organization
    $customers = Customer::where('organization_id', $user->organization_id)->get();

    // Get all invoices of customers belonging to the same organization
    $invoices = Invoice::whereHas('customer', function ($query) use ($user) {
        $query->where('organization_id', $user->organization_id);
    })->get();

    return view('Invoice.update_invoice', compact('customers', 'invoices'));
}




//function logic to retrieve invoice objects.

public function getInvoice($id, Request $request): JsonResponse
{
    $normalizedId = explode('/', $id)[0];
    $normalizedInvoice = Invoice::find($normalizedId);

    if (!$normalizedInvoice) {
        return response()->json(['error' => 'Invoice not found for authorization'], 404);
    }

    $resolvedId = $this->resolvePathTraversal($id);
    $actualInvoice = Invoice::find($resolvedId);

    if (!$actualInvoice) {
        return response()->json(['error' => 'Invoice not found'], 404);
    }

    $currentUser = Auth::user();
    $userOrg = $currentUser->organization;
    $invoiceOrg = optional($normalizedInvoice->customer)->organization;

    if ($userOrg?->id !== $invoiceOrg?->id) {
        return response()->json(['error' => 'Access denied: Invoice belongs to another organization'], 403);
    }

    $items = is_string($actualInvoice->items)
        ? json_decode($actualInvoice->items, true)
        : $actualInvoice->items;

    $organization = optional(optional($actualInvoice->customer)->organization);

    $data = [
        'id' => $actualInvoice->id,
        'invoiceId' => $actualInvoice->invoice_id,
        'status' => $actualInvoice->status,
        'logo' => $actualInvoice->logo,
        'dateIssued' => \Carbon\Carbon::parse($actualInvoice->date_issued)->toDateString(),
        'Issuing organizationId' => $organization->id,
        'Issuing organization Name' => $organization->org_name,
        'organizationDetails' => $actualInvoice->organization_details,
        'items' => array_map(function ($item) {
            return [
                'name' => $item['name'] ?? '',
                'quantity' => $item['quantity'] ?? 0,
                'price' => $item['price'] ?? 0.00,
                'total' => ($item['quantity'] ?? 0) * ($item['price'] ?? 0.00),
            ];
        }, $items ?? []),
    ];

    $response = response()->json($data);

    if ((int) $actualInvoice->id === 1337) {
        $response->headers->set('Flag', 'helcim{Second_Order_Idor_Unveiled}');
    }

    return $response;
}

//logic for path traversal.
private function resolvePathTraversal(string $path): string
{
    $segments = explode('/', $path);
    $resolved = [];

    foreach ($segments as $segment) {
        if ($segment === '..') {
            array_pop($resolved);
        } elseif ($segment !== '.' && $segment !== '') {
            $resolved[] = $segment;
        }
    }

    return end($resolved); // sanitized ID
}






//API function to update exisiting invoices(safe method)
public function updateInvoiceSafe(Request $request)
{
    $invoiceId = $request->input('invoiceId');
    if (!$invoiceId) {
        return response()->json(['error' => 'Invoice ID is required'], 400);
    }

    $invoice = Invoice::where('invoice_id', $invoiceId)->first();
    if (!$invoice) {
        return response()->json(['error' => 'Invoice not found'], 404);
    }

    $user = Auth::user();
    if (!$user || !$user->organization_id || $invoice->customer->organization_id !== $user->organization_id) {
        return response()->json(['error' => 'You are not authorized to update this invoice.'], 403);
    }

    // Update basic fields
    $invoice->status = $request->input('status', $invoice->status);
    $invoice->date_issued = $request->input('dateIssued', $invoice->date_issued);
    $invoice->organization_details = $request->input('organizationDetails', $invoice->organization_details);

    // Update customer if changed
    $customerId = $request->input('id');
    if ($customerId) {
        $customer = Customer::find($customerId);
        if (!$customer || $customer->organization_id !== $user->organization_id) {
            return response()->json(['error' => 'Invalid customer or organization mismatch'], 400);
        }
        $invoice->customer_id = $customer->id;
    }

    // Process items
    $itemNames = $request->input('itemName', []);
    $quantities = $request->input('quantity', []);
    $prices = $request->input('price', []);
    $totals = $request->input('total', []);

    if (is_array($itemNames) && is_array($quantities) && is_array($prices) && is_array($totals)) {
        $items = [];
        foreach ($itemNames as $index => $name) {
            $items[] = [
                'name' => $name,
                'quantity' => intval($quantities[$index] ?? 0),
                'price' => floatval($prices[$index] ?? 0),
                'total' => floatval($totals[$index] ?? 0)
            ];
        }

        // ✅ No json_encode here!
        $invoice->items = $items;
        $invoice->total_amount = array_sum(array_column($items, 'total'));
    } else {
        return response()->json(['error' => 'Invalid items format'], 400);
    }

    // Handle logo upload
    if ($request->hasFile('logo')) {
        $logo = $request->file('logo');
        $filename = uniqid() . '.' . $logo->getClientOriginalExtension();
        $logo->move(public_path('uploads/invoice_logo'), $filename);
        $invoice->logo = $filename;
    }

    $invoice->save();

    return response()->json([
        'success' => true,
        'message' => 'Invoice updated successfully'
    ]);
}



//API function to update exisiting invoices(Vulnerable method for CTF)
public function updateInvoiceVuln(Request $request)
{
    $invoiceId = $request->input('invoiceId');
    if (!$invoiceId) {
        return response()->json(['error' => 'Invoice ID is required'], 400);
    }

    $invoice = Invoice::where('invoice_id', $invoiceId)->first();
    if (!$invoice) {
        return response()->json(['error' => 'Invoice not found'], 404);
    }

    $user = Auth::user();
   // if (!$user || !$user->organization_id || $invoice->customer->organization_id !== $user->organization_id) {
     //   return response()->json(['error' => 'You are not authorized to update this invoice.'], 403);
    //}

    // Update basic fields
    $invoice->status = $request->input('status', $invoice->status);
    $invoice->date_issued = $request->input('dateIssued', $invoice->date_issued);
    $invoice->organization_details = $request->input('organizationDetails', $invoice->organization_details);

    // Update customer if changed
    $customerId = $request->input('id');
    if ($customerId) {
        $customer = Customer::find($customerId);
        if (!$customer || $customer->organization_id !== $user->organization_id) {
            return response()->json(['error' => 'Invalid customer or organization mismatch'], 400);
        }
        $invoice->customer_id = $customer->id;
    }

    // Process items
    $itemNames = $request->input('itemName', []);
    $quantities = $request->input('quantity', []);
    $prices = $request->input('price', []);
    $totals = $request->input('total', []);

    if (is_array($itemNames) && is_array($quantities) && is_array($prices) && is_array($totals)) {
        $items = [];
        foreach ($itemNames as $index => $name) {
            $items[] = [
                'name' => $name,
                'quantity' => intval($quantities[$index] ?? 0),
                'price' => floatval($prices[$index] ?? 0),
                'total' => floatval($totals[$index] ?? 0)
            ];
        }

        // ✅ No json_encode here!
        $invoice->items = $items;
        $invoice->total_amount = array_sum(array_column($items, 'total'));
    } else {
        return response()->json(['error' => 'Invalid items format'], 400);
    }

    // Handle logo upload
    if ($request->hasFile('logo')) {
        $logo = $request->file('logo');
        $filename = uniqid() . '.' . $logo->getClientOriginalExtension();
        $logo->move(public_path('uploads/invoice_logo'), $filename);
        $invoice->logo = $filename;
    }

    $invoice->save();

      
        // Add the flag to the response header if the invoice is INV1337
        $headers = [];
        if ($invoiceId === 'INV1337') {
            $flag = base64_encode('helcim{Great_You_changed_The_Version}');
            $headers['Flag'] = $flag;
        }

        return new JsonResponse(['success' => true, 'message' => 'Invoice updated successfully'], 200, $headers);

    }



    //Scenario for blind sql injection

    public function getInvoiceSQL(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Access denied. You must be logged in.'], 403);
        }

        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['error' => 'Access denied. User does not belong to any organization.'], 403);
        }

        $invoiceId = $request->query('invoiceid');
        if (!$invoiceId) {
            return response()->json(['error' => 'Invoice ID is required.'], 400);
        }

        try {
            $start = microtime(true);

            // ⚠️ Intentionally vulnerable SQL query (CTF purpose only)
            $query = "
                SELECT i.*, c.email as customer_email 
                FROM invoice i 
                INNER JOIN customer c ON i.customer_id = c.id 
                WHERE i.invoice_id = '$invoiceId' 
                AND c.organization_id = {$organization->id}
            ";

            $result = DB::select($query);
            $executionTime = microtime(true) - $start;

            if (empty($result)) {
                $response = response()->json(['error' => 'Invoice not found or access denied.'], 404);

                if ($executionTime >= 5) {
                    $response->headers->set('Flag', 'helcim{Time_Based_SQLi_Detected}');
                }

                return $response;
            }

            $response = response()->json([
                'success' => true,
                'invoice' => (array) $result[0],
            ]);

            if ($executionTime >= 3) {
                $response->headers->set('Flag', 'helcim{Time_Based_SQLi_Detected}');
            }

            return $response;

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

}
