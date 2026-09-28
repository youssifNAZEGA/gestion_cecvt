<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord des Performances') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistiques globales -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total conseillers -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-8 0 3 3 0 018 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total conseillers</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ count($performancesConseillers) }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Moyenne performance -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Performance moyenne</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">
                                            @php
                                                // ✅ UTILISER toArray() pour convertir Collection en tableau
                                                $moyenne = count($performancesConseillers) > 0 ?
                                                    round(array_sum(array_column(collect($performancesConseillers)->toArray(), 'pourcentage')) / count($performancesConseillers), 1) : 0;
                                            @endphp
                                            {{ $moyenne }}%
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dossiers terminés ce mois -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Dossiers terminés ce mois</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">
                                            @php
                                                // ✅ UTILISER les méthodes Collection
                                                $terminesCeMois = collect($tendancesMensuelles)->sum('dossiers_termines');
                                            @endphp
                                            {{ $terminesCeMois }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Performances par conseiller -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Performances par conseiller</h3>
                            <div class="text-sm text-gray-500">
                                {{ count($performancesConseillers) }} conseillers actifs
                            </div>
                        </div>

                        @if(count($performancesConseillers) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conseiller</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dossiers</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terminés</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">En cours</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($performancesConseillers as $perf)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                                <span class="text-gray-600 font-medium text-sm">
                                                                    {{ substr($perf['conseiller']->prenom, 0, 1) }}{{ substr($perf['conseiller']->name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $perf['conseiller']->prenom }} {{ $perf['conseiller']->name }}
                                                            </div>
                                                            <div class="text-sm text-gray-500">
                                                                {{ $perf['conseiller']->email }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $perf['total'] }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <span class="text-green-600 font-medium">{{ $perf['termines'] }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <span class="text-blue-600 font-medium">{{ $perf['en_cours'] }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                                            <div class="bg-{{ $perf['pourcentage'] >= 80 ? 'green' : ($perf['pourcentage'] >= 50 ? 'yellow' : 'red') }}-600 h-2 rounded-full"
                                                                 style="width: {{ $perf['pourcentage'] }}%"></div>
                                                        </div>
                                                        <span class="text-sm font-medium text-gray-900">{{ $perf['pourcentage'] }}%</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Classement -->
                            <div class="mt-6">
                                <h4 class="text-md font-medium text-gray-900 mb-3">Classement</h4>
                                <div class="space-y-2">
                                    @php
                                        // ✅ CONVERTIR EN COLLECTION POUR SORTING
                                        $classement = collect($performancesConseillers)->sortByDesc('pourcentage')->values();
                                    @endphp

                                    @foreach($classement->take(3) as $index => $perf)
                                        <div class="flex items-center p-3 bg-{{ $index === 0 ? 'yellow' : ($index === 1 ? 'gray' : 'orange') }}-50 rounded-lg">
                                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-{{ $index === 0 ? 'yellow' : ($index === 1 ? 'gray' : 'orange') }}-200 flex items-center justify-center">
                                                <span class="font-bold text-{{ $index === 0 ? 'yellow' : ($index === 1 ? 'gray' : 'orange') }}-800">
                                                    {{ $index + 1 }}
                                                </span>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $perf['conseiller']->prenom }} {{ $perf['conseiller']->name }}
                                                </div>
                                            </div>
                                            <div class="text-sm font-bold text-{{ $index === 0 ? 'yellow' : ($index === 1 ? 'gray' : 'orange') }}-800">
                                                {{ $perf['pourcentage'] }}%
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-8 0 3 3 0 018 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun conseiller trouvé</h3>
                                <p class="mt-1 text-sm text-gray-500">Commencez par créer des conseillers.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tendances mensuelles -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-gray-900">Tendances mensuelles</h3>
                            <div class="text-sm text-gray-500">
                                Derniers 6 mois
                            </div>
                        </div>

                        @if(count($tendancesMensuelles) > 0)
                            <div class="space-y-4">
                                @foreach($tendancesMensuelles as $mois => $data)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $mois }}</h4>
                                            <div class="text-sm text-gray-500">
                                                {{ $data['nouveaux_dossiers'] }} nouveaux, {{ $data['dossiers_termines'] }} terminés
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <div class="flex items-center text-xs text-blue-600 mb-1">
                                                    <div class="w-3 h-3 bg-blue-200 rounded mr-2"></div>
                                                    <span>Nouveaux dossiers: {{ $data['nouveaux_dossiers'] }}</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-blue-500 h-2 rounded-full"
                                                         style="width: {{ $data['nouveaux_dossiers'] > 0 ? min(100, ($data['nouveaux_dossiers'] / max(1, collect($tendancesMensuelles)->max('nouveaux_dossiers'))) * 100) : 0 }}%"></div>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="flex items-center text-xs text-green-600 mb-1">
                                                    <div class="w-3 h-3 bg-green-200 rounded mr-2"></div>
                                                    <span>Dossiers terminés: {{ $data['dossiers_termines'] }}</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-green-500 h-2 rounded-full"
                                                         style="width: {{ $data['dossiers_termines'] > 0 ? min(100, ($data['dossiers_termines'] / max(1, collect($tendancesMensuelles)->max('dossiers_termines'))) * 100) : 0 }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune donnée disponible</h3>
                                <p class="mt-1 text-sm text-gray-500">Les données apparaîtront après quelques mois d'activité.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Indicateurs clés -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Indicateurs clés de performance</h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Temps moyen de traitement -->
                        <div class="border border-gray-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600 mb-1">
                                @php
                                    // ✅ CALCUL SIMPLIFIÉ
                                    $tempsMoyen = 0;
                                @endphp
                                {{ $tempsMoyen > 0 ? $tempsMoyen : '~' }} jours
                            </div>
                            <div class="text-sm text-gray-500">Temps moyen traitement</div>
                            <div class="text-xs text-gray-400 mt-1">Du début à la fin</div>
                        </div>

                        <!-- Taux de réussite -->
                        <div class="border border-gray-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600 mb-1">
                                @php
                                    // ✅ UTILISER LES MÉTHODES COLLECTION
                                    $tauxReussite = count($performancesConseillers) > 0 ?
                                        round(collect($performancesConseillers)->avg('pourcentage')) : 0;
                                @endphp
                                {{ $tauxReussite }}%
                            </div>
                            <div class="text-sm text-gray-500">Taux de réussite</div>
                            <div class="text-xs text-gray-400 mt-1">Dossiers terminés</div>
                        </div>

                        <!-- Satisfaction client -->
                        <div class="border border-gray-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-purple-600 mb-1">
                                @php
                                    $satisfaction = 0;
                                @endphp
                                {{ $satisfaction > 0 ? $satisfaction : '~' }}/5
                            </div>
                            <div class="text-sm text-gray-500">Satisfaction</div>
                            <div class="text-xs text-gray-400 mt-1">Note moyenne</div>
                        </div>

                        <!-- Productivité -->
                        <div class="border border-gray-200 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-orange-600 mb-1">
                                @php
                                    $productivite = 0;
                                @endphp
                                {{ $productivite > 0 ? $productivite : '~' }}
                            </div>
                            <div class="text-sm text-gray-500">Productivité</div>
                            <div class="text-xs text-gray-400 mt-1">Dossiers/mois/conseiller</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
