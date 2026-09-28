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
        Schema::create('documents', function (Blueprint $table) {
            $table->id('document_id');
            $table->foreignId('dossier_id')->constrained('dossiers','dossier_id')->onDelete('cascade');
            $table->string('type_document');
            $table->string('nom_original');
            $table->string('chemin_fichier');
            $table->enum('statut', ['en_attente', 'valide', 'a_corriger'])->default('en_attente');
            $table->text('commentaires')->nullable();
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
        Schema::dropIfExists('documents');
    }
};
