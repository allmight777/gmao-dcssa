<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Affiche la page de login.
     */
    public function create(): View
    {
        // Vérifier si l'utilisateur est déjà connecté
        if (Auth::check()) {
            return $this->redirectBasedOnProfile(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Traite la requête d'authentification.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            // Récupérer l'utilisateur authentifié
            $user = Auth::user();

            // Vérifier si le compte est actif
            if ($user->statut !== 'actif') {
                Auth::logout();
                return back()->withErrors([
                    'login' => 'Votre compte est ' . $user->statut . '. Veuillez contacter l\'administrateur.',
                ]);
            }

            // Mettre à jour la date de dernière connexion
            $user->date_derniere_connexion = now();
            $user->save();

            // Log de la connexion
            Log::info('Connexion utilisateur', [
                'user_id' => $user->id,
                'matricule' => $user->matricule,
                'profil' => $user->profil_id,
                'ip' => $request->ip()
            ]);

            // Redirection en fonction du profil
            return $this->redirectBasedOnProfile($user);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'authentification', [
                'error' => $e->getMessage(),
                'login' => $request->login,
                'ip' => $request->ip()
            ]);

            return back()->withErrors([
                'login' => 'Une erreur est survenue lors de la connexion.',
            ]);
        }
    }

    /**
     * Redirige l'utilisateur en fonction de son profil
     */
    private function redirectBasedOnProfile($user): RedirectResponse
    {
        // Définir les routes pour chaque profil
        $profileRoutes = [
            1 => 'dashboard', // Administrateur
            2 => 'inventaire.dashboard', // Gestionnaire d'inventaire
            3 => 'inventaire.dashboard', // Magasinier
            4 => 'inventaire.dashboard', // Technicien
            5 => 'UserSimleDashboard', // Utilisateur simple
            6 => 'dashboard', // Responsable
            7 => 'dashboard', // Superviseur
            8 => 'dashboard', // Intervenant externe
        ];

        // Obtenir la route correspondante au profil
        $route = $profileRoutes[$user->profil_id] ?? 'dashboard';

        // Rediriger vers la route appropriée
        return redirect()->route($route);
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            // Log de la déconnexion
            Log::info('Déconnexion utilisateur', [
                'user_id' => $user->id,
                'matricule' => $user->matricule
            ]);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Vous avez été déconnecté avec succès.');
    }
}
