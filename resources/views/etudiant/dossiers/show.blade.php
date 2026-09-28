<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-600 leading-tight">
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

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            Dossier #{{ $dossier->reference_paiement }}
                        </h3>
                        <a href="{{ route('etudiant.dossiers.index') }}"
                           class="text-sm text-gray-600 hover:text-gray-900">
                            ← Retour aux dossiers
                        </a>
                    </div>

                    <!-- Informations générales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Informations générales</h4>
                            <div class="space-y-2">
                                <p><strong>Étudiant :</strong> {{ $dossier->etudiant->user->prenom }} {{ $dossier->etudiant->user->name }}</p>
                                <p><strong>Email :</strong> {{ $dossier->etudiant->user->email }}</p>
                                <p><strong>Contact :</strong> {{ $dossier->etudiant->user->contact }}</p>
                                <p><strong>Domaine d'étude :</strong> {{ $dossier->etudiant->domaine_etude ?? 'Non spécifié' }}</p>
                                <p><strong>Niveau d'étude :</strong> {{ $dossier->etudiant->niveau_etude ?? 'Non spécifié' }}</p>
                                <p><strong>Université actuelle :</strong> {{ $dossier->etudiant->universite_actuelle ?? 'Non spécifié' }}</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Informations du dossier</h4>
                            <div class="space-y-2">
                                <p><strong>Référence :</strong> {{ $dossier->reference_paiement }}</p>
                                <p><strong>Statut :</strong>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($dossier->statut === 'termine') bg-green-100 text-green-800
                                        @elseif($dossier->statut === 'a_corriger') bg-red-100 text-red-800
                                        @elseif($dossier->statut === 'attente_paiement') bg-yellow-100 text-yellow-800
                                        @elseif($dossier->statut === 'attente_verification_documents') bg-blue-100 text-blue-800
                                        @elseif($dossier->statut === 'attente_verification_paiement') bg-purple-100 text-purple-800
                                        @elseif($dossier->statut === 'paiement_a_corriger') bg-red-100 text-red-800
                                        @elseif($dossier->statut === 'paiement_accepte') bg-green-100 text-green-800
                                        @elseif($dossier->statut === 'traitement_dossier') bg-indigo-100 text-indigo-800
                                        @elseif($dossier->statut === 'attente_validation_finale') bg-orange-100 text-orange-800
                                        @elseif($dossier->statut === 'dossier_complet') bg-green-100 text-green-800
                                        @elseif($dossier->statut === 'soumis_campus_france') bg-blue-200 text-blue-900
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ (new App\Http\Controllers\DossierController())->getStatutFr($dossier->statut) }}
                                    </span>
                                </p>
                                <p><strong>Montant :</strong> {{ number_format($dossier->montant, 0, ',', ' ') }} FCFA</p>
                                <p><strong>Date de création :</strong> {{ $dossier->created_at->format('d/m/Y H:i') }}</p>
                                @if($dossier->assigne_a)
                                    <p><strong>Conseiller assigné :</strong> {{ $dossier->assigne->prenom }} {{ $dossier->assigne->name }}</p>
                                @endif
                                @if($dossier->email_suivi_campus_france)
                                    <p><strong>Email de suivi Campus France :</strong> {{ $dossier->email_suivi_campus_france }}</p>
                                @endif
                                @if($dossier->soumis_a_campus_france_a)
                                    <p><strong>Soumis le :</strong> {{ $dossier->soumis_a_campus_france_a->format('d/m/Y H:i') }}</p>
                                @endif
                                @if($dossier->termine_a)
                                    <p><strong>Terminé le :</strong> {{ $dossier->termine_a->format('d/m/Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!--  BOUTON DE VALIDATION FINALE -->
                    @if($dossier->statut === 'attente_validation_finale' )
                        <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-lg font-medium text-blue-800 mb-2">Validation finale requise</h4>
                                    <p class="text-blue-700">
                                        Le conseiller a ajouté des documents. Veuillez les vérifier et valider votre dossier.
                                    </p>
                                </div>
                                <a href="{{ route('etudiant.dossiers.verifier-documents-conseiller.form', $dossier->dossier_id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Vérifier les documents
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Section Documents -->
                    @if(in_array($dossier->statut, ['nouveau', 'attente_verification_documents', 'documents_a_corriger', 'documents_acceptes', 'traitement_dossier', 'attente_validation_finale', 'dossier_complet','attente_paiement','attente_verification_paiement','paiement_accepte']))
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-lg font-medium text-gray-900">Documents</h4>
                                @if(in_array($dossier->statut, ['nouveau', 'attente_verification_documents', 'documents_a_corriger']))
                                    <a href="{{ route('etudiant.dossiers.upload-document.form', $dossier->dossier_id) }}"
                                       class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">

                                        Charger vos documents
                                    </a>
                                @endif
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
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
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


                                                @if($dossier->statut !=='dossier_complet')

                                                    @if($document->statut !== 'valide' && !$document->type_document==='cv' && !$document->type_document==='lettre_motivation' && !$document->type_document==='autres_documents')
                                                        <a href="{{ route('etudiant.dossiers.upload-document.form', $dossier->dossier_id) }}"
                                                           class="text-sm text-blue-600 hover:text-blue-800">
                                                            Corriger
                                                        </a>
                                                    @endif
                                                @endif

                                                </div>
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
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-red-500">Aucun document</h3>
                                    <p class="mt-1 text-sm text-gray-500">Commencez par charger vos documents.</p>
                                    @if(in_array($dossier->statut, ['nouveau', 'attente_verification_documents', 'documents_a_corriger']))
                                        <div class="mt-6">
                                            <a href="{{ route('etudiant.dossiers.upload-document.form', $dossier->dossier_id) }}"
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                Charger vos documents
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Section Paiement -->
                    @if(in_array($dossier->statut, ['attente_paiement', 'attente_verification_paiement', 'paiement_a_corriger', 'paiement_accepte', 'traitement_dossier', 'attente_validation_finale', 'dossier_complet']))
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-lg font-medium text-gray-900">Paiement</h4>
                                @if($dossier->statut === 'attente_paiement')
                                    <a href="{{ route('etudiant.dossiers.upload-recu.form', $dossier->dossier_id) }}"
                                       class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Uploader le reçu
                                    </a>
                                @endif
                            </div>

                            @if($dossier->paiements->count() > 0)
                                @php $paiement = $dossier->paiements->first() @endphp
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
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
                                                        <a href="{{ Storage::url($paiement->chemin_recu) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
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
                            @else
                                <div class="text-center py-6 bg-gray-50 rounded-lg">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun paiement</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        @if($dossier->statut === 'attente_paiement')
                                            En attente du paiement de l'étudiant.
                                        @else
                                            Aucun paiement enregistré.
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                <!-- Section confirmation email reçu (quand soumis à Campus France) -->
                @if($dossier->statut === 'soumis_campus_france')
                    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-medium text-blue-800">Dossier soumis à Campus France</h4>
                        </div>

                        <div class="mb-4">
                            <p class="text-blue-700">
                                Votre dossier a été soumis à Campus France. Vous avez reçu un email avec les identifiants de suivi.
                            </p>

                            @if($dossier->email_suivi_campus_france)
                                <div class="mt-4 p-4 bg-white rounded-lg border border-blue-200">
                                    <h5 class="font-medium text-blue-800 mb-2">Informations de suivi :</h5>
                                    <div class="space-y-2 text-sm">
                                        <p><strong>Email :</strong> {{ $dossier->email_suivi_campus_france }}</p>
                                        <p><strong>Mot de passe :</strong>{{$dossier->mot_de_passe_campus_france}}</p>
                                        <p><strong>Lien :</strong>
                                            <a href="{{ $dossier->lien_site_campus_france }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-800">
                                                Accéder au site
                                            </a>
                                        </p>
                                        <p><strong>Date d'envoi :</strong>
                                            {{ $dossier->soumis_a_campus_france_a->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('etudiant.dossiers.confirmer-reception', $dossier->dossier_id) }}">
                            @csrf

                            <div class="flex items-center">
                                <input type="checkbox" id="confirme_reception" name="confirme_reception"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required>
                                <label for="confirme_reception" class="ml-2 block text-sm text-blue-700">
                                    J'ai reçu l'email et j'ai accès au site Campus France
                                </label>
                            </div>

                            <div class="mt-4 flex justify-end">
                                <x-primary-button class="ml-4 bg-blue-600 hover:bg-blue-700">
                                    {{ __('Confirmer la réception') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
