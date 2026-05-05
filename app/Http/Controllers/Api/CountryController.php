<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Http\Resources\CountryResource;


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

    public function show(Country $countries)
    {
        return new CountryResource($countries);
    }
}
