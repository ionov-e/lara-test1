<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use App\Models\UserSetting;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{

    const USER_SETTING_MODEL_FIELDS = ['key', 'url']; // Поля из формы, используемых только для модели UserSetting

    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateUserRequest $request)
    {

        $validatedDataForUser = $request->safe()->except(self::USER_SETTING_MODEL_FIELDS);
        $validatedDataForUser['password'] = Hash::make($validatedDataForUser['password']);
        $user = User::create($validatedDataForUser);

        $validatedDataForUserSetting = $request->safe()->only(self::USER_SETTING_MODEL_FIELDS);
        $userSetting = new UserSetting();
        $userSetting->fill($validatedDataForUserSetting);
        $user->userSetting()->save($userSetting);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
