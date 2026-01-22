@extends('layouts.admin')

@section('title', 'Gestion des profils et permissions')

@section('page-title', 'Gestion des profils et permissions')

<br><br>
@section('page-actions')
<div class="btn-toolbar">
    <a href="{{ route('admin.profils.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Nouveau profil
    </a>
</div>
@endsection

@section('content')
<!-- Filtres de recherche -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter"></i> Filtres de recherche
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.profils.index') }}" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" 
                       placeholder="Rechercher par nom, description..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filtrer
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.profils.index') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-undo"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistiques globales -->
<div class="row mt-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-primary border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                            Total Profils
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalProfils }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tag fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-success border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">
                            Total Utilisateurs
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalUtilisateurs }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-warning border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                            Total Permissions
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalPermissions }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-key fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- Liste des profils -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-user-tag"></i> Liste des profils
        </h5>
        <div class="text-muted">
            {{ $profils->total() }} profil(s) trouvé(s)
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nom du profil</th>
                        <th>Description</th>
                        <th class="text-center">Utilisateurs</th>
                        <th class="text-center">Permissions</th>
                        <th>Créé le</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($profils as $profil)
                        @php
                            $permissionsCount = $profil->permissions()->count();
                            $utilisateursCount = $profil->utilisateurs()->count();
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-circle bg-{{ $profil->nom_profil == 'admin' ? 'danger' : 'primary' }}">
                                            {{ strtoupper(substr($profil->nom_profil, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <strong class="d-block">{{ $profil->nom_profil }}</strong>
                                        <div class="badge-container">
                                            @if($profil->is_default)
                                                <span class="badge bg-info">Par défaut</span>
                                            @endif
                                            @if($profil->nom_profil == 'admin')
                                                <span class="badge bg-danger">Admin</span>
                                            @endif
                                            @if($utilisateursCount == 0)
                                                <span class="badge bg-secondary">Aucun utilisateur</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="description-cell">
                                {{ Str::limit($profil->description, 60) }}
                                @if(strlen($profil->description) > 60)
                                    <span class="text-muted" 
                                          data-bs-toggle="tooltip" 
                                          title="{{ $profil->description }}">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="badge bg-primary rounded-pill p-2 mb-1">
                                        {{ $utilisateursCount }}
                                    </span>
                                    @if($utilisateursCount > 0)
                                        <small class="text-muted">utilisateur(s)</small>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="badge bg-secondary rounded-pill p-2 mb-1">
                                        {{ $permissionsCount }}
                                    </span>
                                    @if($permissionsCount > 0)
                                        <small class="text-muted">permission(s)</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ $profil->created_at->format('d/m/Y') }}</span>
                                    <small class="text-muted">{{ $profil->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    <a href="{{ route('admin.profils.show', $profil) }}" 
                                       class="btn btn-sm btn-info action-btn" 
                                       data-bs-toggle="tooltip" 
                                       title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.profils.edit', $profil) }}" 
                                       class="btn btn-sm btn-warning action-btn"
                                       data-bs-toggle="tooltip" 
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.profils.permissions.edit', $profil) }}" 
                                       class="btn btn-sm btn-secondary action-btn"
                                       data-bs-toggle="tooltip" 
                                       title="Gérer les permissions">
                                        <i class="fas fa-key"></i>
                                    </a>
                                    
                                    <!-- Duplicate modal trigger -->
                                    <button type="button" class="btn btn-sm btn-success action-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#duplicateModal{{ $profil->id }}"
                                            data-bs-toggle="tooltip"
                                            title="Dupliquer">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    
                                    <!-- Delete -->
                                    @if($profil->nom_profil != 'admin' && $utilisateursCount == 0)
                                        <form action="{{ route('admin.profils.destroy', $profil) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger action-btn confirm-delete"
                                                    data-bs-toggle="tooltip"
                                                    title="Supprimer"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce profil ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary action-btn" disabled 
                                                data-bs-toggle="tooltip"
                                                title="Ce profil ne peut pas être supprimé">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Duplicate Modal -->
                        <div class="modal fade" id="duplicateModal{{ $profil->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.profils.duplicate', $profil) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-copy me-2"></i>Dupliquer le profil
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Vous allez dupliquer le profil : <strong>{{ $profil->nom_profil }}</strong>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="card border-info mb-2">
                                                        <div class="card-body text-center py-2">
                                                            <small class="text-muted">Utilisateurs</small>
                                                            <div class="h5 mb-0">{{ $utilisateursCount }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card border-info mb-2">
                                                        <div class="card-body text-center py-2">
                                                            <small class="text-muted">Permissions</small>
                                                            <div class="h5 mb-0">{{ $permissionsCount }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="nouveau_nom{{ $profil->id }}" class="form-label">
                                                    <i class="fas fa-tag me-1"></i>Nom du nouveau profil *
                                                </label>
                                                <input type="text" class="form-control" id="nouveau_nom{{ $profil->id }}" 
                                                       name="nouveau_nom" required
                                                       placeholder="Ex: {{ $profil->nom_profil }}_copie"
                                                       value="{{ $profil->nom_profil }}_copie">
                                                <div class="form-text">
                                                    Les permissions seront automatiquement copiées.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1"></i> Annuler
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-check me-1"></i> Dupliquer
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-user-tag fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Aucun profil trouvé</h5>
                                    <p class="text-muted mb-0">Commencez par créer un nouveau profil.</p>
                                    <a href="{{ route('admin.profils.create') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus-circle me-1"></i> Créer un profil
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($profils->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Affichage de {{ $profils->firstItem() }} à {{ $profils->lastItem() }} sur {{ $profils->total() }} profils
                </div>
                <nav aria-label="Page navigation">
                    {{ $profils->withQueryString()->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        @endif
    </div>
</div>

<!-- Distribution des profils -->
<div class="row mt-4">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Distribution des utilisateurs
                </h5>
            </div>
            <div class="card-body">
                <canvas id="utilisateursChart" height="250"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Top profils par permissions
                </h5>
            </div>
            <div class="card-body">
                <canvas id="permissionsChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Information sur les profils -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-info-circle me-2"></i> Informations sur les profils
        </h5>
    </div>
    <div class="card-body">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col">
                <div class="card border-start border-4 border-danger h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-circle-sm bg-danger">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <h6 class="card-title ms-3 mb-0 text-danger">Administrateur</h6>
                        </div>
                        <p class="card-text small text-muted">
                            Accès complet à toutes les fonctionnalités du système. 
                            Gestion des utilisateurs, profils, permissions et configurations.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card border-start border-4 border-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-circle-sm bg-success">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h6 class="card-title ms-3 mb-0 text-success">Gestionnaire d'Inventaire</h6>
                        </div>
                        <p class="card-text small text-muted">
                            Gestion des équipements biomédicaux, inventaire, réformes, transferts 
                            et mouvements de matériel.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card border-start border-4 border-info h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-circle-sm bg-info">
                                <i class="fas fa-wrench"></i>
                            </div>
                            <h6 class="card-title ms-3 mb-0 text-info">Technicien Maintenance</h6>
                        </div>
                        <p class="card-text small text-muted">
                            Interventions techniques, rapports de maintenance, demandes de pièces 
                            et suivis des équipements en panne.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 16px;
    }
    
    .avatar-circle-sm {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
    
    .badge-container {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
        margin-top: 4px;
    }
    
    .description-cell {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .description-cell:hover {
        white-space: normal;
        overflow: visible;
        position: relative;
        z-index: 1;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        padding: 8px;
        border-radius: 4px;
        max-width: none;
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }
    
    .border-start {
        border-left-width: 4px !important;
    }
    
    .card-title {
        display: flex;
        align-items: center;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            border: 0;
        }
        
        .table thead {
            display: none;
        }
        
        .table tbody tr {
            display: block;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .table tbody td {
            display: block;
            text-align: right;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .table tbody td:last-child {
            border-bottom: 0;
        }
        
        .table tbody td:before {
            content: attr(data-label);
            float: left;
            font-weight: bold;
            color: #495057;
        }
        
        .table tbody td[data-label]:before {
            content: attr(data-label);
        }
        
        .action-btn {
            width: 32px;
            height: 32px;
        }
        
        .description-cell {
            max-width: none;
            white-space: normal;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Initialiser les tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Auto-focus sur le champ du nom dans les modals
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('input[type="text"]').first().focus();
        });
        
        // Gérer l'affichage responsive
        function initResponsiveTable() {
            if ($(window).width() <= 768) {
                $('tbody tr').each(function() {
                    const $cells = $(this).find('td');
                    const labels = ['Nom', 'Description', 'Utilisateurs', 'Permissions', 'Créé le', 'Actions'];
                    
                    $cells.each(function(index) {
                        $(this).attr('data-label', labels[index]);
                    });
                });
            } else {
                $('tbody td').removeAttr('data-label');
            }
        }
        
        initResponsiveTable();
        $(window).on('resize', initResponsiveTable);
        
        // Charts
        const ctx1 = document.getElementById('utilisateursChart').getContext('2d');
        const ctx2 = document.getElementById('permissionsChart').getContext('2d');
        
        // Données pour le graphique des utilisateurs
        const utilisateursData = {
            labels: [
                @foreach($profils as $profil)
                    '{{ $profil->nom_profil }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($profils as $profil)
                        {{ $profil->utilisateurs()->count() }},
                    @endforeach
                ],
                backgroundColor: [
                    '#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', 
                    '#9966ff', '#ff9f40', '#8ac926', '#1982c4',
                    '#6a4c93', '#f15bb5'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        };
        
        // Données pour le graphique des permissions
        const permissionsData = {
            labels: [
                @foreach($profils as $profil)
                    '{{ $profil->nom_profil }}',
                @endforeach
            ],
            datasets: [{
                label: 'Permissions',
                data: [
                    @foreach($profils as $profil)
                        {{ $profil->permissions()->count() }},
                    @endforeach
                ],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                borderRadius: 5,
                borderSkipped: false
            }]
        };
        
        // Configuration commune
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.raw;
                            return label;
                        }
                    }
                }
            }
        };
        
        // Graphique circulaire (utilisateurs)
        new Chart(ctx1, {
            type: 'doughnut',
            data: utilisateursData,
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
        
        // Graphique à barres (permissions)
        new Chart(ctx2, {
            type: 'bar',
            data: permissionsData,
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5
                        }
                    }
                }
            }
        });
    });
</script>
@endpush