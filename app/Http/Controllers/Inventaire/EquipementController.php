<?php

namespace App\Http\Controllers\Inventaire;

use App\Http\Controllers\Controller;
use App\Models\Equipement;
use App\Models\Localisation;
use App\Models\Fournisseur;
use App\Models\TypeEquipement;
use App\Models\ContratMaintenance;
use App\Models\HistoriqueMouvement;
use App\Models\LogActivite;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class EquipementController extends Controller
{
    /**
     * UC-INV-01 : Afficher la liste des équipements
     */
    public function index(Request $request)
    {
        $query = Equipement::with(['typeEquipement', 'localisation', 'serviceResponsable', 'fournisseur', 'contrat'])
            ->latest();

        // Filtres
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('numero_inventaire', 'like', '%' . $request->search . '%')
                  ->orWhere('numero_serie', 'like', '%' . $request->search . '%')
                  ->orWhere('marque', 'like', '%' . $request->search . '%')
                  ->orWhere('modele', 'like', '%' . $request->search . '%')
                  ->orWhere('code_barres', 'like', '%' . $request->search . '%')
                  ->orWhereHas('typeEquipement', function($q2) use ($request) {
                      $q2->where('libelle', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('type_equipement_id')) {
            $query->where('type_equipement_id', $request->type_equipement_id);
        }

        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }

        if ($request->filled('localisation_id')) {
            $query->where('localisation_id', $request->localisation_id);
        }

        if ($request->filled('service_responsable_id')) {
            $query->where('service_responsable_id', $request->service_responsable_id);
        }

        if ($request->filled('fournisseur_id')) {
            $query->where('fournisseur_id', $request->fournisseur_id);
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            if ($request->statut == 'actif') {
                $query->where('etat', '!=', 'hors_service');
            } elseif ($request->statut == 'hors_service') {
                $query->where('etat', 'hors_service');
            }
        }

        $equipements = $query->paginate(20);

        // Données pour les filtres
        $types = TypeEquipement::orderBy('libelle')->get();
        $localisations = Localisation::orderBy('nom')->get();
        $services = Localisation::where('type', 'service')->orderBy('nom')->get();
        $fournisseurs = Fournisseur::actif()->orderBy('raison_sociale')->get();

        // Statistiques pour les graphiques
        $statistiques = $this->getStatistiques();

        return view('inventaire.equipements.index', compact(
            'equipements',
            'types',
            'localisations',
            'services',
            'fournisseurs',
            'statistiques'
        ));
    }

    /**
     * UC-INV-01 : Afficher le formulaire de création
     */
    public function create()
    {
        $types = TypeEquipement::orderBy('libelle')->get();
        $localisations = Localisation::orderBy('nom')->get();
        $services = Localisation::where('type', 'service')->orderBy('nom')->get();
        $fournisseurs = Fournisseur::actif()->orderBy('raison_sociale')->get();
        $contrats = ContratMaintenance::where('statut', 'actif')->orderBy('numero_contrat')->get();

        // Valeurs par défaut
        $etats = ['neuf' => 'Neuf', 'bon' => 'Bon état', 'moyen' => 'État moyen', 'mauvais' => 'Mauvais état', 'hors_service' => 'Hors service'];
        $typesMaintenance = ['preventive' => 'Préventive uniquement', 'curative' => 'Curative uniquement', 'mixte' => 'Mixte'];

        return view('inventaire.equipements.create', compact(
            'types',
            'localisations',
            'services',
            'fournisseurs',
            'contrats',
            'etats',
            'typesMaintenance'
        ));
    }

    /**
     * UC-INV-01 : Enregistrer un nouvel équipement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_inventaire' => 'required|unique:equipements|max:50',
            'numero_serie' => 'nullable|max:100',
            'marque' => 'required|max:100',
            'modele' => 'required|max:100',
            'type_equipement_id' => 'required|exists:type_equipements,id',
            'classe_equipement' => 'nullable|max:50',
            'date_achat' => 'required|date',
            'date_mise_service' => 'nullable|date|after_or_equal:date_achat',
            'prix_achat' => 'nullable|numeric|min:0',
            'duree_vie_theorique' => 'nullable|integer|min:0',
            'duree_garantie' => 'nullable|integer|min:0',
            'etat' => 'required|in:neuf,bon,moyen,mauvais,hors_service',
            'type_maintenance' => 'required|in:preventive,curative,mixte',
            'localisation_id' => 'nullable|exists:localisations,id',
            'service_responsable_id' => 'nullable|exists:localisations,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'contrat_id' => 'nullable|exists:contrats_maintenance,id',
            'commentaires' => 'nullable',
            'code_barres' => 'nullable|unique:equipements|max:100',
        ]);

        DB::beginTransaction();

        try {
            // Générer un code-barres si non fourni
            if (empty($validated['code_barres'])) {
                $validated['code_barres'] = 'EQP-' . strtoupper(uniqid());
            }

            // Ajouter l'utilisateur créateur
            $validated['created_by'] = auth()->id();

            $equipement = Equipement::create($validated);

            // Log de l'activité
            LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'creation_equipement',
                'module' => 'inventaire',
                'id_element' => $equipement->id,
                'adresse_ip' => $request->ip(),
                'details' => "Création de l'équipement {$equipement->numero_inventaire}",
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('inventaire.equipements.index')
                ->with('success', 'Équipement créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * UC-INV-02 : Afficher les détails d'un équipement
     */
    public function show(Equipement $equipement)
    {
        $equipement->load([
            'typeEquipement',
            'localisation',
            'serviceResponsable',
            'fournisseur',
            'contrat',
            'createur',
            'editeur',
            'mouvements' => function($query) {
                $query->with(['ancienneLocalisation', 'nouvelleLocalisation', 'operateur'])
                    ->latest()
                    ->limit(10);
            }
        ]);

        // Calculer les indicateurs
        $indicateurs = [
            'age' => $this->calculateAge($equipement->date_achat),
            'est_sous_garantie' => $this->estSousGarantie($equipement->date_achat, $equipement->duree_garantie),
            'date_fin_garantie' => $this->getDateFinGarantie($equipement->date_achat, $equipement->duree_garantie),
            'temps_restant_vie' => $this->getTempsRestantVie($equipement->date_achat, $equipement->duree_vie_theorique),
        ];

        return view('inventaire.equipements.show', compact('equipement', 'indicateurs'));
    }

    /**
     * UC-INV-02 : Afficher le formulaire d'édition
     */
    public function edit(Equipement $equipement)
    {
        $types = TypeEquipement::orderBy('libelle')->get();
        $localisations = Localisation::orderBy('nom')->get();
        $services = Localisation::where('type', 'service')->orderBy('nom')->get();
        $fournisseurs = Fournisseur::actif()->orderBy('raison_sociale')->get();
        $contrats = ContratMaintenance::where('statut', 'actif')->orderBy('numero_contrat')->get();

        $etats = ['neuf' => 'Neuf', 'bon' => 'Bon état', 'moyen' => 'État moyen', 'mauvais' => 'Mauvais état', 'hors_service' => 'Hors service'];
        $typesMaintenance = ['preventive' => 'Préventive uniquement', 'curative' => 'Curative uniquement', 'mixte' => 'Mixte'];

        return view('inventaire.equipements.edit', compact(
            'equipement',
            'types',
            'localisations',
            'services',
            'fournisseurs',
            'contrats',
            'etats',
            'typesMaintenance'
        ));
    }

    /**
     * UC-INV-02 : Mettre à jour un équipement
     */
public function update(Request $request, Equipement $equipement)
{
    $validated = $request->validate([
        'numero_inventaire' => 'required|unique:equipements,numero_inventaire,' . $equipement->id . '|max:50',
        'numero_serie' => 'nullable|max:100',
        'marque' => 'required|max:100',
        'modele' => 'required|max:100',
        'type_equipement_id' => 'required|exists:type_equipements,id',
        'classe_equipement' => 'nullable|max:50',
        'date_achat' => 'required|date',
        'date_mise_service' => 'nullable|date|after_or_equal:date_achat',
        'prix_achat' => 'nullable|numeric|min:0',
        'duree_vie_theorique' => 'nullable|integer|min:0',
        'duree_garantie' => 'nullable|integer|min:0',
        'etat' => 'required|in:neuf,bon,moyen,mauvais,hors_service',
        'type_maintenance' => 'required|in:preventive,curative,mixte',
        'localisation_id' => 'nullable|exists:localisations,id',
        'service_responsable_id' => 'nullable|exists:localisations,id',
        'fournisseur_id' => 'nullable|exists:fournisseurs,id',
        'contrat_id' => 'nullable|exists:contrats_maintenance,id',
        'commentaires' => 'nullable',
        'date_reforme' => 'nullable|date|after_or_equal:date_achat',
        'code_barres' => 'nullable|unique:equipements,code_barres,' . $equipement->id . '|max:100',
    ]);

    DB::beginTransaction();

    try {
        // Récupérer les anciennes valeurs AVANT la mise à jour
        $ancienneLocalisationId = $equipement->localisation_id;
        $nouvelleLocalisationId = $validated['localisation_id'] ?? null;

        $ancienServiceResponsableId = $equipement->service_responsable_id;
        $nouveauServiceResponsableId = $validated['service_responsable_id'] ?? null;

        // Ajouter l'utilisateur qui modifie
        $validated['updated_by'] = auth()->id();

        // Mettre à jour l'équipement
        $equipement->update($validated);

        // ============================================
        // TRACKER LE CHANGEMENT DE LOCALISATION
        // ============================================
        if ($ancienneLocalisationId != $nouvelleLocalisationId) {

            // Déterminer le motif détaillé
            $motifDetail = '';
            if ($ancienneLocalisationId === null && $nouvelleLocalisationId !== null) {
                $motifDetail = 'Affectation initiale de localisation';
            } elseif ($ancienneLocalisationId !== null && $nouvelleLocalisationId === null) {
                $motifDetail = 'Retrait de localisation';
            } else {
                $motifDetail = 'Changement de localisation';
            }

            $mouvementLocalisation = HistoriqueMouvement::create([
                'equipement_id' => $equipement->id,
                'date_mouvement' => now(),
                'ancienne_localisation_id' => $ancienneLocalisationId,
                'nouvelle_localisation_id' => $nouvelleLocalisationId,
                'motif' => $motifDetail,
                'operateur_id' => auth()->id(),
                'commentaire' => sprintf(
                    'Modification localisation physique - Passage de %s à %s',
                    $ancienneLocalisationId ? "localisation #{$ancienneLocalisationId}" : 'Aucune localisation',
                    $nouvelleLocalisationId ? "localisation #{$nouvelleLocalisationId}" : 'Aucune localisation'
                )
            ]);

            \Log::info('Mouvement de localisation créé', [
                'mouvement_id' => $mouvementLocalisation->id,
                'equipement_id' => $equipement->id,
                'ancienne_localisation' => $ancienneLocalisationId,
                'nouvelle_localisation' => $nouvelleLocalisationId,
            ]);
        }

        // ============================================
        // TRACKER LE CHANGEMENT DE SERVICE RESPONSABLE
        // ============================================
        if ($ancienServiceResponsableId != $nouveauServiceResponsableId) {

            // Déterminer le motif détaillé
            $motifService = '';
            if ($ancienServiceResponsableId === null && $nouveauServiceResponsableId !== null) {
                $motifService = 'Affectation initiale service responsable';
            } elseif ($ancienServiceResponsableId !== null && $nouveauServiceResponsableId === null) {
                $motifService = 'Retrait service responsable';
            } else {
                $motifService = 'Changement service responsable';
            }

            $mouvementService = HistoriqueMouvement::create([
                'equipement_id' => $equipement->id,
                'date_mouvement' => now(),
                'ancienne_localisation_id' => $ancienServiceResponsableId,
                'nouvelle_localisation_id' => $nouveauServiceResponsableId,
                'motif' => $motifService,
                'operateur_id' => auth()->id(),
                'commentaire' => sprintf(
                    'Modification service responsable - Passage de %s à %s',
                    $ancienServiceResponsableId ? "service #{$ancienServiceResponsableId}" : 'Aucun service',
                    $nouveauServiceResponsableId ? "service #{$nouveauServiceResponsableId}" : 'Aucun service'
                )
            ]);

            \Log::info('Mouvement de service responsable créé', [
                'mouvement_id' => $mouvementService->id,
                'equipement_id' => $equipement->id,
                'ancien_service' => $ancienServiceResponsableId,
                'nouveau_service' => $nouveauServiceResponsableId,
            ]);
        }

        // Log de l'activité
        LogActivite::create([
            'id_utilisateur' => auth()->id(),
            'date_heure' => now(),
            'action' => 'modification_equipement',
            'module' => 'inventaire',
            'id_element' => $equipement->id,
            'adresse_ip' => $request->ip(),
            'details' => "Modification de l'équipement {$equipement->numero_inventaire}",
            'user_agent' => $request->userAgent(),
        ]);

        DB::commit();

        return redirect()->route('inventaire.equipements.show', $equipement)
            ->with('success', 'Équipement mis à jour avec succès.');

    } catch (\Exception $e) {
        DB::rollBack();

        \Log::error('Erreur mise à jour équipement', [
            'equipement_id' => $equipement->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * UC-INV-05 : Supprimer/réformer un équipement
     */
    public function destroy(Request $request, Equipement $equipement)
    {
        $request->validate([
            'motif_reforme' => 'required|string|max:255',
            'date_reforme' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            // Marquer comme réformé plutôt que supprimer
            $equipement->update([
                'etat' => 'hors_service',
                'date_reforme' => $request->date_reforme,
                'commentaires' => $equipement->commentaires . "\n\n[RÉFORME " . now()->format('d/m/Y') . "]\nMotif: " . $request->motif_reforme,
                'updated_by' => auth()->id()
            ]);

            // Log de l'activité
            LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'date_heure' => now(),
                'action' => 'reforme_equipement',
                'module' => 'inventaire',
                'id_element' => $equipement->id,
                'adresse_ip' => $request->ip(),
                'details' => "Réforme de l'équipement {$equipement->numero_inventaire}. Motif: {$request->motif_reforme}",
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('inventaire.equipements.index')
                ->with('success', 'Équipement marqué comme réformé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Erreur lors de la réforme : ' . $e->getMessage());
        }
    }

    /**
     * UC-INV-09 : Générer un rapport d'inventaire
     */
    public function genererRapport(Request $request)
    {
        $request->validate([
            'type_rapport' => 'required|in:inventaire_complet,localisation,etat,type,age',
            'format' => 'required|in:pdf,excel,csv',
            'localisation_id' => 'nullable|exists:localisations,id',
            'type_equipement_id' => 'nullable|exists:type_equipements,id',
            'etat' => 'nullable|string',
        ]);

        $query = Equipement::with(['typeEquipement', 'localisation', 'serviceResponsable', 'fournisseur']);

        // Appliquer les filtres
        if ($request->filled('localisation_id')) {
            $query->where('localisation_id', $request->localisation_id);
        }

        if ($request->filled('type_equipement_id')) {
            $query->where('type_equipement_id', $request->type_equipement_id);
        }

        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }

        $equipements = $query->get();

        $titre = "Rapport d'inventaire - " . now()->format('d/m/Y');

        if ($request->format == 'csv') {
            return $this->exportCSV($equipements, $titre);
        }

        // Pour PDF et Excel, utilisez les bibliothèques appropriées
        return redirect()->back()->with('info', 'Fonctionnalité en développement');
    }

    /**
     * UC-INV-10 : Scanner par code-barres
     */
    public function scanner(Request $request)
    {
        $code = $request->get('code');

        if (!$code) {
            return view('inventaire.equipements.scanner');
        }

        $equipement = Equipement::where('code_barres', $code)
            ->orWhere('numero_inventaire', $code)
            ->orWhere('numero_serie', $code)
            ->first();

        if (!$equipement) {
            return redirect()->route('inventaire.equipements.scanner')
                ->with('error', 'Équipement non trouvé avec ce code: ' . $code);
        }

        return redirect()->route('inventaire.equipements.show', $equipement);
    }

    /**
     * UC-INV-06 : Générer une étiquette code-barres
     */

public function genererEtiquette(Equipement $equipement)
{
    // Charger les relations
    $equipement->load(['typeEquipement', 'localisation', 'serviceResponsable', 'fournisseur']);

    // QR Code = URL directe vers la page de scan
    $qrContent = route('inventaire.equipements.scan', $equipement->code_barres);

    // Générer le QR code
    $qrSvg = QrCode::format('svg')
        ->size(300)
        ->margin(1)
        ->errorCorrection('H')
        ->generate($qrContent);

    $qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

    $data = [
        'equipement'     => $equipement,
        'qrCode'         => $qrCodeBase64,
        'dateGeneration' => now()->format('d/m/Y H:i'),
        'generateur'     => auth()->user()->name ?? 'Système',
    ];

    return Pdf::loadView('inventaire.equipements.etiquette', $data)
        ->setPaper('a5', 'portrait')
        ->download('etiquette_' . $equipement->numero_inventaire . '.pdf');
}


/**
 * Page de scan QR - Affiche les infos de l'équipement
 */
public function scanQR($code)
{
    $equipement = Equipement::where('code_barres', $code)
        ->orWhere('numero_inventaire', $code)
        ->with(['typeEquipement', 'localisation', 'serviceResponsable', 'fournisseur'])
        ->firstOrFail();

    return view('inventaire.equipements.scan', compact('equipement'));
}

    /**
     * Export CSV
     */
    private function exportCSV($equipements, $titre)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="equipements_' . now()->format('Ymd_His') . '.csv"',
        ];

        $callback = function() use ($equipements) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM pour UTF-8

            // En-têtes
            fputcsv($file, [
                'N° Inventaire',
                'N° Série',
                'Marque',
                'Modèle',
                'Type',
                'Classe',
                'État',
                'Localisation',
                'Service',
                'Fournisseur',
                'Date achat',
                'Prix (FCFA)',
                'Code-barres',
                'Contrat',
                'Date mise service',
                'Garantie (mois)'
            ]);

            // Données
            foreach ($equipements as $equipement) {
                fputcsv($file, [
                    $equipement->numero_inventaire,
                    $equipement->numero_serie ?? '',
                    $equipement->marque,
                    $equipement->modele,
                    $equipement->typeEquipement->libelle ?? '',
                    $equipement->classe_equipement ?? '',
                    $equipement->etat,
                    $equipement->localisation->nom ?? '',
                    $equipement->serviceResponsable->nom ?? '',
                    $equipement->fournisseur->raison_sociale ?? '',
                    $equipement->date_achat->format('d/m/Y'),
                    number_format($equipement->prix_achat ?? 0, 0, ',', ' '),
                    $equipement->code_barres ?? '',
                    $equipement->contrat->numero_contrat ?? '',
                    $equipement->date_mise_service?->format('d/m/Y') ?? '',
                    $equipement->duree_garantie ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Méthodes utilitaires
     */
    private function getStatistiques()
    {
        $total = Equipement::count();
        $actif = Equipement::where('etat', '!=', 'hors_service')->count();
        $hors_service = Equipement::where('etat', 'hors_service')->count();

        // Équipements sous garantie (achetés il y a moins de X mois)
        $sous_garantie = Equipement::whereHas('typeEquipement', function($query) {
            $query->where('duree_garantie', '>', 0);
        })->orWhere('duree_garantie', '>', 0)->count();

        // Par type
        $parType = Equipement::select('type_equipement_id', DB::raw('count(*) as total'))
            ->groupBy('type_equipement_id')
            ->with('typeEquipement')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->typeEquipement->libelle ?? 'Inconnu' => $item->total];
            });

        // Par état
        $parEtat = Equipement::select('etat', DB::raw('count(*) as total'))
            ->groupBy('etat')
            ->get()
            ->mapWithKeys(function($item) {
                $etats = ['neuf' => 'Neuf', 'bon' => 'Bon', 'moyen' => 'Moyen', 'mauvais' => 'Mauvais', 'hors_service' => 'Hors service'];
                return [$etats[$item->etat] ?? $item->etat => $item->total];
            });

        // Par localisation
        $parLocalisation = Equipement::select('localisation_id', DB::raw('count(*) as total'))
            ->groupBy('localisation_id')
            ->with('localisation')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->localisation->nom ?? 'Non localisé' => $item->total];
            });

        // Âge des équipements
        $ageCategories = [
            '0-1 an' => 0,
            '1-3 ans' => 0,
            '3-5 ans' => 0,
            '5-10 ans' => 0,
            '>10 ans' => 0
        ];

        $equipementsAge = Equipement::whereNotNull('date_achat')->get();
        foreach ($equipementsAge as $equipement) {
            $age = $this->calculateAge($equipement->date_achat);

            if ($age <= 1) $ageCategories['0-1 an']++;
            elseif ($age <= 3) $ageCategories['1-3 ans']++;
            elseif ($age <= 5) $ageCategories['3-5 ans']++;
            elseif ($age <= 10) $ageCategories['5-10 ans']++;
            else $ageCategories['>10 ans']++;
        }

        return [
            'total' => $total,
            'actif' => $actif,
            'hors_service' => $hors_service,
            'sous_garantie' => $sous_garantie,
            'par_type' => $parType,
            'par_etat' => $parEtat,
            'par_localisation' => $parLocalisation,
            'par_age' => $ageCategories,
        ];
    }

    private function calculateAge($dateAchat)
    {
        if (!$dateAchat) return 0;
        return Carbon::parse($dateAchat)->diffInYears(Carbon::now());
    }

    private function estSousGarantie($dateAchat, $dureeGarantie)
    {
        if (!$dateAchat || !$dureeGarantie) return false;

        $dateFinGarantie = Carbon::parse($dateAchat)->addMonths($dureeGarantie);
        return $dateFinGarantie->isFuture();
    }

    private function getDateFinGarantie($dateAchat, $dureeGarantie)
    {
        if (!$dateAchat || !$dureeGarantie) return null;
        return Carbon::parse($dateAchat)->addMonths($dureeGarantie)->format('d/m/Y');
    }

    private function getTempsRestantVie($dateAchat, $dureeVie)
    {
        if (!$dateAchat || !$dureeVie) return null;

        $ageMois = Carbon::parse($dateAchat)->diffInMonths(Carbon::now());
        return max(0, $dureeVie - $ageMois);
    }
}
