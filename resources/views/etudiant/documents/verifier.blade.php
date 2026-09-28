<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-600 leading-tight">
            {{ __('Vérifier un document') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            Vérifier : {{ ucfirst(str_replace('_', ' ', $document->type_document)) }}
                        </h3>
                        <a href="{{ route('etudiant.dossiers.show', $document->dossier->dossier_id) }}"
                           class="text-sm text-gray-600 hover:text-gray-900">
                            ← Retour au dossier
                        </a>
                    </div>

                    <!-- Informations du document -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Étudiant</p>
                                <p class="font-medium">{{ $document->dossier->etudiant->user->prenom }} {{ $document->dossier->etudiant->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Dossier</p>
                                <p class="font-medium">#{{ $document->dossier->reference_paiement }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Nom du fichier</p>
                                <p class="font-medium">{{ $document->nom_original }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Date d'upload</p>
                                <p class="font-medium">{{ $document->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Aperçu du document -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-900 mb-3">Aperçu du document</h4>
                        <div class="border border-gray-200 rounded-lg overflow-hidden bg-gray-100">
                            @if(in_array(pathinfo($document->chemin_fichier, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ Storage::url($document->chemin_fichier) }}"
                                     alt="Document"
                                     class="w-full max-h-96 object-contain">
                            @else
                                <div class="p-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">Fichier : {{ $document->nom_original }}</p>
                                    <a href="{{ Storage::url($document->chemin_fichier) }}" target="_blank"
                                       class="mt-2 inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                        Télécharger
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Formulaire de vérification -->
                    <form method="POST" action="{{ route('etudiant.documents.verifier', $document->document_id) }}">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Action de vérification</label>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer">
                                    <input type="radio" name="action" value="valider" class="mr-3 h-5 w-5 text-green-600" required>
                                    <div>
                                        <span class="font-medium text-green-700">Valider le document</span>
                                        <p class="text-sm text-gray-600 mt-1">Le document est conforme et complet.</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-red-50 cursor-pointer">
                                    <input type="radio" name="action" value="corriger" class="mr-3 h-5 w-5 text-red-600" required>
                                    <div>
                                        <span class="font-medium text-red-700">Demander des corrections</span>
                                        <p class="text-sm text-gray-600 mt-1">Le document nécessite des modifications.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="commentaires" class="block text-sm font-medium text-gray-700 mb-1">
                                Commentaires (optionnel)
                            </label>
                            <textarea id="commentaires" name="commentaires" rows="4"
                                      class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full"
                                      placeholder="Ajoutez des commentaires pour l'étudiant..."></textarea>
                            <p class="mt-1 text-sm text-gray-500">Ces commentaires seront visibles par l'étudiant.</p>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('conseiller.dossiers.show', $document->dossier->dossier_id) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Annuler
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Valider la vérification
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
