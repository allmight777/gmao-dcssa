<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Utilisateur;
use App\Models\Profil;
use App\Models\Permission;
use App\Models\Localisation;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Permission::truncate();
        Utilisateur::truncate(); 
        Profil::truncate();
        Localisation::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            LocalisationSeeder::class,
            ProfilSeeder::class,
            PermissionSeeder::class,
            UtilisateurSeeder::class,
        ]);

        $this->command->info('🎉 Base de données initialisée avec succès !');
        $this->command->info('Login: admin | Password: password');
    }
}
