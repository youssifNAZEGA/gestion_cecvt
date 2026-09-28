<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nom')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>


        <div>
            <x-input-label for="prenom" :value="__('Prénom')" />
            <x-text-input id="prenom" class="block mt-1 w-full" type="text" name="prenom" :value="old('prenom')" required autofocus />
            <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="sexe" :value="__('Sexe')" />
            <select id="sexe" name="sexe" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="homme" {{ old('sexe') == 'homme' ? 'selected' : '' }}>{{ __('Homme') }}</option>
                <option value="femme" {{ old('sexe') == 'femme' ? 'selected' : '' }}>{{ __('Femme') }}</option>
            </select>
            <x-input-error :messages="$errors->get('sexe')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="contact" :value="__('Contact')" />
            <x-text-input id="contact" class="block mt-1 w-full" type="text" name="contact" :value="old('contact')" required />
            <x-input-error :messages="$errors->get('contact')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="date_naissance" :value="__('Date de naissance')" />
            <x-text-input id="date_naissance" class="block mt-1 w-full" type="date" name="date_naissance" :value="old('date_naissance')" />
            <x-input-error :messages="$errors->get('date_naissance')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="lieu_naissance" :value="__('Lieu de naissance')" />
            <x-text-input id="lieu_naissance" class="block mt-1 w-full" type="text" name="lieu_naissance" :value="old('lieu_naissance')" />
            <x-input-error :messages="$errors->get('lieu_naissance')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="numero_passeport" :value="__('Numéro de passeport(Optionel)')" />
            <x-text-input id="numero_passeport" class="block mt-1 w-full" type="text" name="numero_passeport" :value="old('numero_passeport')" />
            <x-input-error :messages="$errors->get('numero_passeport')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="universite_actuelle" :value="__('Université actuelle')" />
            <x-text-input id="universite_actuelle" class="block mt-1 w-full" type="text" name="universite_actuelle" :value="old('universite_actuelle')" />
            <x-input-error :messages="$errors->get('universite_actuelle')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="niveau_etude" :value="__('Niveau d\'étude')" />
            <select id="niveau_etude" name="niveau_etude" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="">{{ __('Sélectionnez votre niveau') }}</option>
                <option value="Terminal" {{ old('niveau_etude') == 'Terminal' ? 'selected' : '' }}>Terminal</option>
                <option value="Licence 1" {{ old('niveau_etude') == 'Licence 1' ? 'selected' : '' }}>Licence 1</option>
                <option value="Licence 2" {{ old('niveau_etude') == 'Licence 2' ? 'selected' : '' }}>Licence 2</option>
                <option value="Licence 3" {{ old('niveau_etude') == 'Licence 3' ? 'selected' : '' }}>Licence 3</option>
                <option value="Master 1" {{ old('niveau_etude') == 'Master 1' ? 'selected' : '' }}>Master 1</option>
                <option value="Master 2" {{ old('niveau_etude') == 'Master 2' ? 'selected' : '' }}>Master 2</option>
                <option value="Doctorat" {{ old('niveau_etude') == 'Doctorat' ? 'selected' : '' }}>Doctorat</option>
            </select>
            <x-input-error :messages="$errors->get('niveau_etude')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="domaine_etude" :value="__('Domaine d\'étude')" />
            <x-text-input id="domaine_etude" class="block mt-1 w-full" type="text" name="domaine_etude" :value="old('domaine_etude')" required autofocus />
            <x-input-error :messages="$errors->get('domaine_etude')" class="mt-2" />
        </div>
        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
