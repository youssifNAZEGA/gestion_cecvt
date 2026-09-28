<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer un utilisateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Nouvel utilisateur</h3>
                        <a href="{{ route('admin.utilisateurs.index') }}"
                           class="text-sm text-gray-600 hover:text-gray-900">
                            ← Retour à la liste
                        </a>
                    </div>

                    <!-- Formulaire de création -->
                    <form method="POST" action="{{ route('admin.utilisateurs.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <x-input-label for="name" :value="__('Nom')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Prénom -->
                            <div>
                                <x-input-label for="prenom" :value="__('Prénom')" />
                                <x-text-input id="prenom" class="block mt-1 w-full" type="text" name="prenom" :value="old('prenom')" required />
                                <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
                            </div>

                            <!-- Sexe -->
                            <div>
                                <x-input-label for="sexe" :value="__('Sexe')" />
                                <select id="sexe" name="sexe" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">{{ __('Sélectionnez le sexe') }}</option>
                                    <option value="homme" {{ old('sexe') == 'homme' ? 'selected' : '' }}>{{ __('Homme') }}</option>
                                    <option value="femme" {{ old('sexe') == 'femme' ? 'selected' : '' }}>{{ __('Femme') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('sexe')" class="mt-2" />
                            </div>

                            <!-- Contact -->
                            <div>
                                <x-input-label for="contact" :value="__('Contact')" />
                                <x-text-input id="contact" class="block mt-1 w-full" type="text" name="contact" :value="old('contact')" required />
                                <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div class="md:col-span-2">
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Mot de passe -->
                            <div>
                                <x-input-label for="password" :value="__('Mot de passe')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                <p class="mt-1 text-sm text-gray-500">Minimum 8 caractères</p>
                            </div>

                            <!-- Confirmation mot de passe -->
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <!-- Rôle -->
                            <div class="md:col-span-2">
                                <x-input-label for="role" :value="__('Rôle')" />
                                <select id="role" name="role" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="">{{ __('Sélectionnez un rôle') }}</option>
                                    <option value="conseiller" {{ old('role') == 'conseiller' ? 'selected' : '' }}>{{ __('Conseiller') }}</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>{{ __('Administrateur') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />

                                <div class="mt-2 text-sm text-gray-500">
                                    <p><strong>Conseiller :</strong> Peut gérer les dossiers étudiants</p>
                                    <p><strong>Administrateur :</strong> Peut gérer tous les utilisateurs et paramètres</p>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('admin.utilisateurs.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4">
                                {{ __('Annuler') }}
                            </a>

                            <x-primary-button class="ml-4">
                                {{ __('Créer l\'utilisateur') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
