@extends('layouts.admin')

@section('title', 'Gestion des services et localisations')

@section('page-title', 'Gestion des services et localisations')

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
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" 
                       placeholder="Nom, code géographique, description..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="parent_id" class="form-control">
                    <option value="">Tous les parents</option>
                    @foreach($servicesList as $service)
                        <option value="{{ $service->id }}" 
                                {{ request('parent_id') == $service->id ? 'selected' : '' }}>
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
            <div class="col-md-2">
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-undo"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistiques -->
<div class="row mt-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-primary border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                            Total Services
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ \App\Models\Localisation::service()->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
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
                            Total Sites
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ \App\Models\Localisation::site()->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-map-marker-alt fa-2x text-gray-300"></i>
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
                            Total Bâtiments
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ \App\Models\Localisation::batiment()->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-info border-4 shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">
                            Avec Responsable
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ \App\Models\Localisation::whereNotNull('responsable_id')->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des services -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-list"></i> Liste des services et localisations
        </h5>
        <div class="text-muted">
            {{ $services->total() }} élément(s) trouvé(s)
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Type</th>
                        <th>Nom</th>
                        <th>Code</th>
                        <th>Parent</th>
                        <th>Utilisateurs</th>
                        <th>Responsable</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>
                                <span class="badge bg-{{ getTypeColor($service->type) }}">
                                    {{ ucfirst($service->type) }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $service->nom }}</strong>
                                @if($service->description)
                                    <br>
                                    <small class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <code>{{ $service->code_geographique }}</code>
                            </td>
                            <td>
                                @if($service->parent)
                                    <span class="text-muted">{{ $service->parent->nom }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $service->utilisateurs_count }}</span>
                            </td>
                            <td>
                                @if($service->responsable)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle-sm bg-info me-2">
                                            {{ strtoupper(substr($service->responsable->prenom, 0, 1) . substr($service->responsable->nom, 0, 1)) }}
                                        </div>
                                        <div>
                                            <small>{{ $service->responsable->prenom }} {{ $service->responsable->nom }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $service->telephone }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.services.show', $service) }}" 
                                       class="btn btn-sm btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.services.edit', $service) }}" 
                                       class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.services.gestion-utilisateurs', $service) }}" 
                                       class="btn btn-sm btn-secondary" title="Gérer les utilisateurs">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    @if($service->utilisateurs_count == 0 && $service->children()->count() == 0)
                                        <form action="{{ route('admin.services.destroy', $service) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger confirm-delete"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-building fa-2x mb-3"></i>
                                    <p>Aucun service/localisation trouvé</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($services->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Page navigation">
                    {{ $services->withQueryString()->links() }}
                </nav>
            </div>
        @endif
    </div>
</div>

<!-- Arborescence hiérarchique -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-sitemap"></i> Arborescence hiérarchique
        </h5>
    </div>
    <div class="card-body">
        <div id="hierarchyTree">
            @php
                function displayHierarchy($items, $level = 0) {
                    foreach ($items as $item) {
                        $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
                        $hasChildren = $item->children->count() > 0;
                        
                        echo '<div class="mb-2">';
                        echo $indent;
                        
                        if ($hasChildren) {
                            echo '<i class="fas fa-folder text-warning me-1"></i>';
                        } else {
                            echo '<i class="fas fa-file text-secondary me-1"></i>';
                        }
                        
                        echo '<span class="badge bg-' . getTypeColor($item->type) . ' me-2">' . ucfirst($item->type) . '</span>';
                        echo '<strong>' . $item->nom . '</strong>';
                        
                        if ($item->code_geographique) {
                            echo ' <small class="text-muted">(' . $item->code_geographique . ')</small>';
                        }
                        
                        if ($item->responsable) {
                            echo ' <small class="text-muted"><i class="fas fa-user-tie ms-2"></i> ' . $item->responsable->prenom . ' ' . $item->responsable->nom . '</small>';
                        }
                        
                        echo '</div>';
                        
                        if ($hasChildren) {
                            displayHierarchy($item->children, $level + 1);
                        }
                    }
                }
                
                function getTypeColor($type) {
                    switch($type) {
                        case 'site': return 'primary';
                        case 'direction': return 'danger';
                        case 'batiment': return 'warning';
                        case 'service': return 'success';
                        case 'salle': return 'info';
                        case 'bureau': return 'secondary';
                        case 'atelier': return 'dark';
                        case 'depot': return 'primary';
                        case 'laboratoire': return 'info';
                        default: return 'secondary';
                    }
                }
                
                $rootItems = \App\Models\Localisation::whereNull('parent_id')
                    ->with('children.children.children')
                    ->orderBy('type')
                    ->orderBy('nom')
                    ->get();
            @endphp
            
            @if($rootItems->count() > 0)
                {!! displayHierarchy($rootItems) !!}
            @else
                <div class="text-center text-muted py-3">
                    <p>Aucune hiérarchie disponible</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-circle-sm {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 500;
        font-size: 11px;
    }
    
    #hierarchyTree {
        font-family: 'Courier New', monospace;
        font-size: 14px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Confirmation de suppression
        $('.confirm-delete').on('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                e.preventDefault();
            }
        });
    });
    
    // Helper function pour les couleurs des types (pour JavaScript)
    function getTypeColorJS(type) {
        switch(type) {
            case 'site': return 'primary';
            case 'direction': return 'danger';
            case 'batiment': return 'warning';
            case 'service': return 'success';
            case 'salle': return 'info';
            case 'bureau': return 'secondary';
            case 'atelier': return 'dark';
            case 'depot': return 'primary';
            case 'laboratoire': return 'info';
            default: return 'secondary';
        }
    }
</script>
@endpush