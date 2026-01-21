<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_activite', function (Blueprint $table) {
            $table->id();

            // Référence à l'utilisateur mais sans FK SQL
            $table->unsignedBigInteger('id_utilisateur');

            $table->timestamp('date_heure')->useCurrent();
            $table->string('action');       // login, logout, create, update, delete, etc.
            $table->string('module');       // administration, inventaire, etc.
            $table->string('id_element')->nullable(); // ID de l'élément concerné
            $table->string('adresse_ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('details')->nullable();

            $table->timestamps();

            // Index
            $table->index('id_utilisateur');
            $table->index('date_heure');
            $table->index(['module', 'action']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_activite');
    }
};
