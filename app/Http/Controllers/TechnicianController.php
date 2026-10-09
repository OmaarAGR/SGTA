<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTechnicianRequest;
use App\Http\Requests\UpdateTechnicianRequest;
use App\Models\Technician;
use App\Models\User;
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
            ->with('user')
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
        $data = $request->validated();

        $user = new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        $user->role = 'technician'; // role is not mass assignable
        $user->save();
        $technician = $user->technician()->create(['area_of_expertise' => $data['area_of_expertise']]);

        return to_route('technicians.show', $technician)->with([
            'success' => 'Technician created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Technician $technician)
    {
        $technician->load(['user', 'serviceRecords']);

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
        $data = $request->validated();

        $technician->user->update(array_filter([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'] ?? null,
        ]));
        $technician->update(['area_of_expertise' => $data['area_of_expertise']]);

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
