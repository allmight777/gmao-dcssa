@extends('layouts.welcome')

@section('content')
<div class="container-fluid py-4">

    <!-- Messages d'alerte -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show alert-modern" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show alert-modern" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase small">Total Demandes</p>
                            <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-file-medical"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase small">En Attente</p>
                            <h3 class="mb-0 fw-bold text-warning">{{ $stats['en_attente'] }}</h3>
                        </div>
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase small">En Cours</p>
                            <h3 class="mb-0 fw-bold text-info">{{ $stats['en_cours'] }}</h3>
                        </div>
                        <div class="stats-icon bg-info">
                            <i class="fas fa-cog fa-spin"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase small">Terminées</p>
                            <h3 class="mb-0 fw-bold text-success">{{ $stats['terminees'] }}</h3>
                        </div>
                        <div class="stats-icon bg-success">
                            <i class="fas fa-check-double"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('user.demandes.trash') }}">corbeille </a>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <!-- Graphique circulaire - Statuts -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>
                        Répartition par Statut
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="statutChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Graphique en barres - Urgence -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-chart-bar me-2 text-success"></i>
                        Niveau d'Urgence
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="urgenceChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Graphique en donut - Type d'intervention -->
        <div class="col-xl-4 col-lg-12 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-chart-donut me-2 text-info"></i>
                        Types d'Intervention
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="typeChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Évolution des demandes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-chart-line me-2 text-danger"></i>
                        Évolution des Demandes (6 derniers mois)
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="evolutionChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des demandes -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-list me-2"></i>
                        Mes Demandes d'Intervention
                    </h6>
                    <a href="{{ route('user.demandes.create') }}" class="btn btn-primary btn-modern">
                        <i class="fas fa-plus me-2"></i> Nouvelle Demande
                    </a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if($demandes->count() > 0)
                    <div class="table-responsive p-0">
                        <table class="table table-hover align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bold ps-4">
                                        N° Demande
                                    </th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bold">
                                        Équipement
                                    </th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bold">
                                        Date
                                    </th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bold">
                                        Urgence
                                    </th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bold">
                                        Statut
                                    </th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bold text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($demandes as $demande)
                                <tr class="table-row-hover">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="icon-shape icon-sm bg-gradient-primary shadow text-center rounded-circle me-3">
                                                <i class="fas fa-file-medical text-white opacity-10"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-sm fw-bold">{{ $demande->Numero_Demande }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $demande->Type_Intervention }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <h6 class="mb-0 text-sm">{{ $demande->equipement->numero_inventaire }}</h6>
                                            <p class="text-xs text-secondary mb-0">
                                                {{ $demande->equipement->marque }} {{ $demande->equipement->modele }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0 fw-semibold">{{ $demande->Date_Demande->format('d/m/Y') }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $demande->Heure_Demande }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-modern bg-{{ $demande->badge_urgence }}">
                                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                                            {{ $demande->urgence_formate }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-modern bg-{{ $demande->badge_etat }}">
                                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                                            {{ $demande->etat_formate }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('user.demandes.show', $demande->ID_Demande) }}"
                                               class="btn btn-sm btn-info btn-action"
                                               data-bs-toggle="tooltip"
                                               title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($demande->isEnAttente())
                                            <a href="{{ route('user.demandes.edit', $demande->ID_Demande) }}"
                                               class="btn btn-sm btn-warning btn-action"
                                               data-bs-toggle="tooltip"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('user.demandes.destroy', $demande->ID_Demande) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-danger btn-action"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?')"
                                                        data-bs-toggle="tooltip"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
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
                    <div class="text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-file-medical fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted fw-bold">Aucune demande d'intervention</h5>
                            <p class="text-muted">Vous n'avez pas encore créé de demande d'intervention.</p>
                            <a href="{{ route('user.demandes.create') }}" class="btn btn-primary btn-modern mt-3">
                                <i class="fas fa-plus me-2"></i> Créer ma première demande
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Variables CSS */
:root {
    --primary-color: #4e73df;
    --success-color: #1cc88a;
    --info-color: #36b9cc;
    --warning-color: #f6c23e;
    --danger-color: #e74a3b;
    --secondary-color: #858796;
}

/* Cartes de statistiques */
.stats-card {
    border-radius: 15px !important;
    transition: all 0.3s ease;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

/* Alertes modernes */
.alert-modern {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    animation: slideInDown 0.5s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Cartes */
.card {
    border-radius: 15px !important;
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

/* En-têtes de carte */
.card-header {
    border-bottom: 1px solid rgba(0,0,0,0.05) !important;
}

/* Boutons modernes */
.btn-modern {
    border-radius: 10px;
    padding: 10px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
}

/* Badges modernes */
.badge-modern {
    padding: 8px 14px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.75rem;
    letter-spacing: 0.3px;
}

/* Icônes gradient */
.icon-shape {
    background: linear-gradient(135deg, var(--primary-color), #2e59d9);
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Tableau */
.table-hover tbody tr {
    transition: all 0.3s ease;
}

.table-row-hover:hover {
    background-color: #f8f9fc;
    transform: scale(1.01);
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

/* Boutons d'action */
.btn-action {
    border-radius: 8px;
    margin: 0 2px;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: scale(1.1);
}

/* État vide */
.empty-state {
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }

    .btn-modern {
        font-size: 0.85rem;
        padding: 8px 15px;
    }
}

/* Animation des graphiques */
canvas {
    animation: chartFadeIn 1s ease-out;
}

@keyframes chartFadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Initialiser les tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Configuration globale des graphiques
    Chart.defaults.font.family = "'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto";
    Chart.defaults.color = '#858796';

    // Graphique circulaire - Statuts
    const statutCtx = document.getElementById('statutChart');
    if (statutCtx) {
        new Chart(statutCtx, {
            type: 'pie',
            data: {
                labels: [
                    'En Attente',
                    'Validée',
                    'En Cours',
                    'Terminée',
                    'Rejetée'
                ],
                datasets: [{
                    data: [
                        {{ $statutStats['en_attente'] ?? 0 }},
                        {{ $statutStats['validee'] ?? 0 }},
                        {{ $statutStats['en_cours'] ?? 0 }},
                        {{ $statutStats['terminee'] ?? 0 }},
                        {{ $statutStats['rejetee'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#f6c23e',
                        '#4e73df',
                        '#36b9cc',
                        '#1cc88a',
                        '#e74a3b'
                    ],
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
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                }
            }
        });
    }

    // Graphique en barres - Urgence
    const urgenceCtx = document.getElementById('urgenceChart');
    if (urgenceCtx) {
        new Chart(urgenceCtx, {
            type: 'bar',
            data: {
                labels: ['Normale', 'Urgente', 'Critique'],
                datasets: [{
                    label: 'Nombre de demandes',
                    data: [
                        {{ $urgenceStats['normale'] ?? 0 }},
                        {{ $urgenceStats['urgente'] ?? 0 }},
                        {{ $urgenceStats['critique'] ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(28, 200, 138, 0.8)',
                        'rgba(246, 194, 62, 0.8)',
                        'rgba(231, 74, 59, 0.8)'
                    ],
                    borderColor: [
                        '#1cc88a',
                        '#f6c23e',
                        '#e74a3b'
                    ],
                    borderWidth: 2,
                    borderRadius: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Graphique en donut - Types d'intervention
    const typeCtx = document.getElementById('typeChart');
    if (typeCtx) {
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: [
                    'Maintenance Préventive',
                    'Maintenance Corrective',
                    'Réparation',
                    'Calibration',
                    'Vérification',
                    'Contrôle',
                    'Autre'
                ],
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
                    backgroundColor: [
                        '#4e73df',
                        '#1cc88a',
                        '#36b9cc',
                        '#f6c23e',
                        '#e74a3b',
                        '#858796',
                        '#5a5c69'
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
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12
                    }
                }
            }
        });
    }

    // Graphique d'évolution - Ligne
    const evolutionCtx = document.getElementById('evolutionChart');
    if (evolutionCtx) {
        const evolutionData = @json($evolutionStats);

        new Chart(evolutionCtx, {
            type: 'line',
            data: {
                labels: evolutionData.map(item => {
                    const [year, month] = item.mois.split('-');
                    const date = new Date(year, month - 1);
                    return date.toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
                }),
                datasets: [{
                    label: 'Nombre de demandes',
                    data: evolutionData.map(item => item.total),
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#4e73df',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }
});
</script>
@endsection
