<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Paiement;
use App\Models\Dossier;
use App\Models\User;
use Faker\Factory as Faker;

class PaiementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $dossiers = Dossier::all();
        $conseillers = User::where('role', 'conseiller')->get();

        if ($dossiers->count() == 0) {
            echo "Aucun dossier trouvé, pas de paiements créés\n";
            return;
        }

        echo "Création de paiements pour " . $dossiers->count() . " dossiers\n";

        foreach ($dossiers as $dossier) {
            // Créer 1 paiement par dossier
            $statuts = ['en_attente', 'a_verifier', 'confirme', 'rejete'];
            $statut = $statuts[array_rand($statuts)];

            $paiementData = [
                'dossier_id' => $dossier->dossier_id,
                'montant' => $dossier->montant,
                'methode_paiement' => 'virement_bancaire',
                'statut' => $statut,
            ];

            if ($statut !== 'en_attente') {
                if ($statut !== 'rejete') {
                    $paiementData['chemin_recu'] = 'recus/' . uniqid() . '_recu.pdf';
                }

                if ($statut !== 'en_attente' && $conseillers->count() > 0) {
                    $verifiePar = $conseillers->random();
                    $paiementData['verifie_par'] = $verifiePar->user_id;
                    $paiementData['verifie_a'] = now();

                    if ($statut === 'rejete') {
                        $paiementData['motif_rejet'] = 'Reçu illisible';
                    }
                }
            }

            try {
                Paiement::create($paiementData);
                echo "Paiement créé pour dossier " . $dossier->dossier_id . " (statut: " . $statut . ")\n";
            } catch (\Exception $e) {
                echo "Erreur paiement dossier " . $dossier->dossier_id . ": " . $e->getMessage() . "\n";
            }
        }
    }
}
