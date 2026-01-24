<?php

namespace App\Http\Controllers\Inventaire;

use App\Http\Controllers\Controller;
use App\Models\Equipement;
use App\Models\HistoriqueMouvement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LogActivite;

class ScannerController extends Controller
{
    /**
     * Page principale du scanner
     */
    public function index()
    {
        return view('inventaire.scanner.index');
    }
    
    /**
     * Scanner un équipement
     */
    public function scan(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:100',
        ]);
        
        $code = $request->code;
        
        // Chercher l'équipement
        $equipement = Equipement::where('code_barres', $code)
            ->orWhere('numero_inventaire', $code)
            ->orWhere('numero_serie', $code)
            ->first();
            
        if (!$equipement) {
            return response()->json([
                'success' => false,
                'message' => 'Équipement non trouvé avec ce code : ' . $code
            ], 404);
        }
        
        // Log de l'activité
        LogActivite::create([
            'id_utilisateur' => auth()->id(),
            'date_heure' => now(),
            'action' => 'scan_equipement',
            'module' => 'inventaire',
            'id_element' => $equipement->id,
            'adresse_ip' => $request->ip(),
            'details' => "Scan de l'équipement {$equipement->numero_inventaire}",
            'user_agent' => $request->userAgent(),
        ]);
        
        return response()->json([
            'success' => true,
            'equipement' => [
                'id' => $equipement->id,
                'numero_inventaire' => $equipement->numero_inventaire,
                'numero_serie' => $equipement->numero_serie,
                'marque' => $equipement->marque,
                'modele' => $equipement->modele,
                'type_equipement' => $equipement->type_equipement,
                'etat' => $equipement->etat,
                'localisation' => $equipement->localisation->nom ?? 'Non affecté',
                'service_responsable' => $equipement->serviceResponsable->nom ?? 'Non défini',
                'date_achat' => $equipement->date_achat ? $equipement->date_achat->format('d/m/Y') : null,
                'age' => $equipement->age,
                'est_sous_garantie' => $equipement->est_sous_garantie,
                'show_url' => route('inventaire.equipements.show', $equipement),
                'edit_url' => route('inventaire.equipements.edit', $equipement),
            ]
        ]);
    }
    
    /**
     * Scanner manuel (saisie de code)
     */
    public function scanManuel()
    {
        return view('inventaire.scanner.manuel');
    }
    
    /**
     * Vérifier un équipement (pour inventaire physique)
     */
    public function verifier(Request $request)
    {
        $request->validate([
            'equipement_id' => 'required|exists:equipements,id',
            'verifie' => 'required|boolean',
            'etat' => 'nullable|in:bon,moyen,mauvais',
            'remarques' => 'nullable|string|max:255',
            'nouvelle_localisation_id' => 'nullable|exists:localisations,id',
        ]);
        
        DB::beginTransaction();
        
        try {
            $equipement = Equipement::find($request->equipement_id);
            
            // Mettre à jour l'état si fourni
            if ($request->filled('etat')) {
                $equipement->etat = $request->etat;
            }
            
            // Changer de localisation si demandé
            if ($request->filled('nouvelle_localisation_id') && 
                $request->nouvelle_localisation_id != $equipement->localisation_id) {
                
                $ancienneLocalisation = $equipement->localisation_id;
                
                // Enregistrer le mouvement
                HistoriqueMouvement::create([
                    'equipement_id' => $equipement->id,
                    'date_mouvement' => now(),
                    'ancienne_localisation_id' => $ancienneLocalisation,
                    'nouvelle_localisation_id' => $request->nouvelle_localisation_id,
                    'motif' => 'Inventaire physique',
                    'operateur_id' => auth()->id(),
                    'commentaire' => $request->remarques
                ]);
                
                $equipement->localisation_id = $request->nouvelle_localisation_id;
            }
            
            // Ajouter des remarques
            if ($request->filled('remarques')) {
                $equipement->commentaires = ($equipement->commentaires ?? '') . "\n\n[INVENTAIRE " . now()->format('d/m/Y') . "]\n" . $request->remarques;
            }
            
            $equipement->updated_by = auth()->id();
            $equipement->save();
            
            // Log de l'activité
            LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'verification_inventaire',
                'module' => 'inventaire',
                'id_element' => $equipement->id,
                'adresse_ip' => $request->ip(),
                'details' => "Vérification inventaire de l'équipement {$equipement->numero_inventaire}",
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Équipement vérifié avec succès'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification : ' . $e->getMessage()
            ], 500);
        }
    }
}