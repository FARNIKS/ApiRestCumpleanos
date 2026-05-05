<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with(['country'])
            ->where('estado', true)
            ->orderBy('code', 'asc')
            ->get();

        return BranchResource::collection($branches);
    }

    public function show(Branch $branch)
    {
        return new BranchResource($branch->load(['country']));
    }
}
