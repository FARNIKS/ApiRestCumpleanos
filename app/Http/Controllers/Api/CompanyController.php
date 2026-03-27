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
        // Traemos las empresas activas
        $companies = Company::where('estado', true)
            ->orderBy('name', 'asc')
            ->get();

        return CompanyResource::collection($companies);
    }

    public function store(StoreCompanyRequest $request): JsonResponse
    {
        try {
            $company = Company::create($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Empresa creada con éxito',
                'data'   => new CompanyResource($company)
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }


    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        try {
            $company->update($request->validated());

            return response()->json([
                'status'  => 'success',
                'message' => 'Empresa actualizada correctamente',
                'data'    => new CompanyResource($company->load('branches'))
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }

    public function destroy(Company $company): JsonResponse
    {
        try {
            $company->update(['estado' => false]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Empresa desactivada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }
}
