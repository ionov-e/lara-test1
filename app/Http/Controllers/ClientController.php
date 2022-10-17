<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Services\ViewService;
use App\Services\VetApiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /** Display a listing of the resource. */
    public function index()
    {
        return ViewService::clientList();
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
        $notification =
            ((new VetApiService(Auth::user()))
                ->create(VetApiService::CLIENT_MODEL, $validatedData))
                ? 'Client Was Created'
                : 'Client Was Not Created';
        return ViewService::clientList($notification);
    }

    /** Display the specified resource. */
    public function show($id)
    {
        return ViewService::clientShow($id);
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
        $notification =
            ((new VetApiService(Auth::user()))
                ->edit(VetApiService::CLIENT_MODEL, $validatedData, $id))
                ? 'Client Was Updated'
                : 'Client Was Not Updated';
        return ViewService::clientList($notification);
    }

    /** Remove the specified resource from storage. */
    public function destroy($id)
    {
        $notification =
            ((new VetApiService(Auth::user()))
                ->deleteClient($id))
                ? 'Client Was Deleted'
                : 'Client Was Not Deleted';
        return ViewService::clientList($notification);
    }

    /** Выводит результат поиска */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $clients = (new VetApiService(Auth::user()))->get(VetApiService::CLIENT_MODEL, 'last_name', $query);
        return view('clients.list', ['clients' => $clients, 'title' => "Search result for '$query'"]);
    }
}
