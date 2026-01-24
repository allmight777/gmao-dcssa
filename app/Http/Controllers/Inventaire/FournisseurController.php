<?php

namespace App\Http\Controllers\Inventaire;

use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use App\Models\Equipement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LogActivite;

class FournisseurController extends Controller
{
    /**
     * Afficher la liste des fournisseurs
     */
    public function index(Request $request)
    {
        $query = Fournisseur::query();
        
        // Filtres
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('raison_sociale', 'like', '%' . $request->search . '%')
                  ->orWhere('code_fournisseur', 'like', '%' . $request->search . '%')
                  ->orWhere('contact_principal', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        if ($request->filled('evaluation')) {
            $query->where('evaluation', $request->evaluation);
        }
        
        $fournisseurs = $query->orderBy('raison_sociale')->paginate(20);
        
        // Types de fournisseurs
        $types = [
            'fabricant' => 'Fabricant',
            'distributeur' => 'Distributeur',
            'maintenance' => 'Maintenance',
            'autre' => 'Autre'
        ];
        
        // Statuts
        $statuts = [
            'actif' => 'Actif',
            'inactif' => 'Inactif',
            'suspendu' => 'Suspendu'
        ];
        
        // Évaluations
        $evaluations = [
            'excellent' => 'Excellent',
            'bon' => 'Bon',
            'moyen' => 'Moyen',
            'mauvais' => 'Mauvais'
        ];
        
        // Statistiques
        $statistiques = [
            'total' => Fournisseur::count(),
            'actif' => Fournisseur::where('statut', 'actif')->count(),
            'fabricants' => Fournisseur::where('type', 'fabricant')->count(),
            'excellents' => Fournisseur::where('evaluation', 'excellent')->count(),
        ];
        
        return view('inventaire.fournisseurs.index', compact(
            'fournisseurs',
            'types',
            'statuts',
            'evaluations',
            'statistiques'
        ));
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $types = [
            'fabricant' => 'Fabricant',
            'distributeur' => 'Distributeur',
            'maintenance' => 'Maintenance',
            'autre' => 'Autre'
        ];
        
        $statuts = [
            'actif' => 'Actif',
            'inactif' => 'Inactif',
            'suspendu' => 'Suspendu'
        ];
        
        $evaluations = [
            'excellent' => 'Excellent',
            'bon' => 'Bon',
            'moyen' => 'Moyen',
            'mauvais' => 'Mauvais'
        ];
        
        return view('inventaire.fournisseurs.create', compact('types', 'statuts', 'evaluations'));
    }
    
    /**
     * Enregistrer un nouveau fournisseur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_fournisseur' => 'required|unique:fournisseurs|max:20',
            'raison_sociale' => 'required|max:200',
            'adresse' => 'nullable|max:255',
            'telephone' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'contact_principal' => 'nullable|max:100',
            'type' => 'required|in:fabricant,distributeur,maintenance,autre',
            'statut' => 'required|in:actif,inactif,suspendu',
            'date_premiere_commande' => 'nullable|date',
            'date_derniere_commande' => 'nullable|date',
            'evaluation' => 'nullable|in:excellent,bon,moyen,mauvais',
            'notes' => 'nullable',
        ]);
        
        DB::beginTransaction();
        
        try {
            $fournisseur = Fournisseur::create($validated);
            
            // Log de l'activité
            LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'creation_fournisseur',
                'module' => 'inventaire',
                'id_element' => $fournisseur->id,
                'adresse_ip' => $request->ip(),
                'details' => "Création du fournisseur {$fournisseur->raison_sociale} ({$fournisseur->code_fournisseur})",
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->route('inventaire.fournisseurs.index')
                ->with('success', 'Fournisseur créé avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Afficher les détails d'un fournisseur
     */
    public function show(Fournisseur $fournisseur)
    {
        $fournisseur->load(['equipements' => function($query) {
            $query->with(['localisation', 'serviceResponsable'])
                ->latest()
                ->limit(10);
        }]);
        
        $statistiques = [
            'total_equipements' => $fournisseur->equipements()->count(),
            'actifs' => $fournisseur->equipements()->actif()->count(),
            'hors_service' => $fournisseur->equipements()->horsService()->count(),
            'valeur_total' => $fournisseur->equipements()->sum('prix_achat'),
        ];
        
        return view('inventaire.fournisseurs.show', compact('fournisseur', 'statistiques'));
    }
    
    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Fournisseur $fournisseur)
    {
        $types = [
            'fabricant' => 'Fabricant',
            'distributeur' => 'Distributeur',
            'maintenance' => 'Maintenance',
            'autre' => 'Autre'
        ];
        
        $statuts = [
            'actif' => 'Actif',
            'inactif' => 'Inactif',
            'suspendu' => 'Suspendu'
        ];
        
        $evaluations = [
            'excellent' => 'Excellent',
            'bon' => 'Bon',
            'moyen' => 'Moyen',
            'mauvais' => 'Mauvais'
        ];
        
        return view('inventaire.fournisseurs.edit', compact('fournisseur', 'types', 'statuts', 'evaluations'));
    }
    
    /**
     * Mettre à jour un fournisseur
     */
    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validated = $request->validate([
            'code_fournisseur' => 'required|unique:fournisseurs,code_fournisseur,' . $fournisseur->id . '|max:20',
            'raison_sociale' => 'required|max:200',
            'adresse' => 'nullable|max:255',
            'telephone' => 'nullable|max:20',
            'email' => 'nullable|email|max:100',
            'contact_principal' => 'nullable|max:100',
            'type' => 'required|in:fabricant,distributeur,maintenance,autre',
            'statut' => 'required|in:actif,inactif,suspendu',
            'date_premiere_commande' => 'nullable|date',
            'date_derniere_commande' => 'nullable|date',
            'evaluation' => 'nullable|in:excellent,bon,moyen,mauvais',
            'notes' => 'nullable',
        ]);
        
        DB::beginTransaction();
        
        try {
            $fournisseur->update($validated);
            
            // Log de l'activité
            LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'modification_fournisseur',
                'module' => 'inventaire',
                'id_element' => $fournisseur->id,
                'adresse_ip' => $request->ip(),
                'details' => "Modification du fournisseur {$fournisseur->raison_sociale} ({$fournisseur->code_fournisseur})",
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->route('inventaire.fournisseurs.show', $fournisseur)
                ->with('success', 'Fournisseur mis à jour avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Supprimer un fournisseur
     */
    public function destroy(Request $request, Fournisseur $fournisseur)
    {
        DB::beginTransaction();
        
        try {
            // Vérifier si des équipements sont liés à ce fournisseur
            $equipementsCount = $fournisseur->equipements()->count();
            
            if ($equipementsCount > 0) {
                return redirect()->back()
                    ->with('error', "Impossible de supprimer ce fournisseur. Il est lié à {$equipementsCount} équipement(s).");
            }
            
            $fournisseur->delete();
            
            // Log de l'activité
            LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'suppression_fournisseur',
                'module' => 'inventaire',
                'id_element' => $fournisseur->id,
                'adresse_ip' => $request->ip(),
                'details' => "Suppression du fournisseur {$fournisseur->raison_sociale} ({$fournisseur->code_fournisseur})",
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->route('inventaire.fournisseurs.index')
                ->with('success', 'Fournisseur supprimé avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }


    /**
 * Exporter la liste des fournisseurs en CSV
 */
public function export()
{
    $fournisseurs = Fournisseur::all();
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="fournisseurs_' . date('Y-m-d_H-i') . '.csv"',
    ];

    $callback = function() use ($fournisseurs) {
        $file = fopen('php://output', 'w');
        
        // En-têtes
        fputcsv($file, [
            'Code Fournisseur',
            'Raison Sociale',
            'Type',
            'Statut',
            'Adresse',
            'Téléphone',
            'Email',
            'Contact Principal',
            'Date Première Commande',
            'Date Dernière Commande',
            'Évaluation',
            'Date Création'
        ]);

        // Données
        foreach ($fournisseurs as $fournisseur) {
            fputcsv($file, [
                $fournisseur->code_fournisseur,
                $fournisseur->raison_sociale,
                $fournisseur->type,
                $fournisseur->statut,
                $fournisseur->adresse,
                $fournisseur->telephone,
                $fournisseur->email,
                $fournisseur->contact_principal,
                $fournisseur->date_premiere_commande?->format('d/m/Y'),
                $fournisseur->date_derniere_commande?->format('d/m/Y'),
                $fournisseur->evaluation,
                $fournisseur->created_at->format('d/m/Y H:i')
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

/**
 * Toggle du statut d'un fournisseur
 */
public function toggleStatus(Request $request, Fournisseur $fournisseur)
{
    DB::beginTransaction();
    
    try {
        $ancienStatut = $fournisseur->statut;
        $nouveauStatut = $ancienStatut == 'actif' ? 'inactif' : 'actif';
        
        $fournisseur->update(['statut' => $nouveauStatut]);
        
        // Log de l'activité
        LogActivite::create([
            'id_utilisateur' => auth()->id(),
            'date_heure' => now(),
            'action' => 'changement_statut_fournisseur',
            'module' => 'inventaire',
            'id_element' => $fournisseur->id,
            'adresse_ip' => $request->ip(),
            'details' => "Changement de statut du fournisseur {$fournisseur->raison_sociale} : {$ancienStatut} → {$nouveauStatut}",
            'user_agent' => $request->userAgent(),
        ]);
        
        DB::commit();
        
        return redirect()->back()
            ->with('success', 'Statut du fournisseur modifié avec succès.');
            
    } catch (\Exception $e) {
        DB::rollBack();
        
        return redirect()->back()
            ->with('error', 'Erreur lors du changement de statut : ' . $e->getMessage());
    }
}
}