<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of vendors.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Vendor::query();

        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('code', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        $perPage = (int) $request->input('per_page', 15);
        $vendors = $query->latest('id')->paginate($perPage);

        return $this->paginatedResponse($vendors);
    }

    /**
     * Store a newly created vendor.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:vendors,code',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        $vendor = Vendor::create($validator->validated());

        return $this->successResponse($vendor, 'Vendor berhasil ditambahkan', 201);
    }

    /**
     * Display the specified vendor.
     */
    public function show(string $id): JsonResponse
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return $this->errorResponse('Vendor tidak ditemukan', 404);
        }

        return $this->successResponse($vendor);
    }

    /**
     * Update the specified vendor.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return $this->errorResponse('Vendor tidak ditemukan', 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:vendors,code,' . $vendor->id,
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        $vendor->update($validator->validated());

        return $this->successResponse($vendor, 'Vendor berhasil diperbarui');
    }

    /**
     * Remove the specified vendor.
     */
    public function destroy(string $id): JsonResponse
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return $this->errorResponse('Vendor tidak ditemukan', 404);
        }

        $vendor->delete();

        return $this->successResponse(null, 'Vendor berhasil dihapus');
    }
}
