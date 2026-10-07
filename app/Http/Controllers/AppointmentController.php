<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Technician;
use App\Models\Vehicle;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filter_search = request()->query('search');
        $filter_status = request()->query('status');

        $appointments = Appointment::query()
        ->when($filter_status, function ($q, $filter_status) {
            $q->where('status', $filter_status);
        })
        ->when($filter_search, function ($q, $filter_search) {
            $q->where(function ($q) use ($filter_search) {
                $q->whereHas('vehicle', function ($q) use ($filter_search) {
                    $q->where('license_plate', 'like', '%' . $filter_search . '%')
                    ->orWhereHas('client', function ($q) use ($filter_search) {
                        $q->whereHas('user', function ($q) use ($filter_search) {
                            $q->where('name', 'like', '%' . $filter_search . '%');
                        });
                    });
                });
            });
        })
        ->with('vehicle.client') // eager load for efficiency
        ->paginate(10)
        ->withQueryString();

        return Inertia::render('Appointments/Index', [
            'appointments' => $appointments,
            'filterSearch' => $filter_search,
            'filterStatus' => $filter_status,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $client_id = request()->query('client_id');

        $vehicles = Vehicle::query()
            ->when($client_id, function ($q) use ($client_id) {
                $q->whereHas('client', function ($q) use ($client_id) {
                    $q->where('id', $client_id);
                });
            })
            ->get();

        return Inertia::render('Appointments/Create', [
            'clients' => Client::with('user')->get(),
            'vehicles' => $vehicles,
            'technicians' => Technician::with('user')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        Gate::authorize('create', Appointment::class);

        $data = $request->validated();

        $client = Client::find($data['client_id']);
        if (!$client) {
            return back()->withErrors([
                'client_id' => 'El cliente seleccionado no existe.'
            ]);
        }

        if (!$client->vehicles->contains($data['vehicle_id'])) {
            return back()->withErrors([
                'vehicle_id' => 'El vehículo seleccionado no pertenece a este cliente.'
            ]);
        }

        $data['datetime'] = Carbon::parse(
            $data['date'] . ' ' . $data['time'],
            config('app.timezone_display')
        )->tz(config('app.timezone'));

        unset($data['date'], $data['time']);

        if ($data['technician_id'] === -1) {
            $technician = Technician::inRandomOrder()->first(); // Randomly assign a technician

            if (!$technician) {
                return back()->withErrors([
                    'vehicle_id' => 'No hay técnicos disponibles en este momento.'
                ]);
            }

            $data['technician_id'] = $technician->id;
        }

        $appointment = new Appointment;
        $appointment->fill($data);
        $appointment->user_id = $client->user_id;
        $appointment->technician_id = $data['technician_id'];
        $appointment->save();

        return to_route('appointments.show', $appointment)->with([
            'success' => 'Cita creada correctamente.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment): Response
    {
        Gate::authorize('view', $appointment);

        return Inertia::render('Appointments/Show', [
            'appointment' => $appointment->load('vehicle')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        Gate::authorize('update', $appointment);

        return Inertia::render('Appointments/Edit', [
            'appointment' => $appointment->load(['vehicle', 'technician']),
            'clients' => Client::all(),
            'vehicles' => Vehicle::all(),
            'technicians' => Technician::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        Gate::authorize('update', $appointment);

        $data = $request->validated();

        $client = Client::find($data['client_id']);
        if (!$client) {
            return back()->withErrors([
                'client_id' => 'El cliente seleccionado no existe.'
            ]);
        }

        if (!$client->vehicles->contains($data['vehicle_id'])) {
            return back()->withErrors([
                'vehicle_id' => 'El vehículo seleccionado no pertenece a este cliente.'
            ]);
        }

        $data['datetime'] = Carbon::parse(
            $data['date'] . ' ' . $data['time'],
            config('app.timezone_display')
        )->tz(config('app.timezone'));

        unset($data['date'], $data['time']);

        if ($data['technician_id'] === -1) {
            $technician = Technician::inRandomOrder()->first(); // Randomly assign a technician

            if (!$technician) {
                return back()->withErrors([
                    'vehicle_id' => 'No hay técnicos disponibles en este momento.'
                ]);
            }

            $data['technician_id'] = $technician->id;
        }

        $appointment->fill($data);
        $appointment->user_id = $client->user_id;
        $appointment->save();

        return to_route('appointments.show', [$client, $appointment])->with([
            'success' => 'Cita actualizada correctamente.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        Gate::authorize('delete', $appointment);

        $appointment->delete();

        return to_route('appointments.index')->with([
            'success' => 'Cita eliminada correctamente.'
        ]);
    }
}
