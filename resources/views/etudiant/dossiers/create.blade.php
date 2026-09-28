<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-600 leading-tight">
            {{ __('Nouveau Dossier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Créer un nouveau dossier Campus France</h3>
                        <a href="{{ route('etudiant.dossiers.index') }}"
                           class="text-sm text-gray-600 hover:text-gray-900">
                            ← Retour aux dossiers
                        </a>
                    </div>

                    <form method="POST" action="{{ route('etudiant.dossiers.store') }}">
                        @csrf

                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Informations du dossier</h4>

                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="domaine_etude" :value="__('Domaine d\'étude')" />
                                    <x-text-input id="domaine_etude" class="block mt-1 w-full" type="text" name="domaine_etude" :value="old('domaine_etude', Auth::user()->etudiant->domaine_etude ?? '')" required autofocus />
                                    <x-input-error :messages="$errors->get('domaine_etude')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="niveau_etude" :value="__('Niveau d\'étude')" />
                                    <select id="niveau_etude" name="niveau_etude" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">{{ __('Sélectionnez votre niveau') }}</option>
                                        <option value="Terminal" {{ old('niveau_etude', Auth::user()->etudiant->niveau_etude ?? '') == 'Terminal' ? 'selected' : '' }}>Terminal</option>
                                        <option value="Licence 1" {{ old('niveau_etude', Auth::user()->etudiant->niveau_etude ?? '') == 'Licence 1' ? 'selected' : '' }}>Licence 1</option>
                                        <option value="Licence 2" {{ old('niveau_etude', Auth::user()->etudiant->niveau_etude ?? '') == 'Licence 2' ? 'selected' : '' }}>Licence 2</option>
                                        <option value="Licence 3" {{ old('niveau_etude', Auth::user()->etudiant->niveau_etude ?? '') == 'Licence 3' ? 'selected' : '' }}>Licence 3</option>
                                        <option value="Master 1" {{ old('niveau_etude', Auth::user()->etudiant->niveau_etude ?? '') == 'Master 1' ? 'selected' : '' }}>Master 1</option>
                                        <option value="Master 2" {{ old('niveau_etude', Auth::user()->etudiant->niveau_etude ?? '') == 'Master 2' ? 'selected' : '' }}>Master 2</option>
                                        <option value="Doctorat" {{ old('niveau_etude', Auth::user()->etudiant->niveau_etude ?? '') == 'Doctorat' ? 'selected' : '' }}>Doctorat</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('niveau_etude')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="universite_actuelle" :value="__('Université actuelle')" />
                                    <x-text-input id="universite_actuelle" class="block mt-1 w-full" type="text" name="universite_actuelle" :value="old('universite_actuelle', Auth::user()->etudiant->universite_actuelle ?? '')" required />
                                    <x-input-error :messages="$errors->get('universite_actuelle')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Montant et référence</h4>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Montant à payer</p>
                                        <p class="text-lg font-medium text-red-500">200 000 FCFA</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Référence de paiement</p>
                                        <p class="text-lg font-medium text-red-500">REF{{ time() }}{{ rand(1000, 9999) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('etudiant.dossiers.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4">
                                {{ __('Annuler') }}
                            </a>

                            <x-primary-button class="ml-4">
                                {{ __('Créer le dossier') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
