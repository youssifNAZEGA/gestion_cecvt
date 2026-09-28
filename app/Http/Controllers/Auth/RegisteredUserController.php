<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Etudiant;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
            $request->validate([
                'name' => 'required|string',
                'prenom' => 'required|string',
                'sexe' => ['required','string', 'in:homme,femme'],
                'contact' => 'required',
                'email' =>['required', 'email', 'unique:'.User::class],
                'password' => ['required', 'confirmed'],
                'date_naissance' => 'required',
                'lieu_naissance' => 'nullable|string',
                'numero_passeport' => 'nullable|string',
                'universite_actuelle' => 'nullable|string',
                'niveau_etude' => 'required',
                'domaine_etude' => 'nullable',
            ]);

            $user = User::create([
                'name' => $request->name,
                'prenom' => $request->prenom,
                'sexe' => $request->sexe,
                'contact' => $request->contact,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'etudiant',
            ]);

            Etudiant::create([
                'user_id' => $user->user_id,
                'date_naissance' => $request->date_naissance,
                'lieu_naissance' => $request->lieu_naissance,
                'numero_passeport' => $request->numero_passeport,
                'universite_actuelle' => $request->universite_actuelle,
                'niveau_etude' => $request->niveau_etude,
                'domaine_etude' => $request->domaine_etude,
            ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/etudiant/dashboard');
    }
}
