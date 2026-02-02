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
        Schema::create('intervention', function (Blueprint $table) {

            // 2.1.4 INTERVENTION
            $table->id('ID_Intervention');

            $table->unsignedBigInteger('ID_Demande')->nullable();

            $table->date('Date_Debut')->nullable();
            $table->time('Heure_Debut')->nullable();

            $table->date('Date_Fin')->nullable();
            $table->time('Heure_Fin')->nullable();

            $table->decimal('Duree_Reelle', 8, 2)->nullable();

            $table->string('Type_Intervenant')->nullable();
            $table->unsignedBigInteger('ID_Intervenant')->nullable();

            $table->decimal('Cout_Main_Oeuvre', 12, 2)->default(0);
            $table->decimal('Cout_Pieces', 12, 2)->default(0);
            $table->decimal('Cout_Total', 12, 2)->default(0);

            $table->string('Resultat')->nullable();
            $table->text('Rapport_Technique')->nullable();

            $table->unsignedBigInteger('ID_Equipement_Controle');

            $table->string('Statut_Conformite')->nullable();
            $table->string('Signature_Client')->nullable();

            $table->timestamps();

            // Clé étrangère (si la table equipements existe)
            $table->foreign('ID_Equipement_Controle')
                  ->references('id')
                  ->on('equipements')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intervention');
    }
};
