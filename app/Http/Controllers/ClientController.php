<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Services\VetApiService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = (new VetApiService(Auth::user()))->getClientList();
        return view('dashboard', ['clients' => $clients]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request)
    {
        $validatedData = $request->validated();
        (new VetApiService(Auth::user()))->createClient($validatedData);
        return redirect('/clients');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show($id)
    {
        $clientData = (new VetApiService(Auth::user()))->getClient($id);
        return view('clients.show', ['client' => $clientData]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     */
    public function edit($id)
    {
        return view('clients.edit', ['id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ClientRequest $request
     * @param int $id
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function update(ClientRequest $request, int $id)
    {
        $validatedData = $request->validated();
        (new VetApiService(Auth::user()))->editClient($validatedData, $id);
        return redirect('/clients');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        (new VetApiService(Auth::user()))->deleteClient($id);
        return redirect('/clients');
    }
}
