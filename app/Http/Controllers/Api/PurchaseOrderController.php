<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseOrderController extends Controller
{
    use ApiResponse;

    /**
     * Search/Lookup Purchase Orders for autocomplete/selection in AMS/Fastify.
     * Section 9: GET /api/purchase-orders/lookup?q=PO-2026
     */
    public function lookup(Request $request): JsonResponse
    {
        $keyword = trim($request->input('q', ''));

        $query = PurchaseOrder::with(['vendor']);

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('po_number', 'like', "%{$keyword}%")
                  ->orWhereHas('vendor', function ($vq) use ($keyword) {
                      $vq->where('name', 'like', "%{$keyword}%")
                         ->orWhere('code', 'like', "%{$keyword}%");
                  });
            });
        }

        $results = $query->latest('id')
            ->limit(20)
            ->get()
            ->map(function ($po) {
                return [
                    'id' => $po->id,
                    'po_number' => $po->po_number,
                    'vendor' => $po->vendor?->name ?? 'Unknown Vendor',
                    'status' => $po->status,
                ];
            });

        return $this->successResponse($results);
    }

    /**
     * List Purchase Orders with pagination & filters.
     * Section 9: GET /api/purchase-orders?page=1&per_page=10&status=issued&company_id=1&vendor_id=1
     */
    public function index(Request $request): JsonResponse
    {
        $query = PurchaseOrder::with(['company', 'vendor', 'purchaseRequest', 'items.item']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->input('company_id'));
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->input('vendor_id'));
        }

        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('po_number', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhereHas('vendor', function ($vq) use ($keyword) {
                      $vq->where('name', 'like', "%{$keyword}%")
                         ->orWhere('code', 'like', "%{$keyword}%");
                  });
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        $paginator = $query->latest('id')->paginate($perPage);

        $formattedData = collect($paginator->items())->map(function ($po) {
            return $this->transformPurchaseOrder($po);
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
     * Get single Purchase Order detail.
     * Section 9: GET /api/purchase-orders/{id}
     */
    public function show(string $id): JsonResponse
    {
        $po = PurchaseOrder::with(['company', 'vendor', 'purchaseRequest', 'items.item'])->find($id);

        if (!$po) {
            return $this->errorResponse('Purchase Order tidak ditemukan', 404);
        }

        return $this->successResponse($this->transformPurchaseOrder($po));
    }

    /**
     * Store new Purchase Order with items.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'po_number' => 'required|string|max:50|unique:purchase_orders,po_number',
            'purchase_request_id' => 'nullable|exists:purchase_requests,id',
            'company_id' => 'required|exists:companies,id',
            'vendor_id' => 'required|exists:vendors,id',
            'order_date' => 'required|date',
            'status' => 'nullable|string|in:draft,issued,completed,cancelled',
            'description' => 'nullable|string',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        $validated = $validator->validated();

        $po = DB::transaction(function () use ($validated) {
            $subtotal = 0;
            foreach ($validated['items'] as $itemLine) {
                $subtotal += ($itemLine['quantity'] * $itemLine['unit_price']);
            }

            $tax = (float) ($validated['tax'] ?? 0);
            $total = $subtotal + $tax;

            $po = PurchaseOrder::create([
                'po_number' => $validated['po_number'],
                'purchase_request_id' => $validated['purchase_request_id'] ?? null,
                'company_id' => $validated['company_id'],
                'vendor_id' => $validated['vendor_id'],
                'order_date' => $validated['order_date'],
                'status' => $validated['status'] ?? 'draft',
                'description' => $validated['description'] ?? null,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
            ]);

            foreach ($validated['items'] as $itemLine) {
                $lineSubtotal = $itemLine['quantity'] * $itemLine['unit_price'];
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'item_id' => $itemLine['item_id'],
                    'quantity' => $itemLine['quantity'],
                    'unit_price' => $itemLine['unit_price'],
                    'subtotal' => $lineSubtotal,
                    'description' => $itemLine['description'] ?? null,
                ]);
            }

            return $po->load(['company', 'vendor', 'purchaseRequest', 'items.item']);
        });

        return $this->successResponse($this->transformPurchaseOrder($po), 'Purchase Order berhasil dibuat', 201);
    }

    /**
     * Update existing Purchase Order.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $po = PurchaseOrder::with('items')->find($id);

        if (!$po) {
            return $this->errorResponse('Purchase Order tidak ditemukan', 404);
        }

        $validator = Validator::make($request->all(), [
            'po_number' => 'required|string|max:50|unique:purchase_orders,po_number,' . $po->id,
            'purchase_request_id' => 'nullable|exists:purchase_requests,id',
            'company_id' => 'required|exists:companies,id',
            'vendor_id' => 'required|exists:vendors,id',
            'order_date' => 'required|date',
            'status' => 'nullable|string|in:draft,issued,completed,cancelled',
            'description' => 'nullable|string',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'nullable|array',
            'items.*.item_id' => 'required_with:items|exists:items,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
            'items.*.description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        $validated = $validator->validated();

        $po = DB::transaction(function () use ($po, $validated) {
            if (isset($validated['items'])) {
                $po->items()->delete();
                $subtotal = 0;
                foreach ($validated['items'] as $itemLine) {
                    $lineSubtotal = $itemLine['quantity'] * $itemLine['unit_price'];
                    $subtotal += $lineSubtotal;
                    PurchaseOrderItem::create([
                        'purchase_order_id' => $po->id,
                        'item_id' => $itemLine['item_id'],
                        'quantity' => $itemLine['quantity'],
                        'unit_price' => $itemLine['unit_price'],
                        'subtotal' => $lineSubtotal,
                        'description' => $itemLine['description'] ?? null,
                    ]);
                }
                $tax = isset($validated['tax']) ? (float) $validated['tax'] : (float) $po->tax;
                $total = $subtotal + $tax;
            } else {
                $subtotal = $po->subtotal;
                $tax = isset($validated['tax']) ? (float) $validated['tax'] : (float) $po->tax;
                $total = $subtotal + $tax;
            }

            $po->update([
                'po_number' => $validated['po_number'],
                'purchase_request_id' => $validated['purchase_request_id'] ?? $po->purchase_request_id,
                'company_id' => $validated['company_id'],
                'vendor_id' => $validated['vendor_id'],
                'order_date' => $validated['order_date'],
                'status' => $validated['status'] ?? $po->status,
                'description' => $validated['description'] ?? $po->description,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
            ]);

            return $po->load(['company', 'vendor', 'purchaseRequest', 'items.item']);
        });

        return $this->successResponse($this->transformPurchaseOrder($po), 'Purchase Order berhasil diperbarui');
    }

    /**
     * Delete Purchase Order.
     */
    public function destroy(string $id): JsonResponse
    {
        $po = PurchaseOrder::find($id);

        if (!$po) {
            return $this->errorResponse('Purchase Order tidak ditemukan', 404);
        }

        $po->delete();

        return $this->successResponse(null, 'Purchase Order berhasil dihapus');
    }

    /**
     * Generate & stream Purchase Order PDF.
     * Section 10: GET /api/purchase-orders/{id}/pdf
     */
    public function pdf(string $id): Response|JsonResponse
    {
        $po = PurchaseOrder::with(['company', 'vendor', 'purchaseRequest', 'items.item'])->find($id);

        if (!$po) {
            return $this->errorResponse('Purchase Order tidak ditemukan', 404);
        }

        $pdf = Pdf::loadView('pdf.purchase_order', ['po' => $po]);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Purchase_Order_' . str_replace('/', '_', $po->po_number) . '.pdf';

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    /**
     * Transform PO model to structured format matching section 9 specification.
     */
    private function transformPurchaseOrder(PurchaseOrder $po): array
    {
        return [
            'id' => $po->id,
            'po_number' => $po->po_number,
            'purchase_request' => $po->purchaseRequest ? [
                'id' => $po->purchaseRequest->id,
                'pr_number' => $po->purchaseRequest->pr_number,
            ] : null,
            'company' => $po->company ? [
                'id' => $po->company->id,
                'code' => $po->company->code,
                'name' => $po->company->name,
                'address' => $po->company->address,
            ] : null,
            'vendor' => $po->vendor ? [
                'id' => $po->vendor->id,
                'code' => $po->vendor->code,
                'name' => $po->vendor->name,
                'address' => $po->vendor->address,
                'phone' => $po->vendor->phone,
                'email' => $po->vendor->email,
            ] : null,
            'order_date' => $po->order_date?->format('Y-m-d'),
            'status' => $po->status,
            'description' => $po->description,
            'items' => $po->items->map(function ($itemLine) {
                return [
                    'id' => $itemLine->id,
                    'item_id' => $itemLine->item_id,
                    'item_code' => $itemLine->item?->code,
                    'item_name' => $itemLine->item?->name,
                    'unit' => $itemLine->item?->unit ?? 'unit',
                    'quantity' => $itemLine->quantity,
                    'unit_price' => (float) $itemLine->unit_price,
                    'subtotal' => (float) $itemLine->subtotal,
                    'description' => $itemLine->description,
                ];
            }),
            'subtotal' => (float) $po->subtotal,
            'tax' => (float) $po->tax,
            'total' => (float) $po->total,
            'created_at' => $po->created_at?->toISOString(),
            'updated_at' => $po->updated_at?->toISOString(),
        ];
    }
}
