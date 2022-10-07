<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetRequest;
use App\Services\VetApiService;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(int $ownerId)
    {
        return view('pet.create', ['ownerId' => $ownerId]);
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(PetRequest $request)
    {
        $validatedData = $request->validated();
        (new VetApiService(Auth::user()))
            ->create(VetApiService::PET_MODEL, $validatedData);
        return redirect("/clients/{$validatedData['owner_id']}");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show(int $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     */
    public function update(PetRequest $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
    }
}
