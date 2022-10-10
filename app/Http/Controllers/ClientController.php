<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Services\VetApiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /** Display a listing of the resource. */
    public function index()
    {
        $clients = (new VetApiService(Auth::user()))->get(VetApiService::CLIENT_MODEL);
        return view('dashboard', ['clients' => $clients, 'title' => "Dashboard"]);
    }

    /** Show the form for creating a new resource. */
    public function create()
    {
        return view('clients.create');
    }

    /** Store a newly created resource in storage. */
    public function store(ClientRequest $request)
    {
        $validatedData = $request->validated();
        (new VetApiService(Auth::user()))
            ->create(VetApiService::CLIENT_MODEL, $validatedData);
        return redirect()->route('clients.index');
    }

    /** Display the specified resource. */
    public function show($id)
    {
        $apiService = new VetApiService(Auth::user());

        $client = $apiService->get(VetApiService::CLIENT_MODEL, 'id', $id, VetApiService::EQUAL_OPERATOR, 1)[0];

        $pets = $apiService->get(VetApiService::PET_MODEL, 'owner_id', $id, VetApiService::EQUAL_OPERATOR);

        return view('clients.show', compact('client', 'pets'));
    }

    /** Show the form for editing the specified resource. */
    public function edit($id)
    {
        return view('clients.edit', compact('id'));
    }

    /** Update the specified resource in storage.  */
    public function update(ClientRequest $request, int $id)
    {
        $validatedData = $request->validated();
        (new VetApiService(Auth::user()))
            ->edit(VetApiService::CLIENT_MODEL, $validatedData, $id);
        return redirect()->route('clients.index');
    }

    /** Remove the specified resource from storage. */
    public function destroy($id)
    {
        (new VetApiService(Auth::user()))->deleteClient($id);

        return redirect()->route('clients.index');
    }

    /** Выводит результат поиска */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $clients = (new VetApiService(Auth::user()))->get(VetApiService::CLIENT_MODEL, 'last_name', $query);
        return view('dashboard', ['clients' => $clients, 'title' => "Search result for '$query'"]);
    }
}
