<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dossiers Assignés') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- En-tête avec filtres -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <h3 class="text-lg font-medium text-gray-900">Tous vos dossiers assignés</h3>

                        <!-- Filtres -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ request()->url() }}" class="px-3 py-1 text-sm rounded-full {{ !request()->get('statut') ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                Tous
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['statut' => 'documents_en_cours']) }}" class="px-3 py-1 text-sm rounded-full {{ request()->get('statut') === 'documents_en_cours' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                Documents en cours
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['statut' => 'attente_paiement']) }}" class="px-3 py-1 text-sm rounded-full {{ request()->get('statut') === 'attente_paiement' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                Attente paiement
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['statut' => 'termine']) }}" class="px-3 py-1 text-sm rounded-full {{ request()->get('statut') === 'termine' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                Terminés
                            </a>
                        </div>
                    </div>

                    <!-- Tableau des dossiers -->
                    @if($dossiers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étudiant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documents</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paiement</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($dossiers as $dossier)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $dossier->etudiant->user->prenom }} {{ $dossier->etudiant->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $dossier->etudiant->user->email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $dossier->reference_paiement }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($dossier->statut === 'termine') bg-green-100 text-green-800
                                                    @elseif($dossier->statut === 'a_corriger') bg-red-100 text-red-800
                                                    @elseif($dossier->statut === 'attente_paiement') bg-yellow-100 text-yellow-800
                                                    @elseif($dossier->statut === 'documents_en_cours') bg-blue-100 text-blue-800
                                                    @elseif($dossier->statut === 'preuve_paiement_envoyee') bg-purple-100 text-purple-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ (new App\Http\Controllers\DossierController())->getStatutFr($dossier->statut) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($dossier->montant, 0, ',', ' ') }} FCFA
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="flex items-center">
                                                    <span class="mr-2">{{ $dossier->documents->count() }}</span>
                                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($dossier->paiements->count() > 0)
                                                    @php $paiement = $dossier->paiements->first() @endphp
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        @if($paiement->statut === 'confirme') bg-green-100 text-green-800
                                                        @elseif($paiement->statut === 'rejete') bg-red-100 text-red-800
                                                        @elseif($paiement->statut === 'a_verifier') bg-yellow-100 text-yellow-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        {{ ucfirst(str_replace('_', ' ', $paiement->statut)) }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-500">Aucun</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $dossier->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('conseiller.dossiers.show', $dossier->dossier_id) }}"
                                                   class="text-blue-600 hover:text-blue-900">
                                                    Voir
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($dossiers instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="mt-6">
                                {{ $dossiers->links() }}
                            </div>
                        @endif

                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun dossier trouvé</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if(request()->get('statut'))
                                    Aucun dossier avec le statut "{{ (new App\Http\Controllers\DossierController())->getStatutFr(request()->get('statut')) }}".
                                @else
                                    Vous n'avez pas encore de dossiers assignés.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
