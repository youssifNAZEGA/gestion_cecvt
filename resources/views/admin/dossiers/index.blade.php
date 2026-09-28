<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Dossiers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Filtres -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <h3 class="text-lg font-medium text-gray-900">Liste des dossiers</h3>

                        <!-- Filtres -->
                        <form method="GET" class="flex flex-wrap gap-2">
                            <select name="statut" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                <option value="">Tous les statuts</option>
                                @foreach($statuts as $statut)
                                    <option value="{{ $statut }}" {{ request()->get('statut') === $statut ? 'selected' : '' }}>
                                        {{ (new App\Http\Controllers\DossierController())->getStatutFr($statut) }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="assigne" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                <option value="">Tous les conseillers</option>
                                <option value="non_assigne" {{ request()->get('assigne') === 'non_assigne' ? 'selected' : '' }}>
                                    Non assignés
                                </option>
                                @foreach($conseillers as $conseiller)
                                    <option value="{{ $conseiller->user_id }}" {{ request()->get('assigne') == $conseiller->user_id ? 'selected' : '' }}>
                                        {{ $conseiller->prenom }} {{ $conseiller->name }}
                                    </option>
                                @endforeach
                            </select>

                            

                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                                Filtrer
                            </button>

                            <a href="{{ route('admin.dossiers.index') }}" class="px-3 py-1 bg-gray-600 text-white rounded-md text-sm hover:bg-gray-700">
                                Réinitialiser
                            </a>
                        </form>
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigné à</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
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
                                                @if($dossier->assigne)
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs">
                                                            {{ substr($dossier->assigne->prenom, 0, 1) }}
                                                        </div>
                                                        <span class="ml-2">{{ $dossier->assigne->prenom }} {{ substr($dossier->assigne->name, 0, 1) }}.</span>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">Non assigné</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($dossier->montant, 0, ',', ' ') }} FCFA
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $dossier->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('admin.dossiers.show', $dossier->dossier_id) }}"
                                                       class="text-blue-600 hover:text-blue-900">
                                                        Voir
                                                    </a>
                                                    @if(!$dossier->assigne)
                                                        <a href="{{ route('admin.dossiers.assigner.form', $dossier->dossier_id) }}"
                                                           class="text-green-600 hover:text-green-900">
                                                            Assigner
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $dossiers->appends(request()->query())->links() }}
                        </div>

                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun dossier trouvé</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if(request()->get('statut') || request()->get('assigne') || request()->get('search'))
                                    Aucun dossier ne correspond à vos critères de recherche.
                                @else
                                    Vous n'avez pas encore de dossiers.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
