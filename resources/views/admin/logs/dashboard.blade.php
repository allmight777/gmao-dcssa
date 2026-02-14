@extends('layouts.admin')

@section('title', 'Dashboard des Logs')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line mr-2"></i>Dashboard des Logs
        </h1>
        <div>
            <a href="{{ route('admin.logs.export') }}" class="btn btn-sm btn-success">
                <i class="fas fa-file-csv mr-1"></i>Exporter CSV
            </a>
            <a href="{{ route('admin.logs.fichier') }}" class="btn btn-sm btn-info">
                <i class="fas fa-file-alt mr-1"></i>Voir fichier log
            </a>
            <button onclick="refreshData()" class="btn btn-sm btn-primary">
                <i class="fas fa-sync-alt mr-1"></i>Rafraîchir
            </button>
        </div>
    </div>

    <!-- Statistiques (inchangées) -->
    <!-- ... -->

    <!-- Graphiques avec vérifications -->
    <div class="row">
        <!-- Graphique 1: Actions par jour -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Évolution des actions (30 derniers jours)</h6>
                </div>
                <div class="card-body">
                    @if($actionsParJour->count() > 0)
                        <canvas id="actionsParJourChart" height="300"></canvas>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Aucune donnée disponible pour cette période
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Graphique 2: Répartition par module -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Activité par module</h6>
                </div>
                <div class="card-body">
                    @if($modules->count() > 0)
                        <canvas id="modulesChart" height="300"></canvas>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Aucune donnée disponible
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Graphique 3: Répartition par action -->
        <div class="col-xl-4 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Types d'actions</h6>
                </div>
                <div class="card-body">
                    @if($actions->count() > 0)
                        <canvas id="actionsChart" height="250"></canvas>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Aucune donnée disponible
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Graphique 4: Top utilisateurs -->
        <div class="col-xl-4 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 10 utilisateurs actifs</h6>
                </div>
                <div class="card-body">
                    @if($usersActifs->count() > 0)
                        <canvas id="usersChart" height="250"></canvas>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Aucune donnée disponible
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Graphique 5: Activité par heure -->
        <div class="col-xl-4 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Activité par heure (7 derniers jours)</h6>
                </div>
                <div class="card-body">
                    @if($activiteParHeure->count() > 0)
                        <canvas id="heuresChart" height="250"></canvas>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Aucune donnée disponible
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Graphique 6: Connexions vs Déconnexions -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Connexions vs Déconnexions (30 jours)</h6>
                </div>
                <div class="card-body">
                    @if($connexions->count() > 0)
                        <canvas id="connexionsChart" height="250"></canvas>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Aucune donnée disponible
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Graphique 7: Actions critiques -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions critiques (7 derniers jours)</h6>
                </div>
                <div class="card-body">
                    @if($actionsCritiques->count() > 0)
                        <canvas id="critiquesChart" height="250"></canvas>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Aucune donnée disponible
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Dernières activités (inchangé) -->
    <!-- ... -->
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Graphique Actions par Jour
    @if($actionsParJour->count() > 0)
    try {
        new Chart(document.getElementById('actionsParJourChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($actionsParJour->pluck('date')->map(function($d) {
                    return $d ? \Carbon\Carbon::parse($d)->format('d/m') : '';
                })) !!},
                datasets: [{
                    label: 'Actions',
                    data: {!! json_encode($actionsParJour->pluck('total')) !!},
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true },
                    tooltip: { enabled: true }
                }
            }
        });
    } catch(e) { console.error('Erreur graphique 1:', e); }
    @endif

    // 2. Graphique Modules
    @if($modules->count() > 0)
    try {
        new Chart(document.getElementById('modulesChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($modules->pluck('module')->filter()) !!},
                datasets: [{
                    data: {!! json_encode($modules->pluck('total')) !!},
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    } catch(e) { console.error('Erreur graphique 2:', e); }
    @endif

    // 3. Graphique Actions
    @if($actions->count() > 0)
    try {
        new Chart(document.getElementById('actionsChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($actions->pluck('action')->filter()) !!},
                datasets: [{
                    data: {!! json_encode($actions->pluck('total')) !!},
                    backgroundColor: ['#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#4e73df', '#858796']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    } catch(e) { console.error('Erreur graphique 3:', e); }
    @endif

    // 4. Graphique Top Utilisateurs
    @if($usersActifs->count() > 0)
    try {
        new Chart(document.getElementById('usersChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($usersActifs->map(function($u) {
                    return $u->utilisateur ? $u->utilisateur->nom : 'Inconnu';
                })) !!},
                datasets: [{
                    label: "Nombre d'actions",
                    data: {!! json_encode($usersActifs->pluck('total')) !!},
                    backgroundColor: '#36b9cc'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    } catch(e) { console.error('Erreur graphique 4:', e); }
    @endif

    // 5. Graphique Activité par Heure
    @if($activiteParHeure->count() > 0)
    try {
        new Chart(document.getElementById('heuresChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($activiteParHeure->pluck('heure')->map(function($h) {
                    return $h ? $h . 'h' : '';
                })) !!},
                datasets: [{
                    label: 'Activité',
                    data: {!! json_encode($activiteParHeure->pluck('total')) !!},
                    borderColor: '#f6c23e',
                    backgroundColor: 'rgba(246, 194, 62, 0.1)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    } catch(e) { console.error('Erreur graphique 5:', e); }
    @endif

    // 6. Graphique Connexions
    @if($connexions->count() > 0)
    try {
        const connexionsData = {!! json_encode($connexions) !!};
        const dates = [...new Set(connexionsData.map(c => c.date))].sort();

        new Chart(document.getElementById('connexionsChart'), {
            type: 'line',
            data: {
                labels: dates.map(d => d ? new Date(d).toLocaleDateString('fr-FR', {day:'2-digit', month:'2-digit'}) : ''),
                datasets: [
                    {
                        label: 'Connexions',
                        data: dates.map(d => connexionsData.find(c => c.date === d && c.action === 'login')?.total || 0),
                        borderColor: '#1cc88a',
                        tension: 0.3
                    },
                    {
                        label: 'Déconnexions',
                        data: dates.map(d => connexionsData.find(c => c.date === d && c.action === 'logout')?.total || 0),
                        borderColor: '#e74a3b',
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    } catch(e) { console.error('Erreur graphique 6:', e); }
    @endif

    // 7. Graphique Actions Critiques
    @if($actionsCritiques->count() > 0)
    try {
        new Chart(document.getElementById('critiquesChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($actionsCritiques->pluck('date')->map(function($d) {
                    return $d ? \Carbon\Carbon::parse($d)->format('d/m') : '';
                })) !!},
                datasets: [{
                    label: 'Actions critiques',
                    data: {!! json_encode($actionsCritiques->pluck('total')) !!},
                    backgroundColor: '#e74a3b'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    } catch(e) { console.error('Erreur graphique 7:', e); }
    @endif
});

function refreshData() {
    location.reload();
}
</script>
@endpush
@endsection
