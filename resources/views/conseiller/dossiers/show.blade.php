<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails du Dossier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
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

                    <!-- En-tête avec retour -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            Dossier #{{ $dossier->reference_paiement }}
                        </h3>
                        <a href="{{ route('conseiller.dossiers.index') }}"
                           class="text-sm text-gray-600 hover:text-gray-900">
                            ← Retour aux dossiers
                        </a>
                    </div>

                    <!-- Informations générales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Informations étudiant -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Informations Étudiant</h4>
                            <div class="space-y-2">
                                <p><strong>Nom complet :</strong> {{ $dossier->etudiant->user->prenom }} {{ $dossier->etudiant->user->name }}</p>
                                <p><strong>Email :</strong> {{ $dossier->etudiant->user->email }}</p>
                                <p><strong>Contact :</strong> {{ $dossier->etudiant->user->contact }}</p>
                                <p><strong>Domaine d'étude :</strong> {{ $dossier->etudiant->domaine_etude ?? 'Non spécifié' }}</p>
                                <p><strong>Niveau d'étude :</strong> {{ $dossier->etudiant->niveau_etude ?? 'Non spécifié' }}</p>
                            </div>
                        </div>

                        <!-- Informations dossier -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Informations Dossier</h4>
                            <div class="space-y-2">
                                <p><strong>Statut :</strong>
                                    <span class="px-2 py-1 rounded text-sm font-medium
                                        @if($dossier->statut === 'termine') bg-green-100 text-green-800
                                        @elseif($dossier->statut === 'a_corriger') bg-red-100 text-red-800
                                        @elseif($dossier->statut === 'attente_paiement') bg-yellow-100 text-yellow-800
                                        @elseif($dossier->statut === 'documents_en_cours') bg-blue-100 text-blue-800
                                        @elseif($dossier->statut === 'preuve_paiement_envoyee') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ (new App\Http\Controllers\DossierController())->getStatutFr($dossier->statut) }}
                                    </span>
                                </p>
                                <p><strong>Montant :</strong> {{ number_format($dossier->montant, 0, ',', ' ') }} FCFA</p>
                                <p><strong>Date de création :</strong> {{ $dossier->created_at->format('d/m/Y H:i') }}</p>
                                @if($dossier->email_suivi_campus_france)
                                    <p><strong>Email de suivi Campus France :</strong> {{ $dossier->email_suivi_campus_france }}</p>
                                @endif
                                @if($dossier->soumis_a_campus_france_a)
                                    <p><strong>Soumis le :</strong> {{ $dossier->soumis_a_campus_france_a->format('d/m/Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Section Paiement -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-medium text-gray-900">Paiement</h4>
                            @if($dossier->statut === 'preuve_paiement_envoyee')
                                <a href="{{ route('conseiller.dossiers.verifier-paiement.form', $dossier->dossier_id) }}"
                                class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 transition-colors duration-200">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Vérifier paiement
                                </a>
                            @elseif($dossier->statut === 'attente_paiement')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-md text-sm">
                                    En attente du paiement
                                </span>
                            @elseif($dossier->statut === 'paiement_verifie')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-md text-sm">
                                    Paiement vérifié
                                </span>
                            @elseif($dossier->statut === 'paiement_a_corriger')
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-md text-sm">
                                    Paiement à corriger
                                </span>
                            @endif
                        </div>

                        @if($dossier->paiements->count() > 0)
                            @php $paiement = $dossier->paiements->first() @endphp
                            <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Montant</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Statut</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($paiement->statut === 'confirme') bg-green-100 text-green-800
                                                    @elseif($paiement->statut === 'rejete') bg-red-100 text-red-800
                                                    @elseif($paiement->statut === 'a_verifier') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $paiement->statut)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Méthode</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $paiement->methode_paiement)) }}</td>
                                        </tr>
                                        @if($paiement->verifiePar)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Vérifié par</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $paiement->verifiePar->prenom }} {{ $paiement->verifiePar->name }}
                                                    @if($paiement->verifie_a)
                                                        le {{ $paiement->verifie_a->format('d/m/Y H:i') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                        @if($paiement->chemin_recu)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Reçu</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <a href="{{ Storage::url($paiement->chemin_recu) }}" target="_blank"
                                                    class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Voir le reçu
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                        @if($paiement->motif_rejet)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Motif rejet</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-red-600">{{ $paiement->motif_rejet }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <!-- Bouton de vérification (si nécessaire) -->
                            @if($dossier->statut === 'attente_verification_paiement' && $paiement->statut === 'a_verifier' || $dossier->statut === 'attente_paiement' && $paiement->statut === 'confirme')
                                <div class="mt-4 flex justify-end">
                                    <a href="{{ route('conseiller.dossiers.verifier-paiement.form', $dossier->dossier_id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Vérifier ce paiement
                                    </a>
                                </div>
                            @endif

                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun paiement enregistré</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    @if($dossier->statut === 'attente_paiement')
                                        En attente du paiement de l'étudiant.
                                    @else
                                        Aucun paiement n'a été effectué pour ce dossier.
                                    @endif
                                </p>

                                @if($dossier->statut === 'attente_paiement')
                                    <div class="mt-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            Montant à payer : {{ number_format($dossier->montant, 0, ',', ' ') }} FCFA
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Section Documents -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-medium text-gray-900">Documents</h4>
                        </div>

                        @if($dossier->documents->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($dossier->documents as $document)
                                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                                        <div class="p-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <h5 class="font-medium text-gray-900 text-sm">
                                                    {{ ucfirst(str_replace('_', ' ', $document->type_document)) }}
                                                </h5>
                                                <span class="px-2 py-1 rounded text-xs
                                                    @if($document->statut === 'valide') bg-green-100 text-green-800
                                                    @elseif($document->statut === 'a_corriger') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $document->statut)) }}
                                                </span>
                                            </div>

                                            <p class="text-xs text-gray-500 mb-3">{{ $document->nom_original }}</p>

                                            <div class="flex justify-between items-center">
                                                <a href="{{ Storage::url($document->chemin_fichier) }}" target="_blank"
                                                   class="text-blue-600 hover:text-blue-800 text-xs">
                                                    Voir
                                                </a>

                                                @if($document->statut !== 'valide')
                                                    <a href="{{ route('conseiller.documents.verifier.form', $document->document_id) }}"
                                                       class="text-sm text-blue-600 hover:text-blue-800">
                                                        Vérifier
                                                    </a>
                                                @endif
                                            </div>

                                            @if($document->commentaires)
                                                <div class="mt-2 p-2 bg-gray-50 rounded text-xs text-gray-600">
                                                    {{ $document->commentaires }}
                                                </div>
                                            @endif

                                            @if($document->verifiePar)
                                                <div class="mt-2 text-xs text-gray-500">
                                                    Vérifié par {{ $document->verifiePar->prenom }}
                                                    {{ $document->verifie_a ? 'le ' . $document->verifie_a->format('d/m/Y') : '' }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 bg-gray-50 rounded-lg">
                                <p class="text-gray-500">Aucun document uploadé pour ce dossier.</p>
                                @if(in_array($dossier->statut, ['documents_en_cours', 'documents_a_corriger']))
                                    <p class="text-sm text-gray-400 mt-1">En attente des documents de l'étudiant.</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- section traitement dossier -->
                    @if($dossier->statut === 'paiement_accepte' or $dossier->statut === 'traitement_dossier')
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <h4 class="text-lg font-medium text-gray-900">Traitement du dossier</h4>
                                <a href="{{ route('conseiller.dossiers.traiter.form', $dossier->dossier_id) }}"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                                    Ajouter documents cabinet
                                </a>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">
                                Le paiement est accepté. Vous pouvez maintenant ajouter les documents préparés par le cabinet.
                            </p>
                        </div>
                    @else
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <p class="mt-2 text-sm text-gray-600">
                                    Le dossier n'est pas encore pret pour le traitement vous devriez verifier tout les documents possible et n'oubliez pas de voir aussi le paiement du dossier merci.
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Section Email de suivi (quand dossier complet) -->
                    @if($dossier->statut === 'dossier_complet')
                        <div class="mt-8 bg-green-50 border border-green-200 rounded-lg p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-lg font-medium text-green-800">Envoyer l'email de suivi Campus France</h4>
                            </div>

                            <p class="text-green-700 mb-4">
                                Le dossier est complet. Veuillez envoyer l'email de suivi avec les identifiants Campus France.
                            </p>

                            <form method="POST" action="{{ route('conseiller.dossiers.envoyer-email-suivi', $dossier->dossier_id) }}">
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <x-input-label for="email_suivi" :value="__('Email de suivi Campus France')" />
                                        <x-text-input id="email_suivi" name="email_suivi" type="email"
                                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    placeholder="email.nomcomplet@gmail.com" required />
                                        <x-input-error :messages="$errors->get('email_suivi')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="mot_de_passe" :value="__('Mot de passe Campus France')" />
                                        <x-text-input id="mot_de_passe" name="mot_de_passe" type="string"
                                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    required />
                                        <x-input-error :messages="$errors->get('mot_de_passe')" class="mt-2" />
                                    </div>

                                    <div class="md:col-span-2">
                                        <x-input-label for="lien_site" :value="__('Lien du site Campus France')" />
                                        <x-text-input id="lien_site" name="lien_site" type="url"
                                                    class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    placeholder="https://pastel.diplomatie.gouv.fr/etudesenfrance" required />
                                        <x-input-error :messages="$errors->get('lien_site')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="flex items-center justify-end">
                                    <x-primary-button class="ml-4 bg-green-600 hover:bg-green-700">
                                        {{ __('Envoyer l\'email de suivi') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
