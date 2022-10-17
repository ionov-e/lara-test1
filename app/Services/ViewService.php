<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class ViewService
{
    public static function clientList(string $notification = null)
    {
        $clients = (new VetApiService(Auth::user()))->get(VetApiService::CLIENT_MODEL);
        return view('clients.list', ['clients' => $clients, 'title' => "Client List", 'notification' => $notification]);
    }

    public static function clientShow($id, string $notification = null)
    {
        $apiService = new VetApiService(Auth::user());

        $client = $apiService->get(VetApiService::CLIENT_MODEL, 'id', $id, VetApiService::EQUAL_OPERATOR, 1)[0];

        $pets = $apiService->get(VetApiService::PET_MODEL, 'owner_id', $id, VetApiService::EQUAL_OPERATOR);

        return view('clients.show', compact('client', 'pets', 'notification'));
    }

    public static function petShow(string $id, string $notification = null)
    {
        $pets = (new VetApiService(Auth::user()))
            ->get(VetApiService::PET_MODEL, 'id', $id, VetApiService::EQUAL_OPERATOR, 1);
        if (empty($pets)) {
            logger("APIShowPet: No pet with id: $id");
            return ViewService::clientList("No pet with id: $id");
        }

        $pet = $pets[0];

        return view('pets.show', compact('pet', 'notification'));
    }
}
