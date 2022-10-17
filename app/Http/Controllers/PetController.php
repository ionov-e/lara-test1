<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetRequest;
use App\Services\VetApiService;
use App\Services\ViewService;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    /** Show the form for creating a new resource. */
    public function create(int $ownerId)
    {
        return view('pets.create', compact('ownerId'));
    }

    /** Store a newly created resource in storage. */
    public function store(PetRequest $request, int $id)
    {
        $validatedData = $request->validated();
        $validatedData['owner_id'] = $id;

        $notification =
            ((new VetApiService(Auth::user()))
                ->create(VetApiService::PET_MODEL, $validatedData))
                ? 'Pet Was Created'
                : 'Pet Was Not Created';

        return ViewService::clientShow($id, $notification);
    }

    /** Display the specified resource. */
    public function show(int $id)
    {
        return ViewService::petShow($id);
    }

    /** Show the form for editing the specified resource. */
    public function edit(int $id)
    {
        return view('pets.edit', compact('id'));
    }

    /** Update the specified resource in storage. */
    public function update(PetRequest $request, int $id)
    {
        $validatedData = $request->validated();
        $notification =
            ((new VetApiService(Auth::user()))
                ->edit(VetApiService::PET_MODEL, $validatedData, $id))
                ? 'Pet Was Edited'
                : 'Pet Was Not Edited';

        return ViewService::petShow($id, $notification);
    }

    /** Remove the specified resource from storage. */
    public function destroy($id)
    {
        $notification =
            ((new VetApiService(Auth::user()))
                ->delete(VetApiService::PET_MODEL, $id))
                ? 'Pet Was Edited'
                : 'Pet Was Not Edited';

        return ViewService::clientList($notification);
    }
}
