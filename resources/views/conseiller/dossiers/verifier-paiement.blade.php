<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vérifier le Paiement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            Vérifier le paiement - Dossier #{{ $dossier->reference_paiement }}
                        </h3>
                        <a href="{{ route('conseiller.dossiers.show', $dossier->dossier_id) }}"
                           class="text-sm text-gray-600 hover:text-gray-900">
                            ← Retour au dossier
                        </a>
                    </div>

                    <!-- Informations du paiement -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Étudiant</p>
                                <p class="font-medium">{{ $dossier->etudiant->user->prenom }} {{ $dossier->etudiant->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Montant</p>
                                <p class="font-medium">{{ number_format($dossier->montant, 0, ',', ' ') }} FCFA</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Méthode de paiement</p>
                                <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $paiement->methode_paiement)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Date d'upload</p>
                                <p class="font-medium">{{ $paiement->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Aperçu du reçu -->
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-900 mb-3">Aperçu du reçu de paiement</h4>
                        <div class="border border-gray-200 rounded-lg overflow-hidden bg-gray-100">
                            @if(in_array(pathinfo($paiement->chemin_recu, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ Storage::url($paiement->chemin_recu) }}"
                                     alt="Reçu de paiement"
                                     class="w-full max-h-96 object-contain">
                            @else
                                <div class="p-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">Fichier : {{ basename($paiement->chemin_recu) }}</p>
                                    <a href="{{ Storage::url($paiement->chemin_recu) }}" target="_blank"
                                       class="mt-2 inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                        Télécharger
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Formulaire de vérification -->
                    <form method="POST" action="{{ route('conseiller.dossiers.verifier-paiement', $dossier->dossier_id) }}">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Action de vérification</label>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 cursor-pointer">
                                    <input type="radio" name="action" value="confirmer" class="mr-3 h-5 w-5 text-green-600" required>
                                    <div>
                                        <span class="font-medium text-green-700">Confirmer le paiement</span>
                                        <p class="text-sm text-gray-600 mt-1">Le paiement est conforme et complet.</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-red-50 cursor-pointer">
                                    <input type="radio" name="action" value="rejeter" class="mr-3 h-5 w-5 text-red-600" required>
                                    <div>
                                        <span class="font-medium text-red-700">Rejeter le paiement</span>
                                        <p class="text-sm text-gray-600 mt-1">Le paiement présente des anomalies.</p>
                                    </div>
                                </label>
                            </div>


                        </div>
                            <div class="mb-6" id="motif-rejet-section" style="display: none;">
                                <label for="motif_rejet" class="block text-sm font-medium text-gray-700 mb-1">
                                    Motif du rejet
                                </label>
                                <textarea id="motif_rejet" name="motif_rejet" rows="3"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full"
                                        placeholder="Expliquez pourquoi le paiement est rejeté..."></textarea>
                                <p class="mt-1 text-sm text-gray-500">Ces commentaires seront visibles par l'étudiant.</p>
                            </div>
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('conseiller.dossiers.show', $dossier->dossier_id) }}"
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

<!-- Script simple pour afficher/cacher le motif de rejet -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="action"]');
    const motifRejetSection = document.getElementById('motif-rejet-section');

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'rejeter') {
                motifRejetSection.style.display = 'block';
            } else {
                motifRejetSection.style.display = 'none';
            }
        });
    });
});
</script>
