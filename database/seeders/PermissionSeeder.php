<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profil;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les profils
        $admin = Profil::where('nom_profil', 'admin')->first();
        $gestionnaire = Profil::where('nom_profil', 'gestionnaire_inventaire')->first();
        $magasinier = Profil::where('nom_profil', 'magasinier')->first();
        $technicien = Profil::where('nom_profil', 'technicien')->first();
        $utilisateur = Profil::where('nom_profil', 'utilisateur')->first();
        $superviseur = Profil::where('nom_profil', 'superviseur')->first();
        
        // Permissions pour l'administrateur (tous les droits)
        $adminPermissions = [
            // Administration
            ['module' => 'administration', 'action' => 'view'],
            ['module' => 'administration', 'action' => 'create'],
            ['module' => 'administration', 'action' => 'edit'],
            ['module' => 'administration', 'action' => 'delete'],
            ['module' => 'administration', 'action' => 'manage'],
            
            // Inventaire
            ['module' => 'inventaire', 'action' => 'view'],
            ['module' => 'inventaire', 'action' => 'create'],
            ['module' => 'inventaire', 'action' => 'edit'],
            ['module' => 'inventaire', 'action' => 'delete'],
            ['module' => 'inventaire', 'action' => 'transfer'],
            ['module' => 'inventaire', 'action' => 'reform'],
            
            // Stock
            ['module' => 'stock', 'action' => 'view'],
            ['module' => 'stock', 'action' => 'create'],
            ['module' => 'stock', 'action' => 'edit'],
            ['module' => 'stock', 'action' => 'delete'],
            ['module' => 'stock', 'action' => 'order'],
            ['module' => 'stock', 'action' => 'receive'],
            
            // Maintenance
            ['module' => 'maintenance', 'action' => 'view'],
            ['module' => 'maintenance', 'action' => 'create'],
            ['module' => 'maintenance', 'action' => 'edit'],
            ['module' => 'maintenance', 'action' => 'delete'],
            ['module' => 'maintenance', 'action' => 'execute'],
            ['module' => 'maintenance', 'action' => 'validate'],
            
            // Rapports
            ['module' => 'rapports', 'action' => 'view'],
            ['module' => 'rapports', 'action' => 'export'],
            ['module' => 'rapports', 'action' => 'print'],
            
            // Configuration
            ['module' => 'configuration', 'action' => 'view'],
            ['module' => 'configuration', 'action' => 'edit'],
            
            // Formation
            ['module' => 'formation', 'action' => 'view'],
            ['module' => 'formation', 'action' => 'create'],
            ['module' => 'formation', 'action' => 'edit'],
            ['module' => 'formation', 'action' => 'delete'],
            ['module' => 'formation', 'action' => 'assign'],
        ];
        
        foreach ($adminPermissions as $permission) {
            $admin->permissions()->create($permission);
        }
        
        // Permissions pour le gestionnaire d'inventaire
        $gestionnairePermissions = [
            ['module' => 'inventaire', 'action' => 'view'],
            ['module' => 'inventaire', 'action' => 'create'],
            ['module' => 'inventaire', 'action' => 'edit'],
            ['module' => 'inventaire', 'action' => 'transfer'],
            ['module' => 'inventaire', 'action' => 'reform'],
            ['module' => 'rapports', 'action' => 'view'],
            ['module' => 'rapports', 'action' => 'export'],
        ];
        
        foreach ($gestionnairePermissions as $permission) {
            $gestionnaire->permissions()->create($permission);
        }
        
        // Permissions pour le magasinier
        $magasinierPermissions = [
            ['module' => 'stock', 'action' => 'view'],
            ['module' => 'stock', 'action' => 'create'],
            ['module' => 'stock', 'action' => 'edit'],
            ['module' => 'stock', 'action' => 'order'],
            ['module' => 'stock', 'action' => 'receive'],
            ['module' => 'rapports', 'action' => 'view'],
        ];
        
        foreach ($magasinierPermissions as $permission) {
            $magasinier->permissions()->create($permission);
        }
        
        // Permissions pour le technicien
        $technicienPermissions = [
            ['module' => 'maintenance', 'action' => 'view'],
            ['module' => 'maintenance', 'action' => 'create'],
            ['module' => 'maintenance', 'action' => 'edit'],
            ['module' => 'maintenance', 'action' => 'execute'],
            ['module' => 'stock', 'action' => 'view'],
            ['module' => 'stock', 'action' => 'order'],
            ['module' => 'rapports', 'action' => 'view'],
        ];
        
        foreach ($technicienPermissions as $permission) {
            $technicien->permissions()->create($permission);
        }
        
        // Permissions pour l'utilisateur
        $utilisateurPermissions = [
            ['module' => 'inventaire', 'action' => 'view'],
            ['module' => 'maintenance', 'action' => 'view'],
            ['module' => 'maintenance', 'action' => 'create'],
            ['module' => 'stock', 'action' => 'view'],
        ];
        
        foreach ($utilisateurPermissions as $permission) {
            $utilisateur->permissions()->create($permission);
        }
        
        // Permissions pour le superviseur
        $superviseurPermissions = [
            ['module' => 'administration', 'action' => 'view'],
            ['module' => 'inventaire', 'action' => 'view'],
            ['module' => 'stock', 'action' => 'view'],
            ['module' => 'maintenance', 'action' => 'view'],
            ['module' => 'maintenance', 'action' => 'validate'],
            ['module' => 'rapports', 'action' => 'view'],
            ['module' => 'rapports', 'action' => 'export'],
            ['module' => 'rapports', 'action' => 'print'],
            ['module' => 'configuration', 'action' => 'view'],
        ];
        
        foreach ($superviseurPermissions as $permission) {
            $superviseur->permissions()->create($permission);
        }
    }
}