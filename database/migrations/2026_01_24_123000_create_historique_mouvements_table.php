<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('historique_mouvements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipement_id')->constrained('equipements')->onDelete('cascade');
            $table->dateTime('date_mouvement');
            $table->foreignId('ancienne_localisation_id')->nullable()->constrained('localisations')->onDelete('set null');
            $table->foreignId('nouvelle_localisation_id')->nullable()->constrained('localisations')->onDelete('set null'); 
            $table->string('motif');
            $table->foreignId('operateur_id')->constrained('users')->onDelete('cascade');
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('historique_mouvements');
    }
};
