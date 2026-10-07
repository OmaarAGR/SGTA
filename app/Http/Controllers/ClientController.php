<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filter_search = request()->query('search');

        $clients = Client::query()
            ->when($filter_search, function ($q, $filter_search) {
                $q->whereHas('user', function ($q) use ($filter_search) {
                    $q->where('name', 'like', '%' . $filter_search . '%');
                });
            })
            ->withCount(['vehicles', 'appointments'])
            ->paginate(10)
            ->withQueryString();

        return inertia('Clients/Index', [
            'clients' => $clients,
            'filterSearch' => $filter_search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load('user');

        return inertia('Clients/Show', [
            'client' => $client,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $client->load('user');

        return inertia('Clients/Edit', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update($request->validated());

        return to_route('clients.show', $client)->with([
            'success' => 'Client updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
}
