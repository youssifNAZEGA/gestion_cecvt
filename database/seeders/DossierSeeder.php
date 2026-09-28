<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dossier;
use App\Models\Etudiant;
use App\Models\User;

class DossierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $etudiants = Etudiant::all();
        $conseillers = User::where('role', 'conseiller')->get();

        if ($etudiants->count() == 0) {
            echo "Aucun étudiant trouvé. Seed d'abord les utilisateurs et étudiants.\n";
            return;
        }

        echo "Création de dossiers...\n";



        foreach ($etudiants->take(5) as $etudiant) {
            try {

                $conseiller=$conseillers->random();

                $dossier = Dossier::create([
                    'etudiant_id' => $etudiant->user_id,
                    'assigne_a'=>$conseiller->user_id,
                    'statut' => 'nouveau',
                    'montant' => 200000,
                    'reference_paiement' => 'REF' . time() . rand(100, 999)
                ]);


            echo "Dossier créé pour étudiant " . $etudiant->user_id . " avec ID: " . $dossier->dossier_id . "\n";

            } catch (\Exception $e) {
                echo "Erreur pour étudiant " . $etudiant->user_id . ": " . $e->getMessage() . "\n";
            }
        }
    }
}
