<?php

namespace App\Http\Controllers\Inventaire;

use App\Http\Controllers\Controller;
use App\Models\Equipement;
use App\Models\HistoriqueMouvement;
use App\Models\LogActivite;
use App\Models\Localisation;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HistoriqueMouvementController extends Controller
{
    /**
     * Afficher la liste des mouvements avec filtres et statistiques
     */
    public function index(Request $request)
    {
        // Récupérer les filtres
        $dateDebut = $request->input('date_debut', now()->subMonths(3)->format('Y-m-d'));
        $dateFin = $request->input('date_fin', now()->format('Y-m-d'));
        $equipementId = $request->input('equipement_id');
        $motif = $request->input('motif');
        $operateurId = $request->input('operateur_id');
        $perPage = $request->input('per_page', 15);

        // Query de base pour les mouvements
        $mouvements = HistoriqueMouvement::with([
            'equipement.typeEquipement',
            'ancienneLocalisation',
            'nouvelleLocalisation',
            'operateur'
        ])
            ->whereBetween('date_mouvement', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
            ->when($equipementId, function ($query, $equipementId) {
                return $query->where('equipement_id', $equipementId);
            })
            ->when($motif, function ($query, $motif) {
                return $query->where('motif', 'like', "%{$motif}%");
            })
            ->when($operateurId, function ($query, $operateurId) {
                return $query->where('operateur_id', $operateurId);
            })
            ->orderBy('date_mouvement', 'desc')
            ->paginate($perPage);

        // Statistiques générales
        $stats = $this->getStatistiquesGenerales($dateDebut, $dateFin);

        // Statistiques par motif
        $statsByMotif = $this->getStatistiquesParMotif($dateDebut, $dateFin);

        // Top équipements les plus déplacés
        $topEquipements = $this->getTopEquipementsDeplaces($dateDebut, $dateFin);

        // Mouvements par mois
        $mouvementsParMois = $this->getMouvementsParMois($dateDebut, $dateFin);

        // Mouvements par opérateur
        $mouvementsParOperateur = $this->getMouvementsParOperateur($dateDebut, $dateFin);

        // Top localisations (départ et arrivée)
        $topLocalisationsDepart = $this->getTopLocalisations($dateDebut, $dateFin, 'depart');
        $topLocalisationsArrivee = $this->getTopLocalisations($dateDebut, $dateFin, 'arrivee');

        // Données pour les filtres
        $equipements = Equipement::select('id', 'numero_inventaire', 'marque', 'modele')
            ->orderBy('numero_inventaire')
            ->get();

        $operateurs = Utilisateur::select('id', 'nom', 'prenom')
            ->whereHas('historiqueMouvements')
            ->orderBy('nom')
            ->get();

        $motifs = HistoriqueMouvement::select('motif')
            ->distinct()
            ->orderBy('motif')
            ->pluck('motif');

        return view('inventaire.historiques.index', compact(
            'mouvements',
            'stats',
            'statsByMotif',
            'topEquipements',
            'mouvementsParMois',
            'mouvementsParOperateur',
            'topLocalisationsDepart',
            'topLocalisationsArrivee',
            'equipements',
            'operateurs',
            'motifs',
            'dateDebut',
            'dateFin',
            'equipementId',
            'motif',
            'operateurId',
            'perPage'
        ));
    }

    /**
     * Afficher les détails d'un mouvement spécifique
     */
    public function show($id)
    {
        // CORRIGÉ : Charger les relations avec with() sur l'instance du modèle
        $mouvement = HistoriqueMouvement::with([
            'equipement.typeEquipement',
            'equipement.fournisseur',
            'ancienneLocalisation.service',
            'nouvelleLocalisation.service',
            'operateur'
        ])->findOrFail($id);

        // Récupérer les mouvements précédents et suivants de cet équipement
        $mouvementPrecedent = HistoriqueMouvement::where('equipement_id', $mouvement->equipement_id)
            ->where('date_mouvement', '<', $mouvement->date_mouvement)
            ->orderBy('date_mouvement', 'desc')
            ->first();

        $mouvementSuivant = HistoriqueMouvement::where('equipement_id', $mouvement->equipement_id)
            ->where('date_mouvement', '>', $mouvement->date_mouvement)
            ->orderBy('date_mouvement', 'asc')
            ->first();

        // Récupérer l'historique complet de l'équipement
        $historique = HistoriqueMouvement::where('equipement_id', $mouvement->equipement_id)
            ->with(['ancienneLocalisation', 'nouvelleLocalisation', 'operateur'])
            ->orderBy('date_mouvement', 'desc')
            ->limit(10)
            ->get();

        // Logs d'activité liés
        $logsActivite = LogActivite::where('module', 'inventaire')
            ->where('id_element', $mouvement->equipement_id)
            ->whereBetween('date_heure', [
                $mouvement->date_mouvement->copy()->subMinutes(5),
                $mouvement->date_mouvement->copy()->addMinutes(5)
            ])
            ->orderBy('date_heure', 'desc')
            ->get();

        return view('inventaire.historiques.show', compact(
            'mouvement',
            'mouvementPrecedent',
            'mouvementSuivant',
            'historique',
            'logsActivite'
        ));
    }

    /**
     * Obtenir les statistiques générales
     */
    private function getStatistiquesGenerales($dateDebut, $dateFin)
    {
        $totalMouvements = HistoriqueMouvement::whereBetween('date_mouvement', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])->count();

        $equipementsUniques = HistoriqueMouvement::whereBetween('date_mouvement', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
            ->distinct('equipement_id')
            ->count('equipement_id');

        $operateursUniques = HistoriqueMouvement::whereBetween('date_mouvement', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
            ->distinct('operateur_id')
            ->count('operateur_id');

        // Moyenne de mouvements par jour
        $jours = Carbon::parse($dateDebut)->diffInDays(Carbon::parse($dateFin)) + 1;
        $moyenneParJour = $jours > 0 ? round($totalMouvements / $jours, 2) : 0;

        // Taux de changement (mouvements vs équipements totaux)
        $totalEquipements = Equipement::count();
        $tauxChangement = $totalEquipements > 0 ? round(($equipementsUniques / $totalEquipements) * 100, 2) : 0;

        return [
            'total_mouvements' => $totalMouvements,
            'equipements_uniques' => $equipementsUniques,
            'operateurs_uniques' => $operateursUniques,
            'moyenne_par_jour' => $moyenneParJour,
            'taux_changement' => $tauxChangement,
        ];
    }

    /**
     * Obtenir les statistiques par motif
     */
    private function getStatistiquesParMotif($dateDebut, $dateFin)
    {
        return HistoriqueMouvement::whereBetween('date_mouvement', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
            ->select('motif', DB::raw('count(*) as total'))
            ->groupBy('motif')
            ->orderBy('total', 'desc')
            ->get();
    }

    /**
     * Obtenir le top des équipements les plus déplacés
     */
    private function getTopEquipementsDeplaces($dateDebut, $dateFin, $limit = 10)
    {
        return HistoriqueMouvement::whereBetween('date_mouvement', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
            ->select('equipement_id', DB::raw('count(*) as nb_mouvements'))
            ->with('equipement:id,numero_inventaire,marque,modele')
            ->groupBy('equipement_id')
            ->orderBy('nb_mouvements', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les mouvements par mois
     */
    private function getMouvementsParMois($dateDebut, $dateFin)
    {
        return HistoriqueMouvement::whereBetween('date_mouvement', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
            ->select(
                DB::raw('DATE_FORMAT(date_mouvement, "%Y-%m") as mois'),
                DB::raw('count(*) as total')
            )
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();
    }

    /**
     * Obtenir les mouvements par opérateur
     */
    private function getMouvementsParOperateur($dateDebut, $dateFin, $limit = 10)
    {
        return HistoriqueMouvement::whereBetween('date_mouvement', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
            ->select('operateur_id', DB::raw('count(*) as nb_mouvements'))
            ->with('operateur:id,nom,prenom')
            ->groupBy('operateur_id')
            ->orderBy('nb_mouvements', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les top localisations
     */
    private function getTopLocalisations($dateDebut, $dateFin, $type = 'depart', $limit = 10)
    {
        $column = $type === 'depart' ? 'ancienne_localisation_id' : 'nouvelle_localisation_id';

        return HistoriqueMouvement::whereBetween('date_mouvement', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
            ->whereNotNull($column)
            ->select($column . ' as localisation_id', DB::raw('count(*) as nb_mouvements'))
            ->when($type === 'depart', function ($query) {
                return $query->with('ancienneLocalisation:id,nom,batiment,etage');
            }, function ($query) {
                return $query->with('nouvelleLocalisation:id,nom,batiment,etage');
            })
            ->groupBy($column)
            ->orderBy('nb_mouvements', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Exporter les données
     */
    public function export(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->subMonths(3)->format('Y-m-d'));
        $dateFin = $request->input('date_fin', now()->format('Y-m-d'));

        $mouvements = HistoriqueMouvement::with([
            'equipement',
            'ancienneLocalisation',
            'nouvelleLocalisation',
            'operateur'
        ])
            ->whereBetween('date_mouvement', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
            ->orderBy('date_mouvement', 'desc')
            ->get();

        $filename = 'historique_mouvements_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($mouvements) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'Date Mouvement',
                'Équipement',
                'Numéro Inventaire',
                'Ancienne Localisation',
                'Nouvelle Localisation',
                'Motif',
                'Opérateur',
                'Commentaire'
            ]);

            // Données
            foreach ($mouvements as $mouvement) {
                $operateurNom = $mouvement->operateur ?
                    ($mouvement->operateur->nom . ' ' . $mouvement->operateur->prenom) :
                    'N/A';

                fputcsv($file, [
                    $mouvement->date_mouvement->format('d/m/Y H:i'),
                    $mouvement->equipement->marque . ' ' . $mouvement->equipement->modele,
                    $mouvement->equipement->numero_inventaire,
                    $mouvement->ancienneLocalisation?->nom ?? 'N/A',
                    $mouvement->nouvelleLocalisation?->nom ?? 'N/A',
                    $mouvement->motif,
                    $operateurNom,
                    $mouvement->commentaire ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
