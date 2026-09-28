<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Traiter le Dossier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            Charger des documents cabinet - Dossier #{{ $dossier->reference_paiement }}
                        </h3>
                        <a href="{{ route('conseiller.dossiers.show', $dossier->dossier_id) }}"
                           class="text-sm text-gray-600 hover:text-gray-900">
                            ← Retour au dossier
                        </a>
                    </div>

                    <!-- Informations du dossier -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Étudiant</p>
                                <p class="font-medium">{{ $dossier->etudiant->user->prenom }} {{ $dossier->etudiant->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $dossier->etudiant->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Statut actuel</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    bg-green-100 text-green-800">
                                    {{ (new App\Http\Controllers\DossierController())->getStatutFr($dossier->statut) }}
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

                    <!-- Formulaire d'ajout de documents -->
                    <form method="POST" action="{{ route('conseiller.dossiers.traiter', $dossier->dossier_id) }}"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Charger des documents cabinet</h4>

                            <div id="documents-container">
                                <div class="document-item border border-gray-200 rounded-lg p-4 mb-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Type de document</label>
                                            <select name="type_documents[]"
                                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full" required>
                                                <option value="cv">CV</option>
                                                <option value="lettre_motivation">Lettre de motivation</option>
                                                <option value="autres_documents">Autres documents</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="documents[]" :value="__('Fichier')" />
                                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                                <div class="space-y-1 text-center">
                                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600">
                                                        <label for="documents_cabinet[]" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                                            <span>{{ __('Charger un fichier') }}</span>
                                                            <input id="documents_cabinet[]" name="documents_cabinet[]" type="file" class="sr-only" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                                        </label>
                                                        <p class="pl-1">{{ __('ou glisser-déposer') }}</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">
                                                        {{ __('PDF, DOC, DOCX, JPG, JPEG, PNG jusqu\'à 5MB') }}
                                                    </p>
                                                </div>
                                            </div>
                                            <x-input-error :messages="$errors->get('documents.*')" class="mt-2" />
                                        </div>


                                    </div>
                                </div>
                            </div>

                            <button type="button" onclick="ajouterDocument()"
                                    class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Charger un autre document
                            </button>
                        </div>

                        <div class="mb-6">
                            <label for="commentaires" class="block text-sm font-medium text-gray-700 mb-1">
                                Commentaires (optionnel)
                            </label>
                            <textarea id="commentaires" name="commentaires" rows="3"
                                      class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full"
                                      placeholder="Ajoutez des commentaires sur les documents..."></textarea>
                            <p class="mt-1 text-sm text-gray-500">Ces commentaires seront visibles dans le dossier.</p>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('conseiller.dossiers.show', $dossier->dossier_id) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Annuler
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Charger les documents
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Script pour ajouter dynamiquement des documents -->
<script>
function ajouterDocument() {
    const container = document.getElementById('documents-container');
    const newItem = document.createElement('div');
    newItem.className = 'document-item border border-gray-200 rounded-lg p-4 mb-4';
    newItem.innerHTML = `

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type de document</label>
                <select name="type_documents[]"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full" required>
                    <option value="cv">CV</option>
                    <option value="lettre_motivation">Lettre de motivation</option>
                    <option value="autres_documents">Autres documents</option>
                </select>
            </div>
            <div>
                <x-input-label for="documents[]" :value="__('Fichier')" />
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="documents_cabinet[]" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                <span>{{ __('Charger un fichier') }}</span>
                                <input id="documents_cabinet[]" name="documents_cabinet[]" type="file" class="sr-only" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                            </label>
                            <p class="pl-1">{{ __('ou glisser-déposer') }}</p>
                        </div>
                        <p class="text-xs text-gray-500">
                            {{ __('PDF, DOC, DOCX, JPG, JPEG, PNG jusqu\'à 5MB') }}
                        </p>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('documents.*')" class="mt-2" />
            </div>


        </div>
        <div class="mt-2">
            <button type="button" onclick="this.parentElement.parentElement.remove()"
                    class="text-sm text-red-600 hover:text-red-800">
                Supprimer ce document
            </button>
        </div>
    `;
    container.appendChild(newItem);
}
</script>
