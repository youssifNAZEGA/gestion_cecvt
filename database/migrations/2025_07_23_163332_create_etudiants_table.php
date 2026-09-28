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
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id('user_id');
            //$table->foreignId('user_id')->constrained('users' ,'user_id')->onDelete('cascade');
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('numero_passeport')->nullable();
            $table->string('universite_actuelle')->nullable();
            $table->string('niveau_etude')->nullable();
            $table->string('domaine_etude')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.et
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
