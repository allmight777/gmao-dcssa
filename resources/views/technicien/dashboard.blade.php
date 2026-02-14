@extends('layouts.admin')

@section('title', 'Dashboard Technicien')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Technique
        </h1>
        <div>
            <span class="badge badge-info p-2">
                <i class="fas fa-calendar mr-1"></i>{{ now()->format('d/m/Y') }}
            </span>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row">
        <!-- Demandes en attente -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Demandes en attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['en_attente'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demandes validées -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Demandes validées</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['validees'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interventions en cours -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Interventions en cours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $interventionsEnCours->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wrench fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demandes critiques -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Demandes critiques</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['critiques'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row">
        <!-- Graphique par urgence -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie mr-2"></i>Répartition par urgence
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="urgenceChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Évolution des demandes -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line mr-2"></i>Évolution (30 derniers jours)
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="evolutionChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Demandes récentes -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history mr-2"></i>Demandes récentes
                    </h6>
                    <a href="{{ route('technicien.demandes.index') }}" class="btn btn-sm btn-primary">
                        Voir toutes les demandes
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>N° Demande</th>
                                    <th>Date</th>
                                    <th>Demandeur</th>
                                    <th>Équipement</th>
                                    <th>Urgence</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($demandesRecentes as $demande)
                                <tr>
                                    <td>
                                        <span class="font-weight-bold">{{ $demande->Numero_Demande }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($demande->Date_Demande)->format('d/m/Y') }}</td>
                                    <td>{{ $demande->demandeur->nom }} {{ $demande->demandeur->prenom }}</td>
                                    <td>{{ $demande->equipement->nom }}</td>
                                    <td>
                                        @if($demande->Urgence == 'critique')
                                            <span class="badge badge-danger">Critique</span>
                                        @elseif($demande->Urgence == 'urgente')
                                            <span class="badge badge-warning">Urgente</span>
                                        @else
                                            <span class="badge badge-info">Normale</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($demande->Statut == 'en_attente')
                                            <span class="badge badge-warning">En attente</span>
                                        @elseif($demande->Statut == 'validee')
                                            <span class="badge badge-success">Validée</span>
                                        @elseif($demande->Statut == 'en_cours')
                                            <span class="badge badge-primary">En cours</span>
                                        @elseif($demande->Statut == 'terminee')
                                            <span class="badge badge-secondary">Terminée</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('technicien.demandes.show', $demande->ID_Demande) }}"
                                           class="btn btn-sm btn-info" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($demande->Statut == 'validee')
                                        <a href="{{ route('technicien.interventions.planifier', $demande->ID_Demande) }}"
                                           class="btn btn-sm btn-success" title="Planifier">
                                            <i class="fas fa-calendar-plus"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Aucune demande récente</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interventions en cours -->
    @if($interventionsEnCours->count() > 0)
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tasks mr-2"></i>Interventions en cours
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Demande</th>
                                    <th>Équipement</th>
                                    <th>Début prévu</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($interventionsEnCours as $intervention)
                                <tr>
                                    <td>{{ $intervention->demande->Numero_Demande }}</td>
                                    <td>{{ $intervention->demande->equipement->nom }}</td>
                                    <td>{{ \Carbon\Carbon::parse($intervention->Date_Debut)->format('d/m/Y') }} à {{ $intervention->Heure_Debut }}</td>
                                    <td>
                                        <a href="{{ route('technicien.interventions.show', $intervention->ID_Intervention) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                        <a href="{{ route('technicien.interventions.rapport', $intervention->ID_Intervention) }}"
                                           class="btn btn-sm btn-success">
                                            <i class="fas fa-file-alt"></i> Rapport
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique par urgence
    const urgenceCtx = document.getElementById('urgenceChart').getContext('2d');
    new Chart(urgenceCtx, {
        type: 'pie',
        data: {
            labels: ['Normale', 'Urgente', 'Critique'],
            datasets: [{
                data: [
                    {{ $parUrgence->where('Urgence', 'normale')->first()->total ?? 0 }},
                    {{ $parUrgence->where('Urgence', 'urgente')->first()->total ?? 0 }},
                    {{ $parUrgence->where('Urgence', 'critique')->first()->total ?? 0 }}
                ],
                backgroundColor: ['#36b9cc', '#f6c23e', '#e74a3b'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Graphique d'évolution
    const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
    new Chart(evolutionCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($evolution->pluck('date')->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('d/m');
            })) !!},
            datasets: [{
                label: 'Nombre de demandes',
                data: {!! json_encode($evolution->pluck('total')) !!},
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    });
</script>
@endpush
@endsection
