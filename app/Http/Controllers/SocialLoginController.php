<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Providers\RouteServiceProvider;

class SocialLoginController extends Controller
{
    //const para redirecionar para rota home
    protected $redirectTo = RouteServiceProvider::HOME;

    //verifica se usuário não esta autenticado
    public function __construct()
    {
        $this->middleware('guest');
    }

    //redireciona o usuário para o site de login do provider
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    //método onde o provider redireciona o usuário para a aplicação novamente e é realizado cadastro e login
    public function handleProviderCallback($provider)
    {
        $providerUser = Socialite::driver($provider)->user();

        $user = User::firstOrCreate([
            'email'                     => $providerUser->getEmail(),
            'name'                      => $providerUser->getName(),
            'provider_id'               => $providerUser->getId(),
            'provider'                  => $provider
        ]);

        Auth::login($user);

        return redirect($this->redirectTo);
    }
}
