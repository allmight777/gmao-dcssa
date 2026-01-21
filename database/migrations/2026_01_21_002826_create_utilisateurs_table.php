<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('matricule')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('grade')->nullable();
            $table->string('fonction');

            
            $table->foreignId('service_id')
                  ->nullable()
                  ->constrained('localisations')
                  ->nullOnDelete();

            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('login')->unique();
            $table->string('password');

            $table->foreignId('profil_id')->constrained('profils');

            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            $table->timestamp('date_derniere_connexion')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['nom', 'prenom']);
            $table->index('statut');
            $table->index('profil_id');
            $table->index('service_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};