<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
use App\Models\Profil;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Vérifier que l'utilisateur est bien connecté
        if (!$user) {
            return redirect()->route('login');
        }

        // Si l'utilisateur est admin, rediriger vers la page admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.comptes.index');
        }

        // Récupérer des statistiques pour le dashboard
        $stats = [
            'total_utilisateurs' => Utilisateur::count(),
            'utilisateurs_actifs' => Utilisateur::where('statut', 'actif')->count(),
            'total_profils' => Profil::count(),
        ];

        // Rediriger vers le dashboard approprié selon le profil
        $profilNom = strtolower($user->profil->nom ?? '');

        switch ($profilNom) {
            case 'gestionnaire_inventaire':
                return redirect()->route('inventaire.dashboard');
            case 'technicien':
                return redirect()->route('maintenance.dashboard');
            case 'superviseur':
                return redirect()->route('rapports.dashboard');
            default:
                return view('dashboard', compact('stats'));
        }
    }
}
