<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContratMaintenance;
use App\Models\Fournisseur;
use App\Models\Equipement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContratExpirationAlert;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;
use Carbon\Carbon;

class ContratMaintenanceController extends Controller
{

    /**
     * Affiche la liste des contrats avec statistiques
     */
    public function index(Request $request)
    {
        $query = ContratMaintenance::with('fournisseur', 'createur')
                    ->withCount('equipements');

        // Filtres
        if ($request->filled('statut')) {
            $query->where('Statut', $request->statut);
        }

        if ($request->filled('fournisseur_id')) {
            $query->where('ID_Fournisseur', $request->fournisseur_id);
        }

        if ($request->filled('type')) {
            $query->where('Type', $request->type);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('Date_Debut', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('Date_Fin', '<=', $request->date_fin);
        }

        if ($request->filled('recherche')) {
            $search = $request->recherche;
            $query->where(function ($q) use ($search) {
                $q->where('Numero_Contrat', 'like', "%{$search}%")
                  ->orWhere('Libelle', 'like', "%{$search}%")
                  ->orWhereHas('fournisseur', function ($f) use ($search) {
                      $f->where('raison_sociale', 'like', "%{$search}%");
                  });
            });
        }

        $contrats = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistiques pour les charts
        $statistiques = $this->getStatistiques();

        // Contrats expirant dans les 7 jours
        $contratsExpirants = ContratMaintenance::expirant(7)
                              ->with('fournisseur')
                              ->get();

        // Fournisseurs pour le filtre
        $fournisseurs = Fournisseur::where('statut', 'actif')->orderBy('raison_sociale')->get();

        return view('admin.contrats.index', compact(
            'contrats',
            'statistiques',
            'contratsExpirants',
            'fournisseurs'
        ));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $fournisseurs = Fournisseur::where('statut', 'actif')->orderBy('raison_sociale')->get();
       $equipements = Equipement::all();


        return view('admin.contrats.create', compact('fournisseurs', 'equipements'));
    }

    /**
     * Enregistre un nouveau contrat
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Libelle' => 'required|string|max:255',
            'Type' => 'required|in:preventive,corrective,globale,garantie,autre',
            'Date_Debut' => 'required|date',
            'Date_Fin' => 'required|date|after:Date_Debut',
            'Montant' => 'required|numeric|min:0',
            'Devise' => 'required|string|size:3',
            'Periodicite_Interventions' => 'required|in:hebdomadaire,mensuelle,trimestrielle,semestrielle,annuelle,ponctuelle',
            'Delai_Intervention_Garanti' => 'required|integer|min:1',
            'ID_Fournisseur' => 'required|exists:fournisseurs,id',
            'Couverture_Pieces' => 'sometimes|boolean',
            'Couverture_Main_Oeuvre' => 'sometimes|boolean',
            'Exclusions' => 'nullable|string',
            'Date_Alerte_Renouvellement' => 'nullable|date',
            'Conditions_Particulieres' => 'nullable|string',
            'Notes_Internes' => 'nullable|string',
            'equipements' => 'nullable|array',
            'equipements.*' => 'exists:equipements,id',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:10240'
        ]);

        $validated['Numero_Contrat'] = ContratMaintenance::genererNumeroContrat();
        $validated['cree_par'] = Auth::id();
        $validated['Statut'] = 'brouillon';

        // Gestion du document
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $path = $file->store('contrats', 'public');
            $validated['chemin_document'] = $path;
            $validated['fichier_original'] = $file->getClientOriginalName();
        }

        $contrat = ContratMaintenance::create($validated);

        // Attacher les équipements
        if ($request->has('equipements')) {
            $contrat->equipements()->attach($request->equipements);
        }

        return redirect()->route('admin.contrats.show', $contrat->ID_Contrat)
                         ->with('success', 'Contrat créé avec succès.');
    }

    /**
     * Affiche les détails d'un contrat
     */
    public function show($id)
    {
        $contrat = ContratMaintenance::with([
            'fournisseur',
            'createur',
            'moderateur',
            'equipements' => function ($q) {
                $q->with('type', 'localisation');
            }
        ])->findOrFail($id);

        return view('admin.contrats.show', compact('contrat'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $contrat = ContratMaintenance::with('equipements')->findOrFail($id);
        $fournisseurs = Fournisseur::where('statut', 'actif')->orderBy('raison_sociale')->get();
        $equipements = Equipement::all();


        $equipementsSelectionnes = $contrat->equipements->pluck('id')->toArray();

        return view('admin.contrats.edit', compact('contrat', 'fournisseurs', 'equipements', 'equipementsSelectionnes'));
    }

    /**
     * Met à jour un contrat
     */
    public function update(Request $request, $id)
    {
        $contrat = ContratMaintenance::findOrFail($id);

        $validated = $request->validate([
            'Libelle' => 'required|string|max:255',
            'Type' => 'required|in:preventive,corrective,globale,garantie,autre',
            'Date_Debut' => 'required|date',
            'Date_Fin' => 'required|date|after:Date_Debut',
            'Montant' => 'required|numeric|min:0',
            'Devise' => 'required|string|size:3',
            'Periodicite_Interventions' => 'required|in:hebdomadaire,mensuelle,trimestrielle,semestrielle,annuelle,ponctuelle',
            'Delai_Intervention_Garanti' => 'required|integer|min:1',
            'ID_Fournisseur' => 'required|exists:fournisseurs,id',
            'Couverture_Pieces' => 'sometimes|boolean',
            'Couverture_Main_Oeuvre' => 'sometimes|boolean',
            'Exclusions' => 'nullable|string',
            'Date_Alerte_Renouvellement' => 'nullable|date',
            'Conditions_Particulieres' => 'nullable|string',
            'Notes_Internes' => 'nullable|string',
            'equipements' => 'nullable|array',
            'equipements.*' => 'exists:equipements,id',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:10240'
        ]);

        $validated['modifie_par'] = Auth::id();

        if ($request->hasFile('document')) {
            // Supprimer l'ancien document
            if ($contrat->chemin_document) {
                Storage::disk('public')->delete($contrat->chemin_document);
            }

            $file = $request->file('document');
            $path = $file->store('contrats', 'public');
            $validated['chemin_document'] = $path;
            $validated['fichier_original'] = $file->getClientOriginalName();
        }

        $contrat->update($validated);

        // Synchroniser les équipements
        $contrat->equipements()->sync($request->equipements ?? []);

        return redirect()->route('admin.contrats.show', $contrat->ID_Contrat)
                         ->with('success', 'Contrat mis à jour avec succès.');
    }

    /**
     * Change le statut du contrat
     */
    public function changerStatut(Request $request, $id)
    {
        $contrat = ContratMaintenance::findOrFail($id);

        $request->validate([
            'statut' => 'required|in:actif,expire,resilie,renouvellement_attente,brouillon'
        ]);

        $oldStatut = $contrat->Statut;
        $contrat->Statut = $request->statut;
        $contrat->modifie_par = Auth::id();
        $contrat->save();

        $message = "Statut du contrat changé de '{$oldStatut}' à '{$request->statut}'";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Supprime un contrat
     */
    public function destroy($id)
    {
        $contrat = ContratMaintenance::findOrFail($id);

        // Supprimer le document associé
        if ($contrat->chemin_document) {
            Storage::disk('public')->delete($contrat->chemin_document);
        }

        $contrat->delete();

        return redirect()->route('admin.contrats.index')
                         ->with('success', 'Contrat supprimé avec succès.');
    }

    /**
     * Génère un PDF du contrat
     */
    public function generatePdf($id)
    {
        $contrat = ContratMaintenance::with([
            'fournisseur',
            'equipements',
            'createur'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.contrat', compact('contrat'));

        return $pdf->download('contrat_' . $contrat->Numero_Contrat . '.pdf');
    }

    /**
     * Génère un rapport PDF avec tous les contrats
     */
    public function generateRapport(Request $request)
    {
        $query = ContratMaintenance::with('fournisseur');

        if ($request->filled('statut')) {
            $query->where('Statut', $request->statut);
        }

        if ($request->filled('fournisseur_id')) {
            $query->where('ID_Fournisseur', $request->fournisseur_id);
        }

        $contrats = $query->orderBy('Date_Fin')->get();

        $pdf = Pdf::loadView('pdf.rapport-contrats', compact('contrats'));

        return $pdf->download('rapport_contrats_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Exporte tous les contrats en ZIP avec leurs PDFs
     */
    public function exportZip()
    {
        $contrats = ContratMaintenance::with('fournisseur')->get();

        $zip = new ZipArchive();
        $fileName = 'contrats_' . date('Y-m-d_His') . '.zip';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);

        if ($zip->open($tempFile, ZipArchive::CREATE) === TRUE) {
            foreach ($contrats as $contrat) {
                // Générer le PDF pour chaque contrat
                $pdf = Pdf::loadView('pdf.contrat', compact('contrat'));
                $pdfContent = $pdf->output();

                // Ajouter au zip
                $zip->addFromString('contrat_' . $contrat->Numero_Contrat . '.pdf', $pdfContent);

                // Ajouter le document original s'il existe
                if ($contrat->chemin_document && Storage::disk('public')->exists($contrat->chemin_document)) {
                    $fileContent = Storage::disk('public')->get($contrat->chemin_document);
                    $zip->addFromString('documents/' . $contrat->fichier_original, $fileContent);
                }
            }

            $zip->close();
        }

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Récupère les statistiques pour les charts
     */
    private function getStatistiques()
    {
        $now = Carbon::now();

        // Contrats par statut
        $parStatut = ContratMaintenance::selectRaw('Statut, count(*) as total')
                     ->groupBy('Statut')
                     ->get();

        // Contrats par fournisseur (top 5)
        $parFournisseur = ContratMaintenance::selectRaw('ID_Fournisseur, count(*) as total')
                          ->with('fournisseur')
                          ->groupBy('ID_Fournisseur')
                          ->orderByDesc('total')
                          ->limit(5)
                          ->get()
                          ->map(function ($item) {
                              return [
                                  'fournisseur' => $item->fournisseur->raison_sociale ?? 'N/A',
                                  'total' => $item->total
                              ];
                          });

        // Évolution mensuelle des créations
        $evolution = ContratMaintenance::selectRaw('YEAR(created_at) as annee, MONTH(created_at) as mois, count(*) as total')
                     ->whereYear('created_at', $now->year)
                     ->groupBy('annee', 'mois')
                     ->orderBy('annee')
                     ->orderBy('mois')
                     ->get();

        // Contrats expirant dans les 30 jours
        $expirantBientot = ContratMaintenance::where('Statut', 'actif')
                           ->whereDate('Date_Fin', '<=', $now->copy()->addDays(30))
                           ->whereDate('Date_Fin', '>', $now)
                           ->count();

        // Montant total des contrats actifs
        $montantTotalActifs = ContratMaintenance::where('Statut', 'actif')
                              ->sum('Montant');

        return [
            'parStatut' => $parStatut,
            'parFournisseur' => $parFournisseur,
            'evolution' => $evolution,
            'expirantBientot' => $expirantBientot,
            'montantTotalActifs' => $montantTotalActifs,
            'totalContrats' => ContratMaintenance::count(),
            'contratsActifs' => ContratMaintenance::where('Statut', 'actif')->count()
        ];
    }

    /**
     * API pour les données des charts (AJAX)
     */
    public function chartData()
    {
        return response()->json($this->getStatistiques());
    }

    /**
     * Envoie manuellement les alertes d'expiration
     */
    public function sendExpirationAlerts()
    {
        $contratsAlerte = ContratMaintenance::avecAlerte()->get();
        $count = 0;

        foreach ($contratsAlerte as $contrat) {
            try {
                // Envoyer l'email au fournisseur
                if ($contrat->fournisseur && $contrat->fournisseur->email) {
                    Mail::to($contrat->fournisseur->email)->send(
                        new ContratExpirationAlert($contrat)
                    );
                }

                // Envoyer aussi aux administrateurs
                $admins = User::where('profil_id', 1)->get();
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(
                        new ContratExpirationAlert($contrat, $admin)
                    );
                }

                $contrat->update([
                    'Alerte_envoyee' => true,
                    'Date_derniere_alerte' => now()
                ]);

                $count++;
            } catch (\Exception $e) {
                \Log::error("Erreur envoi alerte contrat {$contrat->Numero_Contrat}: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', "{$count} alerte(s) envoyée(s) avec succès.");
    }
}
