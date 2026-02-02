<?php

namespace App\Http\Controllers\usersSimple;

use App\Http\Controllers\Controller;
use App\Models\DemandeIntervention;
use App\Models\Equipement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DemandeInterventionController extends Controller
{
    /**
     * Afficher la liste des demandes de l'utilisateur avec statistiques
     */
    public function index()
    {
        $user = Auth::user();

        // Récupérer les demandes
        $demandes = DemandeIntervention::with(['equipement', 'validateur'])
            ->where('ID_Demandeur', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Statistiques générales
        $stats = [
            'total' => DemandeIntervention::where('ID_Demandeur', $user->id)->count(),
            'en_attente' => DemandeIntervention::where('ID_Demandeur', $user->id)->where('Statut', 'en_attente')->count(),
            'validees' => DemandeIntervention::where('ID_Demandeur', $user->id)->where('Statut', 'validee')->count(),
            'en_cours' => DemandeIntervention::where('ID_Demandeur', $user->id)->where('Statut', 'en_cours')->count(),
            'terminees' => DemandeIntervention::where('ID_Demandeur', $user->id)->where('Statut', 'terminee')->count(),
            'rejetees' => DemandeIntervention::where('ID_Demandeur', $user->id)->where('Statut', 'rejetee')->count(),
        ];

        // Statistiques par statut pour le diagramme circulaire
        $statutStats = DemandeIntervention::where('ID_Demandeur', $user->id)
            ->select('Statut', DB::raw('count(*) as total'))
            ->groupBy('Statut')
            ->get()
            ->pluck('total', 'Statut');

        // Statistiques par urgence pour le diagramme en barres
        $urgenceStats = DemandeIntervention::where('ID_Demandeur', $user->id)
            ->select('Urgence', DB::raw('count(*) as total'))
            ->groupBy('Urgence')
            ->get()
            ->pluck('total', 'Urgence');

        // Statistiques par type d'intervention
        $typeStats = DemandeIntervention::where('ID_Demandeur', $user->id)
            ->select('Type_Intervention', DB::raw('count(*) as total'))
            ->groupBy('Type_Intervention')
            ->get()
            ->pluck('total', 'Type_Intervention');

        // Évolution des demandes sur les 6 derniers mois
        $evolutionStats = DemandeIntervention::where('ID_Demandeur', $user->id)
            ->where('Date_Demande', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(Date_Demande, "%Y-%m") as mois'),
                DB::raw('count(*) as total')
            )
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        return view('usersSimple.demandes.index', compact(
            'demandes',
            'stats',
            'statutStats',
            'urgenceStats',
            'typeStats',
            'evolutionStats'
        ));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $user = Auth::user();

        // Récupérer seulement les équipements du service de l'utilisateur
        $equipements = Equipement::where('etat', '!=', 'hors_service')
            ->where(function($query) use ($user) {
                // Soit l'équipement est dans le service de l'utilisateur
                $query->where('localisation_id', $user->service_id)
                      // Soit le service responsable est le service de l'utilisateur
                      ->orWhere('service_responsable_id', $user->service_id);
            })
            ->with('localisation') // Pour afficher le nom du service
            ->orderBy('numero_inventaire')
            ->get();

        return view('usersSimple.demandes.create', compact('equipements'));
    }

    /**
     * Enregistrer une nouvelle demande
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Vérifier que l'équipement appartient bien au service de l'utilisateur
        $equipement = Equipement::where('id', $request->ID_Equipement)
            ->where(function($query) use ($user) {
                $query->where('localisation_id', $user->service_id)
                      ->orWhere('service_responsable_id', $user->service_id);
            })
            ->first();

        if (!$equipement) {
            return redirect()->back()
                ->with('error', 'Cet équipement ne fait pas partie de votre service.')
                ->withInput();
        }

        $validator = Validator::make($request->all(), [
            'ID_Equipement' => 'required|exists:equipements,id',
            'Type_Intervention' => 'required|in:maintenance_preventive,maintenance_corrective,reparation,calibration,verification,controle,autre',
            'Urgence' => 'required|in:normale,urgente,critique',
            'Description_Panne' => 'required|min:10|max:2000',
            'Delai_Souhaite' => 'nullable|integer|min:1|max:720',
            'Commentaires' => 'nullable|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $demande = DemandeIntervention::create([
                'Numero_Demande' => DemandeIntervention::generateNumeroDemande(),
                'Date_Demande' => now()->toDateString(),
                'Heure_Demande' => now()->toTimeString(),
                'ID_Demandeur' => Auth::id(),
                'ID_Equipement' => $request->ID_Equipement,
                'Type_Intervention' => $request->Type_Intervention,
                'Urgence' => $request->Urgence,
                'Description_Panne' => $request->Description_Panne,
                'Statut' => 'en_attente',
                'Priorite' => (new DemandeIntervention(['Urgence' => $request->Urgence]))->calculatePriority(),
                'Delai_Souhaite' => $request->Delai_Souhaite,
                'Commentaires' => $request->Commentaires
            ]);

            // ICI: Envoyer un email au chef de service pour validation
            // $this->envoyerEmailValidation($demande);

            return redirect()->route('user.demandes.index')
                ->with('success', 'Demande d\'intervention créée avec succès! Elle est maintenant en attente de validation.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de la demande: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher une demande spécifique
     */
    public function show($id)
    {
        $demande = DemandeIntervention::with(['equipement', 'demandeur', 'validateur'])
            ->where('ID_Demande', $id)
            ->where('ID_Demandeur', Auth::id())
            ->firstOrFail();

        return view('usersSimple.demandes.show', compact('demande'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $user = Auth::user();
        $demande = DemandeIntervention::where('ID_Demande', $id)
            ->where('ID_Demandeur', Auth::id())
            ->where('Statut', 'en_attente')
            ->firstOrFail();

        // Récupérer seulement les équipements du service de l'utilisateur
        $equipements = Equipement::where('etat', '!=', 'hors_service')
            ->where(function($query) use ($user) {
                $query->where('localisation_id', $user->service_id)
                      ->orWhere('service_responsable_id', $user->service_id);
            })
            ->with('localisation')
            ->orderBy('numero_inventaire')
            ->get();

        return view('usersSimple.demandes.edit', compact('demande', 'equipements'));
    }

    /**
     * Mettre à jour une demande
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $demande = DemandeIntervention::where('ID_Demande', $id)
            ->where('ID_Demandeur', Auth::id())
            ->where('Statut', 'en_attente')
            ->firstOrFail();

        // Vérifier que l'équipement appartient bien au service de l'utilisateur
        $equipement = Equipement::where('id', $request->ID_Equipement)
            ->where(function($query) use ($user) {
                $query->where('localisation_id', $user->service_id)
                      ->orWhere('service_responsable_id', $user->service_id);
            })
            ->first();

        if (!$equipement) {
            return redirect()->back()
                ->with('error', 'Cet équipement ne fait pas partie de votre service.')
                ->withInput();
        }

        $validator = Validator::make($request->all(), [
            'ID_Equipement' => 'required|exists:equipements,id',
            'Type_Intervention' => 'required|in:maintenance_preventive,maintenance_corrective,reparation,calibration,verification,controle,autre',
            'Urgence' => 'required|in:normale,urgente,critique',
            'Description_Panne' => 'required|min:10|max:2000',
            'Delai_Souhaite' => 'nullable|integer|min:1|max:720',
            'Commentaires' => 'nullable|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $demande->update([
                'ID_Equipement' => $request->ID_Equipement,
                'Type_Intervention' => $request->Type_Intervention,
                'Urgence' => $request->Urgence,
                'Description_Panne' => $request->Description_Panne,
                'Priorite' => (new DemandeIntervention(['Urgence' => $request->Urgence]))->calculatePriority(),
                'Delai_Souhaite' => $request->Delai_Souhaite,
                'Commentaires' => $request->Commentaires
            ]);

            return redirect()->route('user.demandes.show', $demande->ID_Demande)
                ->with('success', 'Demande mise à jour avec succès!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprimer une demande (soft delete)
     */
    public function destroy($id)
    {
        $demande = DemandeIntervention::where('ID_Demande', $id)
            ->where('ID_Demandeur', Auth::id())
            ->where('Statut', 'en_attente')
            ->firstOrFail();

        try {
            $demande->delete();

            return redirect()->route('user.demandes.index')
                ->with('success', 'Demande supprimée avec succès!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Voir les demandes supprimées
     */
    public function trash()
    {
        $user = Auth::user();
        $demandes = DemandeIntervention::onlyTrashed()
            ->where('ID_Demandeur', $user->id)
            ->with(['equipement', 'validateur'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('usersSimple.demandes.trash', compact('demandes'));
    }

    /**
     * Restaurer une demande supprimée
     */
    public function restore($id)
    {
        $demande = DemandeIntervention::onlyTrashed()
            ->where('ID_Demande', $id)
            ->where('ID_Demandeur', Auth::id())
            ->firstOrFail();

        try {
            $demande->restore();

            return redirect()->route('user.demandes.trash')
                ->with('success', 'Demande restaurée avec succès!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la restauration: ' . $e->getMessage());
        }
    }

    /**
     * Forcer la suppression d'une demande
     */
    public function forceDelete($id)
    {
        $demande = DemandeIntervention::onlyTrashed()
            ->where('ID_Demande', $id)
            ->where('ID_Demandeur', Auth::id())
            ->firstOrFail();

        try {
            $demande->forceDelete();

            return redirect()->route('user.demandes.trash')
                ->with('success', 'Demande définitivement supprimée!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression définitive: ' . $e->getMessage());
        }
    }

    /**
 * Obtenir le type d'intervention formaté
 */
public function getTypeInterventionFormateAttribute()
{
    $types = [
        'maintenance_preventive' => 'Maintenance préventive',
        'maintenance_corrective' => 'Maintenance corrective',
        'reparation' => 'Réparation',
        'calibration' => 'Calibration',
        'verification' => 'Vérification',
        'controle' => 'Contrôle',
        'autre' => 'Autre'
    ];

    return $types[$this->Type_Intervention] ?? $this->Type_Intervention;
}

/**
 * Vérifier si la demande est rejetée
 */
public function isRejetee()
{
    return $this->Statut === 'rejetee';
}

/**
 * Vérifier si la demande est en cours
 */
public function isEnCours()
{
    return $this->Statut === 'en_cours';
}

/**
 * Vérifier si la demande est terminée
 */
public function isTerminee()
{
    return $this->Statut === 'terminee';
}
}
