@extends('layouts.admin')

@section('title', 'Gestion des comptes utilisateurs')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

@section('page-title', ' Utilisateurs')

@section('page-actions')
<div class="btn-toolbar">
    <a href="{{ route('admin.comptes.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Nouveau compte
    </a>
    
    <a href="{{ route('admin.comptes.export') }}" class="btn btn-success ms-2">
        <i class="fas fa-file-export"></i> Exporter CSV
    </a>
</div>
@endsection

@section('content')
<!-- Statistiques principales -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-start border-primary border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                            Total Utilisateurs
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ number_format($statistics['total']) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-start border-success border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">
                            Actifs aujourd'hui
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ $statistics['active_today'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-start border-info border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">
                            Nouveaux ce mois
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ $statistics['new_this_month'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-plus fa-2x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-start border-warning border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                            Services différents
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ count($statistics['top_services']) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="row mt-4">
    <!-- Distribution par profil -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie me-2"></i>Distribution par profil
                </h6>
            </div>
            <div class="card-body">
                @if(count($statistics['profiles_distribution']) > 0)
                    <div class="chart-container" style="position: relative; height: 250px;">
                        <canvas id="profilesChart"></canvas>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-chart-pie fa-2x mb-3 opacity-25"></i>
                        <p>Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Distribution par statut -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-chart-bar me-2"></i>Distribution par statut
                </h6>
            </div>
            <div class="card-body">
                @if(count($statistics['status_distribution']) > 0)
                    <div class="chart-container" style="position: relative; height: 250px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-chart-bar fa-2x mb-3 opacity-25"></i>
                        <p>Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Évolution mensuelle -->
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card shadow h-100">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-chart-line me-2"></i>Évolution (6 derniers mois)
                </h6>
            </div>
            <div class="card-body">
                @if(count($statistics['by_month']) > 0)
                    <div class="chart-container" style="position: relative; height: 250px;">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-chart-line fa-2x mb-3 opacity-25"></i>
                        <p>Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Filtres de recherche -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter"></i> Filtres de recherche
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.comptes.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" 
                       placeholder="Nom, prénom, matricule..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="profil_id" class="form-control">
                    <option value="">Tous les profils</option>
                    @foreach($profils as $profil)
                        <option value="{{ $profil->id }}" {{ request('profil_id') == $profil->id ? 'selected' : '' }}>
                            {{ $profil->nom_profil }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="statut" class="form-control">
                    <option value="">Tous les statuts</option>
                    <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    <option value="suspendu" {{ request('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="service_id" class="form-control">
                    <option value="">Tous les services</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Liste des comptes -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-users"></i> Liste des comptes utilisateurs
        </h5>
        <div class="text-muted">
            {{ $utilisateurs->total() }} compte(s) trouvé(s)
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Matricule</th>
                        <th>Nom & Prénom</th>
                        <th>Fonction</th>
                        <th>Service</th>
                        <th>Profil</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Dernière connexion</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($utilisateurs as $utilisateur)
                        <tr>
                            <td>
                                <code>{{ $utilisateur->matricule }}</code>
                            </td>
                            <td>
                                <strong>{{ $utilisateur->nom }}</strong> {{ $utilisateur->prenom }}
                                @if($utilisateur->id == auth()->id())
                                    <span class="badge bg-info ms-1">Vous</span>
                                @endif
                            </td>
                            <td>{{ $utilisateur->fonction }}</td>
                            <td>{{ $utilisateur->service->nom ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $badgeClass = 'bg-secondary';
                                    switch($utilisateur->profil->nom_profil ?? '') {
                                        case 'admin': $badgeClass = 'bg-danger'; break;
                                        case 'gestionnaire_inventaire': $badgeClass = 'bg-success'; break;
                                        case 'technicien': $badgeClass = 'bg-info'; break;
                                        case 'superviseur': $badgeClass = 'bg-warning'; break;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ $utilisateur->profil->nom_profil ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <a href="mailto:{{ $utilisateur->email }}">{{ $utilisateur->email }}</a>
                            </td>
                            <td>
                                @if($utilisateur->statut == 'actif')
                                    <span class="badge bg-success">
                                        <i class="fas fa-circle"></i> Actif
                                    </span>
                                @elseif($utilisateur->statut == 'inactif')
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-circle"></i> Inactif
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-circle"></i> Suspendu
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($utilisateur->date_derniere_connexion)
                                    {{ $utilisateur->date_derniere_connexion->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">Jamais connecté</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:4px;">
                                    <a href="{{ route('admin.comptes.show', $utilisateur->id) }}"
                                       class="btn btn-sm btn-info"
                                       title="Voir"
                                       style="width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-eye" style="font-size:14px;"></i>
                                    </a>

                                    <a href="{{ route('admin.comptes.edit', $utilisateur->id) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Modifier"
                                       style="width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-edit" style="font-size:14px;"></i>
                                    </a>

                                    <!-- Toggle status -->
                                    <form action="{{ route('admin.comptes.toggle-status', $utilisateur->id) }}"
                                          method="POST"
                                          style="display:inline-flex;align-items:center;">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $utilisateur->statut == 'actif' ? 'btn-danger' : 'btn-success' }} confirm-action"
                                            title="{{ $utilisateur->statut == 'actif' ? 'Désactiver' : 'Activer' }}"
                                            data-confirm="Êtes-vous sûr de vouloir {{ $utilisateur->statut == 'actif' ? 'désactiver' : 'activer' }} ce compte ?"
                                            style="width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas {{ $utilisateur->statut == 'actif' ? 'fa-user-slash' : 'fa-user-check' }}"
                                               style="font-size:14px;"></i>
                                        </button>
                                    </form>

                                    <!-- Reset password -->
                                    <button type="button"
                                        class="btn btn-sm btn-secondary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#resetPasswordModal{{ $utilisateur->id }}"
                                        title="Réinitialiser le mot de passe"
                                        style="width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-key" style="font-size:14px;"></i>
                                    </button>

                                    <!-- Delete -->
                                    @if($utilisateur->id != auth()->id())
                                        <form action="{{ route('admin.comptes.destroy', $utilisateur->id) }}"
                                              method="POST"
                                              style="display:inline-flex;align-items:center;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-danger confirm-delete"
                                                title="Supprimer"
                                                style="width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center;">
                                                <i class="fas fa-trash" style="font-size:14px;"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Reset Password Modal -->
                                <div class="modal fade" id="resetPasswordModal{{ $utilisateur->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.comptes.reset-password', $utilisateur->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Réinitialiser le mot de passe</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Vous allez réinitialiser le mot de passe de :</p>
                                                    <p><strong>{{ $utilisateur->nom_complet }}</strong> ({{ $utilisateur->matricule }})</p>
                                                    <p class="text-muted">Un email sera envoyé à l'utilisateur avec le nouveau mot de passe.</p>

                                                    <div class="mb-3">
                                                        <label class="form-label">Nouveau mot de passe</label>
                                                        <input type="password" class="form-control" name="password" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Confirmation</label>
                                                        <input type="password" class="form-control" name="password_confirmation" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-primary">Réinitialiser</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-users-slash fa-2x mb-3"></i>
                                    <p>Aucun compte utilisateur trouvé</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($utilisateurs->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Page navigation">
                    {{ $utilisateurs->withQueryString()->links() }}
                </nav>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Cartes de statistiques */
    .stat-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    /* Container pour les charts */
    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
    }
    
    /* Style pour les badges de statut */
    .badge i.fa-circle {
        font-size: 0.7em;
        margin-right: 5px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Initialiser Select2 pour les filtres
        $('select[name="profil_id"], select[name="service_id"]').select2({
            theme: 'bootstrap-5',
            placeholder: 'Sélectionnez...',
            allowClear: true
        });
        
        // Générateur de mot de passe pour les modals
        $('.generate-password').on('click', function() {
            var modalId = $(this).data('target');
            var password = generatePassword();
            $(modalId + ' input[name="password"]').val(password);
            $(modalId + ' input[name="password_confirmation"]').val(password);
        });
        
        function generatePassword() {
            var length = 12;
            var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
            var password = "";
            for (var i = 0; i < length; i++) {
                password += charset.charAt(Math.floor(Math.random() * charset.length));
            }
            return password;
        }
        
        // Initialiser les charts
        initializeCharts();
    });
    
    function initializeCharts() {
        // Chart 1: Distribution par profil
        const profilesData = @json($statistics['profiles_distribution']);
        if (Object.keys(profilesData).length > 0) {
            const profilesCanvas = document.getElementById('profilesChart');
            if (profilesCanvas) {
                const profilesCtx = profilesCanvas.getContext('2d');
                new Chart(profilesCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(profilesData).map(label => 
                            label.replace('_', ' ').charAt(0).toUpperCase() + label.replace('_', ' ').slice(1)
                        ),
                        datasets: [{
                            data: Object.values(profilesData),
                            backgroundColor: [
                                'rgba(220, 53, 69, 0.8)',   // Rouge pour admin
                                'rgba(40, 167, 69, 0.8)',   // Vert pour gestionnaire
                                'rgba(23, 162, 184, 0.8)',  // Bleu pour technicien
                                'rgba(255, 193, 7, 0.8)',   // Jaune pour superviseur
                                'rgba(108, 117, 125, 0.8)', // Gris pour autres
                            ],
                            borderWidth: 1,
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
                                    padding: 10,
                                    usePointStyle: true,
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
        
        // Chart 2: Distribution par statut
        const statusData = @json($statistics['status_distribution']);
        if (Object.keys(statusData).length > 0) {
            const statusCanvas = document.getElementById('statusChart');
            if (statusCanvas) {
                const statusCtx = statusCanvas.getContext('2d');
                new Chart(statusCtx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(statusData).map(label => 
                            label.charAt(0).toUpperCase() + label.slice(1)
                        ),
                        datasets: [{
                            label: 'Nombre d\'utilisateurs',
                            data: Object.values(statusData),
                            backgroundColor: [
                                'rgba(40, 167, 69, 0.8)',   // Vert pour actif
                                'rgba(108, 117, 125, 0.8)', // Gris pour inactif
                                'rgba(220, 53, 69, 0.8)',   // Rouge pour suspendu
                            ],
                            borderColor: [
                                'rgba(40, 167, 69, 1)',
                                'rgba(108, 117, 125, 1)',
                                'rgba(220, 53, 69, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        }
        
        // Chart 3: Évolution mensuelle
        const monthlyData = @json($statistics['by_month']);
        if (Object.keys(monthlyData).length > 0) {
            const monthlyCanvas = document.getElementById('monthlyChart');
            if (monthlyCanvas) {
                const monthlyCtx = monthlyCanvas.getContext('2d');
                new Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: Object.keys(monthlyData),
                        datasets: [{
                            label: 'Nouveaux utilisateurs',
                            data: Object.values(monthlyData),
                            borderColor: 'rgba(23, 162, 184, 1)',
                            backgroundColor: 'rgba(23, 162, 184, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        }
    }
</script>
@endpush