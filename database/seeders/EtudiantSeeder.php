<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Etudiant;
use Faker\Factory as Faker;
class EtudiantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $etudiantsUsers = User::where('role', 'etudiant')->get();

        foreach ($etudiantsUsers as $user) {
            Etudiant::create([
                'user_id' => $user->user_id,
                'date_naissance' => $faker->date('Y-m-d', '2000-01-01'),
                'lieu_naissance' => $faker->city(),
                'numero_passeport' => 'P' . $faker->unique()->randomNumber(8),
                'universite_actuelle' => $faker->randomElement([
                    'IFNTI Sokodé', 'Université de Lomé',
                    'Université de Kara', 'ESA','EPL'
                ]),
                'niveau_etude' => $faker->randomElement([
                    'Licence 1', 'Licence 2', 'Licence 3',
                    'Master 1', 'Master 2', 'Doctorat'
                ]),
                'domaine_etude' => $faker->randomElement([
                    'Master Informatique - IA',
                    'Licence Médecine',
                    'Master Économie',
                    'École d\'ingénieurs',
                    'Master Droit des Affaires'
                ]),
            ]);
        }
    }
}
