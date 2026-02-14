<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_intervention_reports_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intervention_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('demande_id')->constrained('demandes_intervention', 'ID_Demande');
            $table->foreignId('technicien_id')->constrained('users');

            $table->date('date_realisation');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('duree_totale'); // Format HH:MM

            $table->text('travaux_realises');
            $table->text('pieces_utilisees')->nullable();
            $table->text('observations')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('demande_id');
            $table->index('technicien_id');
            $table->index('date_realisation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intervention_reports');
    }
};
