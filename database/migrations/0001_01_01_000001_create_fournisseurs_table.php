<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('code_fournisseur')->unique();
            $table->string('raison_sociale');
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_principal')->nullable();
            $table->enum('type', ['fabricant', 'distributeur', 'maintenance', 'autre']);
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            $table->date('date_premiere_commande')->nullable();
            $table->date('date_derniere_commande')->nullable();
            $table->enum('evaluation', ['excellent', 'bon', 'moyen', 'mauvais'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fournisseurs');
    }
};