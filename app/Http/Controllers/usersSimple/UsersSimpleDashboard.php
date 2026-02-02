<?php

namespace App\Http\Controllers\usersSimple;

use App\Http\Controllers\Controller;
use App\Models\Equipement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersSimpleDashboard extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Récupérer les statistiques pour le tableau de bord
        $stats = [
            'total' => Equipement::where(function($q) use ($user) {
                $q->where('localisation_id', $user->service_id)
                  ->orWhere('service_responsable_id', $user->service_id);
            })->count(),

            'disponible' => Equipement::whereIn('etat', ['neuf', 'bon'])
                ->where(function($q) use ($user) {
                    $q->where('localisation_id', $user->service_id)
                      ->orWhere('service_responsable_id', $user->service_id);
                })->count(),

            'limite' => Equipement::where('etat', 'moyen')
                ->where(function($q) use ($user) {
                    $q->where('localisation_id', $user->service_id)
                      ->orWhere('service_responsable_id', $user->service_id);
                })->count(),

            'non_disponible' => Equipement::whereIn('etat', ['mauvais', 'hors_service'])
                ->where(function($q) use ($user) {
                    $q->where('localisation_id', $user->service_id)
                      ->orWhere('service_responsable_id', $user->service_id);
                })->count(),
        ];

        return view('usersSimple.Dashboard', compact('stats'));
    }

    /**
     * Afficher la liste des équipements du service
     */
    public function equipements(Request $request)
    {
        $user = Auth::user();

        // Récupérer les équipements du service de l'utilisateur
        $query = Equipement::with(['typeEquipement', 'localisation', 'serviceResponsable'])
            ->where(function($q) use ($user) {
                // Équipements du service de l'utilisateur
                $q->where('localisation_id', $user->service_id)
                  ->orWhere('service_responsable_id', $user->service_id);
            })
            ->orderBy('numero_inventaire');

        // Recherche
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_inventaire', 'like', "%{$search}%")
                  ->orWhere('numero_serie', 'like', "%{$search}%")
                  ->orWhere('marque', 'like', "%{$search}%")
                  ->orWhere('modele', 'like', "%{$search}%")
                  ->orWhereHas('typeEquipement', function($q) use ($search) {
                      $q->where('libelle', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par type
        if ($request->has('type') && $request->type) {
            $query->where('type_equipement_id', $request->type);
        }

        // Filtre par état
        if ($request->has('etat') && $request->etat) {
            $query->where('etat', $request->etat);
        }

        // Filtre par localisation
        if ($request->has('localisation') && $request->localisation) {
            $query->where('localisation_id', $request->localisation);
        }

        $equipements = $query->paginate(15);

        // Pour les filtres
        $types = \App\Models\TypeEquipement::orderBy('libelle')->get();
        $localisations = \App\Models\Localisation::where('type', 'service')
            ->orderBy('nom')
            ->get();

        return view('usersSimple.equipements.index', compact(
            'equipements',
            'types',
            'localisations'
        ));
    }

    /**
     * Afficher les détails d'un équipement
     */
    public function showEquipement($id)
    {
        $user = Auth::user();

        $equipement = Equipement::with([
            'typeEquipement',
            'localisation',
            'serviceResponsable',
            'fournisseur',
            'contrat',
            'interventions' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(5);
            }
        ])
        ->where('id', $id)
        ->where(function($q) use ($user) {
            $q->where('localisation_id', $user->service_id)
              ->orWhere('service_responsable_id', $user->service_id);
        })
        ->firstOrFail();

        // Vérifier si l'équipement est disponible
        $isAvailable = $this->checkAvailability($equipement);

        return view('usersSimple.equipements.show', compact('equipement', 'isAvailable'));
    }

    /**
     * Vérifier la disponibilité d'un équipement
     */
    private function checkAvailability($equipement)
    {
        // État général
        if (in_array($equipement->etat, ['hors_service', 'mauvais'])) {
            return [
                'status' => 'non_disponible',
                'message' => 'Équipement hors service',
                'color' => 'danger'
            ];
        }

        // Vérifier les interventions en cours
        $interventionEnCours = \App\Models\Intervention::where(
                'ID_Equipement_Controle',
                $equipement->id
            )
            ->where('Statut_Conformite', 'en_cours')
            ->exists();

        if ($interventionEnCours) {
            return [
                'status' => 'en_intervention',
                'message' => 'En cours d\'intervention',
                'color' => 'warning'
            ];
        }

        // État moyen
        if ($equipement->etat === 'moyen') {
            return [
                'status' => 'usage_limite',
                'message' => 'Usage limité - État moyen',
                'color' => 'warning'
            ];
        }

        // État bon/neuf
        return [
            'status' => 'disponible',
            'message' => 'Disponible',
            'color' => 'success'
        ];
    }

    /**
     * Recherche rapide d'équipements
     */
    public function search(Request $request)
    {
        $user = Auth::user();

        $search = $request->get('q');

        $equipements = Equipement::with(['typeEquipement', 'localisation'])
            ->where(function($q) use ($user) {
                $q->where('localisation_id', $user->service_id)
                  ->orWhere('service_responsable_id', $user->service_id);
            })
            ->where(function($q) use ($search) {
                $q->where('numero_inventaire', 'like', "%{$search}%")
                  ->orWhere('numero_serie', 'like', "%{$search}%")
                  ->orWhere('marque', 'like', "%{$search}%")
                  ->orWhere('modele', 'like', "%{$search}%")
                  ->orWhereHas('typeEquipement', function($q) use ($search) {
                      $q->where('libelle', 'like', "%{$search}%");
                  });
            })
            ->orderBy('numero_inventaire')
            ->limit(10)
            ->get()
            ->map(function($equipement) {
                // Vérifier la disponibilité
                $dispo = '';
                $couleur = '';
                if ($equipement->etat === 'hors_service' || $equipement->etat === 'mauvais') {
                    $dispo = 'Non disponible';
                    $couleur = 'danger';
                } elseif ($equipement->etat === 'moyen') {
                    $dispo = 'Usage limité';
                    $couleur = 'warning';
                } else {
                    $dispo = 'Disponible';
                    $couleur = 'success';
                }

                return [
                    'id' => $equipement->id,
                    'text' => $equipement->numero_inventaire . ' - ' . $equipement->marque . ' ' . $equipement->modele . ' (' . ($equipement->typeEquipement->libelle ?? 'N/A') . ')',
                    'numero' => $equipement->numero_inventaire,
                    'marque' => $equipement->marque,
                    'modele' => $equipement->modele,
                    'type' => $equipement->typeEquipement->libelle ?? 'N/A',
                    'etat' => $equipement->etat,
                    'disponibilite' => $dispo,
                    'disponibilite_couleur' => $couleur,
                    'localisation' => $equipement->localisation->nom ?? 'N/A'
                ];
            });

        return response()->json(['results' => $equipements]);
    }
}
