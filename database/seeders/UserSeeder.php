<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Traoré',
            'prenom' => 'Mohamed',
            'sexe' => 'homme',
            'contact' => '90 90 90 90',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1234'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Conseillers
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => 'Conseiller ' . $i,
                'prenom' => 'Prénom ' . $i,
                'sexe' => rand(0, 1) ? 'homme' : 'femme',
                'contact' => '90' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                'email' => 'conseiller' . $i . '@gmail.com',
                'password' => Hash::make('1234'),
                'role' => 'conseiller',
                'email_verified_at' => now(),
            ]);
        }

        // Étudiants
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => 'Étudiant ' . $i,
                'prenom' => 'Prénom ' . $i,
                'sexe' => rand(0, 1) ? 'homme' : 'femme',
                'contact' => '92' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                'email' => 'etudiant' . $i . '@gmail.com',
                'password' => Hash::make('1234'),
                'role' => 'etudiant',
                'email_verified_at' => now(),
            ]);
        }
    }
}
