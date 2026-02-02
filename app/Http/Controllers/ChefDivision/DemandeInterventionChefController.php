<?php

namespace App\Http\Controllers\ChefDivision;

use App\Http\Controllers\Controller;
use App\Models\DemandeIntervention;
use App\Models\Localisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DemandeInterventionChefController extends Controller
{
    /**
     * Vérifier que l'utilisateur est un chef de division
     */
    private function checkChefDivision()
    {
        $user = Auth::user();

        if (!$user || !$user->isChefDivision()) {
            abort(403, 'Accès non autorisé. Vous devez être chef de division.');
        }

        return $user;
    }

    /**
     * Récupérer le service dont l'utilisateur est responsable
     */
    private function getServiceResponsable()
    {
        $user = $this->checkChefDivision();
        $service = $user->serviceResponsable()->first();

        if (!$service) {
            abort(403, 'Vous n\'êtes pas responsable d\'un service.');
        }

        return $service;
    }

    /**
     * Afficher la liste des demandes du service avec statistiques
     */
    public function index(Request $request)
    {
        $service = $this->getServiceResponsable();

        // Construire la requête
        $query = DemandeIntervention::with(['equipement', 'demandeur', 'validateur'])
            ->whereHas('equipement', function ($query) use ($service) {
                $query->where('localisation_id', $service->id);
            })
            ->orderBy('created_at', 'desc');

        // Appliquer les filtres
        if ($request->has('statut') && $request->statut !== 'tous') {
            $query->where('Statut', $request->statut);
        }

        if ($request->has('urgence') && $request->urgence !== 'tous') {
            $query->where('Urgence', $request->urgence);
        }

        if ($request->has('type') && $request->type !== 'tous') {
            $query->where('Type_Intervention', $request->type);
        }

        $demandes = $query->paginate(15);

        // Statistiques générales
        $stats = [
            'total' => DemandeIntervention::whereHas('equipement', function ($q) use ($service) {
                $q->where('localisation_id', $service->id);
            })->count(),

            'en_attente' => DemandeIntervention::where('Statut', 'en_attente')
                ->whereHas('equipement', function ($q) use ($service) {
                    $q->where('localisation_id', $service->id);
                })->count(),

            'validees' => DemandeIntervention::where('Statut', 'validee')
                ->whereHas('equipement', function ($q) use ($service) {
                    $q->where('localisation_id', $service->id);
                })->count(),

            'rejetees' => DemandeIntervention::where('Statut', 'rejetee')
                ->whereHas('equipement', function ($q) use ($service) {
                    $q->where('localisation_id', $service->id);
                })->count(),

            'en_cours' => DemandeIntervention::where('Statut', 'en_cours')
                ->whereHas('equipement', function ($q) use ($service) {
                    $q->where('localisation_id', $service->id);
                })->count(),

            'terminees' => DemandeIntervention::where('Statut', 'terminee')
                ->whereHas('equipement', function ($q) use ($service) {
                    $q->where('localisation_id', $service->id);
                })->count(),
        ];

        // Statistiques par statut pour graphique circulaire
        $statutStats = DemandeIntervention::whereHas('equipement', function ($q) use ($service) {
                $q->where('localisation_id', $service->id);
            })
            ->select('Statut', DB::raw('count(*) as total'))
            ->groupBy('Statut')
            ->get()
            ->pluck('total', 'Statut');

        // Statistiques par urgence
        $urgenceStats = DemandeIntervention::whereHas('equipement', function ($q) use ($service) {
                $q->where('localisation_id', $service->id);
            })
            ->select('Urgence', DB::raw('count(*) as total'))
            ->groupBy('Urgence')
            ->get()
            ->pluck('total', 'Urgence');

        // Statistiques par type d'intervention
        $typeStats = DemandeIntervention::whereHas('equipement', function ($q) use ($service) {
                $q->where('localisation_id', $service->id);
            })
            ->select('Type_Intervention', DB::raw('count(*) as total'))
            ->groupBy('Type_Intervention')
            ->get()
            ->pluck('total', 'Type_Intervention');

        // Évolution des demandes sur 6 mois
        $evolutionStats = DemandeIntervention::whereHas('equipement', function ($q) use ($service) {
                $q->where('localisation_id', $service->id);
            })
            ->where('Date_Demande', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(Date_Demande, "%Y-%m") as mois'),
                DB::raw('count(*) as total')
            )
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        // Statistiques par demandeur (top 5)
        $topDemandeurs = DemandeIntervention::whereHas('equipement', function ($q) use ($service) {
                $q->where('localisation_id', $service->id);
            })
            ->select('ID_Demandeur', DB::raw('count(*) as total'))
            ->groupBy('ID_Demandeur')
            ->orderByDesc('total')
            ->limit(5)
            ->with('demandeur')
            ->get();

        return view('chefDivision.demandes.index', compact(
            'demandes',
            'stats',
            'service',
            'statutStats',
            'urgenceStats',
            'typeStats',
            'evolutionStats',
            'topDemandeurs'
        ));
    }

    /**
     * Afficher une demande spécifique
     */
    public function show($id)
    {
        $service = $this->getServiceResponsable();

        $demande = DemandeIntervention::with(['equipement', 'demandeur', 'validateur'])
            ->where('ID_Demande', $id)
            ->whereHas('equipement', function ($query) use ($service) {
                $query->where('localisation_id', $service->id);
            })
            ->firstOrFail();

        return view('chefDivision.demandes.show', compact('demande', 'service'));
    }

    /**
     * Valider une demande
     */
    public function valider(Request $request, $id)
    {
        $service = $this->getServiceResponsable();

        $validator = Validator::make($request->all(), [
            'commentaire' => 'nullable|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $demande = DemandeIntervention::with('demandeur')
            ->where('ID_Demande', $id)
            ->where('Statut', 'en_attente')
            ->whereHas('equipement', function ($query) use ($service) {
                $query->where('localisation_id', $service->id);
            })
            ->firstOrFail();

        try {
            $demande->update([
                'Statut' => 'validee',
                'Date_Validation' => now(),
                'ID_Validateur' => Auth::id(),
                'Commentaire_Validation' => $request->commentaire
            ]);

            // TODO: Envoyer email de notification
            // Mail::to($demande->demandeur->email)->send(...);

            return redirect()->route('chef-division.demandes.show', $demande->ID_Demande)
                ->with('success', 'Demande validée avec succès!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la validation: ' . $e->getMessage());
        }
    }

    /**
     * Rejeter une demande
     */
    public function rejeter(Request $request, $id)
    {
        $service = $this->getServiceResponsable();

        $validator = Validator::make($request->all(), [
            'commentaire' => 'required|max:500',
            'raison' => 'required|in:inappropriée,équipement_non_disponible,manque_informations,autre'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $demande = DemandeIntervention::with('demandeur')
            ->where('ID_Demande', $id)
            ->where('Statut', 'en_attente')
            ->whereHas('equipement', function ($query) use ($service) {
                $query->where('localisation_id', $service->id);
            })
            ->firstOrFail();

        try {
            $commentaire = $request->commentaire . "\n\nRaison: " . $request->raison;

            $demande->update([
                'Statut' => 'rejetee',
                'Date_Validation' => now(),
                'ID_Validateur' => Auth::id(),
                'Commentaire_Validation' => $commentaire
            ]);

            // TODO: Envoyer email de notification
            // Mail::to($demande->demandeur->email)->send(...);

            return redirect()->route('chef-division.demandes.show', $demande->ID_Demande)
                ->with('success', 'Demande rejetée avec succès!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors du rejet: ' . $e->getMessage());
        }
    }

    /**
     * Mettre en attente avec commentaire
     */
    public function mettreEnAttente(Request $request, $id)
    {
        $service = $this->getServiceResponsable();

        $validator = Validator::make($request->all(), [
            'commentaire' => 'required|max:500',
            'delai_supplementaire' => 'nullable|integer|min:1|max:168'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $demande = DemandeIntervention::with('demandeur')
            ->where('ID_Demande', $id)
            ->where('Statut', 'en_attente')
            ->whereHas('equipement', function ($query) use ($service) {
                $query->where('localisation_id', $service->id);
            })
            ->firstOrFail();

        try {
            $commentaire = "Mise en attente - " . $request->commentaire;

            if ($request->delai_supplementaire) {
                $commentaire .= "\nDélai supplémentaire recommandé: " . $request->delai_supplementaire . " heures";
            }

            $demande->update([
                'Commentaire_Validation' => $commentaire
            ]);

            // TODO: Envoyer email de notification
            // Mail::to($demande->demandeur->email)->send(...);

            return redirect()->route('chef-division.demandes.show', $demande->ID_Demande)
                ->with('success', 'Commentaire ajouté à la demande en attente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise en attente: ' . $e->getMessage());
        }
    }

    /**
     * Dashboard du chef de division
     */
    public function dashboard()
    {
        $service = $this->getServiceResponsable();

        // Dernières demandes
        $demandesRecentes = DemandeIntervention::with(['equipement', 'demandeur'])
            ->whereHas('equipement', function ($query) use ($service) {
                $query->where('localisation_id', $service->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Demandes en attente urgentes
        $urgentes = DemandeIntervention::with(['equipement', 'demandeur'])
            ->where('Statut', 'en_attente')
            ->where('Urgence', 'critique')
            ->whereHas('equipement', function ($query) use ($service) {
                $query->where('localisation_id', $service->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistiques par mois
        $statsMensuelles = DemandeIntervention::selectRaw('
                DATE_FORMAT(Date_Demande, "%Y-%m") as mois,
                COUNT(*) as total,
                SUM(CASE WHEN Statut = "validee" THEN 1 ELSE 0 END) as validees,
                SUM(CASE WHEN Statut = "rejetee" THEN 1 ELSE 0 END) as rejetees
            ')
            ->whereHas('equipement', function ($query) use ($service) {
                $query->where('localisation_id', $service->id);
            })
            ->where('Date_Demande', '>=', now()->subMonths(6))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        return view('chefDivision.dashboard', compact(
            'service',
            'demandesRecentes',
            'urgentes',
            'statsMensuelles'
        ));
    }
}
