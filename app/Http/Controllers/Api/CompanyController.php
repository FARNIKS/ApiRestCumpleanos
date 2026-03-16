<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::where('estado', true)
            ->orderBy('name', 'asc')
            ->get();

        return CompanyResource::collection($company);
    }

    public function store(StoreCompanyRequest $request): JsonResponse
    {
        try {
            $company = Company::create($request->validated());
            return response()->json([
                'status' => 'success',
                'data'   => new CompanyResource($company)
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }
    public function show(Company $company): CompanyResource
    {
        return new CompanyResource($company);
    }


    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        try {
            // Al usar el Binding (Company $company), Laravel ya hace el findOrFail automáticamente
            $company->update($request->validated());

            return response()->json([
                'status' => 'success',
                'data'   => new CompanyResource($company)
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }

    public function destroy(Company $company): JsonResponse
    {
        try {
            // Borrado lógico
            $company->update(['estado' => false]);
            return response()->json([
                'status'  => 'success',
                'message' => 'Empresa desactivada correctamente' // Mensaje corregido
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }
}
