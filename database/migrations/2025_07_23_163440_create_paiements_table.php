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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id('paiement_id');
            $table->foreignId('dossier_id')->constrained('dossiers','dossier_id')->onDelete('cascade');
            $table->decimal('montant', 8, 2);
            $table->string('methode_paiement')->default('virement_bancaire');
            $table->string('chemin_recu')->nullable();
            $table->enum('statut', ['en_attente', 'a_verifier', 'confirme', 'rejete'])
                  ->default('en_attente');
            $table->text('motif_rejet')->nullable();
            $table->foreignId('verifie_par')->nullable()->constrained('users','user_id')->onDelete('cascade');
            $table->timestamp('verifie_a')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
