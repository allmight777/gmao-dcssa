<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('module'); // administration, inventaire, stock, maintenance, etc.
            $table->string('action'); // view, create, edit, delete, etc.
            
            // FK vers profils
            $table->foreignId('profil_id')->constrained('profils')->onDelete('cascade');

            $table->timestamps();

            // Contraintes et index
            $table->unique(['module', 'action', 'profil_id']);
            $table->index(['module', 'action']);
            $table->index('profil_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
