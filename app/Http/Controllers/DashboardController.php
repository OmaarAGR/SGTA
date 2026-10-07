<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        // Preload the appointments and vehicles relationship for the authenticated user's client
        request()->user()->client?->load(['appointments', 'vehicles'])->loadCount(['appointments', 'vehicles']);

        return Inertia::render('Dashboard');
    }
}
