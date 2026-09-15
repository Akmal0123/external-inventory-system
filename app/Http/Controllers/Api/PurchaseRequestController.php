<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseRequestController extends Controller
{
    use ApiResponse;

    /**
     * Search/Lookup Purchase Requests for autocomplete/selection in AMS.
     * Section 8: GET /api/purchase-requests/lookup?q=PR-2026
     */
    public function lookup(Request $request): JsonResponse
    {
        $keyword = trim($request->input('q', ''));

        $query = PurchaseRequest::query();

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('pr_number', 'like', "%{$keyword}%")
                  ->orWhere('requester_name', 'like', "%{$keyword}%")
                  ->orWhere('department', 'like', "%{$keyword}%");
            });
        }

        $results = $query->latest('id')
            ->limit(20)
            ->get(['id', 'pr_number', 'requester_name', 'status']);

        return $this->successResponse($results);
    }

    /**
     * List Purchase Requests with pagination & filters.
     * Section 8: GET /api/purchase-requests?page=1&per_page=10&status=approved
     */
    public function index(Request $request): JsonResponse
    {
        $query = PurchaseRequest::with(['company', 'items.item']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->input('company_id'));
        }

        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('pr_number', 'like', "%{$keyword}%")
                  ->orWhere('requester_name', 'like', "%{$keyword}%")
                  ->orWhere('department', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        $paginator = $query->latest('id')->paginate($perPage);

        // Format items nicely
        $formattedData = collect($paginator->items())->map(function ($pr) {
            return $this->transformPurchaseRequest($pr);
        });

        return response()->json([
            'success' => true,
            'data' => $formattedData,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
                'has_more_pages' => $paginator->hasMorePages(),
            ],
        ]);
    }

    /**
     * Get single Purchase Request detail.
     * Section 8: GET /api/purchase-requests/{id}
     */
    public function show(string $id): JsonResponse
    {
        $pr = PurchaseRequest::with(['company', 'items.item'])
            ->where('id', $id)
            ->orWhere('pr_number', $id)
            ->first();

        if (!$pr) {
            return $this->errorResponse('Purchase Request tidak ditemukan', 404);
        }

        return $this->successResponse($this->transformPurchaseRequest($pr));
    }

    /**
     * Store new Purchase Request with items.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'pr_number' => 'required|string|max:50|unique:purchase_requests,pr_number',
            'company_id' => 'required|exists:companies,id',
            'requester_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'request_date' => 'required|date',
            'status' => 'nullable|string|in:draft,submitted,approved,rejected',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.estimated_price' => 'required|numeric|min:0',
            'items.*.description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        $validated = $validator->validated();

        $pr = DB::transaction(function () use ($validated) {
            $pr = PurchaseRequest::create([
                'pr_number' => $validated['pr_number'],
                'company_id' => $validated['company_id'],
                'requester_name' => $validated['requester_name'],
                'department' => $validated['department'],
                'request_date' => $validated['request_date'],
                'status' => $validated['status'] ?? 'draft',
                'description' => $validated['description'] ?? null,
            ]);

            foreach ($validated['items'] as $itemLine) {
                PurchaseRequestItem::create([
                    'purchase_request_id' => $pr->id,
                    'item_id' => $itemLine['item_id'],
                    'quantity' => $itemLine['quantity'],
                    'estimated_price' => $itemLine['estimated_price'],
                    'description' => $itemLine['description'] ?? null,
                ]);
            }

            return $pr->load(['company', 'items.item']);
        });

        return $this->successResponse($this->transformPurchaseRequest($pr), 'Purchase Request berhasil dibuat', 201);
    }

    /**
     * Update existing Purchase Request.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $pr = PurchaseRequest::with('items')->find($id);

        if (!$pr) {
            return $this->errorResponse('Purchase Request tidak ditemukan', 404);
        }

        $validator = Validator::make($request->all(), [
            'pr_number' => 'required|string|max:50|unique:purchase_requests,pr_number,' . $pr->id,
            'company_id' => 'required|exists:companies,id',
            'requester_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'request_date' => 'required|date',
            'status' => 'nullable|string|in:draft,submitted,approved,rejected',
            'description' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.item_id' => 'required_with:items|exists:items,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.estimated_price' => 'required_with:items|numeric|min:0',
            'items.*.description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        $validated = $validator->validated();

        $pr = DB::transaction(function () use ($pr, $validated) {
            $pr->update([
                'pr_number' => $validated['pr_number'],
                'company_id' => $validated['company_id'],
                'requester_name' => $validated['requester_name'],
                'department' => $validated['department'],
                'request_date' => $validated['request_date'],
                'status' => $validated['status'] ?? $pr->status,
                'description' => $validated['description'] ?? $pr->description,
            ]);

            if (isset($validated['items'])) {
                $pr->items()->delete();
                foreach ($validated['items'] as $itemLine) {
                    PurchaseRequestItem::create([
                        'purchase_request_id' => $pr->id,
                        'item_id' => $itemLine['item_id'],
                        'quantity' => $itemLine['quantity'],
                        'estimated_price' => $itemLine['estimated_price'],
                        'description' => $itemLine['description'] ?? null,
                    ]);
                }
            }

            return $pr->load(['company', 'items.item']);
        });

        return $this->successResponse($this->transformPurchaseRequest($pr), 'Purchase Request berhasil diperbarui');
    }

    /**
     * Delete Purchase Request.
     */
    public function destroy(string $id): JsonResponse
    {
        $pr = PurchaseRequest::find($id);

        if (!$pr) {
            return $this->errorResponse('Purchase Request tidak ditemukan', 404);
        }

        $pr->delete();

        return $this->successResponse(null, 'Purchase Request berhasil dihapus');
    }

    /**
     * Transform PR model to structured format.
     */
    private function transformPurchaseRequest(PurchaseRequest $pr): array
    {
        $totalEstimated = 0;
        $items = $pr->items->map(function ($line) use (&$totalEstimated) {
            $subtotal = $line->quantity * (float) $line->estimated_price;
            $totalEstimated += $subtotal;

            return [
                'id' => $line->id,
                'item_id' => $line->item_id,
                'item_code' => $line->item?->code,
                'item_name' => $line->item?->name,
                'unit' => $line->item?->unit ?? 'unit',
                'quantity' => $line->quantity,
                'estimated_price' => (float) $line->estimated_price,
                'subtotal' => $subtotal,
                'description' => $line->description,
            ];
        });

        return [
            'id' => $pr->id,
            'pr_number' => $pr->pr_number,
            'company' => $pr->company ? [
                'id' => $pr->company->id,
                'code' => $pr->company->code,
                'name' => $pr->company->name,
            ] : null,
            'requester_name' => $pr->requester_name,
            'department' => $pr->department,
            'request_date' => $pr->request_date?->format('Y-m-d'),
            'status' => $pr->status,
            'description' => $pr->description,
            'items' => $items,
            'total_estimated_price' => $totalEstimated,
            'created_at' => $pr->created_at?->toISOString(),
            'updated_at' => $pr->updated_at?->toISOString(),
        ];
    }
}
