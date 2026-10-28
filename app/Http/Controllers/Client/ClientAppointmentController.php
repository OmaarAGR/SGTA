<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Technician;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ClientAppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Client $client): Response
    {
        $filter_status = request()->query('status', '%');

        $appointments = $client->appointments()
            ->where('status', 'like', $filter_status)
            ->get();

        return Inertia::render('Clients/Appointments/Index', [
            'appointments' => $appointments,
            'filterStatus' => $filter_status,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Client $client): Response
    {
        return Inertia::render('Clients/Appointments/Create', [
            'vehicles' => $client->vehicles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Client $client, StoreAppointmentRequest $request): RedirectResponse
    {
        Gate::authorize('create', Appointment::class);

        $data = $request->validated();

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

        $technician = Technician::inRandomOrder()->first(); // Randomly assign a technician

        if (!$technician) {
            return back()->withErrors([
                'vehicle_id' => 'No hay técnicos disponibles en este momento.'
            ]);
        }

        $appointment = new Appointment;
        $appointment->fill($data);
        $appointment->user_id = $client->user_id;
        $appointment->technician_id = $technician->id;
        $appointment->save();

        return to_route('appointments.show', [$client, $appointment])->with([
            'success' => 'Cita creada correctamente.'
        ]);
    }
}
