<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dossiers', function (Blueprint $table) {
            $table->id('dossier_id');
            $table->integer('etudiant_id');
            $table->integer('assigne_a')->nullable();
            $table->enum('statut', [
                'nouveau',
                'attente_verification_documents',
                'documents_a_corriger',
                'documents_acceptes',
                'attente_paiement',
                'attente_verification_paiement',
                'paiement_a_corriger',
                'paiement_accepte',
                'traitement_dossier',
                'attente_validation_finale',
                'dossier_complet',
                'soumis_campus_france',
                'termine'
            ])->default('nouveau');
            $table->decimal('montant', 8, 2)->default(0);
            $table->string('reference_paiement')->nullable();
            $table->text('motif_rejet')->nullable();
            $table->string('email_suivi_campus_france')->nullable();
            $table->string('mot_de_passe_campus_france')->nullable();
            $table->string('lien_site_campus_france')->nullable();
            $table->timestamp('soumis_a_campus_france_a')->nullable();
            $table->timestamp('termine_a')->nullable();
            $table->timestamps();

            $table->foreign('etudiant_id')->references('user_id')->on('etudiants')->onDelete('cascade');
            $table->foreign('assigne_a')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};
