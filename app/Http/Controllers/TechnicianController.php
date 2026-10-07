<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTechnicianRequest;
use App\Http\Requests\UpdateTechnicianRequest;
use App\Models\Technician;
use Inertia\Inertia;
use Inertia\Response;

class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $filter_search = request()->query('search');

        $technicians = Technician::query()
            ->when($filter_search, function ($q, $filter_search) {
                $q->whereHas('user', function ($q) use ($filter_search) {
                    $q->where('name', 'like', '%' . $filter_search . '%');
                });
            })
            ->paginate(10)
            ->withQueryString();

        return inertia('Technicians/Index', [
            'technicians' => $technicians,
            'filterSearch' => $filter_search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Technicians/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTechnicianRequest $request)
    {
        $technician = Technician::create($request->validated());

        return to_route('technicians.show', $technician)->with([
            'success' => 'Technician created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Technician $technician)
    {
        $technician->load(['user', 'appointments.vehicle.client', 'serviceRecords']);

        return Inertia::render('Technicians/Show', [
            'technician' => $technician,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Technician $technician)
    {
        return Inertia::render('Technicians/Edit', [
            'technician' => $technician->load('user'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTechnicianRequest $request, Technician $technician)
    {
        $technician->update($request->validated());

        return to_route('technicians.show', $technician)->with([
            'success' => 'Technician updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technician $technician)
    {
        $technician->delete();

        return to_route('technicians.index')->with([
            'success' => 'Technician deleted successfully.',
        ]);
    }
}
