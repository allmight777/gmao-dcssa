<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profil;

class ProfilSeeder extends Seeder
{
    public function run()
    {
        // Profils par défaut du système GMAO DCSSA
        $profils = [
            [
                'nom_profil' => 'admin',
                'description' => 'Administrateur système avec tous les droits',
                'is_default' => false
            ],
            [
                'nom_profil' => 'gestionnaire_inventaire',
                'description' => 'Gestionnaire d\'inventaire et équipements biomédicaux',
                'is_default' => false
            ],
            [
                'nom_profil' => 'magasinier',
                'description' => 'Gestionnaire de stocks et pièces détachées',
                'is_default' => false
            ],
            [
                'nom_profil' => 'technicien',
                'description' => 'Technicien de maintenance interne',
                'is_default' => false
            ],
            [
                'nom_profil' => 'utilisateur',
                'description' => 'Utilisateur final (médecin, chef de service)',
                'is_default' => true
            ],
            [
                'nom_profil' => 'responsable',
                'description' => 'Responsable contrats et validations',
                'is_default' => false
            ],
            [
                'nom_profil' => 'superviseur',
                'description' => 'Superviseur et directeur',
                'is_default' => false
            ],
            [
                'nom_profil' => 'intervenant_externe',
                'description' => 'Technicien de société de maintenance externe',
                'is_default' => false
            ],
            [
                'nom_profil' => 'formateur',
                'description' => 'Responsable formation du personnel',
                'is_default' => false
            ],
        ];
        
        foreach ($profils as $profil) {
            Profil::create($profil);
        }
    }
}