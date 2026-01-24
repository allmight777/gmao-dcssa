<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Profil;
use App\Models\Localisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CompteController extends Controller
{
    /**
     * UC-ADM-01 : Afficher la liste des comptes
     */
    public function index(Request $request)
    {
        $query = Utilisateur::with(['profil', 'service'])->latest();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('prenom', 'like', '%' . $request->search . '%')
                  ->orWhere('matricule', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('login', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('profil_id')) {
            $query->where('profil_id', $request->profil_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        $utilisateurs = $query->paginate(20);
        $profils = Profil::all();
        $services = Localisation::where('type', 'service')->get();

        // Statistiques pour les charts
        $statistics = $this->getStatistics();

        return view('admin.comptes.index', compact('utilisateurs', 'profils', 'services', 'statistics'));
    }

    /**
     * Récupérer les statistiques pour les charts
     */
    private function getStatistics()
    {
        // Distribution par profil
        $profilesDistribution = DB::table('users')
            ->join('profils', 'users.profil_id', '=', 'profils.id')
            ->select('profils.nom_profil', DB::raw('count(*) as count'))
            ->groupBy('profils.nom_profil')
            ->get()
            ->pluck('count', 'nom_profil')
            ->toArray();

        // Distribution par statut
        $statusDistribution = DB::table('users')
            ->select('statut', DB::raw('count(*) as count'))
            ->groupBy('statut')
            ->get()
            ->pluck('count', 'statut')
            ->toArray();

        // Activité des derniers 30 jours - SI la table log_activite existe
        $activityLast30Days = [];
        try {
            // Vérifier si la table existe
            if (Schema::hasTable('log_activite')) {
                $activityLast30Days = DB::table('log_activite')
                    ->select(DB::raw('DATE(date_heure) as date'), DB::raw('count(*) as count'))
                    ->where('date_heure', '>=', now()->subDays(30))
                    ->groupBy(DB::raw('DATE(date_heure)'))
                    ->orderBy('date')
                    ->get();
            }
        } catch (\Exception $e) {
            // Si la table n'existe pas, on continue sans ces données
            $activityLast30Days = collect([]);
        }

        // Top 5 services avec le plus d'utilisateurs
        $topServices = DB::table('users')
            ->join('localisations', 'users.service_id', '=', 'localisations.id')
            ->select('localisations.nom', DB::raw('count(*) as count'))
            ->whereNotNull('users.service_id')
            ->groupBy('localisations.nom')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Nouveaux utilisateurs du mois
        $newUsersThisMonth = DB::table('users')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Utilisateurs actifs aujourd'hui
        $activeUsersToday = DB::table('users')
            ->where('date_derniere_connexion', '>=', now()->startOfDay())
            ->count();

        return [
            'total' => Utilisateur::count(),
            'active_today' => $activeUsersToday,
            'new_this_month' => $newUsersThisMonth,
            'profiles_distribution' => $profilesDistribution,
            'status_distribution' => $statusDistribution,
            'activity_last_30_days' => $activityLast30Days,
            'top_services' => $topServices,
            'by_month' => $this->getUsersByMonth(),
        ];
    }

    /**
     * Récupérer les utilisateurs par mois (derniers 6 mois)
     */
    private function getUsersByMonth()
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthYear = $date->format('M Y');
            
            $count = DB::table('users')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $months[$monthYear] = $count;
        }

        return $months;
    }

    /**
     * UC-ADM-01 : Afficher le formulaire de création
     */
    public function create()
    {
        $profils = Profil::all();
        $services = Localisation::where('type', 'service')->get();

        return view('admin.comptes.create', compact('profils', 'services'));
    }

    /**
     * UC-ADM-01 : Enregistrer un nouveau compte
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule' => 'required|unique:users|max:50',
            'nom' => 'required|max:100',
            'prenom' => 'required|max:100',
            'grade' => 'nullable|max:50',
            'fonction' => 'required|max:100',
            'service_id' => 'nullable|exists:localisations,id',
            'email' => 'required|email|unique:users|max:100',
            'telephone' => 'nullable|max:20',
            'login' => 'required|unique:users|max:50',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profil_id' => 'required|exists:profils,id',
            'statut' => 'required|in:actif,inactif,suspendu',
        ]);

        $validated['password'] = Hash::make($request->password);

        $utilisateur = Utilisateur::create($validated);

        // Log d'activité si la table existe
        try {
            if (Schema::hasTable('log_activite')) {
                DB::table('log_activite')->insert([
                    'id_utilisateur' => auth()->id(),
                    'date_heure' => now(),
                    'action' => 'creation_compte',
                    'module' => 'administration',
                    'id_element' => $utilisateur->id,
                    'adresse_ip' => $request->ip(),
                    'details' => "Création du compte pour {$utilisateur->nom} {$utilisateur->prenom}",
                    'user_agent' => $request->userAgent(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Ignorer l'erreur si la table n'existe pas
        }

        return redirect()->route('admin.comptes.index')
            ->with('success', 'Compte créé avec succès.');
    }

    /**
     * UC-ADM-01 : Afficher les détails d'un compte
     */
    public function show(Utilisateur $compte)
    {
        $compte->load(['profil', 'service']);
        
        // Charger les logs si la table existe
        $logs = [];
        try {
            if (Schema::hasTable('log_activite')) {
                $logs = DB::table('log_activite')
                    ->where('id_element', $compte->id)
                    ->orWhere('id_utilisateur', $compte->id)
                    ->latest()
                    ->limit(50)
                    ->get();
            }
        } catch (\Exception $e) {
            // Ignorer l'erreur si la table n'existe pas
        }

        return view('admin.comptes.show', compact('compte', 'logs'));
    }

    /**
     * UC-ADM-01 : Afficher le formulaire d'édition
     */
    public function edit(Utilisateur $compte)
    {
        $profils = Profil::all();
        $services = Localisation::where('type', 'service')->get();

        return view('admin.comptes.edit', compact('compte', 'profils', 'services'));
    }

    /**
     * UC-ADM-01 : Mettre à jour un compte
     */
    public function update(Request $request, Utilisateur $compte)
    {
        $validated = $request->validate([
            'matricule' => 'required|unique:users,matricule,' . $compte->id . '|max:50',
            'nom' => 'required|max:100',
            'prenom' => 'required|max:100',
            'grade' => 'nullable|max:50',
            'fonction' => 'required|max:100',
            'service_id' => 'nullable|exists:localisations,id',
            'email' => 'required|email|unique:users,email,' . $compte->id . '|max:100',
            'telephone' => 'nullable|max:20',
            'login' => 'required|unique:users,login,' . $compte->id . '|max:50',
            'profil_id' => 'required|exists:profils,id',
            'statut' => 'required|in:actif,inactif,suspendu',
            'new_password' => 'nullable|min:12|confirmed',
        ]);

        // Si un nouveau mot de passe est fourni, le mettre à jour
        if ($request->filled('new_password')) {
            $validated['password'] = Hash::make($request->new_password);
        }

        // Supprimer le champ temporaire du tableau de validation
        unset($validated['new_password']);

        $compte->update($validated);

        // Log d'activité si la table existe
        try {
            if (Schema::hasTable('log_activite')) {
                DB::table('log_activite')->insert([
                    'id_utilisateur' => auth()->id(),
                    'date_heure' => now(),
                    'action' => 'modification_compte',
                    'module' => 'administration',
                    'id_element' => $compte->id,
                    'adresse_ip' => $request->ip(),
                    'details' => "Modification du compte {$compte->nom} {$compte->prenom}",
                    'user_agent' => $request->userAgent(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Ignorer l'erreur si la table n'existe pas
        }

        return redirect()->route('admin.comptes.index')
            ->with('success', 'Compte mis à jour avec succès.');
    }

    /**
     * UC-ADM-01 : Supprimer un compte
     */
    public function destroy(Request $request, Utilisateur $compte)
    {
        if ($compte->id == auth()->id()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $compte->delete();

        // Log d'activité si la table existe
        try {
            if (Schema::hasTable('log_activite')) {
                DB::table('log_activite')->insert([
                    'id_utilisateur' => auth()->id(),
                    'date_heure' => now(),
                    'action' => 'suppression_compte',
                    'module' => 'administration',
                    'id_element' => $compte->id,
                    'adresse_ip' => $request->ip(),
                    'details' => "Suppression du compte {$compte->nom} {$compte->prenom}",
                    'user_agent' => $request->userAgent(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Ignorer l'erreur si la table n'existe pas
        }

        return redirect()->route('admin.comptes.index')
            ->with('success', 'Compte désactivé avec succès.');
    }

    /**
     * UC-ADM-01 : Réinitialiser le mot de passe
     */
    public function resetPassword(Request $request, Utilisateur $compte)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $compte->update([
            'password' => Hash::make($request->password),
        ]);

        // Log d'activité si la table existe
        try {
            if (Schema::hasTable('log_activite')) {
                DB::table('log_activite')->insert([
                    'id_utilisateur' => auth()->id(),
                    'date_heure' => now(),
                    'action' => 'reinitialisation_mdp',
                    'module' => 'administration',
                    'id_element' => $compte->id,
                    'adresse_ip' => $request->ip(),
                    'details' => "Réinitialisation du mot de passe pour {$compte->nom} {$compte->prenom}",
                    'user_agent' => $request->userAgent(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Ignorer l'erreur si la table n'existe pas
        }

        return redirect()->back()
            ->with('success', 'Mot de passe réinitialisé avec succès.');
    }

    /**
     * UC-ADM-01 : Basculer le statut d'un compte
     */
    public function toggleStatus(Request $request, Utilisateur $compte)
    {
        if ($compte->id == auth()->id()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas modifier le statut de votre propre compte.');
        }

        $ancienStatut = $compte->statut;
        $nouveauStatut = $compte->statut == 'actif' ? 'inactif' : 'actif';

        $compte->update(['statut' => $nouveauStatut]);

        // Log d'activité si la table existe
        try {
            if (Schema::hasTable('log_activite')) {
                DB::table('log_activite')->insert([
                    'id_utilisateur' => auth()->id(),
                    'date_heure' => now(),
                    'action' => 'changement_statut_compte',
                    'module' => 'administration',
                    'id_element' => $compte->id,
                    'adresse_ip' => $request->ip(),
                    'details' => "Changement de statut de {$ancienStatut} à {$nouveauStatut} pour {$compte->nom} {$compte->prenom}",
                    'user_agent' => $request->userAgent(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Ignorer l'erreur si la table n'existe pas
        }

        return redirect()->back()
            ->with('success', "Statut du compte modifié avec succès.");
    }

    /**
     * UC-ADM-01 : Exporter la liste des comptes
     */
    public function export()
    {
        $utilisateurs = Utilisateur::with(['profil', 'service'])->where('statut', 'actif')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="utilisateurs_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($utilisateurs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Matricule', 'Nom', 'Prénom', 'Fonction', 'Service', 'Profil', 'Email', 'Téléphone', 'Statut']);

            foreach ($utilisateurs as $utilisateur) {
                fputcsv($file, [
                    $utilisateur->matricule,
                    $utilisateur->nom,
                    $utilisateur->prenom,
                    $utilisateur->fonction,
                    $utilisateur->service->nom ?? 'N/A',
                    $utilisateur->profil->nom_profil ?? 'N/A',
                    $utilisateur->email,
                    $utilisateur->telephone,
                    $utilisateur->statut,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}