<?php
// app/Http/Controllers/Technicien/TechnicienInterventionController.php

namespace App\Http\Controllers\Technicien;

use App\Http\Controllers\Controller;
use App\Models\DemandeIntervention;
use App\Models\Intervention;
use App\Models\Equipement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TechnicienInterventionController extends Controller
{
    /**
     * UC-TEC-01 : Consulter les demandes d'intervention
     * Dashboard du technicien avec toutes les demandes filtrées par service
     */
    public function dashboard()
    {
        $user = Auth::user();
        $serviceId = $user->service_id;

        // Statistiques pour les charts
        $stats = [
            'total' => DemandeIntervention::whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })->count(),

            'en_attente' => DemandeIntervention::whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })->where('Statut', 'en_attente')->count(),

            'validees' => DemandeIntervention::whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })->where('Statut', 'validee')->count(),

            'en_cours' => DemandeIntervention::whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })->where('Statut', 'en_cours')->count(),

            'terminees' => DemandeIntervention::whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })->where('Statut', 'terminee')->count(),

            'critiques' => DemandeIntervention::whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })->where('Urgence', 'critique')
              ->whereIn('Statut', ['en_attente', 'validee', 'en_cours'])
              ->count(),
        ];

        // Demandes récentes
        $demandesRecentes = DemandeIntervention::with(['demandeur', 'equipement'])
            ->whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })
            ->orderBy('Date_Demande', 'desc')
            ->orderBy('Heure_Demande', 'desc')
            ->limit(10)
            ->get();

        // Demandes par urgence pour graphique
        $parUrgence = DemandeIntervention::whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })
            ->select('Urgence', DB::raw('count(*) as total'))
            ->groupBy('Urgence')
            ->get();

        // Évolution des demandes (30 derniers jours)
        $evolution = DemandeIntervention::whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })
            ->where('Date_Demande', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(Date_Demande) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Récupérer d'abord les IDs des demandes du service
        $demandeIds = DemandeIntervention::whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })
            ->pluck('ID_Demande');

        // Puis récupérer les interventions liées à ces demandes
        $interventionsEnCours = Intervention::with(['demande.demandeur', 'demande.equipement'])
            ->whereIn('ID_Demande', $demandeIds)
            ->whereNull('Date_Fin')
            ->get();

        return view('technicien.dashboard', compact(
            'stats',
            'demandesRecentes',
            'parUrgence',
            'evolution',
            'interventionsEnCours'
        ));
    }

    /**
     * Liste des demandes avec filtres
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $serviceId = $user->service_id;

        $query = DemandeIntervention::with(['demandeur', 'equipement', 'validateur'])
            ->whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            });

        // Filtres
        if ($request->filled('statut')) {
            $query->where('Statut', $request->statut);
        }

        if ($request->filled('urgence')) {
            $query->where('Urgence', $request->urgence);
        }

        if ($request->filled('type')) {
            $query->where('Type_Intervention', $request->type);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('Date_Demande', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('Date_Demande', '<=', $request->date_fin);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Numero_Demande', 'like', "%{$search}%")
                  ->orWhere('Description_Panne', 'like', "%{$search}%")
                  ->orWhereHas('demandeur', function($sq) use ($search) {
                      $sq->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%");
                  })
                  ->orWhereHas('equipement', function($sq) use ($search) {
                      $sq->where('nom', 'like', "%{$search}%")
                        ->orWhere('code_barre', 'like', "%{$search}%");
                  });
            });
        }

        // Tri
        $orderBy = $request->get('order_by', 'Date_Demande');
        $orderDir = $request->get('order_dir', 'desc');
        $query->orderBy($orderBy, $orderDir);

        $demandes = $query->paginate(15)->withQueryString();

        // Statistiques pour les filtres
        $stats = [
            'total' => DemandeIntervention::whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })->count(),
            'en_attente' => DemandeIntervention::whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })->where('Statut', 'en_attente')->count(),
            'validees' => DemandeIntervention::whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })->where('Statut', 'validee')->count(),
        ];

        return view('technicien.demandes.index', compact('demandes', 'stats'));
    }

    /**
     * Afficher les détails d'une demande
     */
    public function show($id)
    {
        $user = Auth::user();
        $serviceId = $user->service_id;

        $demande = DemandeIntervention::with(['demandeur', 'equipement', 'validateur'])
            ->whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })
            ->findOrFail($id);

        // Vérifier si une intervention existe déjà
        $intervention = Intervention::where('ID_Demande', $id)->first();

        return view('technicien.demandes.show', compact('demande', 'intervention'));
    }

    /**
     * UC-TEC-02 : Planifier une intervention
     * Formulaire de planification
     */
    public function planifierForm($id)
    {
        $user = Auth::user();
        $serviceId = $user->service_id;

        $demande = DemandeIntervention::with(['demandeur', 'equipement'])
            ->whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })
            ->where('Statut', 'validee')
            ->findOrFail($id);

        return view('technicien.interventions.planifier', compact('demande'));
    }

    /**
     * Enregistrer la planification - CORRIGÉ avec ID_Equipement_Controle
     */
    public function planifierStore(Request $request, $id)
    {
        $user = Auth::user();
        $serviceId = $user->service_id;

        $demande = DemandeIntervention::whereHas('demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            })
            ->findOrFail($id);

        $request->validate([
            'Date_Debut' => 'required|date',
            'Heure_Debut' => 'required',
            'Date_Fin_Prevue' => 'nullable|date|after_or_equal:Date_Debut',
            'Observations' => 'nullable|string',
        ]);

        // Créer l'intervention avec l'ID de l'équipement
        $intervention = Intervention::create([
            'ID_Demande' => $id,
            'Date_Debut' => $request->Date_Debut,
            'Heure_Debut' => $request->Heure_Debut,
            'Type_Intervenant' => 'technicien',
            'ID_Intervenant' => $user->id,
            'ID_Equipement_Controle' => $demande->ID_Equipement, // AJOUT IMPORTANT
            'Observations' => $request->Observations,
        ]);

        // Mettre à jour le statut de la demande
        $demande->update(['Statut' => 'en_cours']);

        // Mettre à jour le statut de l'équipement si nécessaire
        if ($request->has('mettre_hors_service') && $request->mettre_hors_service) {
            Equipement::where('id', $demande->ID_Equipement)
                ->update(['statut' => 'en_maintenance']);
        }

        return redirect()->route('technicien.interventions.show', $intervention->ID_Intervention)
            ->with('success', 'Intervention planifiée avec succès.');
    }

    /**
     * UC-TEC-03 : Saisir un rapport d'intervention
     */
    public function saisirRapportForm($id)
    {
        $intervention = Intervention::with(['demande.demandeur', 'demande.equipement'])
            ->where('ID_Intervenant', Auth::id())
            ->findOrFail($id);

        return view('technicien.interventions.rapport', compact('intervention'));
    }

    /**
     * Enregistrer le rapport d'intervention - CORRIGÉ avec mapping pour etat
     */
    public function saisirRapportStore(Request $request, $id)
    {
        $intervention = Intervention::where('ID_Intervenant', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'Rapport_Technique' => 'required|string',
            'Resultat' => 'required|in:termine,partiel,reporte,echec',
            'Date_Fin' => 'required|date',
            'Heure_Fin' => 'required',
            'Cout_Main_Oeuvre' => 'nullable|numeric|min:0',
            'Cout_Pieces' => 'nullable|numeric|min:0',
            'Statut_Conformite' => 'nullable|in:conforme,non_conforme',
        ]);

        // Calculer la durée
        $debut = \Carbon\Carbon::parse($intervention->Date_Debut . ' ' . $intervention->Heure_Debut);
        $fin = \Carbon\Carbon::parse($request->Date_Fin . ' ' . $request->Heure_Fin);
        $duree = $debut->diffInHours($fin) + ($debut->diffInMinutes($fin) % 60) / 60;

        // Calculer le coût total
        $coutMain = $request->Cout_Main_Oeuvre ?? 0;
        $coutPieces = $request->Cout_Pieces ?? 0;
        $coutTotal = $coutMain + $coutPieces;

        $intervention->update([
            'Rapport_Technique' => $request->Rapport_Technique,
            'Resultat' => $request->Resultat,
            'Date_Fin' => $request->Date_Fin,
            'Heure_Fin' => $request->Heure_Fin,
            'Duree_Reelle' => round($duree, 2),
            'Cout_Main_Oeuvre' => $coutMain,
            'Cout_Pieces' => $coutPieces,
            'Cout_Total' => $coutTotal,
            'Statut_Conformite' => $request->Statut_Conformite,
            'Signature_Client' => $request->Signature_Client ?? null,
        ]);

        // Mettre à jour le statut de la demande
        $demande = DemandeIntervention::find($intervention->ID_Demande);

        if ($request->Resultat === 'termine') {
            $demande->update(['Statut' => 'terminee']);

            // UC-TEC-08 : Mettre à jour l'état de l'équipement avec mapping
            if ($request->has('nouveau_statut_equipement') && !empty($request->nouveau_statut_equipement)) {
                $nouveauStatut = $request->nouveau_statut_equipement;

                // Mapping entre statut (interface) et etat (base de données)
                $mappingEtat = [
                    'disponible' => 'bon',
                    'en_maintenance' => 'moyen',
                    'en_panne' => 'mauvais',
                    'hors_service' => 'hors_service',
                    'reserve' => 'bon',
                ];

                $nouvelEtat = $mappingEtat[$nouveauStatut] ?? 'moyen';

                Equipement::where('id', $demande->ID_Equipement)
                    ->update(['etat' => $nouvelEtat]);
            }
        } elseif ($request->Resultat === 'partiel' || $request->Resultat === 'reporte') {
            $demande->update(['Statut' => 'en_attente']);
        }

        return redirect()->route('technicien.interventions.show', $id)
            ->with('success', 'Rapport d\'intervention enregistré avec succès.');
    }

    /**
     * Liste des interventions
     */
    public function interventionsList(Request $request)
    {
        $user = Auth::user();
        $serviceId = $user->service_id;

        $query = Intervention::with(['demande.demandeur', 'demande.equipement'])
            ->whereHas('demande.demandeur', function($q) use ($serviceId) {
                $q->where('service_id', $serviceId);
            });

        // Filtres
        if ($request->filled('statut')) {
            if ($request->statut === 'en_cours') {
                $query->whereNull('Date_Fin');
            } elseif ($request->statut === 'terminees') {
                $query->whereNotNull('Date_Fin');
            }
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('Date_Debut', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('Date_Fin', '<=', $request->date_fin);
        }

        $interventions = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('technicien.interventions.index', compact('interventions'));
    }

    /**
     * Afficher une intervention
     */
    public function showIntervention($id)
    {
        $intervention = Intervention::with(['demande.demandeur', 'demande.equipement', 'demande.validateur'])
            ->findOrFail($id);

        return view('technicien.interventions.show', compact('intervention'));
    }

    /**
     * UC-TEC-08 : Mettre à jour le statut d'un équipement
     */
    public function updateEquipementStatus(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:disponible,en_maintenance,en_panne,hors_service,reserve',
            'commentaire' => 'nullable|string',
        ]);

        $equipement = Equipement::findOrFail($id);

        // Mapping pour la colonne etat
        $mappingEtat = [
            'disponible' => 'bon',
            'en_maintenance' => 'moyen',
            'en_panne' => 'mauvais',
            'hors_service' => 'hors_service',
            'reserve' => 'bon',
        ];

        $nouvelEtat = $mappingEtat[$request->statut] ?? 'moyen';

        // Mettre à jour les deux colonnes si elles existent
        $updateData = ['etat' => $nouvelEtat];

        // Si la colonne statut existe, la mettre à jour aussi
        if (\Illuminate\Support\Facades\Schema::hasColumn('equipements', 'statut')) {
            $updateData['statut'] = $request->statut;
        }

        $equipement->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'État de l\'équipement mis à jour avec succès.',
            'ancien_etat' => $equipement->getOriginal('etat'),
            'nouvel_etat' => $nouvelEtat
        ]);
    }

    /**
     * Export des demandes
     */
   /**
 * Export des demandes au format CSV
 */
public function exportDemandes(Request $request)
{
    $user = Auth::user();
    $serviceId = $user->service_id;

    $demandes = DemandeIntervention::with(['demandeur', 'equipement', 'validateur'])
        ->whereHas('demandeur', function($q) use ($serviceId) {
            $q->where('service_id', $serviceId);
        })
        ->orderBy('Date_Demande', 'desc')
        ->get();

    // Nom du fichier
    $filename = 'demandes_intervention_' . date('Y-m-d_His') . '.csv';

    // En-têtes HTTP pour le téléchargement
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Créer le flux de sortie
    $output = fopen('php://output', 'w');

    // Ajouter le BOM UTF-8 pour Excel
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    // En-têtes des colonnes
    fputcsv($output, [
        'N° Demande',
        'Date Demande',
        'Heure',
        'Demandeur',
        'Service',
        'Équipement',
        'Code barre',
        "Type d'intervention",
        'Urgence',
        'Description',
        'Statut',
        'Date Validation',
        'Validateur',
        'Priorité',
        'Délai souhaité (h)',
        'Commentaires'
    ], ';');

    // Données
    foreach ($demandes as $demande) {
        fputcsv($output, [
            $demande->Numero_Demande,
            \Carbon\Carbon::parse($demande->Date_Demande)->format('d/m/Y'),
            $demande->Heure_Demande,
            $demande->demandeur ? $demande->demandeur->nom . ' ' . $demande->demandeur->prenom : 'N/A',
            $demande->demandeur && $demande->demandeur->service ? $demande->demandeur->service->nom : 'N/A',
            $demande->equipement ? $demande->equipement->nom : 'N/A',
            $demande->equipement ? $demande->equipement->code_barre : 'N/A',
            $demande->type_intervention_formate ?? $demande->Type_Intervention,
            $demande->urgence_formate ?? $demande->Urgence,
            $demande->Description_Panne,
            $demande->etat_formate ?? $demande->Statut,
            $demande->Date_Validation ? \Carbon\Carbon::parse($demande->Date_Validation)->format('d/m/Y H:i') : '',
            $demande->validateur ? $demande->validateur->nom . ' ' . $demande->validateur->prenom : '',
            $demande->Priorite,
            $demande->Delai_Souhaite,
            $demande->Commentaires
        ], ';');
    }

    fclose($output);
    exit;
}





/**
 * Afficher la liste des équipements pour maintenance préventive
 */
public function equipementsPreventive(Request $request)
{
    $user = Auth::user();
    $serviceId = $user->service_id;

    // Récupérer les équipements du service
    $query = Equipement::with(['typeEquipement', 'localisation', 'fournisseur'])
        ->where('service_responsable_id', $serviceId);

    // Filtres
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('code_barres', 'like', "%{$search}%")
              ->orWhere('numero_serie', 'like', "%{$search}%");
        });
    }

    if ($request->filled('type_maintenance')) {
        $query->where('type_maintenance', $request->type_maintenance);
    }

    if ($request->filled('etat')) {
        $query->where('etat', $request->etat);
    }

    $equipements = $query->paginate(15);

    // Statistiques détaillées
    $stats = [
        'total' => Equipement::where('service_responsable_id', $serviceId)->count(),
        'preventive' => Equipement::where('service_responsable_id', $serviceId)
            ->where('type_maintenance', 'preventive')
            ->count(),
        'mixte' => Equipement::where('service_responsable_id', $serviceId)
            ->where('type_maintenance', 'mixte')
            ->count(),
        'disponible' => Equipement::where('service_responsable_id', $serviceId)
            ->whereIn('etat', ['neuf', 'bon'])
            ->count(),
        'en_maintenance' => Equipement::where('service_responsable_id', $serviceId)
            ->where('etat', 'moyen')
            ->count(),
        'en_panne' => Equipement::where('service_responsable_id', $serviceId)
            ->where('etat', 'mauvais')
            ->count(),
        'hors_service' => Equipement::where('service_responsable_id', $serviceId)
            ->where('etat', 'hors_service')
            ->count(),
    ];

    return view('technicien.equipements.preventive', compact('equipements', 'stats'));
}

/**
 * Formulaire de planification de maintenance préventive
 */
public function planifierPreventiveForm($id)
{
    $user = Auth::user();
    $serviceId = $user->service_id;

    $equipement = Equipement::with(['type_equipement', 'localisation'])
        ->where('service_responsable_id', $serviceId)
        ->findOrFail($id);

    return view('technicien.equipements.planifier-preventive', compact('equipement'));
}

/**
 * Enregistrer la planification de maintenance préventive
 */
public function planifierPreventiveStore(Request $request, $id)
{
    $user = Auth::user();
    $serviceId = $user->service_id;

    $equipement = Equipement::where('service_responsable_id', $serviceId)
        ->findOrFail($id);

    $request->validate([
        'Date_Prevue' => 'required|date|after_or_equal:today',
        'Heure_Prevue' => 'required',
        'Type_Maintenance' => 'required|in:preventive,mixte',
        'Description' => 'required|string|min:10',
        'Observations' => 'nullable|string',
        'mettre_en_maintenance' => 'nullable|boolean',
    ]);

    // Créer une demande d'intervention pour maintenance préventive
    $demande = DemandeIntervention::create([
        'Numero_Demande' => DemandeIntervention::generateNumeroDemande(),
        'Date_Demande' => now()->format('Y-m-d'),
        'Heure_Demande' => now()->format('H:i:s'),
        'ID_Demandeur' => $user->id,
        'ID_Equipement' => $id,
        'Type_Intervention' => 'maintenance_preventive',
        'Urgence' => 'normale',
        'Description_Panne' => $request->Description,
        'Statut' => 'validee', // Directement validée pour le technicien
        'Priorite' => 2, // Priorité moyenne
        'Commentaires' => $request->Observations,
    ]);

    // Créer l'intervention directement
    $intervention = Intervention::create([
        'ID_Demande' => $demande->ID_Demande,
        'Date_Debut' => $request->Date_Prevue,
        'Heure_Debut' => $request->Heure_Prevue,
        'Type_Intervenant' => 'technicien',
        'ID_Intervenant' => $user->id,
        'ID_Equipement_Controle' => $id,
        'Observations' => 'Maintenance préventive planifiée: ' . $request->Description,
    ]);

    // Mettre l'équipement en maintenance si demandé
    if ($request->has('mettre_en_maintenance') && $request->mettre_en_maintenance) {
        $equipement->update(['statut' => 'en_maintenance']);

        // Mettre à jour aussi l'état si besoin
        if ($equipement->etat !== 'moyen') {
            $equipement->update(['etat' => 'moyen']);
        }
    }

    // Envoyer un email au chef de service
    $this->sendPreventiveNotification($demande, $equipement, $user);

    return redirect()->route('technicien.interventions.show', $intervention->ID_Intervention)
        ->with('success', 'Maintenance préventive planifiée avec succès.');
}

/**
 * Envoyer une notification par email
 */
private function sendPreventiveNotification($demande, $equipement, $technicien)
{
    try {
        // Récupérer le chef de service
        $chefService = User::where('service_id', $technicien->service_id)
            ->whereHas('profil', function($q) {
                $q->where('nom_profil', 'chef_service');
            })
            ->first();

        if (!$chefService) {
            return;
        }

        // Sujet de l'email
        $subject = "Maintenance préventive planifiée - " . $equipement->nom;

        // Contenu de l'email
        $content = "
            <h2>Maintenance préventive planifiée</h2>
            <p><strong>Technicien :</strong> {$technicien->prenom} {$technicien->nom}</p>
            <p><strong>Équipement :</strong> {$equipement->nom} ({$equipement->code_barres})</p>
            <p><strong>Date prévue :</strong> " . \Carbon\Carbon::parse($demande->Date_Debut)->format('d/m/Y') . "</p>
            <p><strong>Description :</strong> {$demande->Description_Panne}</p>
            <p><strong>N° Demande :</strong> {$demande->Numero_Demande}</p>
            <br>
            <p><a href='" . route('technicien.interventions.show', $demande->interventions->first()->ID_Intervention) . "'>Voir le détail</a></p>
        ";

        // Envoyer l'email (à adapter selon votre système de mail)
        \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($chefService, $subject, $content) {
            $message->to($chefService->email)
                ->subject($subject)
                ->setBody($content, 'text/html');
        });

    } catch (\Exception $e) {
        // Log l'erreur mais ne bloque pas le processus
        \Illuminate\Support\Facades\Log::error('Erreur envoi email: ' . $e->getMessage());
    }
}

/**
 * Liste des maintenances préventives planifiées
 */
public function preventivesList(Request $request)
{
    $user = Auth::user();
    $serviceId = $user->service_id;

    $query = Intervention::with(['demande.demandeur', 'demande.equipement'])
        ->whereHas('demande', function($q) {
            $q->where('Type_Intervention', 'maintenance_preventive');
        })
        ->whereHas('demande.demandeur', function($q) use ($serviceId) {
            $q->where('service_id', $serviceId);
        });

    // Filtres
    if ($request->filled('statut')) {
        if ($request->statut === 'planifiees') {
            $query->whereNull('Date_Fin');
        } elseif ($request->statut === 'realisees') {
            $query->whereNotNull('Date_Fin');
        }
    }

    if ($request->filled('date_debut')) {
        $query->whereDate('Date_Debut', '>=', $request->date_debut);
    }

    $preventives = $query->orderBy('Date_Debut', 'desc')->paginate(15);

    return view('technicien.preventives.index', compact('preventives'));
}
}
