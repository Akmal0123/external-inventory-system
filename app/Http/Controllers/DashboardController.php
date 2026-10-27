<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\Vendor;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Render the main external system dashboard with user-scoped private data.
     */
    public function index(): View
    {
        $user = auth()->user();
        $isGlobal = $user && in_array($user->username, ['admin', 'integration-service'], true);

        $poQuery = PurchaseOrder::query();
        $prQuery = PurchaseRequest::query();

        // Regular users only see their own private PO & PR data
        if (!$isGlobal && $user) {
            $poQuery->where('user_id', $user->id);
            $prQuery->where('user_id', $user->id);
        }

        $stats = [
            'companies_count' => Company::count(),
            'vendors_count' => Vendor::count(),
            'items_count' => Item::count(),
            'prs_count' => (clone $prQuery)->count(),
            'pos_count' => (clone $poQuery)->count(),
            'issued_pos_count' => (clone $poQuery)->where('status', 'issued')->count(),
            'approved_prs_count' => (clone $prQuery)->where('status', 'approved')->count(),
        ];

        $recentPOs = (clone $poQuery)->with(['company', 'vendor', 'purchaseRequest', 'items.item'])
            ->latest('id')
            ->take(10)
            ->get();

        $recentPRs = (clone $prQuery)->with(['company', 'items'])
            ->latest('id')
            ->take(10)
            ->get();

        $companies = Company::orderBy('name')->get();
        $vendors = Vendor::orderBy('name')->get();
        $items = Item::orderBy('name')->get();

        $nextPoNumber = PurchaseOrder::generateNextNumber();
        $nextPrNumber = PurchaseRequest::generateNextNumber();

        return view('dashboard', compact(
            'stats',
            'recentPOs',
            'recentPRs',
            'companies',
            'vendors',
            'items',
            'nextPoNumber',
            'nextPrNumber',
            'isGlobal'
        ));
    }
}
