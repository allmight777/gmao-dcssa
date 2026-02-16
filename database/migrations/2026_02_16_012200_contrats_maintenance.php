<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrats_maintenance', function (Blueprint $table) {
            $table->id('ID_Contrat');

            // Informations générales
            $table->string('Numero_Contrat')->unique();
            $table->string('Libelle');
            $table->enum('Type', [
                'preventive',
                'corrective',
                'globale',
                'garantie',
                'autre'
            ])->default('preventive');

            // Période
            $table->date('Date_Debut');
            $table->date('Date_Fin');

            // Aspects financiers
            $table->decimal('Montant', 15, 2);
            $table->string('Devise')->default('XOF');

            // Détails d'intervention
            $table->enum('Periodicite_Interventions', [
                'hebdomadaire',
                'mensuelle',
                'trimestrielle',
                'semestrielle',
                'annuelle',
                'ponctuelle'
            ])->default('mensuelle');

            $table->integer('Delai_Intervention_Garanti')->comment('En heures');

            // Fournisseur
            $table->foreignId('ID_Fournisseur')
                  ->constrained('fournisseurs', 'id')
                  ->onDelete('restrict');

            // Couverture
            $table->boolean('Couverture_Pieces')->default(true);
            $table->boolean('Couverture_Main_Oeuvre')->default(true);
            $table->text('Exclusions')->nullable();

            // Statut et suivi
            $table->enum('Statut', [
                'actif',
                'expire',
                'resilie',
                'renouvellement_attente',
                'brouillon'
            ])->default('brouillon');

            $table->date('Date_Alerte_Renouvellement')->nullable();
            $table->boolean('Alerte_envoyee')->default(false);
            $table->timestamp('Date_derniere_alerte')->nullable();

            // Documents
            $table->string('chemin_document')->nullable();
            $table->string('fichier_original')->nullable();

            // Notes
            $table->text('Conditions_Particulieres')->nullable();
            $table->text('Notes_Internes')->nullable();

            // Utilisateurs
            $table->foreignId('cree_par')->constrained('users');
            $table->foreignId('modifie_par')->nullable()->constrained('users');

            $table->timestamps();
            $table->softDeletes();

            // Index pour les recherches
            $table->index(['Statut', 'Date_Fin']);
            $table->index('ID_Fournisseur');
            $table->index('Date_Debut');
            $table->index('Date_Fin');
            $table->index('Date_Alerte_Renouvellement');
            $table->index('Alerte_envoyee');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrats_maintenance');
    }
};
