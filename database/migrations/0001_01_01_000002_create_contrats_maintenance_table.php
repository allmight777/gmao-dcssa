<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contrats_maintenance', function (Blueprint $table) {
            $table->id();
            $table->string('numero_contrat')->unique();
            $table->string('libelle');
            $table->enum('type', ['preventif', 'curatif', 'mixte', 'garantie']);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->decimal('montant', 15, 2)->nullable();
            $table->integer('periodicite_interventions')->nullable()->comment('en mois');
            $table->integer('delai_intervention_garanti')->nullable()->comment('en heures');
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('cascade');
            $table->boolean('couverture_pieces')->default(false);
            $table->boolean('couverture_main_oeuvre')->default(false);
            $table->enum('statut', ['actif', 'expire', 'en_attente', 'resilie'])->default('actif');
            $table->date('date_alerte_renouvellement')->nullable();
            $table->text('conditions_particulieres')->nullable();
            $table->text('notes')->nullable();

            // Sans FK pour l'instant
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contrats_maintenance');
    }
};
