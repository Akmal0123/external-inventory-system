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
     * Render the main external system dashboard.
     */
    public function index(): View
    {
        $stats = [
            'companies_count' => Company::count(),
            'vendors_count' => Vendor::count(),
            'items_count' => Item::count(),
            'prs_count' => PurchaseRequest::count(),
            'pos_count' => PurchaseOrder::count(),
            'issued_pos_count' => PurchaseOrder::where('status', 'issued')->count(),
            'approved_prs_count' => PurchaseRequest::where('status', 'approved')->count(),
        ];

        $recentPOs = PurchaseOrder::with(['company', 'vendor', 'purchaseRequest', 'items.item'])
            ->latest('id')
            ->take(10)
            ->get();

        $recentPRs = PurchaseRequest::with(['company', 'items'])
            ->latest('id')
            ->take(10)
            ->get();

        $companies = Company::orderBy('name')->get();
        $vendors = Vendor::orderBy('name')->get();
        $items = Item::orderBy('name')->get();

        $year = now()->year;

        $maxPoSeq = PurchaseOrder::where('po_number', 'like', "PO-{$year}-%")
            ->pluck('po_number')
            ->map(function ($num) {
                return preg_match('/-(\d+)$/', $num, $m) ? (int) $m[1] : 0;
            })
            ->max() ?? 0;
        $nextPoNumber = sprintf('PO-%d-%04d', $year, $maxPoSeq + 1);

        $maxPrSeq = PurchaseRequest::where('pr_number', 'like', "PR-{$year}-%")
            ->pluck('pr_number')
            ->map(function ($num) {
                return preg_match('/-(\d+)$/', $num, $m) ? (int) $m[1] : 0;
            })
            ->max() ?? 0;
        $nextPrNumber = sprintf('PR-%d-%04d', $year, $maxPrSeq + 1);

        return view('dashboard', compact(
            'stats',
            'recentPOs',
            'recentPRs',
            'companies',
            'vendors',
            'items',
            'nextPoNumber',
            'nextPrNumber'
        ));
    }
}
