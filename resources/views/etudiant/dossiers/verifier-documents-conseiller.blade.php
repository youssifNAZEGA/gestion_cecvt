<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-600 leading-tight">
            {{ __('Vérifier les documents du conseiller') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            Dossier #{{ $dossier->reference_paiement }}
                        </h3>
                        <a href="{{ route('etudiant.dossiers.show', $dossier->dossier_id) }}"
                           class="text-sm text-gray-600 hover:text-gray-900">
                            ← Retour au dossier
                        </a>
                    </div>

                    <!-- Informations du dossier -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Étudiant</p>
                                <p class="font-medium">
                                    {{ $dossier->etudiant->user->prenom }} {{ $dossier->etudiant->user->name }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $dossier->etudiant->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Conseiller assigné</p>
                                <p class="font-medium">
                                    {{ $dossier->assigne->prenom }} {{ $dossier->assigne->name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $dossier->statut === 'attente_validation_finale' ? 'En attente de votre validation' : 'Statut actuel' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Documents du conseiller -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-medium text-gray-900">Documents ajoutés par le conseiller</h4>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $documentsConseiller->count() }} document(s)
                            </span>
                        </div>

                        @if($documentsConseiller->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                @foreach($documentsConseiller as $document)
                                    <div class="border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow">
                                        <div class="p-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <h5 class="font-medium text-gray-900 text-sm">
                                                    {{ ucfirst(str_replace('_', ' ', $document->type_document)) }}
                                                </h5>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Document vérifier par conseiller
                                                </span>
                                            </div>

                                            <p class="text-xs text-gray-500 mb-3">{{ $document->nom_original }}</p>

                                            <div class="flex justify-between items-center">
                                                <a href="{{ Storage::url($document->chemin_fichier) }}"
                                                   target="_blank"
                                                   class="text-blue-600 hover:text-blue-800 text-xs">
                                                    Voir le document
                                                </a>

                                                @if($document->verifiePar)
                                                    <div class="text-xs text-gray-500">
                                                        Par {{ $document->verifiePar->prenom }} {{ substr($document->verifiePar->name, 0, 1) }}.
                                                    </div>
                                                @endif
                                            </div>

                                            @if($document->verifie_a)
                                                <div class="mt-2 text-xs text-gray-400">
                                                    Le {{ $document->verifie_a->format('d/m/Y H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 bg-gray-50 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun document du conseiller</h3>
                                <p class="mt-1 text-sm text-gray-500">Le conseiller n'a pas encore ajouté de documents.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Instructions de validation -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Validation requise</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Veuillez vérifier attentivement les documents ajoutés par le conseiller. Si tout vous semble correct, validez le dossier. Sinon, demandez des corrections.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de validation -->
                    <form method="POST" action="{{ route('etudiant.dossiers.verifier-documents-conseiller', $dossier->dossier_id) }}">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Votre décision</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="flex items-center p-4 border border-green-200 rounded-lg hover:bg-green-50 cursor-pointer">
                                    <input type="radio" name="action" value="valider" class="mr-3 h-5 w-5 text-green-600" required>
                                    <div>
                                        <span class="font-medium text-green-700">Tout est correct</span>
                                        <p class="text-sm text-gray-600 mt-1">Valider tous les documents et terminer le dossier.</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border border-red-200 rounded-lg hover:bg-red-50 cursor-pointer">
                                    <input type="radio" name="action" value="corriger" class="mr-3 h-5 w-5 text-red-600" required>
                                    <div>
                                        <span class="font-medium text-red-700">Demander des corrections</span>
                                        <p class="text-sm text-gray-600 mt-1">Certains documents nécessitent des modifications.</p>
                                    </div>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('action')" class="mt-2" />
                        </div>

                        <div id="motif-rejet-section" class="mb-6" style="display: none;">
                            <label for="motif_rejet" class="block text-sm font-medium text-gray-700 mb-2">
                                Motif de la demande de correction
                            </label>
                            <textarea id="motif_rejet" name="motif_rejet" rows="3"
                                      class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full text-sm"
                                      placeholder="Expliquez pourquoi des corrections sont nécessaires..."></textarea>
                            <p class="mt-1 text-sm text-gray-500">Ces commentaires seront visibles par le conseiller.</p>
                            <x-input-error :messages="$errors->get('motif_rejet')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('etudiant.dossiers.show', $dossier->dossier_id) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4">
                                {{ __('Annuler') }}
                            </a>

                            <x-primary-button class="ml-4">
                                {{ __('Valider ma décision') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Script pour afficher/cacher le motif de rejet -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="action"]');
    const motifRejetSection = document.getElementById('motif-rejet-section');

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'corriger') {
                motifRejetSection.style.display = 'block';
            } else {
                motifRejetSection.style.display = 'none';
            }
        });
    });
});
</script>
