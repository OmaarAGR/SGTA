<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', Vehicle::class);

        $filter_search = request()->query('search');

        $vehicles = Vehicle::query()
        ->when($filter_search, function ($q, $filter_search) {
            $q->where('license_plate', 'like', '%' . $filter_search . '%')
            ->orWhereHas('client', function ($q) use ($filter_search) {
                $q->where('name', 'like', '%' . $filter_search . '%');
            });
        })
        ->with('client') // eager load for efficiency
        ->paginate(10)
        ->withQueryString();

        return Inertia::render('Vehicles/Index', [
            'vehicles' => $vehicles,
            'filterSearch' => $filter_search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        Gate::authorize('create', Vehicle::class);

        return Inertia::render('Vehicles/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        Gate::authorize('create', Vehicle::class);

        $vehicle = Vehicle::create($request->validated());

        return to_route('vehicles.show', $vehicle)->with([
            'success' => 'Vehiculo registrado correctamente.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle): Response
    {
        Gate::authorize('view', $vehicle);

        return Inertia::render('Vehicles/Show', [
            'vehicle' => $vehicle->load('client'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle): Response
    {
        Gate::authorize('update', Vehicle::class);

        return Inertia::render('Vehicles/Edit', [
            'vehicle' => $vehicle->load('client'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        Gate::authorize('update', Vehicle::class);

        $vehicle->update($request->validated());

        return to_route('vehicles.show', $vehicle)->with([
            'success' => 'Vehiculo actualizado correctamente.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        Gate::authorize('delete', $vehicle);

        $vehicle->delete();

        return to_route('vehicles.index')->with([
            'success' => 'Vehiculo eliminado correctamente.'
        ]);
    }
}
