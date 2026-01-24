<?php

namespace App\Http\Controllers\Inventaire;

use App\Http\Controllers\Controller;
use App\Models\TypeEquipement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LogActivite;

class TypeEquipementController extends Controller
{
    /**
     * Afficher la liste des types d'équipement
     */
    public function index()
    {
        $types = TypeEquipement::orderBy('libelle')->paginate(20);
        $statistiques = [
            'total' => TypeEquipement::count(),
            'faible_risque' => TypeEquipement::where('risque', 'faible')->count(),
            'moyen_risque' => TypeEquipement::where('risque', 'moyen')->count(),
            'eleve_risque' => TypeEquipement::where('risque', 'eleve')->count(),
            'critique_risque' => TypeEquipement::where('risque', 'critique')->count(),
        ];
        
        return view('inventaire.types.index', compact('types', 'statistiques'));
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $risques = [
            'faible' => 'Faible',
            'moyen' => 'Moyen', 
            'eleve' => 'Élevé',
            'critique' => 'Critique'
        ];
        
        $classes = [
            'classe_i' => 'Classe I (Basse tension)',
            'classe_ii' => 'Classe II (Double isolation)',
            'classe_iii' => 'Classe III (Très basse tension)',
            'medical_a' => 'Médical Classe A',
            'medical_b' => 'Médical Classe B',
            'medical_bf' => 'Médical Classe BF',
            'medical_cf' => 'Médical Classe CF'
        ];
        
        return view('inventaire.types.create', compact('risques', 'classes'));
    }
    
    /**
     * Enregistrer un nouveau type d'équipement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_type' => 'required|unique:type_equipements|max:20',
            'libelle' => 'required|max:100',
            'classe' => 'nullable|max:50',
            'duree_vie_standard' => 'nullable|integer|min:0',
            'periodicite_maintenance' => 'nullable|integer|min:0',
            'risque' => 'required|in:faible,moyen,eleve,critique',
            'description' => 'nullable',
        ]);
        
        DB::beginTransaction();
        
        try {
            $type = TypeEquipement::create($validated);
            
            // Log de l'activité
            LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'creation_type_equipement',
                'module' => 'inventaire',
                'id_element' => $type->id,
                'adresse_ip' => $request->ip(),
                'details' => "Création du type d'équipement {$type->libelle} ({$type->code_type})",
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->route('inventaire.types.index')
                ->with('success', 'Type d\'équipement créé avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Afficher le formulaire d'édition
     */
    public function edit(TypeEquipement $type_equipement)
    {
        $risques = [
            'faible' => 'Faible',
            'moyen' => 'Moyen', 
            'eleve' => 'Élevé',
            'critique' => 'Critique'
        ];
        
        $classes = [
            'classe_i' => 'Classe I (Basse tension)',
            'classe_ii' => 'Classe II (Double isolation)',
            'classe_iii' => 'Classe III (Très basse tension)',
            'medical_a' => 'Médical Classe A',
            'medical_b' => 'Médical Classe B',
            'medical_bf' => 'Médical Classe BF',
            'medical_cf' => 'Médical Classe CF'
        ];
        
        return view('inventaire.types.edit', compact('type_equipement', 'risques', 'classes'));
    }
    
    /**
     * Mettre à jour un type d'équipement
     */
    public function update(Request $request, TypeEquipement $type_equipement)
    {
        $validated = $request->validate([
            'code_type' => 'required|unique:type_equipements,code_type,' . $type_equipement->id . '|max:20',
            'libelle' => 'required|max:100',
            'classe' => 'nullable|max:50',
            'duree_vie_standard' => 'nullable|integer|min:0',
            'periodicite_maintenance' => 'nullable|integer|min:0',
            'risque' => 'required|in:faible,moyen,eleve,critique',
            'description' => 'nullable',
        ]);
        
        DB::beginTransaction();
        
        try {
            $type_equipement->update($validated);
            
            // Log de l'activité
            LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'modification_type_equipement',
                'module' => 'inventaire',
                'id_element' => $type_equipement->id,
                'adresse_ip' => $request->ip(),
                'details' => "Modification du type d'équipement {$type_equipement->libelle} ({$type_equipement->code_type})",
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->route('inventaire.types.index')
                ->with('success', 'Type d\'équipement mis à jour avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Supprimer un type d'équipement
     */
    public function destroy(Request $request, TypeEquipement $type_equipement)
    {
        DB::beginTransaction();
        
        try {
            // Vérifier si des équipements utilisent ce type
            $equipementsCount = $type_equipement->equipements()->count();
            
            if ($equipementsCount > 0) {
                return redirect()->back()
                    ->with('error', "Impossible de supprimer ce type. Il est utilisé par {$equipementsCount} équipement(s).");
            }
            
            $type_equipement->delete();
            
            // Log de l'activité
            LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'suppression_type_equipement',
                'module' => 'inventaire',
                'id_element' => $type_equipement->id,
                'adresse_ip' => $request->ip(),
                'details' => "Suppression du type d'équipement {$type_equipement->libelle} ({$type_equipement->code_type})",
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->route('inventaire.types.index')
                ->with('success', 'Type d\'équipement supprimé avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}