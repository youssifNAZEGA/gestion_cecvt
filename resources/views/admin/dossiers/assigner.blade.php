<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assigner un dossier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Assigner le dossier #{{ $dossier->reference_paiement }}</h3>
                        <a href="{{ route('admin.dossiers.index') }}"
                           class="text-sm text-gray-600 hover:text-gray-900">
                            ← Retour
                        </a>
                    </div>

                    <!-- Informations du dossier -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Étudiant</p>
                                <p class="font-medium">{{ $dossier->etudiant->user->prenom }} {{ $dossier->etudiant->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Statut actuel</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($dossier->statut === 'termine') bg-green-100 text-green-800
                                    @elseif($dossier->statut === 'attente_paiement') bg-yellow-100 text-yellow-800
                                    @elseif($dossier->statut === 'documents_en_cours') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $dossier->statut)) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Montant</p>
                                <p class="font-medium">{{ number_format($dossier->montant, 0, ',', ' ') }} FCFA</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Date de création</p>
                                <p class="font-medium">{{ $dossier->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire d'assignation -->
                    <form method="POST" action="{{ route('admin.dossiers.assigner', $dossier->dossier_id) }}">
                        @csrf

                        <div>
                            <x-input-label for="conseiller_id" :value="__('Conseiller à assigner')" />
                            <select id="conseiller_id" name="conseiller_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">{{ __('Sélectionnez un conseiller') }}</option>
                                @foreach($conseillers as $conseiller)
                                    <option value="{{ $conseiller->user_id }}">
                                        {{ $conseiller->prenom }} {{ $conseiller->name }}
                                        @if($dossier->assigne_a === $conseiller->user_id) (actuellement assigné) @endif
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('conseiller_id')" class="mt-2" />
                        </div>

                        <!-- Informations sur les conseillers -->
                        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-blue-800 mb-2">Conseillers disponibles</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-blue-700">
                                @foreach($conseillers as $conseiller)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-200 flex items-center justify-center text-xs mr-2">
                                            {{ substr($conseiller->prenom, 0, 1) }}{{ substr($conseiller->name, 0, 1) }}
                                        </div>
                                        <span>{{ $conseiller->prenom }} {{ $conseiller->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('admin.dossiers.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4">
                                {{ __('Annuler') }}
                            </a>

                            <x-primary-button class="ml-4">
                                {{ __('Assigner le dossier') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
