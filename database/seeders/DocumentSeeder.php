<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\Dossier;
use App\Models\User;
use Faker\Factory as Faker;

class DocumentSeeder extends Seeder
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
            echo "Aucun dossier trouvé, pas de documents créés\n";
            return;
        }

        echo "Création de documents pour " . $dossiers->count() . " dossiers\n";

        $typesDocuments = [
            'passport',
            'diplome',
            'lettre_motivation',
            'releve_notes',
            'cv',
            'photo'
        ];

        foreach ($dossiers as $dossier) {
            // Créer 3 à 6 documents par dossier
            $nbDocuments = rand(3, 6);

            for ($i = 0; $i < $nbDocuments; $i++) {
                $typeDocument = $typesDocuments[array_rand($typesDocuments)];
                $statuts = ['en_attente', 'valide', 'a_corriger'];
                $statut = $statuts[array_rand($statuts)];

                $documentData = [
                    'dossier_id' => $dossier->dossier_id,
                    'type_document' => $typeDocument,
                    'nom_original' => $typeDocument . '_' . uniqid() . '.pdf',
                    'chemin_fichier' => 'documents/' . uniqid() . '_' . $typeDocument . '.pdf',
                    'statut' => $statut,
                ];

                // Ajouter un vérificateur et commentaire si le document est vérifié
                if ($statut !== 'en_attente' && $conseillers->count() > 0) {
                    $verifiePar = $conseillers->random();
                    $documentData['verifie_par'] = $verifiePar->user_id;
                    $documentData['verifie_a'] = now();

                    if ($statut === 'a_corriger') {
                        $documentData['commentaires'] = 'Document incomplet, veuillez le compléter';
                    } elseif ($statut === 'valide') {
                        $documentData['commentaires'] = 'Document validé';
                    }
                }

                try {
                    Document::create($documentData);
                    echo "Document créé : " . $typeDocument . " pour dossier " . $dossier->dossier_id . "\n";
                } catch (\Exception $e) {
                    echo "Erreur document " . $typeDocument . ": " . $e->getMessage() . "\n";
                }
            }

        }
    }
}
