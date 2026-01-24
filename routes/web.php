<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CompteController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Inventaire\EquipementController;
use App\Http\Controllers\Inventaire\TypeEquipementController;
use App\Http\Controllers\Inventaire\FournisseurController;
use App\Http\Controllers\Inventaire\InventaireController;
use App\Http\Controllers\Inventaire\ScannerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

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
});

// Redirection racine
Route::redirect('/', '/dashboard');