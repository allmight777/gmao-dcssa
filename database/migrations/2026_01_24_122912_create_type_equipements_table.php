<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('type_equipements', function (Blueprint $table) {
            $table->id();
            $table->string('code_type')->unique();
            $table->string('libelle');
            $table->string('classe')->nullable();
            $table->integer('duree_vie_standard')->nullable();
            $table->integer('periodicite_maintenance')->nullable();
            $table->enum('risque', ['faible', 'moyen', 'eleve', 'critique'])->default('faible');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('type_equipements');
    }
};