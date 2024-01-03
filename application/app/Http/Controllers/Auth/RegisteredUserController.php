<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use App\Notifications\Register;
use Illuminate\Support\Facades\URL; //署名付きURLの実装に使用

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/FirstRegister');
    }

    public function register_sendmail(Request $request)
    {
        $request->validate(['email' => 'required|string|lowercase|email|max:255|unique:' . User::class,]);

        $user = DB::transaction(function () use ($request) {
            // ユーザーを作成または取得
            $user = User::create([
                'email' => $request->email,
            ]);
            return $user;
        });

        // 有効期限が30分の署名つきURLを生成する
        $link = URL::temporarySignedRoute('register.form', now()->addMinutes(30), ['user' => $user->id]);

        $user->notify(new Register($link));

        return Inertia::render('Auth/ThanksRegisterEmail');
    }

    public function register(User $user)
    {
        return Inertia::render('Auth/Register', ['user' => $user]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
