<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Inertia\Inertia;
use Inertia\Response;

class ClientVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Client $client): Response
    {
        return Inertia::render('Vehicles/Index', [
            'vehicles' => $client->vehicles,
        ]);
    }
}
