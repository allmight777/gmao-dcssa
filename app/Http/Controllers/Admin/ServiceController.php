<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Localisation;
use App\Models\Utilisateur; // Changé de User à Utilisateur
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    /**
     * UC-ADM-03 : Afficher la liste des services
     */
    public function index(Request $request)
    {
        $query = Localisation::where('type', 'service')
            ->with(['parent', 'responsable'])
            ->withCount('utilisateurs'); // Changé de 'users' à 'utilisateurs'
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('code_geographique', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }
        
        $services = $query->latest()->paginate(20);
        
        // Pour les filtres : récupérer uniquement les services
        $servicesList = Localisation::where('type', 'service')->get();
        
        return view('admin.services.index', compact('services', 'servicesList'));
    }

    /**
     * UC-ADM-03 : Afficher le formulaire de création
     */
    public function create()
    {
        // Types avec option "Autre"
        $types = [
            'service' => 'Service',
            'site' => 'Site',
            'direction' => 'Direction',
            'batiment' => 'Bâtiment',
            'salle' => 'Salle',
            'bureau' => 'Bureau',
            'atelier' => 'Atelier',
            'depot' => 'Dépôt',
            'laboratoire' => 'Laboratoire',
            'autre' => 'Autre (saisie libre)',
        ];
        
        // Parent : uniquement les services déjà enregistrés
        $parents = Localisation::where('type', 'service')
            ->select('id', 'nom', 'parent_id')
            ->orderBy('nom')
            ->get()
            ->mapWithKeys(function ($item) {
                $prefix = $item->parent_id ? '↳ ' : '';
                return [$item->id => $prefix . $item->nom];
            });
        
        // Responsables : depuis la table users (Utilisateur model)
        $responsables = Utilisateur::where('statut', 'actif')
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get()
            ->mapWithKeys(function ($user) {
                return [$user->id => "{$user->nom} {$user->prenom} - {$user->matricule} ({$user->grade})"];
            });
        
        return view('admin.services.create', compact('types', 'parents', 'responsables'));
    }

    /**
     * UC-ADM-03 : Enregistrer un nouveau service
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required',
            'type_custom' => 'required_if:type,autre|max:50',
            'nom' => 'required|max:255',
            'parent_id' => 'nullable|exists:localisations,id',
            'code_geographique' => 'nullable|max:50|unique:localisations,code_geographique',
            'responsable_id' => 'nullable|exists:users,id', // Table users
            'adresse' => 'nullable|max:500',
            'telephone' => 'nullable|max:20',
            'description' => 'nullable',
        ]);
        
        // Gérer le type personnalisé
        if ($validated['type'] === 'autre' && !empty($validated['type_custom'])) {
            $validated['type'] = $validated['type_custom'];
        }
        unset($validated['type_custom']);
        
        // Générer un code géographique automatique si vide
        if (empty($validated['code_geographique'])) {
            $validated['code_geographique'] = $this->generateGeographicCode(
                $validated['type'],
                $validated['nom']
            );
        }
        
        DB::beginTransaction();
        
        try {
            $service = Localisation::create($validated);
            
            // Log l'activité
            \App\Models\LogActivite::create([
                'id_utilisateur' => auth()->id(), // Changé user_id à id_utilisateur
                'date_heure' => now(),
                'action' => 'creation_service',
                'module' => 'administration',
                'id_element' => $service->id, // Changé element_id à id_element
                'adresse_ip' => $request->ip(),
                'details' => "Création du {$service->type} : {$service->nom}",
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.services.index')
                ->with('success', "{$service->type} créé avec succès.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * UC-ADM-03 : Afficher les détails d'un service
     */
    public function show(Localisation $service)
    {
        $service->load([
            'parent',
            'responsable',
            'children',
            'utilisateurs' => function($query) {
                $query->withPivot('date_affectation', 'fonction_service');
            }
        ]);
        
        // Statistiques
        $statistiques = [
            'total_utilisateurs' => $service->utilisateurs()->count(),
            'total_enfants' => $service->children()->count(),
            'derniers_utilisateurs' => $service->utilisateurs()
                ->orderBy('utilisateur_service.date_affectation', 'desc')
                ->limit(5)
                ->get(),
        ];
        
        return view('admin.services.show', compact('service', 'statistiques'));
    }

    /**
     * UC-ADM-03 : Afficher le formulaire d'édition
     */
    public function edit(Localisation $service)
    {
        // Types avec option "Autre"
        $types = [
            'service' => 'Service',
            'site' => 'Site',
            'direction' => 'Direction',
            'batiment' => 'Bâtiment',
            'salle' => 'Salle',
            'bureau' => 'Bureau',
            'atelier' => 'Atelier',
            'depot' => 'Dépôt',
            'laboratoire' => 'Laboratoire',
            'autre' => 'Autre (saisie libre)',
        ];
        
        // Parent : uniquement les services (sauf lui-même)
        $parents = Localisation::where('type', 'service')
            ->where('id', '!=', $service->id)
            ->select('id', 'nom', 'parent_id')
            ->orderBy('nom')
            ->get()
            ->mapWithKeys(function ($item) {
                $prefix = $item->parent_id ? '↳ ' : '';
                return [$item->id => $prefix . $item->nom];
            });
        
        // Responsables : depuis la table users (Utilisateur model)
        $responsables = Utilisateur::where('statut', 'actif')
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get()
            ->mapWithKeys(function ($user) {
                return [$user->id => "{$user->nom} {$user->prenom} - {$user->matricule} ({$user->grade})"];
            });
        
        // Déterminer si c'est un type personnalisé
        $isCustomType = !in_array($service->type, array_keys($types));
        if ($isCustomType) {
            $service->original_type = $service->type;
            $service->type = 'autre';
        }
        
        return view('admin.services.edit', compact('service', 'types', 'parents', 'responsables', 'isCustomType'));
    }

    /**
     * UC-ADM-03 : Mettre à jour un service
     */
    public function update(Request $request, Localisation $service)
    {
        $validated = $request->validate([
            'type' => 'required',
            'type_custom' => 'required_if:type,autre|max:50',
            'nom' => 'required|max:255',
            'parent_id' => 'nullable|exists:localisations,id',
            'code_geographique' => [
                'nullable',
                'max:50',
                Rule::unique('localisations', 'code_geographique')->ignore($service->id)
            ],
            'responsable_id' => 'nullable|exists:users,id',
            'adresse' => 'nullable|max:500',
            'telephone' => 'nullable|max:20',
            'description' => 'nullable',
        ]);
        
        // Gérer le type personnalisé
        if ($validated['type'] === 'autre' && !empty($validated['type_custom'])) {
            $validated['type'] = $validated['type_custom'];
        }
        unset($validated['type_custom']);
        
        // Vérifier qu'on ne crée pas de boucle dans la hiérarchie
        if ($validated['parent_id'] && $this->createsHierarchyLoop($service, $validated['parent_id'])) {
            return redirect()->back()
                ->with('error', 'Impossible de définir ce parent car cela créerait une boucle dans la hiérarchie.')
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            $service->update($validated);
            
            // Log l'activité
            \App\Models\LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'modification_service',
                'module' => 'administration',
                'id_element' => $service->id,
                'adresse_ip' => $request->ip(),
                'details' => "Modification du {$service->type} : {$service->nom}",
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.services.index')
                ->with('success', "{$service->type} mis à jour avec succès.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * UC-ADM-03 : Supprimer un service
     */
    public function destroy(Request $request, Localisation $service)
    {
        // Vérifier si le service a des enfants
        if ($service->children()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Ce service a des sous-éléments. Vous devez d\'abord les supprimer ou les déplacer.');
        }
        
        // Vérifier si le service a des utilisateurs
        if ($service->utilisateurs()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Ce service est associé à des utilisateurs. Vous ne pouvez pas le supprimer.');
        }
        
        DB::beginTransaction();
        
        try {
            $service->delete();
            
            // Log l'activité
            \App\Models\LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'suppression_service',
                'module' => 'administration',
                'id_element' => $service->id,
                'adresse_ip' => $request->ip(),
                'details' => "Suppression du {$service->type} : {$service->nom}",
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.services.index')
                ->with('success', "{$service->type} supprimé avec succès.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * UC-ADM-03 : Gérer les utilisateurs d'un service
     */
    public function gestionUtilisateurs(Localisation $service)
    {
        $service->load(['utilisateurs' => function($query) {
            $query->withPivot('date_affectation', 'fonction_service');
        }]);
        
        $utilisateursDisponibles = Utilisateur::where('statut', 'actif')
            ->whereNotIn('id', $service->utilisateurs->pluck('id'))
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();
        
        return view('admin.services.gestion-utilisateurs', 
            compact('service', 'utilisateursDisponibles'));
    }

    /**
     * UC-ADM-03 : Ajouter un utilisateur à un service
     */
    public function ajouterUtilisateur(Request $request, Localisation $service)
    {
        $validated = $request->validate([
            'utilisateur_id' => 'required|exists:users,id',
            'date_affectation' => 'nullable|date',
            'fonction_service' => 'nullable|max:100',
        ]);
        
        // Vérifier si l'utilisateur n'est pas déjà dans ce service
        if ($service->utilisateurs()->where('utilisateur_id', $validated['utilisateur_id'])->exists()) {
            return redirect()->back()
                ->with('error', 'Cet utilisateur est déjà affecté à ce service.');
        }
        
        DB::beginTransaction();
        
        try {
            $service->utilisateurs()->attach($validated['utilisateur_id'], [
                'date_affectation' => $validated['date_affectation'] ?? now(),
                'fonction_service' => $validated['fonction_service'],
            ]);
            
            // Mettre à jour le service_id de l'utilisateur
            Utilisateur::where('id', $validated['utilisateur_id'])
                ->update(['service_id' => $service->id]);
            
            // Log l'activité
            $utilisateur = Utilisateur::find($validated['utilisateur_id']);
            \App\Models\LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'affectation_utilisateur',
                'module' => 'administration',
                'id_element' => $service->id,
                'adresse_ip' => $request->ip(),
                'details' => "Affectation de {$utilisateur->nom} {$utilisateur->prenom} au service {$service->nom}",
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Utilisateur affecté au service avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'affectation : ' . $e->getMessage());
        }
    }

    /**
     * UC-ADM-03 : Retirer un utilisateur d'un service
     */
    public function retirerUtilisateur(Request $request, Localisation $service, Utilisateur $utilisateur)
    {
        DB::beginTransaction();
        
        try {
            $service->utilisateurs()->detach($utilisateur->id);
            
            // Si c'était le service principal, le retirer
            if ($utilisateur->service_id == $service->id) {
                $utilisateur->update(['service_id' => null]);
            }
            
            // Log l'activité
            \App\Models\LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'retrait_utilisateur',
                'module' => 'administration',
                'id_element' => $service->id,
                'adresse_ip' => $request->ip(),
                'details' => "Retrait de {$utilisateur->nom} {$utilisateur->prenom} du service {$service->nom}",
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Utilisateur retiré du service avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors du retrait : ' . $e->getMessage());
        }
    }

    /**
     * UC-ADM-03 : Mettre à jour l'affectation d'un utilisateur
     */
    public function updateAffectation(Request $request, Localisation $service, Utilisateur $utilisateur)
    {
        $validated = $request->validate([
            'date_affectation' => 'nullable|date',
            'fonction_service' => 'nullable|max:100',
        ]);
        
        DB::beginTransaction();
        
        try {
            $service->utilisateurs()->updateExistingPivot($utilisateur->id, [
                'date_affectation' => $validated['date_affectation'],
                'fonction_service' => $validated['fonction_service'],
            ]);
            
            // Log l'activité
            \App\Models\LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'modification_affectation',
                'module' => 'administration',
                'id_element' => $service->id,
                'adresse_ip' => $request->ip(),
                'details' => "Modification de l'affectation de {$utilisateur->nom} {$utilisateur->prenom} dans le service {$service->nom}",
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Affectation mise à jour avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * UC-ADM-03 : Exporter la liste des services
     */
    public function export()
    {
        $services = Localisation::with(['parent', 'responsable'])
            ->orderBy('type')
            ->orderBy('nom')
            ->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="services_' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($services) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Type', 'Nom', 'Code Géographique', 'Parent', 'Responsable', 'Téléphone', 'Adresse', 'Description']);
            
            foreach ($services as $service) {
                fputcsv($file, [
                    $service->type,
                    $service->nom,
                    $service->code_geographique,
                    $service->parent->nom ?? 'N/A',
                    $service->responsable ? $service->responsable->nom . ' ' . $service->responsable->prenom : 'N/A',
                    $service->telephone,
                    $service->adresse,
                    $service->description,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Générer un code géographique automatique
     */
    private function generateGeographicCode($type, $nom)
    {
        $prefix = strtoupper(substr($type, 0, 3));
        $nomCode = strtoupper(preg_replace('/[^A-Z]/', '', substr($nom, 0, 3)));
        $timestamp = date('ymd');
        $random = strtoupper(substr(md5(time()), 0, 3));
        
        return $prefix . '-' . $nomCode . '-' . $timestamp . $random;
    }

    /**
     * Vérifier si la hiérarchie crée une boucle
     */
    private function createsHierarchyLoop(Localisation $service, $parentId)
    {
        if ($parentId == $service->id) {
            return true;
        }
        
        $parent = Localisation::find($parentId);
        while ($parent) {
            if ($parent->parent_id == $service->id) {
                return true;
            }
            $parent = $parent->parent;
        }
        
        return false;
    }
}