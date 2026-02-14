@extends('layouts.welcome')

@section('content')
<div class="container-fluid py-4" style="width: 80%;">

    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="header-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-bold text-white">
                            <i class="fas fa-tasks me-2"></i>
                            Gestion des Demandes d'Intervention
                        </h4>
                        <p class="mb-0 text-white-50">
                            <i class="fas fa-building me-2"></i>Service: {{ $service->nom }}
                        </p>
                    </div>
                    <a href="{{ route('chef-division.dashboard') }}" class="btn btn-light-custom">
                        <i class="fas fa-chart-bar me-2"></i>Tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stats-label">Total</p>
                            <h3 class="stats-value">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stats-label">En Attente</p>
                            <h3 class="stats-value text-warning">{{ $stats['en_attente'] }}</h3>
                        </div>
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stats-label">Validées</p>
                            <h3 class="stats-value text-success">{{ $stats['validees'] }}</h3>
                        </div>
                        <div class="stats-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stats-label">Rejetées</p>
                            <h3 class="stats-value text-danger">{{ $stats['rejetees'] }}</h3>
                        </div>
                        <div class="stats-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <!-- Graphique circulaire - Statuts -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card-modern">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>
                        Répartition par Statut
                    </h6>
                </div>
                <div class="card-body-modern">
                    <canvas id="statutChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Graphique en barres - Urgence -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card-modern">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-success"></i>
                        Niveau d'Urgence
                    </h6>
                </div>
                <div class="card-body-modern">
                    <canvas id="urgenceChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Graphique donut - Types -->
        <div class="col-xl-4 col-lg-12 mb-4">
            <div class="card-modern">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-tools me-2 text-info"></i>
                        Types d'Intervention
                    </h6>
                </div>
                <div class="card-body-modern">
                    <canvas id="typeChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Évolution et Top demandeurs -->
    <div class="row mb-4">
        <!-- Évolution -->
        <div class="col-xl-8 mb-4">
            <div class="card-modern">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-line me-2 text-danger"></i>
                        Évolution des Demandes (6 derniers mois)
                    </h6>
                </div>
                <div class="card-body-modern">
                    <canvas id="evolutionChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Top demandeurs -->
        <div class="col-xl-4 mb-4">
            <div class="card-modern">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-users me-2 text-purple"></i>
                        Top 5 Demandeurs
                    </h6>
                </div>
                <div class="card-body-modern">
                    <canvas id="demandeursChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card-modern mb-4">
        <div class="card-header-modern">
            <h6 class="mb-0">
                <i class="fas fa-filter me-2"></i>
                Filtres
            </h6>
        </div>
        <div class="card-body-modern">
            <form method="GET" action="{{ route('chef-division.demandes.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label-custom">Statut</label>
                    <select name="statut" class="form-control-custom">
                        <option value="tous" {{ request('statut') == 'tous' ? 'selected' : '' }}>Tous</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="validee" {{ request('statut') == 'validee' ? 'selected' : '' }}>Validées</option>
                        <option value="rejetee" {{ request('statut') == 'rejetee' ? 'selected' : '' }}>Rejetées</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">Urgence</label>
                    <select name="urgence" class="form-control-custom">
                        <option value="tous" {{ request('urgence') == 'tous' ? 'selected' : '' }}>Tous</option>
                        <option value="normale" {{ request('urgence') == 'normale' ? 'selected' : '' }}>Normale</option>
                        <option value="urgente" {{ request('urgence') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                        <option value="critique" {{ request('urgence') == 'critique' ? 'selected' : '' }}>Critique</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">Type</label>
                    <select name="type" class="form-control-custom">
                        <option value="tous" {{ request('type') == 'tous' ? 'selected' : '' }}>Tous</option>
                        <option value="maintenance_preventive" {{ request('type') == 'maintenance_preventive' ? 'selected' : '' }}>Maintenance préventive</option>
                        <option value="maintenance_corrective" {{ request('type') == 'maintenance_corrective' ? 'selected' : '' }}>Maintenance corrective</option>
                        <option value="reparation" {{ request('type') == 'reparation' ? 'selected' : '' }}>Réparation</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn-filter flex-grow-1">
                        <i class="fas fa-search me-2"></i>Filtrer
                    </button>
                    <a href="{{ route('chef-division.demandes.index') }}" class="btn-reset">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des demandes -->
    <div class="card-modern">
        <div class="card-header-modern">
            <h6 class="mb-0">
                <i class="fas fa-list me-2"></i>
                Liste des Demandes
            </h6>
        </div>
        <div class="card-body-modern p-0">
            @if($demandes->count() > 0)
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>N° Demande</th>
                            <th>Date</th>
                            <th>Demandeur</th>
                            <th>Équipement</th>
                            <th>Type</th>
                            <th>Urgence</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($demandes as $demande)
                        <tr class="table-row-hover">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-mini bg-primary">
                                        <i class="fas fa-file-medical"></i>
                                    </div>
                                    <span class="fw-bold ms-2">{{ $demande->Numero_Demande }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $demande->Date_Demande->format('d/m/Y') }}</span>
                                <br><small class="text-muted">{{ $demande->Heure_Demande }}</small>
                            </td>
                            <td>
                                {{ $demande->demandeur->nom_complet ?? 'N/A' }}
                                <br><small class="text-muted">{{ $demande->demandeur->matricule ?? '' }}</small>
                            </td>
                            <td>
                                {{ $demande->equipement->numero_inventaire ?? 'N/A' }}
                                <br><small class="text-muted">{{ $demande->equipement->marque ?? '' }} {{ $demande->equipement->modele ?? '' }}</small>
                            </td>
                            <td>
                                <small class="badge-custom bg-info">
                                    {{ $demande->getTypeInterventionFormateAttribute() }}
                                </small>
                            </td>
                            <td>
                                <span class="badge-custom bg-{{ $demande->badge_urgence }}">
                                    {{ $demande->urgence_formate }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-custom bg-{{ $demande->badge_etat }}">
                                    {{ $demande->etat_formate }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group-custom">
                                    <a href="{{ route('chef-division.demandes.show', $demande->ID_Demande) }}"
                                       class="btn-action btn-info"
                                       data-bs-toggle="tooltip"
                                       title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($demande->isEnAttente())
                                    <button type="button"
                                            class="btn-action btn-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#validerModal{{ $demande->ID_Demande }}"
                                            title="Valider">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button"
                                            class="btn-action btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejeterModal{{ $demande->ID_Demande }}"
                                            title="Rejeter">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <button type="button"
                                            class="btn-action btn-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#attenteModal{{ $demande->ID_Demande }}"
                                            title="Mettre en attente">
                                        <i class="fas fa-clock"></i>
                                    </button>
                                    @endif
                                </div>

                                <!-- Modales -->
                                @include('chefDivision.demandes.modals.valider', ['demande' => $demande])
                                @include('chefDivision.demandes.modals.rejeter', ['demande' => $demande])
                                @include('chefDivision.demandes.modals.attente', ['demande' => $demande])
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $demandes->links() }}
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5 class="text-muted fw-bold">Aucune demande trouvée</h5>
                <p class="text-muted">Aucune demande ne correspond à vos critères.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
:root {
    --primary: #4e73df;
    --success: #1cc88a;
    --danger: #e74a3b;
    --warning: #f6c23e;
    --info: #36b9cc;
    --purple: #6f42c1;
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Header Card */
.header-card {
    background: var(--gradient-primary);
    padding: 25px 30px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    animation: slideInDown 0.6s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.btn-light-custom {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-light-custom:hover {
    background: white;
    color: var(--primary);
    transform: translateY(-2px);
}

/* Stats Cards */
.stats-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
    animation: fadeInUp 0.6s ease-out backwards;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stats-card:nth-child(1) { animation-delay: 0.1s; }
.stats-card:nth-child(2) { animation-delay: 0.2s; }
.stats-card:nth-child(3) { animation-delay: 0.3s; }
.stats-card:nth-child(4) { animation-delay: 0.4s; }

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stats-card-body {
    padding: 25px;
}

.stats-label {
    color: #858796;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.stats-value {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: #2d3748;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

/* Modern Cards */
.card-modern {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    border: none;
    overflow: hidden;
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.card-header-modern {
    background: linear-gradient(135deg, #f6f8fb 0%, #e9ecf5 100%);
    padding: 20px 25px;
    border-bottom: 2px solid #e2e8f0;
}

.card-header-modern h6 {
    font-weight: 700;
    color: #2d3748;
}

.card-body-modern {
    padding: 25px;
}

/* Form Controls */
.form-label-custom {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.form-control-custom {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control-custom:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
}

/* Buttons */
.btn-filter {
    background: var(--primary);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-filter:hover {
    background: #2e59d9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
}

.btn-reset {
    background: #f8f9fa;
    color: #6c757d;
    border: 2px solid #dee2e6;
    padding: 12px 16px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-reset:hover {
    background: #e2e6ea;
    color: #495057;
    transform: rotate(180deg);
}

/* Table */
.table-custom {
    margin: 0;
}

.table-custom thead {
    background: linear-gradient(135deg, #f6f8fb 0%, #e9ecf5 100%);
}

.table-custom thead th {
    padding: 15px 20px;
    font-weight: 700;
    font-size: 0.85rem;
    text-transform: uppercase;
    color: #2d3748;
    border: none;
}

.table-custom tbody td {
    padding: 18px 20px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
}

.table-row-hover {
    transition: all 0.3s ease;
}

.table-row-hover:hover {
    background: #f8f9fc;
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

/* Icon Mini */
.icon-mini {
    width: 35px;
    height: 35px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
}

/* Badge Custom */
.badge-custom {
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.75rem;
    display: inline-block;
}

/* Button Group */
.btn-group-custom {
    display: flex;
    gap: 5px;
}

.btn-action {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    color: white;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-action:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.btn-action.btn-info { background: var(--info); }
.btn-action.btn-success { background: var(--success); }
.btn-action.btn-danger { background: var(--danger); }
.btn-action.btn-warning { background: var(--warning); }

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    animation: pulse 1s ease-out;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
}

/* Responsive */
@media (max-width: 768px) {
    .header-card {
        padding: 20px;
    }

    .btn-group-custom {
        flex-wrap: wrap;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Configuration globale
    Chart.defaults.font.family = "'Nunito', sans-serif";
    Chart.defaults.color = '#858796';

    // Graphique Statuts
    new Chart(document.getElementById('statutChart'), {
        type: 'pie',
        data: {
            labels: ['En Attente', 'Validée', 'Rejetée', 'En Cours', 'Terminée'],
            datasets: [{
                data: [
                    {{ $statutStats['en_attente'] ?? 0 }},
                    {{ $statutStats['validee'] ?? 0 }},
                    {{ $statutStats['rejetee'] ?? 0 }},
                    {{ $statutStats['en_cours'] ?? 0 }},
                    {{ $statutStats['terminee'] ?? 0 }}
                ],
                backgroundColor: ['#f6c23e', '#4e73df', '#e74a3b', '#36b9cc', '#1cc88a'],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 15 } }
            }
        }
    });

    // Graphique Urgence
    new Chart(document.getElementById('urgenceChart'), {
        type: 'bar',
        data: {
            labels: ['Normale', 'Urgente', 'Critique'],
            datasets: [{
                label: 'Demandes',
                data: [
                    {{ $urgenceStats['normale'] ?? 0 }},
                    {{ $urgenceStats['urgente'] ?? 0 }},
                    {{ $urgenceStats['critique'] ?? 0 }}
                ],
                backgroundColor: ['rgba(28, 200, 138, 0.8)', 'rgba(246, 194, 62, 0.8)', 'rgba(231, 74, 59, 0.8)'],
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Graphique Types
    new Chart(document.getElementById('typeChart'), {
        type: 'doughnut',
        data: {
            labels: ['Maintenance Préventive', 'Maintenance Corrective', 'Réparation', 'Calibration', 'Vérification', 'Contrôle', 'Autre'],
            datasets: [{
                data: [
                    {{ $typeStats['maintenance_preventive'] ?? 0 }},
                    {{ $typeStats['maintenance_corrective'] ?? 0 }},
                    {{ $typeStats['reparation'] ?? 0 }},
                    {{ $typeStats['calibration'] ?? 0 }},
                    {{ $typeStats['verification'] ?? 0 }},
                    {{ $typeStats['controle'] ?? 0 }},
                    {{ $typeStats['autre'] ?? 0 }}
                ],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69'],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: { legend: { position: 'bottom', labels: { padding: 12, font: { size: 11 } } } }
        }
    });

    // Graphique Évolution
    const evolutionData = @json($evolutionStats);
    new Chart(document.getElementById('evolutionChart'), {
        type: 'line',
        data: {
            labels: evolutionData.map(item => {
                const [year, month] = item.mois.split('-');
                return new Date(year, month - 1).toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
            }),
            datasets: [{
                label: 'Demandes',
                data: evolutionData.map(item => item.total),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Graphique Demandeurs
    const topDemandeurs = @json($topDemandeurs);
    new Chart(document.getElementById('demandeursChart'), {
        type: 'doughnut',
        data: {
            labels: topDemandeurs.map(d => d.demandeur?.nom_complet || 'Inconnu'),
            datasets: [{
                data: topDemandeurs.map(d => d.total),
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: { legend: { position: 'bottom', labels: { padding: 10, font: { size: 10 } } } }
        }
    });
});
</script>
@endsection
