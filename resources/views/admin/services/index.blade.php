@extends('layouts.admin')

@section('title', 'Gestion des services et Localisations')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
@section('page-title', 'Services et localisations')

@section('page-actions')
<div class="btn-toolbar">
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Nouveau service/localisation
    </a>
    <a href="{{ route('admin.services.export') }}" class="btn btn-success ms-2">
        <i class="fas fa-file-export"></i> Exporter
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
        <form method="GET" action="{{ route('admin.services.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" 
                       placeholder="Nom, code géographique, description..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="parent_id" class="form-control">
                    <option value="">Tous les parents</option>
                    @foreach($allLocalisations as $localisation)
                        <option value="{{ $localisation->id }}" 
                                {{ request('parent_id') == $localisation->id ? 'selected' : '' }}>
                            {{ $localisation->nom }} ({{ $localisation->type }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="type" class="form-control">
                    <option value="">Tous les types</option>
                    @foreach($allTypes as $type => $count)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }} ({{ $count }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filtrer
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-undo"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistiques principales -->
<div class="row mt-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card border-start border-primary border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                            Total Localisations
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ number_format($statistics['total']) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-map-marker-alt fa-2x text-primary opacity-50"></i>
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
                            Avec Responsable
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ number_format($statistics['with_responsable']) }}
                        </div>
                        <div class="mt-2">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ $statistics['total'] > 0 ? ($statistics['with_responsable'] / $statistics['total'] * 100) : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tie fa-2x text-success opacity-50"></i>
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
                            Avec Sous-éléments
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ number_format($statistics['with_children']) }}
                        </div>
                        <div class="mt-2">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" role="progressbar" 
                                     style="width: {{ $statistics['total'] > 0 ? ($statistics['with_children'] / $statistics['total'] * 100) : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-sitemap fa-2x text-warning opacity-50"></i>
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
                            Types différents
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ count($statistics['types_distribution']) }}
                        </div>
                        <div class="mt-2">
                            @if(count($statistics['types_distribution']) > 0)
                                @php
                                    $topType = array_key_first($statistics['types_distribution']);
                                    $topCount = $statistics['types_distribution'][$topType];
                                @endphp
                                <small class="text-muted">
                                    Plus fréquent : {{ ucfirst($topType) }} ({{ $topCount }})
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-layer-group fa-2x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques et visualisations -->
<div class="row mt-4">
    <!-- Graphique de répartition par type -->
    <div class="col-xl-6 col-md-12 mb-4">
        <div class="card shadow h-100">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie me-2"></i>Répartition par type
                </h6>
            </div>
            <div class="card-body">
                @if(count($statistics['types_distribution']) > 0)
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="typesChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    @foreach($statistics['types_distribution'] as $type => $count)
                                        @php
                                            $percentage = $statistics['total'] > 0 ? ($count / $statistics['total'] * 100) : 0;
                                            $color = $type === 'service' ? 'success' : 
                                                    ($type === 'site' ? 'primary' : 
                                                    ($type === 'direction' ? 'danger' : 
                                                    ($type === 'batiment' ? 'warning' : 
                                                    ($type === 'salle' ? 'info' : 
                                                    ($type === 'bureau' ? 'secondary' : 
                                                    ($type === 'atelier' ? 'dark' : 
                                                    ($type === 'depot' ? 'primary' : 
                                                    ($type === 'laboratoire' ? 'info' : 'secondary'))))))));
                                        @endphp
                                        <tr>
                                            <td width="30%">
                                                <span class="badge bg-{{ $color }}">
                                                    <i class="{{ $type === 'service' ? 'fas fa-building' : 
                                                            ($type === 'site' ? 'fas fa-map-marker-alt' : 
                                                            ($type === 'direction' ? 'fas fa-flag' : 
                                                            ($type === 'batiment' ? 'fas fa-warehouse' : 
                                                            ($type === 'salle' ? 'fas fa-door-open' : 
                                                            ($type === 'bureau' ? 'fas fa-desktop' : 
                                                            ($type === 'atelier' ? 'fas fa-tools' : 
                                                            ($type === 'depot' ? 'fas fa-box' : 
                                                            ($type === 'laboratoire' ? 'fas fa-flask' : 'fas fa-folder')))))))) }} me-1"></i>
                                                    {{ ucfirst($type) }}
                                                </span>
                                            </td>
                                            <td width="60%">
                                                <div class="progress" style="height: 10px;">
                                                    <div class="progress-bar bg-{{ $color }}" 
                                                         role="progressbar" 
                                                         style="width: {{ $percentage }}%">
                                                    </div>
                                                </div>
                                            </td>
                                            <td width="10%" class="text-end">
                                                <strong>{{ $count }}</strong>
                                                <small class="text-muted">({{ number_format($percentage, 1) }}%)</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-chart-pie fa-3x mb-3 opacity-25"></i>
                        <p>Aucune donnée disponible pour le graphique</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Top 5 par nombre d'utilisateurs -->
    <div class="col-xl-6 col-md-12 mb-4">
        <div class="card shadow h-100">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-users me-2"></i>Top 5 par nombre d'utilisateurs
                </h6>
            </div>
            <div class="card-body">
                @if($statistics['top_by_users']->count() > 0)
                    <div class="chart-container" style="position: relative; height: 300px;">
                        <canvas id="usersChart"></canvas>
                    </div>
                    <div class="mt-3">
                        @foreach($statistics['top_by_users'] as $index => $localisation)
                            @php
                                $color = $localisation->type === 'service' ? 'success' : 
                                        ($localisation->type === 'site' ? 'primary' : 
                                        ($localisation->type === 'direction' ? 'danger' : 
                                        ($localisation->type === 'batiment' ? 'warning' : 
                                        ($localisation->type === 'salle' ? 'info' : 
                                        ($localisation->type === 'bureau' ? 'secondary' : 
                                        ($localisation->type === 'atelier' ? 'dark' : 
                                        ($localisation->type === 'depot' ? 'primary' : 
                                        ($localisation->type === 'laboratoire' ? 'info' : 'secondary'))))))));
                            @endphp
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <span class="badge bg-{{ $color }} me-2">
                                        #{{ $index + 1 }}
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $localisation->nom }}</strong>
                                            <small class="text-muted ms-2">{{ ucfirst($localisation->type) }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $localisation->utilisateurs_count }} utilisateurs
                                            </span>
                                        </div>
                                    </div>
                                    @if($localisation->responsable)
                                        <small class="text-muted">
                                            <i class="fas fa-user-tie me-1"></i>
                                            {{ $localisation->responsable->prenom }} {{ $localisation->responsable->nom }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-users fa-3x mb-3 opacity-25"></i>
                        <p>Aucun utilisateur assigné aux localisations</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Liste des localisations -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-list"></i> Liste des localisations (tous types)
        </h5>
        <div class="text-muted">
            {{ $localisations->total() }} élément(s) trouvé(s)
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th width="10%">Type</th>
                        <th width="20%">Nom</th>
                        <th width="10%">Code</th>
                        <th width="15%">Parent</th>
                        <th width="10%">Utilisateurs</th>
                        <th width="20%">Responsable</th>
                        <th width="10%">Téléphone</th>
                        <th width="15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($localisations as $localisation)
                        @php
                            $typeColor = $localisation->type === 'service' ? 'success' : 
                                        ($localisation->type === 'site' ? 'primary' : 
                                        ($localisation->type === 'direction' ? 'danger' : 
                                        ($localisation->type === 'batiment' ? 'warning' : 
                                        ($localisation->type === 'salle' ? 'info' : 
                                        ($localisation->type === 'bureau' ? 'secondary' : 
                                        ($localisation->type === 'atelier' ? 'dark' : 
                                        ($localisation->type === 'depot' ? 'primary' : 
                                        ($localisation->type === 'laboratoire' ? 'info' : 'secondary'))))))));
                            $typeIcon = $localisation->type === 'service' ? 'fas fa-building' : 
                                       ($localisation->type === 'site' ? 'fas fa-map-marker-alt' : 
                                       ($localisation->type === 'direction' ? 'fas fa-flag' : 
                                       ($localisation->type === 'batiment' ? 'fas fa-warehouse' : 
                                       ($localisation->type === 'salle' ? 'fas fa-door-open' : 
                                       ($localisation->type === 'bureau' ? 'fas fa-desktop' : 
                                       ($localisation->type === 'atelier' ? 'fas fa-tools' : 
                                       ($localisation->type === 'depot' ? 'fas fa-box' : 
                                       ($localisation->type === 'laboratoire' ? 'fas fa-flask' : 'fas fa-folder'))))))));
                        @endphp
                        <tr>
                            <td>
                                <span class="badge bg-{{ $typeColor }}">
                                    <i class="{{ $typeIcon }} me-1"></i>
                                    {{ ucfirst($localisation->type) }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $localisation->nom }}</strong>
                                @if($localisation->description)
                                    <br>
                                    <small class="text-muted">{{ Str::limit($localisation->description, 60) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($localisation->code_geographique)
                                    <code class="bg-light p-1 rounded">{{ $localisation->code_geographique }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($localisation->parent)
                                    @php
                                        $parentColor = $localisation->parent->type === 'service' ? 'success' : 
                                                     ($localisation->parent->type === 'site' ? 'primary' : 
                                                     ($localisation->parent->type === 'direction' ? 'danger' : 
                                                     ($localisation->parent->type === 'batiment' ? 'warning' : 
                                                     ($localisation->parent->type === 'salle' ? 'info' : 
                                                     ($localisation->parent->type === 'bureau' ? 'secondary' : 
                                                     ($localisation->parent->type === 'atelier' ? 'dark' : 
                                                     ($localisation->parent->type === 'depot' ? 'primary' : 
                                                     ($localisation->parent->type === 'laboratoire' ? 'info' : 'secondary'))))))));
                                        $parentIcon = $localisation->parent->type === 'service' ? 'fas fa-building' : 
                                                     ($localisation->parent->type === 'site' ? 'fas fa-map-marker-alt' : 
                                                     ($localisation->parent->type === 'direction' ? 'fas fa-flag' : 
                                                     ($localisation->parent->type === 'batiment' ? 'fas fa-warehouse' : 
                                                     ($localisation->parent->type === 'salle' ? 'fas fa-door-open' : 
                                                     ($localisation->parent->type === 'bureau' ? 'fas fa-desktop' : 
                                                     ($localisation->parent->type === 'atelier' ? 'fas fa-tools' : 
                                                     ($localisation->parent->type === 'depot' ? 'fas fa-box' : 
                                                     ($localisation->parent->type === 'laboratoire' ? 'fas fa-flask' : 'fas fa-folder'))))))));
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <i class="{{ $parentIcon }} text-{{ $parentColor }} me-2"></i>
                                        <span class="text-truncate" style="max-width: 150px;">
                                            {{ $localisation->parent->nom }}
                                        </span>
                                    </div>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-home"></i> Racine
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary rounded-pill me-2">
                                        {{ $localisation->utilisateurs_count }}
                                    </span>
                                    @if($localisation->children()->count() > 0)
                                        <span class="badge bg-success rounded-pill">
                                            <i class="fas fa-sitemap"></i> {{ $localisation->children()->count() }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($localisation->responsable)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle-sm bg-info me-2 d-flex align-items-center justify-content-center">
                                            {{ strtoupper(substr($localisation->responsable->prenom, 0, 1) . substr($localisation->responsable->nom, 0, 1)) }}
                                        </div>
                                        <div>
                                            <small class="d-block">{{ $localisation->responsable->prenom }} {{ $localisation->responsable->nom }}</small>
                                            <small class="text-muted">{{ $localisation->responsable->matricule }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-user-slash"></i> Non assigné
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($localisation->telephone)
                                    <a href="tel:{{ $localisation->telephone }}" class="text-decoration-none">
                                        <i class="fas fa-phone text-success me-1"></i>
                                        {{ $localisation->telephone }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                     <td>
    <div style="
        display:flex;
        align-items:center;
        gap:8px;
    ">

        <!-- Voir -->
        <div style="
            width:36px;
            height:36px;
            background:#0dcaf0;
            border-radius:8px;
            display:flex;
            align-items:center;
            justify-content:center;
            cursor:pointer;
        ">
            <a href="{{ route('admin.services.show', $localisation) }}"
               title="Voir détails"
               style="color:white;text-decoration:none;">
                <i class="fas fa-eye"></i>
            </a>
        </div>

        <!-- Modifier -->
        <div style="
            width:36px;
            height:36px;
            background:#ffc107;
            border-radius:8px;
            display:flex;
            align-items:center;
            justify-content:center;
            cursor:pointer;
        ">
            <a href="{{ route('admin.services.edit', $localisation) }}"
               title="Modifier"
               style="color:black;text-decoration:none;">
                <i class="fas fa-edit"></i>
            </a>
        </div>

        <!-- Utilisateurs -->
        <div style="
            width:36px;
            height:36px;
            background:#6c757d;
            border-radius:8px;
            display:flex;
            align-items:center;
            justify-content:center;
            cursor:pointer;
        ">
            <a href="{{ route('admin.services.gestion-utilisateurs', $localisation) }}"
               title="Gérer les utilisateurs"
               style="color:white;text-decoration:none;">
                <i class="fas fa-users"></i>
            </a>
        </div>

        <!-- Supprimer -->
        @if($localisation->utilisateurs_count == 0 && $localisation->children()->count() == 0)
            <form action="{{ route('admin.services.destroy', $localisation) }}"
                  method="POST" style="margin:0;">
                @csrf
                @method('DELETE')

                <div id="btn-delete-service"
                     class="confirm-delete"
                     title="Supprimer"
                     style="
                        width:36px;
                        height:36px;
                        background:#dc3545;
                        border-radius:8px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        cursor:pointer;
                     "
                     onclick="this.closest('form').submit();">
                    <i class="fas fa-trash" style="color:white;"></i>
                </div>
            </form>
        @endif

    </div>
</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-map-marker-alt fa-3x mb-3 opacity-25"></i>
                                    <p class="mb-2">Aucune localisation trouvée</p>
                                    <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus-circle"></i> Créer la première localisation
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($localisations->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Page navigation">
                    {{ $localisations->withQueryString()->links() }}
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
    
    /* Avatar circulaire */
    .avatar-circle-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Progress bars */
    .progress {
        border-radius: 10px;
        background-color: #f8f9fa;
    }
    
    .progress-bar {
        border-radius: 10px;
    }
    
    /* Table styles */
    .table-hover tbody tr:hover {
        background-color: rgba(3, 81, 188, 0.05);
    }
    
    /* Badges */
    .badge {
        font-size: 12px;
        padding: 5px 10px;
        font-weight: 500;
    }
    
    .badge i {
        font-size: 11px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Confirmation de suppression avec SweetAlert
        $('.confirm-delete').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0351BC',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
        
        // Animation pour les cartes de statistiques
        $('.stat-card').click(function() {
            const cardTitle = $(this).find('.text-uppercase').text().trim();
            const count = $(this).find('.h5').text().trim();
            
            Swal.fire({
                title: cardTitle,
                text: 'Total : ' + count,
                icon: 'info',
                confirmButtonColor: '#0351BC',
                confirmButtonText: 'OK'
            });
        });
        
        // Initialiser les graphiques
        initializeCharts();
    });
    
    function initializeCharts() {
        // Données pour le graphique des types
        const typesData = @json($statistics['types_distribution']);
        
        if (Object.keys(typesData).length > 0) {
            const typesLabels = Object.keys(typesData).map(type => {
                return type.charAt(0).toUpperCase() + type.slice(1);
            });
            const typesCounts = Object.values(typesData);
            
            // Définir les couleurs pour chaque type
            const typeColors = Object.keys(typesData).map(type => {
                switch(type.toLowerCase()) {
                    case 'service': return 'rgba(3, 81, 188, 0.8)';
                    case 'site': return 'rgba(40, 167, 69, 0.8)';
                    case 'direction': return 'rgba(220, 53, 69, 0.8)';
                    case 'batiment': return 'rgba(255, 193, 7, 0.8)';
                    case 'salle': return 'rgba(23, 162, 184, 0.8)';
                    case 'bureau': return 'rgba(108, 117, 125, 0.8)';
                    case 'atelier': return 'rgba(52, 58, 64, 0.8)';
                    case 'depot': return 'rgba(111, 66, 193, 0.8)';
                    case 'laboratoire': return 'rgba(253, 126, 20, 0.8)';
                    default: return 'rgba(158, 158, 158, 0.8)';
                }
            });
            
            // Vérifier si l'élément canvas existe
            const typesCanvas = document.getElementById('typesChart');
            if (typesCanvas) {
                const typesCtx = typesCanvas.getContext('2d');
                new Chart(typesCtx, {
                    type: 'doughnut',
                    data: {
                        labels: typesLabels,
                        datasets: [{
                            data: typesCounts,
                            backgroundColor: typeColors,
                            borderWidth: 1,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }
        
        // Données pour le graphique des utilisateurs
        const topLocalisations = @json($statistics['top_by_users']);
        
        if (topLocalisations.length > 0) {
            const usersLabels = topLocalisations.map(item => {
                const name = item.nom.length > 20 ? item.nom.substring(0, 20) + '...' : item.nom;
                return name + ' (' + (item.type.charAt(0).toUpperCase() + item.type.slice(1)) + ')';
            });
            const usersCounts = topLocalisations.map(item => item.utilisateurs_count);
            
            // Définir les couleurs pour chaque localisation
            const usersColors = topLocalisations.map(item => {
                switch(item.type.toLowerCase()) {
                    case 'service': return 'rgba(3, 81, 188, 0.8)';
                    case 'site': return 'rgba(40, 167, 69, 0.8)';
                    case 'direction': return 'rgba(220, 53, 69, 0.8)';
                    case 'batiment': return 'rgba(255, 193, 7, 0.8)';
                    case 'salle': return 'rgba(23, 162, 184, 0.8)';
                    case 'bureau': return 'rgba(108, 117, 125, 0.8)';
                    case 'atelier': return 'rgba(52, 58, 64, 0.8)';
                    case 'depot': return 'rgba(111, 66, 193, 0.8)';
                    case 'laboratoire': return 'rgba(253, 126, 20, 0.8)';
                    default: return 'rgba(158, 158, 158, 0.8)';
                }
            });
            
            // Vérifier si l'élément canvas existe
            const usersCanvas = document.getElementById('usersChart');
            if (usersCanvas) {
                const usersCtx = usersCanvas.getContext('2d');
                new Chart(usersCtx, {
                    type: 'bar',
                    data: {
                        labels: usersLabels,
                        datasets: [{
                            label: 'Nombre d\'utilisateurs',
                            data: usersCounts,
                            backgroundColor: usersColors,
                            borderColor: usersColors.map(color => color.replace('0.8', '1')),
                            borderWidth: 1
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
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${context.raw}`;
                                    }
                                }
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