<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('equipements', function (Blueprint $table) {
            $table->id();
            $table->string('numero_inventaire')->unique();
            $table->string('numero_serie')->nullable();
            $table->string('marque');
            $table->string('modele');
            $table->foreignId('type_equipement_id')->nullable()->constrained('type_equipements')->nullOnDelete();
            $table->string('classe_equipement')->nullable();
            $table->date('date_achat');
            $table->date('date_mise_service')->nullable();
            $table->decimal('prix_achat', 15, 2)->nullable();
            $table->integer('duree_vie_theorique')->nullable();
            $table->integer('duree_garantie')->nullable();
            $table->enum('etat', ['neuf', 'bon', 'moyen', 'mauvais', 'hors_service']);
            $table->enum('type_maintenance', ['preventive', 'curative', 'mixte']);
            $table->foreignId('localisation_id')->nullable()->constrained('localisations')->nullOnDelete();
            $table->foreignId('service_responsable_id')->nullable()->constrained('localisations')->nullOnDelete();
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs')->nullOnDelete();
            
            // Rendons cette contrainte nullable pour l'instant
            $table->foreignId('contrat_id')->nullable()->constrained('contrats_maintenance')->nullOnDelete();
            
            $table->text('commentaires')->nullable();
            $table->date('date_reforme')->nullable();
            $table->string('code_barres')->nullable()->unique();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipements');
    }
};