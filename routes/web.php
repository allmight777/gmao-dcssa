<?php

use App\Http\Controllers\Admin\AdminLogController;
use App\Http\Controllers\Admin\CompteController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ChefDivision\DemandeInterventionChefController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Inventaire\EquipementController;
use App\Http\Controllers\Inventaire\FournisseurController;
use App\Http\Controllers\Inventaire\HistoriqueMouvementController;
use App\Http\Controllers\Inventaire\InventaireController;
use App\Http\Controllers\Inventaire\ScannerController;
use App\Http\Controllers\Inventaire\TypeEquipementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Technicien\TechnicienInterventionController;
use App\Http\Controllers\usersSimple\DemandeInterventionController;
use App\Http\Controllers\usersSimple\ProfilSimpleController;
use App\Http\Controllers\usersSimple\UsersSimpleDashboard;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes d'authentification Breeze
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});


//Gestions des

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Gestion des contrats de maintenance
    Route::prefix('contrats')->name('contrats.')->group(function () {

        // Routes principales
        Route::get('/', [App\Http\Controllers\Admin\ContratMaintenanceController::class, 'index'])
            ->name('index');

        Route::get('/create', [App\Http\Controllers\Admin\ContratMaintenanceController::class, 'create'])
            ->name('create');

        Route::post('/', [App\Http\Controllers\Admin\ContratMaintenanceController::class, 'store'])
            ->name('store');

        Route::get('/{id}', [App\Http\Controllers\Admin\ContratMaintenanceController::class, 'show'])
            ->name('show');

        Route::get('/{id}/edit', [App\Http\Controllers\Admin\ContratMaintenanceController::class, 'edit'])
            ->name('edit');

        Route::put('/{id}', [App\Http\Controllers\Admin\ContratMaintenanceController::class, 'update'])
            ->name('update');

        Route::delete('/{id}', [App\Http\Controllers\Admin\ContratMaintenanceController::class, 'destroy'])
            ->name('destroy');

        // Actions spéciales
        Route::post('/{id}/changer-statut', [App\Http\Controllers\Admin\ContratMaintenanceController::class, 'changerStatut'])
            ->name('changer-statut');

        // Export et rapports
        Route::get('/export/zip', [App\Http\Controllers\Admin\ContratMaintenanceController::class, 'exportZip'])
            ->name('export.zip');

        Route::post('/rapport/pdf', [App\Http\Controllers\Admin\ContratMaintenanceController::class, 'generateRapport'])
            ->name('rapport.pdf');

        Route::get('/{id}/pdf', [App\Http\Controllers\Admin\ContratMaintenanceController::class, 'generatePdf'])
            ->name('pdf');

        // API pour les charts
        Route::get('/chart-data', [App\Http\Controllers\Admin\ContratMaintenanceController::class, 'chartData'])
            ->name('chart-data');

        // Alertes
        Route::post('/send-alerts', [App\Http\Controllers\Admin\ContratMaintenanceController::class, 'sendExpirationAlerts'])
            ->name('send-alerts');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes pour l'administrateur système
    Route::prefix('admin')->name('admin.')->group(function () {
        // Gestion des comptes
        Route::resource('comptes', CompteController::class)->except(['show']);
        Route::get('/comptes/{compte}', [CompteController::class, 'show'])->name('comptes.show');
        Route::post('/comptes/{compte}/reset-password', [CompteController::class, 'resetPassword'])->name('comptes.reset-password');
        Route::post('/comptes/{compte}/toggle-status', [CompteController::class, 'toggleStatus'])->name('comptes.toggle-status');
        Route::get('/comptes/export/csv', [CompteController::class, 'export'])->name('comptes.export');

        // Gestion des profils
        Route::resource('profils', ProfilController::class)->except(['show']);
        Route::get('/profils/{profil}', [ProfilController::class, 'show'])->name('profils.show');
        Route::get('/profils/{profil}/permissions', [ProfilController::class, 'editPermissions'])->name('profils.permissions.edit');
        Route::post('/profils/{profil}/permissions', [ProfilController::class, 'updatePermissions'])->name('profils.permissions.update');
        Route::post('/profils/{profil}/duplicate', [ProfilController::class, 'duplicate'])->name('profils.duplicate');

        // Routes pour la gestion des services
        Route::prefix('services')->name('services.')->group(function () {
            // IMPORTANT: La route export doit être AVANT les routes avec paramètres {service}
            Route::get('/export', [ServiceController::class, 'export'])->name('export');

            // Routes principales
            Route::get('/', [ServiceController::class, 'index'])->name('index');
            Route::get('/create', [ServiceController::class, 'create'])->name('create');
            Route::post('/', [ServiceController::class, 'store'])->name('store');

            // Routes avec paramètre {service} - DOIVENT ÊTRE APRÈS export
            Route::get('/{service}', [ServiceController::class, 'show'])->name('show');
            Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('edit');
            Route::put('/{service}', [ServiceController::class, 'update'])->name('update');
            Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('destroy');

            // Gestion des utilisateurs
            Route::get('/{service}/utilisateurs', [ServiceController::class, 'gestionUtilisateurs'])->name('gestion-utilisateurs');
            Route::post('/{service}/utilisateurs', [ServiceController::class, 'ajouterUtilisateur'])->name('ajouter-utilisateur');
            Route::delete('/{service}/utilisateurs/{utilisateur}', [ServiceController::class, 'retirerUtilisateur'])->name('retirer-utilisateur');
            Route::put('/{service}/utilisateurs/{utilisateur}', [ServiceController::class, 'updateAffectation'])->name('update-affectation');
        });

        // NOUVELLES ROUTES POUR LES LOGS
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', [AdminLogController::class, 'index'])
                ->name('dashboard');

            Route::get('/export', [AdminLogController::class, 'export'])
                ->name('export');

            Route::get('/fichier', [AdminLogController::class, 'viewLogFile'])
                ->name('fichier');

            Route::post('/clear', [AdminLogController::class, 'clearLogs'])
                ->name('clear');
        });

    });

    // Routes pour l'inventaire
    Route::prefix('inventaire')->name('inventaire.')->group(function () {
        // Routes pour les équipements - CORRIGÉES
        Route::prefix('equipements')->name('equipements.')->group(function () {
            Route::get('/', [EquipementController::class, 'index'])->name('index');
            Route::get('/create', [EquipementController::class, 'create'])->name('create');
            Route::post('/', [EquipementController::class, 'store'])->name('store');
            Route::get('/{equipement}', [EquipementController::class, 'show'])->name('show');
            Route::get('/{equipement}/edit', [EquipementController::class, 'edit'])->name('edit');
            Route::put('/{equipement}', [EquipementController::class, 'update'])->name('update');
            Route::delete('/{equipement}', [EquipementController::class, 'destroy'])->name('destroy');

            // Routes supplémentaires
            Route::get('/{equipement}/etiquette', [EquipementController::class, 'genererEtiquette'])
                ->name('genererEtiquette');

            Route::get('/scanner', [EquipementController::class, 'scanner'])
                ->name('scanner');

            // IMPORTANT: Ajoutez cette route pour l'export/rapport
            Route::post('/generer-rapport', [EquipementController::class, 'genererRapport'])
                ->name('genererRapport');

            // Routes optionnelles (si vous en avez besoin)
            Route::get('/{equipement}/mouvement', [EquipementController::class, 'mouvement'])->name('mouvement.create');
            Route::post('/{equipement}/mouvement', [EquipementController::class, 'enregistrerMouvement'])
                ->name('mouvement.store');
            Route::get('/{equipement}/historique', [EquipementController::class, 'historique'])->name('historique');
        });

        // Routes pour les types d'équipement
        Route::prefix('types')->name('types.')->group(function () {
            Route::get('/', [TypeEquipementController::class, 'index'])->name('index');
            Route::get('/create', [TypeEquipementController::class, 'create'])->name('create');
            Route::post('/', [TypeEquipementController::class, 'store'])->name('store');
            Route::get('/{type_equipement}/edit', [TypeEquipementController::class, 'edit'])->name('edit');
            Route::put('/{type_equipement}', [TypeEquipementController::class, 'update'])->name('update');
            Route::delete('/{type_equipement}', [TypeEquipementController::class, 'destroy'])->name('destroy');
        });

        // Routes pour les fournisseurs
        Route::prefix('fournisseurs')->name('fournisseurs.')->group(function () {
            Route::get('/', [FournisseurController::class, 'index'])->name('index');
            Route::get('/create', [FournisseurController::class, 'create'])->name('create');
            Route::post('/', [FournisseurController::class, 'store'])->name('store');
            Route::get('/export', [FournisseurController::class, 'export'])->name('export');
            Route::get('/{fournisseur}', [FournisseurController::class, 'show'])->name('show');
            Route::get('/{fournisseur}/edit', [FournisseurController::class, 'edit'])->name('edit');
            Route::put('/{fournisseur}', [FournisseurController::class, 'update'])->name('update');
            Route::delete('/{fournisseur}', [FournisseurController::class, 'destroy'])->name('destroy');
            Route::patch('/{fournisseur}/toggle-status', [FournisseurController::class, 'toggleStatus'])->name('toggle-status');
        });

        // ============================================
        // NOUVELLES ROUTES : Historique des mouvements
        // ============================================
        Route::prefix('historiques')->name('historiques.')->group(function () {
            // Export CSV - DOIT être AVANT la route {id}
            Route::get('/export', [HistoriqueMouvementController::class, 'export'])->name('export');

            // Liste avec filtres et statistiques
            Route::get('/', [HistoriqueMouvementController::class, 'index'])->name('index');

            // Détails d'un mouvement spécifique
            Route::get('/{id}', [HistoriqueMouvementController::class, 'show'])->name('show');
        });

        // Routes pour les rapports
        Route::prefix('rapports')->name('rapports.')->group(function () {
            Route::get('/', [InventaireController::class, 'index'])->name('index');
            Route::post('/generer', [EquipementController::class, 'genererRapport'])->name('generer');
            Route::get('/stats', [InventaireController::class, 'statistiques'])->name('stats');
            Route::get('/inventaire-physique', [InventaireController::class, 'inventairePhysique'])->name('inventaire-physique');
            Route::post('/inventaire-physique', [InventaireController::class, 'sauvegarderInventaire'])->name('sauvegarder-inventaire');
        });

        // Routes pour le scanner
        Route::prefix('scanner')->name('scanner.')->group(function () {
            Route::get('/', [ScannerController::class, 'index'])->name('index');
            Route::post('/scan', [ScannerController::class, 'scan'])->name('scan');
            Route::get('/scan-manuel', [ScannerController::class, 'scanManuel'])->name('scan-manuel');
            Route::post('/verifier', [ScannerController::class, 'verifier'])->name('verifier');
        });

        // Route pour le tableau de bord inventaire
        Route::get('/dashboard', [InventaireController::class, 'dashboard'])->name('dashboard');
    });

    // Route publique pour scanner les équipements
    Route::get('/scan/{code}', [EquipementController::class, 'scanQR'])->name('inventaire.equipements.scan');

    // Route utilisateurs simples
    Route::get('/UserSimleDashboard', [UsersSimpleDashboard::class, 'index'])->name('UserSimleDashboard');

});

// Routes pour le technicien interne
Route::middleware(['auth'])->prefix('technicien')->name('technicien.')->group(function () {

    // Dashboard technicien
    Route::get('/dashboard', [TechnicienInterventionController::class, 'dashboard'])
        ->name('dashboard');

    // Gestion des demandes d'intervention (UC-TEC-01)
    Route::prefix('demandes')->name('demandes.')->group(function () {
        Route::get('/', [TechnicienInterventionController::class, 'index'])
            ->name('index');
        Route::get('/{id}', [TechnicienInterventionController::class, 'show'])
            ->name('show');
        Route::get('/export/csv', [TechnicienInterventionController::class, 'exportDemandes'])
            ->name('export');
    });

    // Gestion des interventions (UC-TEC-02 et UC-TEC-03)
    Route::prefix('interventions')->name('interventions.')->group(function () {
        // Liste des interventions
        Route::get('/', [TechnicienInterventionController::class, 'interventionsList'])
            ->name('index');

        // Planification (UC-TEC-02)
        Route::get('/planifier/{id}', [TechnicienInterventionController::class, 'planifierForm'])
            ->name('planifier');
        Route::post('/planifier/{id}', [TechnicienInterventionController::class, 'planifierStore'])
            ->name('planifier.store');

        // Saisie de rapport (UC-TEC-03)
        Route::get('/{id}/rapport', [TechnicienInterventionController::class, 'saisirRapportForm'])
            ->name('rapport');
        Route::post('/{id}/rapport', [TechnicienInterventionController::class, 'saisirRapportStore'])
            ->name('rapport.store');

        // Détail d'une intervention
        Route::get('/{id}', [TechnicienInterventionController::class, 'showIntervention'])
            ->name('show');
    });

    // Gestion des équipements (UC-TEC-08)
    Route::prefix('equipements')->name('equipements.')->group(function () {
        Route::patch('/{id}/statut', [TechnicienInterventionController::class, 'updateEquipementStatus'])
            ->name('update-statut');
    });

    // Routes pour la maintenance préventive
    Route::prefix('preventive')->name('preventive.')->group(function () {
        // Liste des équipements éligibles
        Route::get('/equipements', [TechnicienInterventionController::class, 'equipementsPreventive'])
            ->name('equipements');

        // Formulaire de planification
        Route::get('/planifier/{id}', [TechnicienInterventionController::class, 'planifierPreventiveForm'])
            ->name('planifier');

        // Enregistrement de la planification
        Route::post('/planifier/{id}', [TechnicienInterventionController::class, 'planifierPreventiveStore'])
            ->name('planifier.store');

        // Liste des maintenances préventives
        Route::get('/liste', [TechnicienInterventionController::class, 'preventivesList'])
            ->name('index');
    });
});

// Routes pour les utilisateurs simples
Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    // Routes pour les demandes d'intervention
    Route::prefix('demandes')->name('demandes.')->group(function () {
        Route::get('/', [DemandeInterventionController::class, 'index'])->name('index');
        Route::get('/create', [DemandeInterventionController::class, 'create'])->name('create');
        Route::post('/', [DemandeInterventionController::class, 'store'])->name('store');
        Route::get('/{demande}', [DemandeInterventionController::class, 'show'])->name('show');
        Route::get('/{demande}/edit', [DemandeInterventionController::class, 'edit'])->name('edit');
        Route::put('/{demande}', [DemandeInterventionController::class, 'update'])->name('update');
        Route::delete('/{demande}', [DemandeInterventionController::class, 'destroy'])->name('destroy');

        // Gestion de la corbeille
        Route::get('/corbeille', [DemandeInterventionController::class, 'trash'])->name('trash');
        Route::post('/corbeille/{demande}/restore', [DemandeInterventionController::class, 'restore'])->name('restore');
        Route::delete('/corbeille/{demande}/force', [DemandeInterventionController::class, 'forceDelete'])->name('forceDelete');

        // NOUVELLES ROUTES POUR LE PLANNING
        Route::get('/{id}/planning', [DemandeInterventionController::class, 'planning'])->name('planning');
        Route::get('/{id}/planning/detaille', [DemandeInterventionController::class, 'showPlanning'])->name('planning.detaille');

        // Calendrier global
        Route::get('/calendrier', [DemandeInterventionController::class, 'calendrier'])->name('calendrier');

    });
});

// Routes pour le chef de division
Route::middleware(['auth'])->group(function () {
    Route::prefix('chef-division')->name('chef-division.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DemandeInterventionChefController::class, 'dashboard'])
            ->name('dashboard');

        // Gestion des demandes
        Route::prefix('demandes')->name('demandes.')->group(function () {
            Route::get('/', [DemandeInterventionChefController::class, 'index'])
                ->name('index');

            Route::get('/{id}', [DemandeInterventionChefController::class, 'show'])
                ->name('show');

            Route::post('/{id}/valider', [DemandeInterventionChefController::class, 'valider'])
                ->name('valider');

            Route::post('/{id}/rejeter', [DemandeInterventionChefController::class, 'rejeter'])
                ->name('rejeter');

            Route::post('/{id}/mettre-en-attente', [DemandeInterventionChefController::class, 'mettreEnAttente'])
                ->name('mettre-en-attente');
        });
    });
});

// Routes pour les utilisateurs simples
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {

    // Disponibilité équipements
    Route::prefix('equipements')->name('equipements.')->group(function () {
        Route::get('/', [UsersSimpleDashboard::class, 'equipements'])->name('index');
        Route::get('/{id}', [UsersSimpleDashboard::class, 'showEquipement'])->name('show');
        Route::get('/search/quick', [UsersSimpleDashboard::class, 'search'])->name('search');
    });

});

// Pour tous les utilisateurs authentifiés
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // Mon profil
    Route::get('/mon-profile', [ProfilSimpleController::class, 'view'])->name('profile.view');
    Route::get('/mon-profile/modifier', [ProfilSimpleController::class, 'modifier'])->name('profile.modifier');
    Route::put('/mon-profile/mettre-a-jour', [ProfilSimpleController::class, 'mettreAJour'])->name('profile.mettre-a-jour');
    Route::put('/mon-profile/modifier-mot-de-passe', [ProfilSimpleController::class, 'modifierMotDePasse'])->name('profile.modifier-mot-de-passe');
});

Route::get('/medDashboard', function () {
    return view('welcome');
});

// Redirection racine
Route::redirect('/', '/medDashboard');
