<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-600 leading-tight">
            {{ __('Charger plusieurs documents') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Messages de succès/erreur -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

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
                                <p class="text-sm text-gray-500">Statut actuel</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($dossier->statut === 'termine') bg-green-100 text-green-800
                                    @elseif($dossier->statut === 'documents_a_corriger') bg-red-100 text-red-800
                                    @elseif($dossier->statut === 'attente_paiement') bg-yellow-100 text-yellow-800
                                    @elseif($dossier->statut === 'attente_verification_documents') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ (new App\Http\Controllers\DossierController())->getStatutFr($dossier->statut) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire multiple documents -->
                    <form method="POST" action="{{ route('etudiant.dossiers.upload-documents', $dossier->dossier_id) }}"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-md font-medium text-gray-900">Documents à charger</h4>

                            </div>

                            <div id="documents-container">
                                <!-- Premier document -->
                                <div class="document-item border border-gray-200 rounded-lg p-4 mb-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label for="type_documents[]" :value="__('Type de document')" />
                                            <p class="text-xs text-gray-500">
                                                    Pour eviter les corrections,selectionnez bien les documents qui vous sont réquis selon votre niveau candidaté.
                                            </p>
                                            <select name="type_documents[]"
                                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                                <option value="">{{ __('Sélectionnez un type') }}</option>
                                                <option value="Bulletins_seconde">Bulletins de notes Séconde(semestres(1 et 2)/trimestres(1,2 et 3))</option>
                                                <option value="Bulletins_premiere">Bulletins de notes Première(semestres(1 et 2)/trimestres(1,2 et 3))</option>
                                                <option value="Bulletins_Terminale">Bulletins de notes Terminale(semestres(1 et 2)/trimestres(1,2 et 3))</option>
                                                <option value="releves_examen_BAC1">Relevés de notes d'examen BAC1</option>
                                                <option value="releves_examen_BAC2">Relevés de notes d'examen BAC</option>
                                                <option value="releves_licence_1">Relevés de notes L1(Licence1: S1 et S2)</option>
                                                <option value="releves_licence_2">Relevés de notes L2(Licence2: S3 et S4)</option>
                                                <option value="releves_licence_3">Relevés de notes L3(Licence3: S5 et S6)</option>
                                                <option value="attestation_bac">Attestation  BAC</option>
                                                <option value="attestation_licence">Attestation  licence</option>
                                                <option value="acte_de_naissance">Acte de naissance</option>
                                                <option value="photo_identite">Photo d'identité</option>
                                                <option value="passport/carte_identite">Passport/Carte d'identité</option>

                                            </select>
                                            <x-input-error :messages="$errors->get('type_documents.*')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="documents[]" :value="__('Fichier')" />
                                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                                <div class="space-y-1 text-center">
                                                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600">
                                                        <label for="documents[]" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                                            <span>{{ __('Charger un fichier') }}</span>
                                                            <input id="documents[]" name="documents[]" type="file" class="sr-only" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
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
                        </div>

                        <button type="button" onclick="ajouterDocument()"
                                        class="inline-flex items-center px-3 py-1 border border-black text-black rounded-md text-sm hover:bg-blue-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Ajouter un document
                                </button>

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('etudiant.dossiers.show', $dossier->dossier_id) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4">
                                {{ __('Annuler') }}
                            </a>

                            <x-primary-button class="ml-4">
                                {{ __('Charger les documents') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

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
                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    <option value="">Sélectionnez un type</option>
                    <option value="Bulletins_seconde">Bulletins de notes Séconde(semestres(1 et 2)/trimestres(1,2 et 3))</option>
                    <option value="Bulletins_premiere">Bulletins de notes Première(semestres(1 et 2)/trimestres(1,2 et 3))</option>
                    <option value="Bulletins_Terminale">Bulletins de notes Terminale(semestres(1 et 2)/trimestres(1,2 et 3))</option>
                    <option value="releves_examen_BAC1">Relevés de notes d'examen BAC1</option>
                    <option value="releves_examen_BAC2">Relevés de notes d'examen BAC</option>
                    <option value="releves_licence_1">Relevés de notes L1(Licence1: S1 et S2)</option>
                    <option value="releves_licence_2">Relevés de notes L2(Licence2: S3 et S4)</option>
                    <option value="releves_licence_3">Relevés de notes L3(Licence3: S5 et S6)</option>
                    <option value="attestation_bac">Attestation  BAC</option>
                    <option value="attestation_licence">Attestation  licence</option>
                    <option value="acte_de_naissance">Acte de naissance</option>
                    <option value="photo_identite">Photo d'identité</option>
                    <option value="passport/carte_identite">Passport/Carte d'identité</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fichier</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                <span>Charger un fichier</span>
                                <input name="documents[]" type="file" class="sr-only" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500">
                            PDF, DOC, DOCX, JPG, JPEG, PNG jusqu'à 5MB
                        </p>
                    </div>
                </div>
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
