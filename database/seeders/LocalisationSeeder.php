<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Localisation;

class LocalisationSeeder extends Seeder
{
    public function run()
    {
        // Services principaux de la DCSSA
        $localisations = [
            [
                'type' => 'service',
                'nom' => 'Direction Centrale',
                'code_geographique' => 'DCSSA-001',
                'adresse' => 'Cité Administrative, Dakar',
                'telephone' => '+221 33 889 12 34',
                'description' => 'Direction Centrale du Service de Santé des Armées'
            ],
            [
                'type' => 'service',
                'nom' => 'Service Informatique',
                'code_geographique' => 'DCSSA-002',
                'adresse' => 'Cité Administrative, Dakar',
                'telephone' => '+221 33 889 12 35',
                'description' => 'Service informatique et systèmes d\'information'
            ],
            [
                'type' => 'service',
                'nom' => 'Maintenance Biomédicale',
                'code_geographique' => 'DCSSA-003',
                'adresse' => 'Hôpital Principal, Dakar',
                'telephone' => '+221 33 889 12 36',
                'description' => 'Service de maintenance des équipements biomédicaux'
            ],
            [
                'type' => 'service',
                'nom' => 'Logistique',
                'code_geographique' => 'DCSSA-004',
                'adresse' => 'Cité Administrative, Dakar',
                'telephone' => '+221 33 889 12 37',
                'description' => 'Service logistique et gestion des stocks'
            ],
            [
                'type' => 'service',
                'nom' => 'Radiologie',
                'code_geographique' => 'DCSSA-005',
                'adresse' => 'Hôpital Principal, Dakar',
                'telephone' => '+221 33 889 12 38',
                'description' => 'Service de radiologie et imagerie médicale'
            ],
            [
                'type' => 'service',
                'nom' => 'Laboratoire',
                'code_geographique' => 'DCSSA-006',
                'adresse' => 'Hôpital Principal, Dakar',
                'telephone' => '+221 33 889 12 39',
                'description' => 'Laboratoire d\'analyses médicales'
            ],
            [
                'type' => 'service',
                'nom' => 'Bloc Opératoire',
                'code_geographique' => 'DCSSA-007',
                'adresse' => 'Hôpital Principal, Dakar',
                'telephone' => '+221 33 889 12 40',
                'description' => 'Bloc opératoire et salles d\'opération'
            ],
            [
                'type' => 'service',
                'nom' => 'Réanimation',
                'code_geographique' => 'DCSSA-008',
                'adresse' => 'Hôpital Principal, Dakar',
                'telephone' => '+221 33 889 12 41',
                'description' => 'Service de réanimation et soins intensifs'
            ],
        ];
        
        foreach ($localisations as $localisation) {
            Localisation::create($localisation);
        }
    }
}