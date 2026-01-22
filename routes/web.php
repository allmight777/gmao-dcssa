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

    // Routes pour l'administrateur système (simplifié)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('comptes', CompteController::class)->except(['show']);
        Route::get('/comptes/{compte}', [CompteController::class, 'show'])->name('comptes.show');
        Route::post('/comptes/{compte}/reset-password', [CompteController::class, 'resetPassword'])->name('comptes.reset-password');
        Route::post('/comptes/{compte}/toggle-status', [CompteController::class, 'toggleStatus'])->name('comptes.toggle-status');
        Route::get('/comptes/export/csv', [CompteController::class, 'export'])->name('comptes.export');

        Route::resource('profils', ProfilController::class)->except(['show']);
        Route::get('/profils/{profil}', [ProfilController::class, 'show'])->name('profils.show');
        Route::get('/profils/{profil}/permissions', [ProfilController::class, 'editPermissions'])->name('profils.permissions.edit');
        Route::post('/profils/{profil}/permissions', [ProfilController::class, 'updatePermissions'])->name('profils.permissions.update');
        Route::post('/profils/{profil}/duplicate', [ProfilController::class, 'duplicate'])->name('profils.duplicate');
    });



});


    // Routes pour la gestion des services
Route::prefix('admin/services')->name('admin.services.')->middleware(['auth'])->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('/create', [ServiceController::class, 'create'])->name('create');
    Route::post('/', [ServiceController::class, 'store'])->name('store');
    Route::get('/{service}', [ServiceController::class, 'show'])->name('show');
    Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('edit');
    Route::put('/{service}', [ServiceController::class, 'update'])->name('update');
    Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('destroy');
    
    // Gestion des utilisateurs
    Route::get('/{service}/utilisateurs', [ServiceController::class, 'gestionUtilisateurs'])->name('gestion-utilisateurs');
    Route::post('/{service}/utilisateurs', [ServiceController::class, 'ajouterUtilisateur'])->name('ajouter-utilisateur');
    Route::delete('/{service}/utilisateurs/{utilisateur}', [ServiceController::class, 'retirerUtilisateur'])->name('retirer-utilisateur');
    Route::put('/{service}/utilisateurs/{utilisateur}', [ServiceController::class, 'updateAffectation'])->name('update-affectation');
    
    // Export
    Route::get('/export', [ServiceController::class, 'export'])->name('export');
});

// Redirection racine
Route::redirect('/', '/dashboard');
