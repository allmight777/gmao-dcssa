<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Localisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Affiche la page d'inscription
     */
    public function create(): View
    {
        $services = Localisation::where('type', 'service')
            ->orderBy('nom')
            ->get();

        return view('auth.register', compact('services'));
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'matricule' => 'required|string|max:50|unique:users',
            'login' => 'required|string|max:50|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'service_id' => 'required|exists:localisations,id',
            'telephone' => 'nullable|string|max:20',
        ]);

        // Créer l'utilisateur
        $user = Utilisateur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'matricule' => $request->matricule,
            'login' => $request->login,
            'email' => $request->email,
            'password' => $request->password,
            'telephone' => $request->telephone,
            'service_id' => $request->service_id,
            'fonction' => 'Utilisateur', // Valeur par défaut
            'statut' => 'actif',
            'profil_id' => $request->profil_id ?? 5,
        ]);

        Auth::login($user);

        return redirect()->route('UserSimleDashboard');
    }
}
