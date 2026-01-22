<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utilisateur_service', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('utilisateur_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            $table->foreignId('service_id')
                  ->constrained('localisations')
                  ->onDelete('cascade');
            
            $table->date('date_affectation')->nullable();
            $table->string('fonction_service')->nullable();
            
            $table->timestamps();
            
            // Index pour optimiser les requêtes
            $table->index(['utilisateur_id', 'service_id']);
            $table->unique(['utilisateur_id', 'service_id']); // Un utilisateur ne peut être qu'une fois dans un service
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilisateur_service');
    }
};