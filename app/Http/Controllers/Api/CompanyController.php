<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of companies.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Company::query();

        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('code', 'like', "%{$keyword}%");
            });
        }

        $perPage = (int) $request->input('per_page', 15);
        $companies = $query->latest('id')->paginate($perPage);

        return $this->paginatedResponse($companies);
    }

    /**
     * Store a newly created company.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:companies,code',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        $company = Company::create($validator->validated());

        return $this->successResponse($company, 'Company berhasil ditambahkan', 201);
    }

    /**
     * Display the specified company.
     */
    public function show(string $id): JsonResponse
    {
        $company = Company::find($id);

        if (!$company) {
            return $this->errorResponse('Company tidak ditemukan', 404);
        }

        return $this->successResponse($company);
    }

    /**
     * Update the specified company.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $company = Company::find($id);

        if (!$company) {
            return $this->errorResponse('Company tidak ditemukan', 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:companies,code,' . $company->id,
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        $company->update($validator->validated());

        return $this->successResponse($company, 'Company berhasil diperbarui');
    }

    /**
     * Remove the specified company.
     */
    public function destroy(string $id): JsonResponse
    {
        $company = Company::find($id);

        if (!$company) {
            return $this->errorResponse('Company tidak ditemukan', 404);
        }

        $company->delete();

        return $this->successResponse(null, 'Company berhasil dihapus');
    }
}
