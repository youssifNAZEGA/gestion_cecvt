<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails du Dossier') }} #{{ $dossier->reference_paiement }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Barre d'avancement -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Progression du dossier</h3>
                        <span class="text-sm font-medium text-gray-700">{{ $avancement }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="{{ str_replace('text-', 'bg-', str_replace('bg-', '', $couleur_statut)) }} h-2.5 rounded-full"
                             style="width: {{ $avancement }}%"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Informations générales -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Étudiant</p>
                                    <p class="font-medium">
                                        {{ $dossier->etudiant->user->prenom }} {{ $dossier->etudiant->user->name }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $dossier->etudiant->user->email }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500">Statut</p>
                                    <span class="px-2 py-1 rounded text-sm font-medium {{ $couleur_statut }}">
                                        {{ $statut_fr }}
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

                                @if($dossier->assigne)
                                    <div>
                                        <p class="text-sm text-gray-500">Conseiller assigné</p>
                                        <p class="font-medium">
                                            {{ $dossier->assigne->prenom }} {{ $dossier->assigne->name }}
                                        </p>
                                    </div>
                                @endif

                                @if($dossier->termine_a)
                                    <div>
                                        <p class="text-sm text-gray-500">Date de fin</p>
                                        <p class="font-medium">{{ $dossier->termine_a->format('d/m/Y H:i') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Documents</h3>

                            @if($dossier->documents->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vérifié par</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($dossier->documents as $document)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ ucfirst(str_replace('_', ' ', $document->type_document)) }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">{{ $document->nom_original }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            @if($document->statut === 'valide') bg-green-100 text-green-800
                                                            @elseif($document->statut === 'a_corriger') bg-red-100 text-red-800
                                                            @else bg-yellow-100 text-yellow-800 @endif">
                                                            {{ ucfirst(str_replace('_', ' ', $document->statut)) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        @if($document->verifiePar)
                                                            {{ $document->verifiePar->prenom }} {{ substr($document->verifiePar->name, 0, 1) }}.
                                                        @else
                                                            <span class="text-gray-400">Non vérifié</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $document->created_at->format('d/m/Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-4">Aucun document uploadé.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Paiements -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Paiements</h3>

                            @if($dossier->paiements->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vérifié par</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($dossier->paiements as $paiement)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            @if($paiement->statut === 'confirme') bg-green-100 text-green-800
                                                            @elseif($paiement->statut === 'rejete') bg-red-100 text-red-800
                                                            @else bg-yellow-100 text-yellow-800 @endif">
                                                            {{ ucfirst(str_replace('_', ' ', $paiement->statut)) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        @if($paiement->verifiePar)
                                                            {{ $paiement->verifiePar->prenom }} {{ substr($paiement->verifiePar->name, 0, 1) }}.
                                                        @else
                                                            <span class="text-gray-400">Non vérifié</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $paiement->created_at->format('d/m/Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-4">Aucun paiement enregistré.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Colonne de droite -->
                <div>
                    <!-- Actions rapides -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions rapides</h3>

                            <div class="space-y-3">
                                @if(!$dossier->assigne)
                                    <a href="{{ route('admin.dossiers.assigner.form', $dossier->dossier_id) }}"
                                       class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                        Assigner à un conseiller
                                    </a>
                                @else
                                    <a href="{{ route('admin.dossiers.assigner.form', $dossier->dossier_id) }}"
                                       class="block w-full text-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">
                                        Changer de conseiller
                                    </a>
                                @endif

                                <a href="{{ route('admin.dossiers.index') }}"
                                   class="block w-full text-center px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 text-sm">
                                    Retour à la liste
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Historique -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Historique</h3>

                            <div class="space-y-3 text-sm">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-2 w-2 rounded-full bg-green-500 mt-2"></div>
                                    <div class="ml-3">
                                        <p class="font-medium">Dossier créé</p>
                                        <p class="text-gray-500">{{ $dossier->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>

                                @if($dossier->assigne)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-2 w-2 rounded-full bg-blue-500 mt-2"></div>
                                        <div class="ml-3">
                                            <p class="font-medium">Assigné à {{ $dossier->assigne->prenom }}</p>
                                            <p class="text-gray-500">{{ $dossier->updated_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($dossier->termine_a)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-2 w-2 rounded-full bg-gray-500 mt-2"></div>
                                        <div class="ml-3">
                                            <p class="font-medium">Dossier terminé</p>
                                            <p class="text-gray-500">{{ $dossier->termine_a->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
