<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRecordRequest;
use App\Http\Requests\UpdateServiceRecordRequest;
use App\Models\ServiceRecord;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ServiceRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        Gate::authorize('viewAny', ServiceRecord::class);

        $filter_search = request()->query('search');

        $serviceRecords = ServiceRecord::query()
        ->when($filter_search, function ($q, $filter_search) {
            $q->where('service_performed', 'like', '%' . $filter_search . '%')
            ->orWhere('notes', 'like', '%' . $filter_search . '%')
            ->orWhereHas('vehicle', function ($q) use ($filter_search) {
                $q->where('license_plate', 'like', '%' . $filter_search . '%');
            });
        })
        ->with('client') // eager load for efficiency
        ->paginate(10)
        ->withQueryString();

        return Inertia::render('ServiceRecords/Index', [
            'serviceRecords' => $serviceRecords,
            'filterSearch' => $filter_search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', ServiceRecord::class);

        return Inertia::render('ServiceRecords/Create', [
            'vehicles' => Vehicle::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRecordRequest $request)
    {
        Gate::authorize('create', ServiceRecord::class);

        $serviceRecord = ServiceRecord::create($request->validated());

        return to_route('service-records.show', $serviceRecord)->with([
            'success' => 'Service record created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceRecord $serviceRecord)
    {
        Gate::authorize('view', $serviceRecord);

        $serviceRecord->load(['vehicle.client', 'technician.user']);

        return Inertia::render('ServiceRecords/Show', [
            'serviceRecord' => $serviceRecord,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceRecord $serviceRecord)
    {
        Gate::authorize('update', $serviceRecord);

        return Inertia::render('ServiceRecords/Edit', [
            'serviceRecord' => $serviceRecord->load(['vehicle.client', 'technician.user']),
            'vehicles' => Vehicle::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRecordRequest $request, ServiceRecord $serviceRecord)
    {
        Gate::authorize('update', $serviceRecord);

        $serviceRecord->update($request->validated());

        return to_route('service-records.show', $serviceRecord)->with([
            'success' => 'Service record updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceRecord $serviceRecord)
    {
        Gate::authorize('delete', $serviceRecord);

        $serviceRecord->delete();

        return to_route('service-records.index')->with([
            'success' => 'Service record deleted successfully.',
        ]);
    }
}
