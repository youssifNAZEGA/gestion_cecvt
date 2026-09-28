<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Votre profile') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Mettez à jour les informations de votre profil et votre adresse email.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nom')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->updateProfileInformation->get('name')" />
        </div>

        <div>
            <x-input-label for="prenom" :value="__('Prénom')" />
            <x-text-input id="prenom" name="prenom" type="text" class="mt-1 block w-full" :value="old('prenom', $user->prenom)" required />
            <x-input-error class="mt-2" :messages="$errors->updateProfileInformation->get('prenom')" />
        </div>

        <div>
            <x-input-label for="sexe" :value="__('Sexe')" />
            <select id="sexe" name="sexe" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="homme" {{ old('sexe', $user->sexe) == 'homme' ? 'selected' : '' }}>{{ __('Homme') }}</option>
                <option value="femme" {{ old('sexe', $user->sexe) == 'femme' ? 'selected' : '' }}>{{ __('Femme') }}</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->updateProfileInformation->get('sexe')" />
        </div>

        <div>
            <x-input-label for="contact" :value="__('Contact')" />
            <x-text-input id="contact" name="contact" type="text" class="mt-1 block w-full" :value="old('contact', $user->contact)" required />
            <x-input-error class="mt-2" :messages="$errors->updateProfileInformation->get('contact')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->updateProfileInformation->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Votre adresse email n\'est pas vérifiée.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Cliquez ici pour renvoyer l\'email de vérification.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Un nouveau lien de vérification a été envoyé à votre adresse email.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Champs étudiant (si c'est un étudiant) -->
        @if($user->role === 'etudiant' && $user->etudiant)
            @php $etudiant = $user->etudiant @endphp

            <div class="border-t border-gray-200 pt-6 mt-6">
                <h3 class="text-lg font-medium text-gray-900">{{ __('Informations Étudiant') }}</h3>

                <div class="mt-4">
                    <x-input-label for="date_naissance" :value="__('Date de naissance')" />
                    <x-text-input id="date_naissance" name="date_naissance" type="date" class="mt-1 block w-full" :value="old('date_naissance', $etudiant->date_naissance ?? '')" />
                    <x-input-error class="mt-2" :messages="$errors->updateProfileInformation->get('date_naissance')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="lieu_naissance" :value="__('Lieu de naissance')" />
                    <x-text-input id="lieu_naissance" name="lieu_naissance" type="text" class="mt-1 block w-full" :value="old('lieu_naissance', $etudiant->lieu_naissance ?? '')" />
                    <x-input-error class="mt-2" :messages="$errors->updateProfileInformation->get('lieu_naissance')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="numero_passeport" :value="__('Numéro de passeport')" />
                    <x-text-input id="numero_passeport" name="numero_passeport" type="text" class="mt-1 block w-full" :value="old('numero_passeport', $etudiant->numero_passeport ?? '')" />
                    <x-input-error class="mt-2" :messages="$errors->updateProfileInformation->get('numero_passeport')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="universite_actuelle" :value="__('Université actuelle')" />
                    <x-text-input id="universite_actuelle" name="universite_actuelle" type="text" class="mt-1 block w-full" :value="old('universite_actuelle', $etudiant->universite_actuelle ?? '')" />
                    <x-input-error class="mt-2" :messages="$errors->updateProfileInformation->get('universite_actuelle')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="niveau_etude" :value="__('Niveau d\'étude')" />
                    <select id="niveau_etude" name="niveau_etude" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">{{ __('Sélectionnez votre niveau') }}</option>
                        <option value="Terminal" {{ old('niveau_etude', $etudiant->niveau_etude ?? '') == 'Terminal' ? 'selected' : '' }}>Terminal</option>
                        <option value="Licence 1" {{ old('niveau_etude', $etudiant->niveau_etude ?? '') == 'Licence 1' ? 'selected' : '' }}>Licence 1</option>
                        <option value="Licence 2" {{ old('niveau_etude', $etudiant->niveau_etude ?? '') == 'Licence 2' ? 'selected' : '' }}>Licence 2</option>
                        <option value="Licence 3" {{ old('niveau_etude', $etudiant->niveau_etude ?? '') == 'Licence 3' ? 'selected' : '' }}>Licence 3</option>
                        <option value="Master 1" {{ old('niveau_etude', $etudiant->niveau_etude ?? '') == 'Master 1' ? 'selected' : '' }}>Master 1</option>
                        <option value="Master 2" {{ old('niveau_etude', $etudiant->niveau_etude ?? '') == 'Master 2' ? 'selected' : '' }}>Master 2</option>
                        <option value="Doctorat" {{ old('niveau_etude', $etudiant->niveau_etude ?? '') == 'Doctorat' ? 'selected' : '' }}>Doctorat</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->updateProfileInformation->get('niveau_etude')" />
                </div>

                <div class="mt-4">
                    <x-input-label for="domaine_etude" :value="__('Domaine d\'étude')" />
                    <x-text-input id="domaine_etude" name="domaine_etude" type="text" class="mt-1 block w-full" :value="old('domaine_etude', $etudiant->programme_souhaite ?? '')" />
                    <x-input-error class="mt-2" :messages="$errors->updateProfileInformation->get('domaine_etude')" />
                </div>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button class="ml-4">Modifier</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Sauvegardé.') }}</p>
            @endif
        </div>
    </form>
</section>
