<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrat_equipements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrat_id')->constrained('contrats_maintenance', 'ID_Contrat');
            $table->foreignId('equipement_id')->constrained('equipements');
            $table->date('date_debut_couverture')->nullable();
            $table->date('date_fin_couverture')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['contrat_id', 'equipement_id']);
            $table->index(
                ['date_debut_couverture', 'date_fin_couverture'],
                'contrat_eq_date_idx'
            );

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrat_equipements');
    }
};
