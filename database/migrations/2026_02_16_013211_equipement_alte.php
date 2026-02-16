<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('equipements', function (Blueprint $table) {
        $table->foreign('contrat_id')
              ->references('ID_Contrat')
              ->on('contrats_maintenance')
              ->nullOnDelete();
    });
}

public function down()
{
    Schema::table('equipements', function (Blueprint $table) {
        $table->dropForeign(['contrat_id']);
    });
}

};
