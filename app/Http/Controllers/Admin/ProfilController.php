<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profil;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    /**
     * UC-ADM-02 : Afficher la liste des profils
     */
    public function index(Request $request)
    {
        $query = Profil::withCount('utilisateurs')
            ->latest();
        
        if ($request->filled('search')) {
            $query->where('nom_profil', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        $profils = $query->paginate(15);
        
        return view('admin.profils.index', compact('profils'));
    }

    /**
     * UC-ADM-02 : Afficher le formulaire de création
     */
    public function create()
    {
        $modules = Profil::getAvailableModules();
        
        return view('admin.profils.create', compact('modules'));
    }

    /**
     * UC-ADM-02 : Enregistrer un nouveau profil
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_profil' => 'required|unique:profils|max:100',
            'description' => 'nullable|max:255',
            'permissions' => 'nullable|array',
            'permissions.*.module' => 'required|string',
            'permissions.*.actions' => 'required|array',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Créer le profil
            $profil = Profil::create([
                'nom_profil' => $validated['nom_profil'],
                'description' => $validated['description'],
            ]);
            
            // Ajouter les permissions
            if (isset($validated['permissions'])) {
                foreach ($validated['permissions'] as $permData) {
                    $module = $permData['module'];
                    foreach ($permData['actions'] as $action) {
                        $profil->permissions()->create([
                            'module' => $module,
                            'action' => $action,
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            // Log l'activité
            \App\Models\LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'creation_profil',
                'module' => 'administration',
                'id_element' => $profil->id,
                'adresse_ip' => $request->ip(),
                'details' => "Création du profil {$profil->nom_profil}",
                'user_agent' => $request->userAgent(),
            ]);
            
            return redirect()->route('admin.profils.index')
                ->with('success', 'Profil créé avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la création du profil: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * UC-ADM-02 : Afficher les détails d'un profil
     */
    public function show(Profil $profil)
    {
        $profil->load(['utilisateurs', 'permissions']);
        
        // Grouper les permissions par module
        $permissionsParModule = $profil->permissions->groupBy('module');
        
        $modules = Profil::getAvailableModules();
        
        return view('admin.profils.show', compact('profil', 'permissionsParModule', 'modules'));
    }

    /**
     * UC-ADM-02 : Afficher le formulaire d'édition
     */
    public function edit(Profil $profil)
    {
        $profil->load('permissions');
        
        // Grouper les permissions par module
        $permissionsParModule = $profil->permissions->groupBy('module');
        
        $modules = Profil::getAvailableModules();
        
        return view('admin.profils.edit', compact('profil', 'permissionsParModule', 'modules'));
    }

    /**
     * UC-ADM-02 : Mettre à jour un profil
     */
    public function update(Request $request, Profil $profil)
    {
        $validated = $request->validate([
            'nom_profil' => 'required|unique:profils,nom_profil,' . $profil->id . '|max:100',
            'description' => 'nullable|max:255',
            'permissions' => 'nullable|array',
            'permissions.*.module' => 'required|string',
            'permissions.*.actions' => 'required|array',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Mettre à jour le profil
            $profil->update([
                'nom_profil' => $validated['nom_profil'],
                'description' => $validated['description'],
            ]);
            
            // Synchroniser les permissions
            $profil->permissions()->delete();
            
            if (isset($validated['permissions'])) {
                foreach ($validated['permissions'] as $permData) {
                    $module = $permData['module'];
                    foreach ($permData['actions'] as $action) {
                        $profil->permissions()->create([
                            'module' => $module,
                            'action' => $action,
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            // Log l'activité
            \App\Models\LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'modification_profil',
                'module' => 'administration',
                'id_element' => $profil->id,
                'adresse_ip' => $request->ip(),
                'details' => "Modification du profil {$profil->nom_profil}",
                'user_agent' => $request->userAgent(),
            ]);
            
            return redirect()->route('admin.profils.index')
                ->with('success', 'Profil mis à jour avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du profil: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * UC-ADM-02 : Supprimer un profil
     */
    public function destroy(Request $request, Profil $profil)
    {
        // Vérifier si le profil est utilisé
        if ($profil->utilisateurs()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Ce profil est utilisé par des utilisateurs. Vous ne pouvez pas le supprimer.');
        }
        
        // Empêcher la suppression du profil admin
        if ($profil->nom_profil === 'admin') {
            return redirect()->back()
                ->with('error', 'Le profil administrateur ne peut pas être supprimé.');
        }
        
        $profil->delete();
        
        // Log l'activité
        \App\Models\LogActivite::create([
            'id_utilisateur' => auth()->id(),
            'date_heure' => now(),
            'action' => 'suppression_profil',
            'module' => 'administration',
            'id_element' => $profil->id,
            'adresse_ip' => $request->ip(),
            'details' => "Suppression du profil {$profil->nom_profil}",
            'user_agent' => $request->userAgent(),
        ]);
        
        return redirect()->route('admin.profils.index')
            ->with('success', 'Profil supprimé avec succès.');
    }

    /**
     * UC-ADM-02 : Gérer les permissions d'un profil
     */
    public function editPermissions(Profil $profil)
    {
        $profil->load('permissions');
        
        // Grouper les permissions par module
        $permissionsParModule = $profil->permissions->groupBy('module');
        
        $modules = Profil::getAvailableModules();
        
        return view('admin.profils.permissions', compact('profil', 'permissionsParModule', 'modules'));
    }

    /**
     * UC-ADM-02 : Mettre à jour les permissions d'un profil
     */
    public function updatePermissions(Request $request, Profil $profil)
    {
        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*.module' => 'required|string',
            'permissions.*.actions' => 'required|array',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Synchroniser les permissions
            $profil->permissions()->delete();
            
            if (isset($validated['permissions'])) {
                foreach ($validated['permissions'] as $permData) {
                    $module = $permData['module'];
                    foreach ($permData['actions'] as $action) {
                        $profil->permissions()->create([
                            'module' => $module,
                            'action' => $action,
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            // Log l'activité
            \App\Models\LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'modification_permissions',
                'module' => 'administration',
                'id_element' => $profil->id,
                'adresse_ip' => $request->ip(),
                'details' => "Modification des permissions pour le profil {$profil->nom_profil}",
                'user_agent' => $request->userAgent(),
            ]);
            
            return redirect()->route('admin.profils.show', $profil)
                ->with('success', 'Permissions mises à jour avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour des permissions: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * UC-ADM-02 : Dupliquer un profil
     */
    public function duplicate(Request $request, Profil $profil)
    {
        $request->validate([
            'nouveau_nom' => 'required|unique:profils,nom_profil|max:100',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Créer le nouveau profil
            $nouveauProfil = Profil::create([
                'nom_profil' => $request->nouveau_nom,
                'description' => $profil->description . ' (Copie)',
            ]);
            
            // Dupliquer les permissions
            foreach ($profil->permissions as $permission) {
                $nouveauProfil->permissions()->create([
                    'module' => $permission->module,
                    'action' => $permission->action,
                ]);
            }
            
            DB::commit();
            
            // Log l'activité
            \App\Models\LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'duplication_profil',
                'module' => 'administration',
                'id_element' => $nouveauProfil->id,
                'adresse_ip' => $request->ip(),
                'details' => "Duplication du profil {$profil->nom_profil} vers {$nouveauProfil->nom_profil}",
                'user_agent' => $request->userAgent(),
            ]);
            
            return redirect()->route('admin.profils.index')
                ->with('success', 'Profil dupliqué avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la duplication du profil: ' . $e->getMessage());
        }
    }
}