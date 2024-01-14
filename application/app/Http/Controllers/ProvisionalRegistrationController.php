<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProvisionalRegistrationRequest;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\ProvisionalRegistration;
use App\Consts\RegisterStatusConsts;
use Illuminate\Support\Facades\DB;
use App\Notifications\Register;
use Illuminate\Support\Facades\URL; //署名付きURLの実装に使用

class ProvisionalRegistrationController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/FirstRegister');
    }


    public function sendmail(ProvisionalRegistrationRequest $request)
    {
        $data = $request->validated();
        $temp_user = DB::transaction(function () use ($data) {

            $user = ProvisionalRegistration::firstOrCreate([
                'email' => $data['email'],
                'status' => RegisterStatusConsts::SEND_MAIL
            ]);
            return $user;
        });

        // 有効期限が30分の署名つきURLを生成する
        $link = URL::temporarySignedRoute('register.form', now()->addMinutes(30), ['user' => $temp_user->id]);

        $temp_user->notify(new Register($link));

        return Inertia::render('Auth/ThanksRegisterEmail');
    }
}
