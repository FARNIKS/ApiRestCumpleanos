<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Http\Resources\CountryResource;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::withCount('branches')
            ->where('estado', true)
            ->orderBy('name', 'asc')
            ->get();

        return CountryResource::collection($countries);
    }

    public function store(StoreCountryRequest $request): JsonResponse
    {
        try {
            $country = Country::create($request->validated());
            return response()->json([
                'status' => 'success',
                'data'   => new CountryResource($country)
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateCountryRequest $request, Country $country): JsonResponse
    {
        try {
            $country->update($request->validated());
            return response()->json([
                'status'  => 'success',
                'message' => 'País actualizado',
                'data'    => new CountryResource($country)
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }

    public function destroy(Country $country): JsonResponse
    {
        try {
            $country->update(['estado' => false]);
            return response()->json(['status' => 'success', 'message' => 'País desactivado'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'info' => $e->getMessage()], 500);
        }
    }
}
