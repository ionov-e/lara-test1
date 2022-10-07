<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Services\VetApiService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = (new VetApiService(Auth::user()))->search(VetApiService::CLIENT_MODEL);
        return view('dashboard', ['clients' => $clients, 'title' => "Dashboard"]);
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
        (new VetApiService(Auth::user()))
            ->create(VetApiService::CLIENT_MODEL, $validatedData);
        return redirect('/clients');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show($id)
    {
        $clientData = (new VetApiService(Auth::user()))
            ->search(VetApiService::CLIENT_MODEL, 'id', $id, VetApiService::EQUAL_OPERATOR, 1);

        $petsData = (new VetApiService(Auth::user()))
            ->search(VetApiService::PET_MODEL, 'owner_id', $id, VetApiService::EQUAL_OPERATOR,);

        return view('clients.show', ['client' => $clientData[0], 'pets' => $petsData]);
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
        (new VetApiService(Auth::user()))
            ->edit(VetApiService::CLIENT_MODEL, $validatedData, $id);
        return redirect('/clients');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        (new VetApiService(Auth::user()))
            ->delete(VetApiService::CLIENT_MODEL, $id);
        return redirect('/clients');
    }

    /** Выводит результат поиска */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $clients = (new VetApiService(Auth::user()))->search(VetApiService::CLIENT_MODEL, 'last_name', $query);
        return view('dashboard', ['clients' => $clients, 'title' => "Search result for '$query'"]);
    }
}
