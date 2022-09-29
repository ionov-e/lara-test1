<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    const USER_SETTING_MODEL_FIELDS = ['key', 'url'];

    public function create(CreateUserRequest $request)
    {
        $validatedDataForUser = $request->safe()->except(self::USER_SETTING_MODEL_FIELDS);
        $validatedDataForUser['password'] = Hash::make($validatedDataForUser['password']);
        $validatedDataForUserSetting = $request->safe()->only(self::USER_SETTING_MODEL_FIELDS);
        $user = User::create($validatedDataForUser);
        $userSetting = new UserSetting();
        $userSetting->fill($validatedDataForUserSetting);
        $user->userSetting()->save($userSetting);
        return response('User created successfully'); // Ну, можно было вьюшку вернуть
    }
}
