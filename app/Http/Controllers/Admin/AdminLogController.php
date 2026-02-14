<?php
// app/Http/Controllers/Admin/AdminLogController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogActivite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminLogController extends Controller
{
    /**
     * Afficher le tableau de bord des logs avec graphiques
     */
    public function index(Request $request)
    {
        // Statistiques générales
        $stats = [
            'total_logs' => LogActivite::count(),
            'total_users' => User::count(),
            'logs_aujourdhui' => LogActivite::whereDate('date_heure', today())->count(),
            'logs_semaine' => LogActivite::where('date_heure', '>=', now()->subDays(7))->count(),
            'logs_mois' => LogActivite::where('date_heure', '>=', now()->subDays(30))->count(),
        ];

        // 1. Graphique: Actions par jour (30 derniers jours)
        $actionsParJour = LogActivite::where('date_heure', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(date_heure) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 2. Graphique: Répartition par module
        $modules = LogActivite::select('module', DB::raw('count(*) as total'))
            ->groupBy('module')
            ->orderByDesc('total')
            ->get();

        // 3. Graphique: Répartition par action
        $actions = LogActivite::select('action', DB::raw('count(*) as total'))
            ->groupBy('action')
            ->orderByDesc('total')
            ->get();

        // 4. Graphique: Top 10 utilisateurs les plus actifs
        $usersActifs = LogActivite::select('id_utilisateur', DB::raw('count(*) as total'))
            ->with('utilisateur')
            ->groupBy('id_utilisateur')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // 5. Graphique: Activité par heure (24h)
        $activiteParHeure = LogActivite::where('date_heure', '>=', now()->subDays(7))
            ->select(DB::raw('HOUR(date_heure) as heure'), DB::raw('count(*) as total'))
            ->groupBy('heure')
            ->orderBy('heure')
            ->get();

        // 6. Graphique: Évolution des connexions (login/logout)
        $connexions = LogActivite::whereIn('action', ['login', 'logout'])
            ->where('date_heure', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(date_heure) as date'), 'action', DB::raw('count(*) as total'))
            ->groupBy('date', 'action')
            ->orderBy('date')
            ->get();

        // 7. Graphique: Actions critiques (erreurs, échecs)
        $actionsCritiques = LogActivite::whereIn('action', ['error', 'echec', 'failed', 'rejetee'])
            ->where('date_heure', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(date_heure) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 8. Graphique: Modules les plus sollicités
        $modulesPopulaires = LogActivite::select('module', DB::raw('count(*) as total'))
            ->groupBy('module')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // Analyse du fichier laravel.log
        $logFile = storage_path('logs/laravel.log');
        $logStats = $this->analyzeLaravelLog($logFile);

        // Dernières activités
        $dernieresActivites = LogActivite::with('utilisateur')
            ->orderByDesc('date_heure')
            ->limit(20)
            ->get();



            
        return view('admin.logs.dashboard', compact(
            'stats',
            'actionsParJour',
            'modules',
            'actions',
            'usersActifs',
            'activiteParHeure',
            'connexions',
            'actionsCritiques',
            'modulesPopulaires',
            'logStats',
            'dernieresActivites'
        ));
    }

    /**
     * Analyser le fichier laravel.log
     */
    private function analyzeLaravelLog($logFile)
    {
        $stats = [
            'total_errors' => 0,
            'errors_par_jour' => [],
            'types_erreur' => [],
            'dernieres_erreurs' => [],
            'taille_fichier' => 0,
            'date_modification' => null,
        ];

        if (File::exists($logFile)) {
            $stats['taille_fichier'] = File::size($logFile);
            $stats['date_modification'] = File::lastModified($logFile);

            // Lire les 1000 dernières lignes
            $lines = $this->tailFile($logFile, 1000);

            foreach ($lines as $line) {
                // Compter les erreurs
                if (preg_match('/\[(.*?)\].*?\.(ERROR|CRITICAL|ALERT|EMERGENCY)/', $line, $matches)) {
                    $stats['total_errors']++;

                    // Par jour
                    $date = substr($matches[1], 0, 10);
                    $stats['errors_par_jour'][$date] = ($stats['errors_par_jour'][$date] ?? 0) + 1;

                    // Type d'erreur
                    if (preg_match('/: (.*?) in /', $line, $typeMatch)) {
                        $type = $typeMatch[1];
                        $stats['types_erreur'][$type] = ($stats['types_erreur'][$type] ?? 0) + 1;
                    }

                    // Dernières erreurs
                    if (count($stats['dernieres_erreurs']) < 10) {
                        $stats['dernieres_erreurs'][] = [
                            'date' => $matches[1],
                            'niveau' => $matches[2],
                            'message' => substr($line, 0, 200) . '...'
                        ];
                    }
                }
            }
        }

        return $stats;
    }

    /**
     * Lire les dernières lignes d'un fichier
     */
    private function tailFile($filepath, $lines = 100)
    {
        $f = fopen($filepath, "rb");
        if ($f === false) return [];

        fseek($f, -1, SEEK_END);
        $pos = ftell($f);
        $data = "";
        $lineCount = 0;

        while ($pos > 0 && $lineCount < $lines) {
            $char = fgetc($f);
            if ($char === "\n") {
                $lineCount++;
                if ($lineCount <= $lines) {
                    $data = $char . $data;
                }
            } else {
                $data = $char . $data;
            }
            fseek($f, --$pos);
        }

        fclose($f);
        return explode("\n", trim($data));
    }

    /**
     * Exporter les logs au format CSV
     */
    public function export(Request $request)
    {
        $query = LogActivite::with('utilisateur')
            ->orderByDesc('date_heure');

        // Filtres
        if ($request->filled('date_debut')) {
            $query->whereDate('date_heure', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_heure', '<=', $request->date_fin);
        }
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->get();

        $filename = 'logs_activite_' . date('Y-m-d_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

        // En-têtes
        fputcsv($output, [
            'ID',
            'Date/Heure',
            'Utilisateur',
            'Action',
            'Module',
            'Élément',
            'Adresse IP',
            'User Agent',
            'Détails'
        ], ';');

        // Données
        foreach ($logs as $log) {
            fputcsv($output, [
                $log->id,
                $log->date_heure,
                $log->utilisateur ? $log->utilisateur->nom . ' ' . $log->utilisateur->prenom : 'Système',
                $log->action,
                $log->module,
                $log->id_element,
                $log->adresse_ip,
                $log->user_agent,
                $log->details
            ], ';');
        }

        fclose($output);
        exit;
    }

    /**
     * Voir les détails du fichier log
     */
    public function viewLogFile(Request $request)
    {
        $logFile = storage_path('logs/laravel.log');

        if (!File::exists($logFile)) {
            return redirect()->route('admin.logs.dashboard')
                ->with('error', 'Fichier log non trouvé');
        }

        $lines = $this->tailFile($logFile, 500);
        $fileInfo = [
            'taille' => File::size($logFile),
            'modification' => File::lastModified($logFile),
            'lignes' => count(file($logFile))
        ];

        // Filtrer par niveau d'erreur si demandé
        if ($request->filled('niveau')) {
            $lines = array_filter($lines, function($line) use ($request) {
                return stripos($line, $request->niveau) !== false;
            });
        }

        return view('admin.logs.view-file', compact('lines', 'fileInfo'));
    }

    /**
     * Nettoyer les logs (vider le fichier)
     */
    public function clearLogs(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Action non autorisée');
        }

        $logFile = storage_path('logs/laravel.log');

        if (File::exists($logFile)) {
            File::put($logFile, '');

            // Logger l'action
            LogActivite::create([
                'id_utilisateur' => auth()->id(),
                'action' => 'clear_logs',
                'module' => 'administration',
                'adresse_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'details' => 'Nettoyage du fichier de logs'
            ]);

            return redirect()->route('admin.logs.dashboard')
                ->with('success', 'Fichier de logs nettoyé avec succès');
        }

        return redirect()->back()->with('error', 'Fichier log non trouvé');
    }
}
