<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Affiche la page de login.
     */
    public function create(): View
    {
        // Si l'utilisateur est déjà connecté, le rediriger
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Traite la requête d'authentification.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
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

        // Redirection en fonction du profil
        return $this->redirectUser($user);
    }

    /**
     * Redirige l'utilisateur en fonction de son profil
     */
    private function redirectUser($user): RedirectResponse
    {
        // Vérifier le profil de l'utilisateur
        if ($user->profil_id == 5) {
            // Utilisateur simple
            return redirect()->route('UserSimleDashboard');
        } else {
            // Administrateurs et autres profils
            return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    /**
     * Déconnecte l'utilisateur.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Vous avez été déconnecté avec succès.');
    }
}
