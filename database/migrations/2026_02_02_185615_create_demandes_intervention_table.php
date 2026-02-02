<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes_intervention', function (Blueprint $table) {
            $table->id('ID_Demande');

            // Numéro unique de demande (format: DEM-YYYYMMDD-XXXX)
            $table->string('Numero_Demande')->unique();

            $table->date('Date_Demande');
            $table->time('Heure_Demande');

            // Demandeur (utilisateur connecté)
            $table->foreignId('ID_Demandeur')->constrained('users');

            // Équipement concerné
            $table->foreignId('ID_Equipement')->constrained('equipements');

            // Type d'intervention
            $table->enum('Type_Intervention', [
                'maintenance_preventive',
                'maintenance_corrective',
                'reparation',
                'calibration',
                'verification',
                'controle',
                'autre'
            ])->default('maintenance_corrective');

            // Niveau d'urgence
            $table->enum('Urgence', [
                'normale',
                'urgente',
                'critique'
            ])->default('normale');

            $table->text('Description_Panne');

            // Statut de la demande
            $table->enum('Statut', [
                'en_attente',
                'en_cours',
                'validee',
                'rejetee',
                'terminee',
                'annulee'
            ])->default('en_attente');

            // Validation (par chef de service)
            $table->timestamp('Date_Validation')->nullable();
            $table->foreignId('ID_Validateur')->nullable()->constrained('users');

            // Priorité (1 = Haute, 2 = Moyenne, 3 = Basse)
            $table->integer('Priorite')->default(3);

            // Délai souhaité (en heures)
            $table->integer('Delai_Souhaite')->nullable();

            $table->text('Commentaires')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index pour optimiser les recherches
            $table->index(['Statut', 'Urgence']);
            $table->index('Date_Demande');
            $table->index('ID_Demandeur');
            $table->index('ID_Equipement');
            $table->index('ID_Validateur');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes_intervention');
    }
};
