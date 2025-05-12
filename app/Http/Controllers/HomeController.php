<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\User;

class HomeController extends Controller
{
    public function search(Request $request)
    {
        $query = $this->sanitizeQuery($request->input('query', ''));
        $results = [];

       // Check if the query contains `<p>` with an event handler
        $pattern = '/<p\b[^>]*\s+on\w+\s*=\s*["\']?[^"\'>]*["\']?/i';
        if (preg_match($pattern, $query)) {
            return response()
                ->view('search.index', [
                    'query' => $query,
                    'results' => $results,
                    'error' => 'Potential XSS payload detected.',
                ])
                ->header('Flag', 'helcim{Bypassed_XSS_Against_Protection!}');
        }

        $user = Auth::user();
        if (!$user) {
            return view('search.index', [
                'query' => $query,
                'results' => [],
                'error' => 'Access denied. You must be logged in to perform this action.',
            ]);
        }

        $organizationId = $user->organization_id;

        if (!empty($query)) {
            $q = strtolower($query);

            // Invoices
            $invoices = Invoice::whereHas('customer', function ($sub) use ($organizationId) {
                $sub->where('organization_id', $organizationId);
            })->where(function ($sub) use ($q) {
                $sub->whereRaw('LOWER(invoice_id) LIKE ?', ["%$q%"])
                    ->orWhereRaw('LOWER(organization_details) LIKE ?', ["%$q%"]);
            })->get();

            foreach ($invoices as $invoice) {
                $results[] = [
                    'type' => 'Invoice',
                    'name' => $invoice->invoice_id,
                    'id' => $invoice->id,
                ];
            }

            // Customers
            $customers = Customer::where('organization_id', $organizationId)
                ->where(function ($sub) use ($q) {
                    $sub->whereRaw('LOWER(cust_name) LIKE ?', ["%$q%"])
                        ->orWhereRaw('LOWER(email) LIKE ?', ["%$q%"]);
                })->get();

            foreach ($customers as $customer) {
                $results[] = [
                    'type' => 'Customer',
                    'name' => $customer->cust_name,
                    'id' => $customer->id,
                ];
            }

            // Users
            $users = User::where('organization_id', $organizationId)
                ->whereRaw('LOWER(email) LIKE ?', ["%$q%"])
                ->get();

            foreach ($users as $usr) {
                $results[] = [
                    'type' => 'User',
                    'name' => $usr->email,
                    'id' => $usr->id,
                ];
            }
        }

        return view('search.index', [
            'query' => $query,
            'results' => $results,
        ]);
    }

 /**
     * Custom function to sanitize the query parameter.
     * Strips off all HTML tags except <p>.
     */

    private function sanitizeQuery(string $input): string
    {
        $allowedTags = '<p>';
        $sanitized = strip_tags($input, $allowedTags);
        return preg_replace('/<script\b[^>]*>(.*?)<\/script>/i', '', $sanitized);
    }
}
