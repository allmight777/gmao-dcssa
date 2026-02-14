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
                            <i class="fas fa-search me-2"></i>
                            Disponibilité des Équipements
                        </h4>
                        <p class="mb-0 text-white-50">
                            <i class="fas fa-building me-2"></i>
                            Service: {{ Auth::user()->service->nom ?? 'Votre service' }}
                        </p>
                    </div>
                    <a href="{{ route('UserSimleDashboard') }}" class="btn btn-light-custom">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        @php
            $stats = [
                'total' => $equipements->total(),
                'disponible' => 0,
                'limite' => 0,
                'non_disponible' => 0
            ];

            foreach($equipements as $equipement) {
                $dispo = '';
                if ($equipement->etat === 'hors_service' || $equipement->etat === 'mauvais') {
                    $dispo = 'non_disponible';
                } elseif ($equipement->etat === 'moyen') {
                    $dispo = 'limite';
                } else {
                    $dispo = 'disponible';
                }

                if(isset($stats[$dispo])) {
                    $stats[$dispo]++;
                }
            }
        @endphp

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="stats-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stats-label">Total Équipements</p>
                            <h3 class="stats-value">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-laptop-medical"></i>
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
                            <p class="stats-label">Disponibles</p>
                            <h3 class="stats-value text-success">{{ $stats['disponible'] }}</h3>
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
                            <p class="stats-label">Usage Limité</p>
                            <h3 class="stats-value text-warning">{{ $stats['limite'] }}</h3>
                        </div>
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-exclamation-triangle"></i>
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
                            <p class="stats-label">Non Disponibles</p>
                            <h3 class="stats-value text-danger">{{ $stats['non_disponible'] }}</h3>
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
        <!-- Graphique États -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card-modern">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>
                        Répartition par État
                    </h6>
                </div>
                <div class="card-body-modern">
                    <canvas id="etatChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Graphique Types -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card-modern">
                <div class="card-header-modern">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-success"></i>
                        Répartition par Type
                    </h6>
                </div>
                <div class="card-body-modern">
                    <canvas id="typeChart" height="250"></canvas>
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
            <form method="GET" action="{{ route('user.equipements.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label-custom">Recherche</label>
                    <div class="input-group-custom">
                        <span class="input-group-text-custom">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text"
                               name="search"
                               class="form-control-custom"
                               placeholder="N° inventaire, marque, modèle..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label-custom">Type</label>
                    <select name="type" class="form-control-custom">
                        <option value="">Tous les types</option>
                        @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                            {{ $type->libelle }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-custom">État</label>
                    <select name="etat" class="form-control-custom">
                        <option value="">Tous les états</option>
                        <option value="neuf" {{ request('etat') == 'neuf' ? 'selected' : '' }}>Neuf</option>
                        <option value="bon" {{ request('etat') == 'bon' ? 'selected' : '' }}>Bon</option>
                        <option value="moyen" {{ request('etat') == 'moyen' ? 'selected' : '' }}>Moyen</option>
                        <option value="mauvais" {{ request('etat') == 'mauvais' ? 'selected' : '' }}>Mauvais</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label-custom">Localisation</label>
                    <select name="localisation" class="form-control-custom">
                        <option value="">Toutes</option>
                        @foreach($localisations as $loc)
                        <option value="{{ $loc->id }}" {{ request('localisation') == $loc->id ? 'selected' : '' }}>
                            {{ $loc->nom }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn-filter flex-grow-1">
                        <i class="fas fa-filter me-2"></i>Appliquer
                    </button>
                    <a href="{{ route('user.equipements.index') }}" class="btn-reset">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des équipements -->
    <div class="card-modern">
        <div class="card-header-modern d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="fas fa-list me-2"></i>
                Liste des Équipements
                <span class="badge-custom bg-primary ms-2">{{ $equipements->total() }}</span>
            </h6>
        </div>
        <div class="card-body-modern p-0">
            @if($equipements->count() > 0)
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>N° Inventaire</th>
                            <th>Marque / Modèle</th>
                            <th>Type</th>
                            <th>Localisation</th>
                            <th>État</th>
                            <th>Disponibilité</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($equipements as $equipement)
                        @php
                            // Déterminer la disponibilité
                            $disponibilite = '';
                            $couleur = '';
                            if ($equipement->etat === 'hors_service' || $equipement->etat === 'mauvais') {
                                $disponibilite = 'Non disponible';
                                $couleur = 'danger';
                            } elseif ($equipement->etat === 'moyen') {
                                $disponibilite = 'Usage limité';
                                $couleur = 'warning';
                            } else {
                                $disponibilite = 'Disponible';
                                $couleur = 'success';
                            }
                        @endphp
                        <tr class="table-row-hover">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="icon-mini bg-primary">
                                        <i class="fas fa-desktop"></i>
                                    </div>
                                    <div class="ms-2">
                                        <span class="fw-bold">{{ $equipement->numero_inventaire }}</span>
                                        @if($equipement->numero_serie)
                                        <br><small class="text-muted">S/N: {{ $equipement->numero_serie }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $equipement->marque }}</span>
                                <br><small class="text-muted">{{ $equipement->modele }}</small>
                            </td>
                            <td>
                                <small class="badge-custom bg-info">
                                    {{ $equipement->typeEquipement->libelle ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                {{ $equipement->localisation->nom ?? 'N/A' }}
                            </td>
                            <td>
                                @php
                                    $etatCouleur = match($equipement->etat) {
                                        'neuf', 'bon' => 'success',
                                        'moyen' => 'warning',
                                        default => 'danger'
                                    };
                                @endphp
                                <span class="badge-custom bg-{{ $etatCouleur }}">
                                    {{ ucfirst($equipement->etat) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-custom bg-{{ $couleur }}">
                                    <i class="fas fa-{{ $couleur === 'success' ? 'check' : ($couleur === 'warning' ? 'exclamation-triangle' : 'times') }} me-1"></i>
                                    {{ $disponibilite }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group-custom">
                                    <a href="{{ route('user.equipements.show', $equipement->id) }}"
                                       class="btn-action btn-info"
                                       data-bs-toggle="tooltip"
                                       title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $equipements->links() }}
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                <h5 class="text-muted fw-bold">Aucun équipement trouvé</h5>
                <p class="text-muted">Aucun équipement ne correspond à vos critères de recherche.</p>
                <a href="{{ route('user.equipements.index') }}" class="btn-filter mt-2">
                    <i class="fas fa-redo me-2"></i>Réinitialiser la recherche
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Légende -->
    <div class="card-modern mt-4">
        <div class="card-header-modern">
            <h6 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Légende de Disponibilité
            </h6>
        </div>
        <div class="card-body-modern">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge-custom bg-success me-3">●</span>
                        <div>
                            <small class="fw-bold d-block">Disponible</small>
                            <small class="text-muted">Équipement en bon état et utilisable</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge-custom bg-warning me-3">●</span>
                        <div>
                            <small class="fw-bold d-block">Usage limité</small>
                            <small class="text-muted">État moyen, utilisation avec précaution</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge-custom bg-danger me-3">●</span>
                        <div>
                            <small class="fw-bold d-block">Non disponible</small>
                            <small class="text-muted">Équipement en mauvais état/hors service</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center">
                        <span class="badge-custom bg-info me-3">●</span>
                        <div>
                            <small class="fw-bold d-block">En intervention</small>
                            <small class="text-muted">En cours de réparation/maintenance</small>
                        </div>
                    </div>
                </div>
            </div>
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
    display: block;
}

.input-group-custom {
    position: relative;
    display: flex;
    align-items: center;
}

.input-group-text-custom {
    position: absolute;
    left: 12px;
    z-index: 4;
    color: #6c757d;
}

.form-control-custom {
    width: 100%;
    padding: 12px 16px 12px 40px;
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

select.form-control-custom {
    padding-left: 16px;
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
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-filter:hover {
    background: #2e59d9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
    color: white;
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
    text-decoration: none;
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
    display: inline-flex;
    align-items: center;
}

/* Button Group */
.btn-group-custom {
    display: flex;
    gap: 5px;
    justify-content: center;
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
    text-decoration: none;
}

.btn-action:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.btn-action.btn-info { background: var(--info); }
.btn-action.btn-success { background: var(--success); }
.btn-action.btn-danger { background: var(--danger); }
.btn-action.btn-warning { background: var(--warning); }
.btn-action.btn-primary { background: var(--primary); }

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

/* Pagination */
.pagination {
    margin-bottom: 0;
}

.page-link {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    margin: 0 4px;
    color: #2d3748;
    font-weight: 600;
}

.page-item.active .page-link {
    background: var(--primary);
    border-color: var(--primary);
}

/* Responsive */
@media (max-width: 768px) {
    .header-card {
        padding: 20px;
    }

    .btn-group-custom {
        flex-wrap: wrap;
    }

    .table-custom {
        font-size: 0.9rem;
    }

    .stats-value {
        font-size: 1.5rem;
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

    // Données pour les graphiques
    @php
        $etatStats = [];
        $typeStats = [];

        foreach($equipements as $equipement) {
            // Statistiques par état
            $etat = $equipement->etat;
            if(!isset($etatStats[$etat])) {
                $etatStats[$etat] = 0;
            }
            $etatStats[$etat]++;

            // Statistiques par type
            $type = $equipement->typeEquipement->libelle ?? 'Autre';
            if(!isset($typeStats[$type])) {
                $typeStats[$type] = 0;
            }
            $typeStats[$type]++;
        }
    @endphp

    // Graphique États
    new Chart(document.getElementById('etatChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($etatStats)) !!},
            datasets: [{
                data: {!! json_encode(array_values($etatStats)) !!},
                backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b', '#858796'],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Graphique Types
    new Chart(document.getElementById('typeChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($typeStats)) !!},
            datasets: [{
                data: {!! json_encode(array_values($typeStats)) !!},
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e',
                    '#e74a3b', '#858796', '#5a5c69', '#2d3748'
                ],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 12,
                        font: { size: 11 }
                    }
                }
            }
        }
    });

    // Recherche en temps réel
    const searchInput = document.querySelector('input[name="search"]');
    const searchForm = document.querySelector('form');

    if(searchInput && searchForm) {
        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                searchForm.submit();
            }, 500);
        });
    }
});
</script>
@endsection
