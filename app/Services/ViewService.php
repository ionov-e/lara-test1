<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class ViewService
{
    public static function clientList(string $notification = null)
    {
        $clients = (new VetApiService(Auth::user()))->get(VetApiService::MODEL_CLIENT);
        return view('clients.list', ['clients' => $clients, 'title' => "Client List", 'notification' => $notification]);
    }

    public static function clientShow($id, string $notification = null)
    {
        $apiService = new VetApiService(Auth::user());

        $client = $apiService->get(VetApiService::MODEL_CLIENT, 'id', $id, VetApiService::OPERATOR_EQUAL, 1)[0];

        $pets = $apiService->get(VetApiService::MODEL_PET, 'owner_id', $id, VetApiService::OPERATOR_EQUAL);

        return view('clients.show', compact('client', 'pets', 'notification'));
    }

    public static function petShow(int $id, string $notification = null)
    {
        return self::petShowOrEdit($id, $notification, 'show');
    }

    public static function petEdit(int $id, string $notification = null)
    {
        return self::petShowOrEdit($id, $notification, 'edit');
    }

    private static function petShowOrEdit (int $id, $notification, string $method)
    {
        $pets = (new VetApiService(Auth::user()))
            ->get(VetApiService::MODEL_PET, 'id', $id, VetApiService::OPERATOR_EQUAL, 1);
        if (empty($pets)) {
            logger("API{$method}Pet: No pet with id: $id");
            return ViewService::clientList("No pet with id: $id");
        }

        $pet = $pets[0];

        return view("pets.$method", compact('pet', 'id', 'notification'));
    }
}
