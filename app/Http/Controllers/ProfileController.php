<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use App\Models\User;



class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        \Log::info("Profile update request : ", $request->all());

        if ($request->user()->role === 'admin' || $request->user()->role === 'conseiller') {
            $validated_data = $request->validate([
                'name' => ['required', 'string'],
                'prenom' => ['required', 'string'],
                'sexe' => ['required', 'in:homme,femme'],
                'contact' => ['required', 'string'],
                'email' => ['required', 'string', 'email',
                        Rule::unique(User::class)->ignore($request->user()->user_id, 'user_id')],
            ]);
        } elseif ($request->user()->role === 'etudiant') {
            $validated_data = $request->validate([
                'name' => ['required', 'string'],
                'prenom' => ['required', 'string'],
                'sexe' => ['required', 'in:homme,femme'],
                'contact' => ['required', 'string'],
                'email' => ['required', 'string', 'email',
                        Rule::unique(User::class)->ignore($request->user()->user_id, 'user_id')],
                'date_naissance' => ['required', 'date'],
                'lieu_naissance' => ['required', 'string'],
                'numero_passeport' => ['required', 'string'],
                'universite_actuelle' => ['required', 'string'],
                'niveau_etude' => ['required', 'string'],
                'domaine_etude' => ['nullable', 'string'],
            ]);

            $etudiant = $request->user()->etudiant;
            if ($etudiant) {
                $etudiant->update([
                    'date_naissance' => $validated_data['date_naissance'],
                    'lieu_naissance' => $validated_data['lieu_naissance'],
                    'numero_passeport' => $validated_data['numero_passeport'],
                    'universite_actuelle' => $validated_data['universite_actuelle'],
                    'niveau_etude' => $validated_data['niveau_etude'],
                    'domaine_etude' => $validated_data['domaine_etude'],
                ]);
            }
        }

        $request->user()->fill($validated_data);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $saved = $request->user()->save();

        \Log::info("User Modifier : ", [
            'saved' => $saved,
            'user' => $request->user()->toArray(),
        ]);

        return Redirect::route('profile.edit')->with('success', 'Profil modifié avec succès');
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
