<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utilisateur;
use App\Models\Profil;
use App\Models\Localisation;
use Illuminate\Support\Facades\Hash;

class UtilisateurSeeder extends Seeder
{
    public function run()
    {
        // Récupérer les profils
        $adminProfil = Profil::where('nom_profil', 'admin')->first();
        $gestionnaireProfil = Profil::where('nom_profil', 'gestionnaire_inventaire')->first();
        $magasinierProfil = Profil::where('nom_profil', 'magasinier')->first();
        $technicienProfil = Profil::where('nom_profil', 'technicien')->first();
        $utilisateurProfil = Profil::where('nom_profil', 'utilisateur')->first();
        $superviseurProfil = Profil::where('nom_profil', 'superviseur')->first();
        
        // Récupérer les services
        $directionService = Localisation::where('nom', 'Direction Centrale')->first();
        $infoService = Localisation::where('nom', 'Service Informatique')->first();
        $maintenanceService = Localisation::where('nom', 'Maintenance Biomédicale')->first();
        $logistiqueService = Localisation::where('nom', 'Logistique')->first();
        $laboService = Localisation::where('nom', 'Laboratoire')->first();
        
        // 1. ADMINISTRATEUR SYSTÈME (Compte principal pour tests)
        Utilisateur::create([
            'matricule' => 'ADMIN001',
            'nom' => 'ADMIN',
            'prenom' => 'Système',
            'grade' => 'Ingénieur Principal',
            'fonction' => 'Responsable Informatique DCSSA',
            'service_id' => $directionService->id,
            'email' => 'admin@gmao.dcssa',
            'telephone' => '+221 77 123 45 67',
            'login' => 'admin',
            'password' => 'password',
            'profil_id' => $adminProfil->id,
            'statut' => 'actif',
            'date_derniere_connexion' => now(),
        ]);
        
        // 2. ADMINISTRATEUR DE TEST (Compte de secours)
        Utilisateur::create([
            'matricule' => 'ADMIN002',
            'nom' => 'DIOP',
            'prenom' => 'Amadou',
            'grade' => 'Ingénieur',
            'fonction' => 'Administrateur Systèmes',
            'service_id' => $infoService->id,
            'email' => 'a.diop@dcssa.sn',
            'telephone' => '+221 77 234 56 78',
            'login' => 'adiop',
            'password' => 'password',
            'profil_id' => $adminProfil->id,
            'statut' => 'actif',
        ]);
        
        // 3. GESTIONNAIRE D'INVENTAIRE
        Utilisateur::create([
            'matricule' => 'INV001',
            'nom' => 'NDIAYE',
            'prenom' => 'Fatou',
            'grade' => 'Adjoint Technique',
            'fonction' => 'Gestionnaire d\'Inventaire',
            'service_id' => $logistiqueService->id,
            'email' => 'f.ndiaye@dcssa.sn',
            'telephone' => '+221 77 345 67 89',
            'login' => 'fndiaye',
            'password' => 'password',
            'profil_id' => $gestionnaireProfil->id,
            'statut' => 'actif',
        ]);
        
        // 4. MAGASINIER
        Utilisateur::create([
            'matricule' => 'MAG001',
            'nom' => 'SALL',
            'prenom' => 'Moussa',
            'grade' => 'Agent Technique',
            'fonction' => 'Magasinier Central',
            'service_id' => $logistiqueService->id,
            'email' => 'm.sall@dcssa.sn',
            'telephone' => '+221 77 456 78 90',
            'login' => 'msall',
            'password' => 'password',
            'profil_id' => $magasinierProfil->id,
            'statut' => 'actif',
        ]);
        
        // 5. TECHNICIEN BIOMÉDICAL
        Utilisateur::create([
            'matricule' => 'TEC001',
            'nom' => 'FALL',
            'prenom' => 'Ibrahima',
            'grade' => 'Technicien Supérieur',
            'fonction' => 'Technicien Biomédical',
            'service_id' => $maintenanceService->id,
            'email' => 'i.fall@dcssa.sn',
            'telephone' => '+221 77 567 89 01',
            'login' => 'ifall',
            'password' => 'password',
            'profil_id' => $technicienProfil->id,
            'statut' => 'actif',
        ]);
        
        // 6. MÉDECIN CHEF (Utilisateur standard)
        Utilisateur::create([
            'matricule' => 'MED001',
            'nom' => 'DIENG',
            'prenom' => 'Aïssatou',
            'grade' => 'Médecin Colonel',
            'fonction' => 'Médecin Chef de Service',
            'service_id' => $laboService->id,
            'email' => 'a.dieng@dcssa.sn',
            'telephone' => '+221 77 678 90 12',
            'login' => 'adieng',
            'password' => 'password',
            'profil_id' => $utilisateurProfil->id,
            'statut' => 'actif',
        ]);
        
        // 7. SUPERVISEUR / DIRECTEUR
        Utilisateur::create([
            'matricule' => 'SUP001',
            'nom' => 'MBAYE',
            'prenom' => 'Omar',
            'grade' => 'Directeur',
            'fonction' => 'Directeur de la Logistique',
            'service_id' => $directionService->id,
            'email' => 'o.mbaye@dcssa.sn',
            'telephone' => '+221 77 789 01 23',
            'login' => 'ombaye',
            'password' => 'password',
            'profil_id' => $superviseurProfil->id,
            'statut' => 'actif',
        ]);
        
        // 8-15. Autres utilisateurs de test
        $noms = ['SOW', 'TOURE', 'KANE', 'SY', 'GUEYE', 'BA', 'DIOUF', 'CISSE'];
        $prenoms = ['Mariama', 'Abdoulaye', 'Khadija', 'Mamadou', 'Rokhaya', 'Cheikh', 'Maimouna', 'Alioune'];
        $fonctions = [
            'Infirmier Chef', 'Radiologue', 'Pharmacien', 'Manipulateur Radio',
            'Laborantin', 'Chirurgien', 'Anesthésiste', 'Cadre Administratif'
        ];
        
        for ($i = 0; $i < 8; $i++) {
            Utilisateur::create([
                'matricule' => 'EMP' . str_pad($i + 100, 3, '0', STR_PAD_LEFT),
                'nom' => $noms[$i],
                'prenom' => $prenoms[$i],
                'grade' => 'Grade ' . ($i + 1),
                'fonction' => $fonctions[$i],
                'service_id' => Localisation::inRandomOrder()->first()->id,
                'email' => strtolower($prenoms[$i][0]) . '.' . strtolower($noms[$i]) . '@dcssa.sn',
                'telephone' => '+221 77 ' . rand(1000000, 9999999),
                'login' => strtolower($prenoms[$i][0]) . strtolower($noms[$i]),
                'password' => 'password',
                'profil_id' => $utilisateurProfil->id,
                'statut' => rand(0, 1) ? 'actif' : 'inactif',
            ]);
        }
        
        echo "✅ Utilisateurs créés avec succès !\n";
        echo "===============================\n";
        echo "Comptes de test disponibles :\n";
        echo "1. Administrateur : admin / password\n";
        echo "2. Gestionnaire : fndiaye / password\n";
        echo "3. Technicien : ifall / password\n";
        echo "4. Médecin : adieng / password\n";
        echo "5. Superviseur : ombaye / password\n";
        echo "===============================\n";
    }
}