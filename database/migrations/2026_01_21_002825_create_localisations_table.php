<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('localisations', function (Blueprint $table) {
            $table->id();

            $table->string('type'); // site, batiment, service, salle...
            $table->string('nom');

            // hiérarchie interne OK
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('localisations')
                  ->nullOnDelete();

            $table->string('code_geographique')->nullable();

            // ❌ PAS de clé étrangère SQL (relation logique)
            $table->unsignedBigInteger('responsable_id')->nullable();

            $table->text('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'nom']);
            $table->index('parent_id');
            $table->index('responsable_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('localisations');
    }
};
