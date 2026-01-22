<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use App\Models\Profil;
use App\Models\Localisation;
use App\Models\LogActivite;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

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
        $services = Localisation::service()->get();

        return view('admin.comptes.index', compact('utilisateurs', 'profils', 'services'));
    }

    /**
     * UC-ADM-01 : Afficher le formulaire de création
     */
    public function create()
    {
        $profils = Profil::all();
        $services = Localisation::service()->get();

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

        LogActivite::create([
            'id_utilisateur' => auth()->id(),
            'date_heure' => now(),
            'action' => 'creation_compte',
            'module' => 'administration',
            'id_element' => $utilisateur->id,
            'adresse_ip' => $request->ip(),
            'details' => "Création du compte pour {$utilisateur->nom} {$utilisateur->prenom}",
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.comptes.index')
            ->with('success', 'Compte créé avec succès.');
    }

    public function show(Utilisateur $compte)
    {
        $compte->load(['profil', 'service', 'logs' => function($query) {
            $query->latest()->limit(50);
        }]);

        return view('admin.comptes.show', compact('compte'));
    }

    public function edit(Utilisateur $compte)
    {
        $profils = Profil::all();
        $services = Localisation::service()->get();

        return view('admin.comptes.edit', compact('compte', 'profils', 'services'));
    }

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

    LogActivite::create([
        'id_utilisateur' => auth()->id(),
        'date_heure' => now(),
        'action' => 'modification_compte',
        'module' => 'administration',
        'id_element' => $compte->id,
        'adresse_ip' => $request->ip(),
        'details' => "Modification du compte {$compte->nom} {$compte->prenom}",
        'user_agent' => $request->userAgent(),
    ]);

    return redirect()->route('admin.comptes.index')
        ->with('success', 'Compte mis à jour avec succès.');
}

    public function destroy(Request $request, Utilisateur $compte)
    {
        if ($compte->id == auth()->id()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $compte->delete();

        LogActivite::create([
            'id_utilisateur' => auth()->id(),
            'date_heure' => now(),
            'action' => 'suppression_compte',
            'module' => 'administration',
            'id_element' => $compte->id,
            'adresse_ip' => $request->ip(),
            'details' => "Suppression du compte {$compte->nom} {$compte->prenom}",
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.comptes.index')
            ->with('success', 'Compte désactivé avec succès.');
    }

    public function resetPassword(Request $request, Utilisateur $compte)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $compte->update([
            'password' => Hash::make($request->password),
        ]);

        LogActivite::create([
            'id_utilisateur' => auth()->id(),
            'date_heure' => now(),
            'action' => 'reinitialisation_mdp',
            'module' => 'administration',
            'id_element' => $compte->id,
            'adresse_ip' => $request->ip(),
            'details' => "Réinitialisation du mot de passe pour {$compte->nom} {$compte->prenom}",
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()
            ->with('success', 'Mot de passe réinitialisé avec succès.');
    }

    public function toggleStatus(Request $request, Utilisateur $compte)
    {
        if ($compte->id == auth()->id()) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas modifier le statut de votre propre compte.');
        }

        $ancienStatut = $compte->statut;
        $nouveauStatut = $compte->statut == 'actif' ? 'inactif' : 'actif';

        $compte->update(['statut' => $nouveauStatut]);

        LogActivite::create([
            'id_utilisateur' => auth()->id(),
            'date_heure' => now(),
            'action' => 'changement_statut_compte',
            'module' => 'administration',
            'id_element' => $compte->id,
            'adresse_ip' => $request->ip(),
            'details' => "Changement de statut de {$ancienStatut} à {$nouveauStatut} pour {$compte->nom} {$compte->prenom}",
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()
            ->with('success', "Statut du compte modifié avec succès.");
    }

    public function export()
    {
        $utilisateurs = Utilisateur::with(['profil', 'service'])->actif()->get();

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
