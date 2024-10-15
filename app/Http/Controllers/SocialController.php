<?php

namespace App\Http\Controllers;

use App\Models\Social;
use App\Models\User;
use App\Rules\NoSpaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirect($provider): \Symfony\Component\HttpFoundation\Response
    {
        return Inertia::location(Socialite::driver($provider)->redirect());
    }

    public function callback($provider): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Throwable $th) {
            return redirect(route('login'))->with('error', 'Erreur lors de la connexion avec ' . $provider);
        }
//        dd(Socialite::driver($provider)->user());

        // check if already exists
        $user = User::where('email', $socialUser->getEmail())->first();
        $name = $socialUser->getNickname() ?? $socialUser->getName();
        $name = $this->generateValidName($name);

        // if it doesn't exist
        if (!$user) {
            // create user
            $user = User::create([
                'name' => $name,
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(10)),
                'email_verified_at' => now(),
                'remember_token' => Str::random(60)
            ]);
            // create socials for user
            $user->socials()->create([
                'provider_id' => $socialUser->getId(),
                'provider' => $provider,
                'provider_token' => $socialUser->token,
                'provider_refresh_token' => $socialUser->refreshToken
            ]);
        }

        // if user does exist
        $socials = Social::where('provider', $provider)
            ->where('user_id', $user->id)->first();

        //check if user doesn't have socials
        if (!$socials) {
            // add socials to user
            $user->socials()->create([
                'provider_id' => $socialUser->getId(),
                'provider' => $provider,
                'provider_token' => $socialUser->token,
                'provider_refresh_token' => $socialUser->refreshToken
            ]);
        }

        // login user
        Auth::login($user, true);
        if (url()->previous() === route('admin.login.create')) {
            return redirect()->route('admin.dashboard')->with('success', 'Connexion avec ' . $provider . ' réussie');
        } else {
            return redirect()->intended(route('profile.settings', $user->name))->with('success', 'Connexion avec ' . $provider . ' réussie');
        }
    }


    /**
     * Generate a valid name for a user from a given name or generate a random one
     *
     * @param string|null $name
     * @return string
     */
    public function generateValidName(?string $name): string
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:16',
                'regex:/^[a-zA-Z0-9 ]+$/',
                new NoSpaces(),
                Rule::unique('users'),
            ],
        ];

        if ($name) {
            $validator = Validator::make(['name' => $name], $rules);

            if (!$validator->fails()) {
                return $name;
            }
        }

        // if the name is not valid or not provided generate a random one
        return $this->generateRandomName();
    }

    /**
     * Generate a random name for a user
     *
     * @return string
     */
    public function generateRandomName(): string
    {
        do {
            $pseudo = Str::random(3) . ucfirst(Str::random(rand(4, 13)));
        } while (User::where('name', $pseudo)->exists()); // Assure that the name is unique

        return $pseudo;
    }
}
