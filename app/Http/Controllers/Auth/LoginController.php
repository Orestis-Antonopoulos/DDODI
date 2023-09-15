<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Player;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::GAME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function loginOrRegister(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::firstOrNew(['username' => $request->username]);

        $action = $request->input('action');

        if (!$user->exists) {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);
            $player = new Player();
            $player->name = $user->username;  // Provide a name here
            $player->user_id = $user->id;  // Set the user ID
            $player->STR = rand(1,20);
            $player->DEX = rand(1,20);
            $player->CON = rand(1,20);
            $player->level = 1;
            $player->experience = 0;
            $player->hp = round(10+(($player->CON -10)/2)+(($player->level -1)*(6+(($player->CON -10)/2))));
            $player->bosses = 0;
            $player->save();  // Save the player to the database

            // Log in the new user
            $this->guard()->login($user);

            return redirect($this->redirectPath());
        } else {
            // If user exists, attempt login
            if (!Hash::check($request->password, $user->password)) {
                return redirect()->back()->withErrors(['password' => 'Incorrect password']);
            }

            // Log the user in
            $this->guard()->login($user);

            return redirect($this->redirectPath());

        }

        return redirect()->back();
    }
}

