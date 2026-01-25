<?php

namespace App\Http\Controllers\Inventaire;

use App\Http\Controllers\Controller;
use App\Models\Equipement;
use App\Models\Localisation;
use App\Models\TypeEquipement;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LogActivite;
use Carbon\Carbon;

class InventaireController extends Controller
{
    /**
     * Tableau de bord inventaire
     */
    public function dashboard()
    {
        // Statistiques principales
        $statistiques = [
            'total_equipements' => Equipement::count(),
            'equipements_actifs' => Equipement::actif()->count(),
            'equipements_hors_service' => Equipement::horsService()->count(),
            'valeur_totale' => Equipement::sum('prix_achat'),
            'equipements_sous_garantie' => $this->getEquipementsSousGarantieCount(),
            'types_equipements' => TypeEquipement::count(),
            'fournisseurs_actifs' => Fournisseur::actif()->count(),
        ];
        
        // Équipements récemment ajoutés
        $equipements_recents = Equipement::with(['localisation', 'fournisseur'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
// Équipements par type (avec libellé)
$equipements_par_type = DB::table('equipements')
    ->join('type_equipements', 'equipements.type_equipement_id', '=', 'type_equipements.id')
    ->select('type_equipements.libelle as type', DB::raw('count(equipements.id) as total'))
    ->groupBy('type_equipements.libelle')
    ->orderByDesc('total')
    ->limit(5)
    ->get();


            
        // Équipements par état
        $equipements_par_etat = Equipement::select('etat', DB::raw('count(*) as total'))
            ->groupBy('etat')
            ->get();
            
        // Alertes
        $alertes = $this->getAlertes();
        
        return view('inventaire.dashboard', compact(
            'statistiques',
            'equipements_recents',
            'equipements_par_type',
            'equipements_par_etat',
            'alertes'
        ));
    }
    
    /**
     * Page des rapports
     */
    public function index()
    {
        $localisations = Localisation::orderBy('nom')->get();
        $types = TypeEquipement::orderBy('libelle')->get();
        $etats = ['neuf', 'bon', 'moyen', 'mauvais', 'hors_service'];
        
        return view('inventaire.rapports.index', compact('localisations', 'types', 'etats'));
    }
    
    /**
     * Statistiques détaillées
     */
    public function statistiques()
    {
        // Statistiques par localisation
        $stats_localisation = Equipement::select(
            'localisations.nom as localisation',
            DB::raw('count(equipements.id) as total'),
            DB::raw('sum(equipements.prix_achat) as valeur')
        )
        ->leftJoin('localisations', 'equipements.localisation_id', '=', 'localisations.id')
        ->groupBy('localisations.id', 'localisations.nom')
        ->orderBy('total', 'desc')
        ->get();
        
        // Statistiques par type
        $stats_type = Equipement::select(
            'type_equipement',
            DB::raw('count(*) as total'),
            DB::raw('sum(prix_achat) as valeur'),
            DB::raw('avg(prix_achat) as prix_moyen')
        )
        ->groupBy('type_equipement')
        ->orderBy('total', 'desc')
        ->get();
        
        // Statistiques par année d'achat
        $stats_annee = Equipement::select(
            DB::raw('YEAR(date_achat) as annee'),
            DB::raw('count(*) as total'),
            DB::raw('sum(prix_achat) as investissement')
        )
        ->whereNotNull('date_achat')
        ->groupBy('annee')
        ->orderBy('annee', 'desc')
        ->get();
        
        // Équipements arrivant en fin de vie
        $fin_vie = Equipement::whereNotNull('duree_vie_theorique')
            ->whereNotNull('date_achat')
            ->get()
            ->filter(function($equipement) {
                $age = $equipement->age;
                return $age >= ($equipement->duree_vie_theorique * 0.8); // 80% de la durée de vie
            })
            ->take(10);
            
        return view('inventaire.rapports.statistiques', compact(
            'stats_localisation',
            'stats_type',
            'stats_annee',
            'fin_vie'
        ));
    }
    
    /**
     * Inventaire physique
     */
    public function inventairePhysique(Request $request)
    {
        $localisation_id = $request->get('localisation_id');
        
        if ($localisation_id) {
            $equipements = Equipement::with(['localisation', 'serviceResponsable'])
                ->where('localisation_id', $localisation_id)
                ->actif()
                ->get();
                
            $localisation = Localisation::find($localisation_id);
        } else {
            $equipements = collect();
            $localisation = null;
        }
        
        $localisations = Localisation::orderBy('nom')->get();
        
        return view('inventaire.rapports.inventaire_physique', compact(
            'equipements',
            'localisations',
            'localisation'
        ));
    }
    
    /**
     * Sauvegarder l'inventaire physique
     */
    public function sauvegarderInventaire(Request $request)
    {
        $validated = $request->validate([
            'localisation_id' => 'required|exists:localisations,id',
            'inventaire.*.equipement_id' => 'required|exists:equipements,id',
            'inventaire.*.verifie' => 'boolean',
            'inventaire.*.etat' => 'nullable|in:bon,moyen,mauvais',
            'inventaire.*.remarques' => 'nullable|string|max:255',
        ]);
        
        DB::beginTransaction();
        
        try {
            $localisation = Localisation::find($validated['localisation_id']);
            
            // Log de l'activité
            LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'inventaire_physique',
                'module' => 'inventaire',
                'id_element' => $localisation->id,
                'adresse_ip' => $request->ip(),
                'details' => "Inventaire physique réalisé pour {$localisation->nom}",
                'user_agent' => $request->userAgent(),
            ]);
            
            DB::commit();
            
            return redirect()->route('inventaire.rapports.inventaire-physique')
                ->with('success', 'Inventaire physique enregistré avec succès pour ' . $localisation->nom);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
        }
    }
    
    /**
     * Méthodes privées
     */
    private function getEquipementsSousGarantieCount()
    {
        return Equipement::all()->filter(function($equipement) {
            return $equipement->est_sous_garantie;
        })->count();
    }
    
    private function getAlertes()
    {
        $alertes = [];
        
        // Équipements sans localisation
        $sans_localisation = Equipement::whereNull('localisation_id')->actif()->count();
        if ($sans_localisation > 0) {
            $alertes[] = [
                'type' => 'warning',
                'message' => "{$sans_localisation} équipement(s) sans localisation",
                'route' => route('inventaire.equipements.index', ['localisation_id' => 'null']),
            ];
        }
        
        // Équipements hors garantie
        $hors_garantie = Equipement::where('date_achat', '<=', Carbon::now()->subMonths(12))
            ->actif()
            ->count();
        if ($hors_garantie > 0) {
            $alertes[] = [
                'type' => 'info',
                'message' => "{$hors_garantie} équipement(s) hors garantie",
                'route' => route('inventaire.equipements.index'),
            ];
        }
        
        // Équipements en fin de vie
        $fin_vie = Equipement::whereNotNull('duree_vie_theorique')
            ->whereNotNull('date_achat')
            ->get()
            ->filter(function($equipement) {
                $age = $equipement->age;
                return $age >= ($equipement->duree_vie_theorique * 0.9); // 90% de la durée de vie
            })
            ->count();
            
        if ($fin_vie > 0) {
            $alertes[] = [
                'type' => 'danger',
                'message' => "{$fin_vie} équipement(s) en fin de vie théorique",
                'route' => route('inventaire.rapports.statistiques'),
            ];
        }
        
        return $alertes;
    }
}