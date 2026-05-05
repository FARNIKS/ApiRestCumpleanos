<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

class BirthdaySettingsController extends Controller
{
    public function index()
    {
        return response()->json([
            'is_paused' => Cache::get('birthdays_paused', false)
        ]);
    }

    public function toggleStatus()
    {
        $isPaused = Cache::get('birthdays_paused', false);
        $newStatus = !$isPaused;

        Cache::forever('birthdays_paused', $newStatus);

        return response()->json([
            'status' => 'success',
            'message' => $newStatus ? 'Envío de correos pausado' : 'Envío de correos reanudado',
            'is_paused' => $newStatus
        ]);
    }
}
