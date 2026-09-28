<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-600 leading-tight">
            {{ __('Charger le reçu de paiement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
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

                    <!-- Informations du paiement -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Montant à payer</p>
                                <p class="text-lg font-medium text-gray-900">
                                    {{ number_format($dossier->montant, 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Référence de paiement</p>
                                <p class="font-medium text-gray-900">
                                    {{ $dossier->reference_paiement }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Statut actuel</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($dossier->statut === 'attente_paiement') bg-yellow-100 text-yellow-800
                                    @elseif($dossier->statut === 'preuve_paiement_envoyee') bg-blue-100 text-blue-800
                                    @elseif($dossier->statut === 'paiement_verifie') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ (new App\Http\Controllers\DossierController())->getStatutFr($dossier->statut) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Date de création</p>
                                <p class="font-medium text-gray-900">
                                    {{ $dossier->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Page d'informations bancaires -->
                    <div class="container mx-auto px-4 py-8">
                        <div class="bg-blue-50 border border-blue-300  rounded-lg shadow-md p-6 max-w-2xl mx-auto">
                            <div class="flex justify-center mb-6">
                                <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>

                            <h2 class="text-2xl font-bold text-center mb-4">Informations Bancaires</h2>
                            <p class="text-center text-gray-600 mb-6">
                                Veuillez effectuer le paiement via virement bancaire en utilisant les informations ci-dessous.
                            </p>

                            <!-- Informations principales -->
                            <div class="mb-6">
                                <p class="text-xl font-medium text-center text-gray-800 mb-2">
                                    Banque : Coris BANQUE
                                </p>
                                <p class="text-xl font-medium text-center text-blue-600 mb-2">
                                    Compte Bancaire :
                                    <span class="font-bold">CECVT</span>
                                </p>
                                <p class="text-xl font-medium text-center text-blue-600 mb-2">
                                    N°  010463240132
                                </p>

                                <p class="text-xl font-medium text-center text-gray-800 mb-2">
                                    Montant à payer : <strong class="text-red-500">{{ number_format($dossier->montant, 0, ',', ' ') }} FCFA </strong>
                                </p>

                            </div>

                            <!-- Instructions importantes -->
                            <div class="bg-red-50 border border-red-300 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-red-600 size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                    </svg>

                                    <p class="text-sm font-medium text-red-500">
                                        Important : Écrivez la réference du paiement ainsi que le nom et prénom de l'étudiant qui effectue le paiement sur le bordereau de virement.
                                    </p>
                                </div>
                            </div>

                            <!-- Contacts utiles -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white rounded-lg shadow-sm p-4 flex items-center space-x-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-green-600">
                                        <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Téléphone</p>
                                        <p class="text-sm text-gray-600">+228 90 90 90 53</p>
                                    </div>
                                </div>

                                <div class="bg-white rounded-lg shadow-sm p-4 flex items-center space-x-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-green-500">
                                        <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                                        <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                                    </svg>

                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Email</p>
                                        <p class="text-sm text-gray-600">cecvt99@gmail.com</p>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <!-- Formulaire d'upload du reçu -->
                    <form method="POST" action="{{ route('etudiant.dossiers.enregistrer-recu', $dossier->dossier_id) }}"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="mb-6">
                            <x-input-label for="recu" :value="__('Reçu de paiement')" />
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="recu" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>{{ __('Charger le reçu') }}</span>
                                            <input id="recu" name="recu" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png" required>
                                        </label>
                                        <p class="pl-1">{{ __('ou glisser-déposer') }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        {{ __('PDF, JPG, JPEG, PNG jusqu\'à 2MB') }}
                                    </p>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('recu')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <a href="{{ route('etudiant.dossiers.show', $dossier->dossier_id) }}"
                               class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-red-500 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-4">
                                {{ __('Annuler') }}
                            </a>

                            <x-primary-button class="ml-4">
                                {{ __('Charger le reçu') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
