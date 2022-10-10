<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetRequest;
use App\Services\VetApiService;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    /** Show the form for creating a new resource. */
    public function create(int $ownerId)
    {
        return view('pets.create', compact('ownerId'));
    }

    /** Store a newly created resource in storage. */
    public function store(PetRequest $request)
    {
        $validatedData = $request->validated();
        (new VetApiService(Auth::user()))
            ->create(VetApiService::PET_MODEL, $validatedData);

        return redirect()->route('clients.show', $validatedData['owner_id']);
    }

    /** Display the specified resource. */
    public function show(int $id)
    {
        $petData = (new VetApiService(Auth::user()))
            ->get(VetApiService::PET_MODEL, 'id', $id, VetApiService::EQUAL_OPERATOR, 1);
        if (empty($petData)) {
            logger("APIShowPet: No pet with id: $id");
            return redirect()->route('clients.index');
        }
        return view('pets.show', ['pet' => $petData[0]]);
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
        (new VetApiService(Auth::user()))
            ->edit(VetApiService::PET_MODEL, $validatedData, $id);

        return redirect()->route('pets.show', $id);
    }

    /** Remove the specified resource from storage. */
    public function destroy($id)
    {
        (new VetApiService(Auth::user()))
            ->delete(VetApiService::PET_MODEL, $id);

        return redirect()->route('clients.index');
    }
}
