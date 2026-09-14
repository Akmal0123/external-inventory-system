<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of items.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Item::query();

        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('code', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $perPage = (int) $request->input('per_page', 15);
        $items = $query->latest('id')->paginate($perPage);

        return $this->paginatedResponse($items);
    }

    /**
     * Store a newly created item.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:items,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        $item = Item::create($validator->validated());

        return $this->successResponse($item, 'Barang berhasil ditambahkan', 201);
    }

    /**
     * Display the specified item.
     */
    public function show(string $id): JsonResponse
    {
        $item = Item::find($id);

        if (!$item) {
            return $this->errorResponse('Barang tidak ditemukan', 404);
        }

        return $this->successResponse($item);
    }

    /**
     * Update the specified item.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $item = Item::find($id);

        if (!$item) {
            return $this->errorResponse('Barang tidak ditemukan', 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:items,code,' . $item->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        $item->update($validator->validated());

        return $this->successResponse($item, 'Barang berhasil diperbarui');
    }

    /**
     * Remove the specified item.
     */
    public function destroy(string $id): JsonResponse
    {
        $item = Item::find($id);

        if (!$item) {
            return $this->errorResponse('Barang tidak ditemukan', 404);
        }

        $item->delete();

        return $this->successResponse(null, 'Barang berhasil dihapus');
    }
}
