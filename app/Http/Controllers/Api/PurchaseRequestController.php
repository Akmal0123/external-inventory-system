<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Http\Traits\UserScopedQuery;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseRequestController extends Controller
{
    use ApiResponse, UserScopedQuery;

    /**
     * Search/Lookup Purchase Requests for autocomplete/selection in AMS.
     * Section 8: GET /api/purchase-requests/lookup?q=PR-2026
     */
    public function lookup(Request $request): JsonResponse
    {
        $keyword = trim($request->input('q', ''));

        $query = PurchaseRequest::query();

        // User data privacy scoping
        if (!$this->isServiceOrAdmin($request)) {
            $user = $this->resolveUser($request);
            if (!$user) {
                return $this->errorResponse('Akses ditolak: Data Purchase Request bersifat privat. Silakan login atau sertakan token otentikasi.', 401);
            }
            $query->where('user_id', $user->id);
        }

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('pr_number', 'like', "%{$keyword}%")
                  ->orWhere('requester_name', 'like', "%{$keyword}%")
                  ->orWhere('department', 'like', "%{$keyword}%");
            });
        }

        $results = $query->latest('id')
            ->limit(20)
            ->get(['id', 'user_id', 'pr_number', 'requester_name', 'status']);

        return $this->successResponse($results);
    }

    /**
     * List Purchase Requests with pagination & filters.
     * Section 8: GET /api/purchase-requests?page=1&per_page=10&status=approved
     */
    public function index(Request $request): JsonResponse
    {
        $query = PurchaseRequest::with(['company', 'items.item']);

        // User data privacy scoping
        if (!$this->isServiceOrAdmin($request)) {
            $user = $this->resolveUser($request);
            if (!$user) {
                return $this->errorResponse('Akses ditolak: Data Purchase Request bersifat privat. Silakan login atau sertakan token otentikasi.', 401);
            }
            $query->where('user_id', $user->id);
        }

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
    public function show(Request $request, string $id): JsonResponse
    {
        $pr = PurchaseRequest::with(['company', 'items.item'])
            ->where('id', $id)
            ->orWhere('pr_number', $id)
            ->first();

        if (!$pr) {
            return $this->errorResponse('Purchase Request tidak ditemukan', 404);
        }

        // User data privacy scoping
        if (!$this->isServiceOrAdmin($request)) {
            $user = $this->resolveUser($request);
            if (!$user) {
                return $this->errorResponse('Akses ditolak: Data Purchase Request bersifat privat. Silakan login atau sertakan token otentikasi.', 401);
            }
            if ($pr->user_id !== null && $pr->user_id !== $user->id) {
                return $this->errorResponse('Akses ditolak: Anda hanya dapat mengakses Purchase Request milik Anda sendiri.', 403);
            }
        }

        return $this->successResponse($this->transformPurchaseRequest($pr));
    }

    /**
     * Store new Purchase Request with items.
     */
    public function store(Request $request): JsonResponse
    {
        $user = $this->resolveUser($request);
        if (!$user && !$this->isServiceOrAdmin($request)) {
            return $this->errorResponse('Akses ditolak: Silakan login terlebih dahulu untuk membuat Purchase Request.', 401);
        }

        $data = $request->all();
        if (empty($data['pr_number'])) {
            $data['pr_number'] = PurchaseRequest::generateNextNumber($data['request_date'] ?? null);
        }
        // Status awal untuk PR selalu 'draft' by default dan hanya dapat diupdate melalui status dokumen di AMS
        $data['status'] = 'draft';

        $validator = Validator::make($data, [
            'pr_number' => 'required|string|max:50|unique:purchase_requests,pr_number',
            'company_id' => 'required|exists:companies,id',
            'requester_name' => 'nullable|string|max:255',
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
        $requesterName = $validated['requester_name'] ?? ($user?->name ?? 'Requester');

        $pr = DB::transaction(function () use ($validated, $user, $requesterName) {
            $pr = PurchaseRequest::create([
                'user_id' => $user?->id,
                'pr_number' => $validated['pr_number'],
                'company_id' => $validated['company_id'],
                'requester_name' => $requesterName,
                'department' => $validated['department'],
                'request_date' => $validated['request_date'],
                'status' => 'draft',
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

        // Trigger sinkronisasi otomatis ke AMS via microservice-lookup
        $syncMeta = null;
        try {
            $lookupUrl = rtrim(config('services.lookup.url', env('LOOKUP_SERVICE_URL', 'http://localhost:5000')), '/');
            $syncRes = \Illuminate\Support\Facades\Http::timeout(5)->post("{$lookupUrl}/api/v1/integration/sync/pr", [
                'pr_number' => $pr->pr_number,
            ]);
            if ($syncRes->successful()) {
                $syncMeta = $syncRes->json('data');
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Gagal auto-sync PR {$pr->pr_number} ke microservice: " . $e->getMessage());
        }

        $response = $this->transformPurchaseRequest($pr);
        if ($syncMeta) {
            $response['sync_info'] = $syncMeta;
        }

        return $this->successResponse($response, 'Purchase Request berhasil dibuat dan disinkronisasikan ke microservice-lookup', 201);
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

        // User data privacy scoping
        if (!$this->isServiceOrAdmin($request)) {
            $user = $this->resolveUser($request);
            if (!$user) {
                return $this->errorResponse('Akses ditolak: Data Purchase Request bersifat privat. Silakan login atau sertakan token otentikasi.', 401);
            }
            if ($pr->user_id !== null && $pr->user_id !== $user->id) {
                return $this->errorResponse('Akses ditolak: Anda hanya dapat memperbarui Purchase Request milik Anda sendiri.', 403);
            }
        }

        $validator = Validator::make($request->all(), [
            'pr_number' => 'required|string|max:50|unique:purchase_requests,pr_number,' . $pr->id,
            'company_id' => 'required|exists:companies,id',
            'requester_name' => 'nullable|string|max:255',
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
            $updateData = [
                'pr_number' => $validated['pr_number'],
                'company_id' => $validated['company_id'],
                'department' => $validated['department'],
                'request_date' => $validated['request_date'],
                'status' => $validated['status'] ?? $pr->status,
                'description' => $validated['description'] ?? $pr->description,
            ];

            if (isset($validated['requester_name'])) {
                $updateData['requester_name'] = $validated['requester_name'];
            }

            $pr->update($updateData);

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
    public function destroy(Request $request, string $id): JsonResponse
    {
        $pr = PurchaseRequest::find($id);

        if (!$pr) {
            return $this->errorResponse('Purchase Request tidak ditemukan', 404);
        }

        // User data privacy scoping
        if (!$this->isServiceOrAdmin($request)) {
            $user = $this->resolveUser($request);
            if (!$user) {
                return $this->errorResponse('Akses ditolak: Data Purchase Request bersifat privat. Silakan login atau sertakan token otentikasi.', 401);
            }
            if ($pr->user_id !== null && $pr->user_id !== $user->id) {
                return $this->errorResponse('Akses ditolak: Anda hanya dapat menghapus Purchase Request milik Anda sendiri.', 403);
            }
        }

        $pr->delete();

        return $this->successResponse(null, 'Purchase Request berhasil dihapus');
    }

    /**
     * Trigger manual sync of Purchase Request to AMS via microservice-lookup.
     */
    public function sync(Request $request, string $id): JsonResponse
    {
        $pr = PurchaseRequest::with(['company', 'items.item'])->find($id);
        if (!$pr) {
            $pr = PurchaseRequest::where('pr_number', $id)->first();
        }

        if (!$pr) {
            return $this->errorResponse('Purchase Request tidak ditemukan', 404);
        }

        if (!$this->isServiceOrAdmin($request)) {
            $user = $this->resolveUser($request);
            if (!$user || ($pr->user_id !== null && $pr->user_id !== $user->id)) {
                return $this->errorResponse('Akses ditolak: Anda hanya dapat melakukan sync pada Purchase Request milik Anda sendiri.', 403);
            }
        }

        try {
            $lookupUrl = rtrim(config('services.lookup.url', env('LOOKUP_SERVICE_URL', 'http://localhost:5000')), '/');
            $response = \Illuminate\Support\Facades\Http::timeout(10)->post("{$lookupUrl}/api/v1/integration/sync/pr", [
                'pr_number' => $pr->pr_number,
            ]);

            if ($response->successful()) {
                return $this->successResponse($response->json('data'), 'Sinkronisasi berhasil dijalankan oleh microservice-lookup');
            }

            return $this->errorResponse('Gagal melakukan sync: ' . ($response->json('error.message') ?? $response->body()), $response->status());
        } catch (\Throwable $e) {
            return $this->errorResponse('Gagal menghubungi microservice-lookup: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Transform PR model to structured format.
     */
    private function transformPurchaseRequest(PurchaseRequest $pr): array
    {
        return [
            'id' => $pr->id,
            'user_id' => $pr->user_id,
            'pr_number' => $pr->pr_number,
            'company' => $pr->company ? [
                'id' => $pr->company->id,
                'code' => $pr->company->code,
                'name' => $pr->company->name,
                'address' => $pr->company->address,
            ] : null,
            'requester_name' => $pr->requester_name,
            'department' => $pr->department,
            'request_date' => $pr->request_date?->format('Y-m-d'),
            'status' => $pr->status,
            'description' => $pr->description,
            'total_estimated_price' => $pr->total_estimated_price,
            'items' => $pr->items->map(function ($line) {
                return [
                    'id' => $line->id,
                    'item' => $line->item ? [
                        'id' => $line->item->id,
                        'code' => $line->item->code,
                        'name' => $line->item->name,
                        'unit' => $line->item->unit,
                    ] : null,
                    'quantity' => $line->quantity,
                    'estimated_price' => (float) $line->estimated_price,
                    'subtotal' => (float) ($line->quantity * $line->estimated_price),
                    'description' => $line->description,
                ];
            })->values()->all(),
            'created_at' => $pr->created_at?->toIso8601String(),
            'updated_at' => $pr->updated_at?->toIso8601String(),
        ];
    }
}
