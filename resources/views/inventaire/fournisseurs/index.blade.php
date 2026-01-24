@extends('layouts.admin')

@section('title', 'Gestion des fournisseurs')

@section('page-title', 'Fournisseurs')

@section('page-actions')
<div class="btn-toolbar">
    <a href="{{ route('inventaire.fournisseurs.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Nouveau fournisseur
    </a>
    
    <a href="{{ route('inventaire.fournisseurs.export') }}" class="btn btn-success ms-2">
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
                            Total Fournisseurs
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ number_format($statistiques['total']) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck fa-2x text-primary opacity-50"></i>
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
                            Fournisseurs Actifs
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ $statistiques['actif'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
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
                            Fabricants
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ $statistiques['fabricants'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-industry fa-2x text-info opacity-50"></i>
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
                            Évaluation Excellent
                        </div>
                        <div class="h5 mb-0 fw-bold text-gray-800">
                            {{ $statistiques['excellents'] }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-star fa-2x text-warning opacity-50"></i>
                    </div>
                </div>
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
        <form method="GET" action="{{ route('inventaire.fournisseurs.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" 
                       placeholder="Raison sociale, code, contact..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="type" class="form-control">
                    <option value="">Tous types</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="statut" class="form-control">
                    <option value="">Tous statuts</option>
                    @foreach($statuts as $key => $label)
                        <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="evaluation" class="form-control">
                    <option value="">Toutes évaluations</option>
                    @foreach($evaluations as $key => $label)
                        <option value="{{ $key }}" {{ request('evaluation') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Rechercher
                </button>
                @if(request()->hasAny(['search', 'type', 'statut', 'evaluation']))
                    <a href="{{ route('inventaire.fournisseurs.index') }}" class="btn btn-secondary w-100 mt-2">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Liste des fournisseurs -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-truck"></i> Liste des fournisseurs
        </h5>
        <div class="text-muted">
            {{ $fournisseurs->total() }} fournisseur(s) trouvé(s)
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Raison Sociale</th>
                        <th>Type</th>
                        <th>Contact</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Évaluation</th>
                        <th>Dernière commande</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fournisseurs as $fournisseur)
                        <tr>
                            <td>
                                <code>{{ $fournisseur->code_fournisseur }}</code>
                            </td>
                            <td>
                                <strong>{{ $fournisseur->raison_sociale }}</strong>
                                @if($fournisseur->adresse)
                                    <br><small class="text-muted">{{ Str::limit($fournisseur->adresse, 40) }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $typeColor = match($fournisseur->type) {
                                        'fabricant' => 'bg-primary',
                                        'distributeur' => 'bg-info',
                                        'maintenance' => 'bg-warning',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $typeColor }}">
                                    {{ $types[$fournisseur->type] ?? $fournisseur->type }}
                                </span>
                            </td>
                            <td>{{ $fournisseur->contact_principal ?? 'N/A' }}</td>
                            <td>
                                @if($fournisseur->telephone)
                                    <a href="tel:{{ $fournisseur->telephone }}">{{ $fournisseur->telephone }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($fournisseur->email)
                                    <a href="mailto:{{ $fournisseur->email }}">{{ $fournisseur->email }}</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($fournisseur->statut == 'actif')
                                    <span class="badge bg-success">
                                        <i class="fas fa-circle"></i> Actif
                                    </span>
                                @elseif($fournisseur->statut == 'inactif')
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
                                @if($fournisseur->evaluation)
                                    @php
                                        $evalColor = match($fournisseur->evaluation) {
                                            'excellent' => 'bg-success',
                                            'bon' => 'bg-info',
                                            'moyen' => 'bg-warning',
                                            'mauvais' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $evalColor }}">
                                        <i class="fas fa-star"></i> {{ ucfirst($fournisseur->evaluation) }}
                                    </span>
                                @else
                                    <span class="text-muted">Non évalué</span>
                                @endif
                            </td>
                            <td>
                                @if($fournisseur->date_derniere_commande)
                                    {{ $fournisseur->date_derniere_commande->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Aucune</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:4px;">
                                    <a href="{{ route('inventaire.fournisseurs.show', $fournisseur->id) }}"
                                       class="btn btn-sm btn-info"
                                       title="Voir détails"
                                       style="width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-eye" style="font-size:14px;"></i>
                                    </a>

                                    <a href="{{ route('inventaire.fournisseurs.edit', $fournisseur->id) }}"
                                       class="btn btn-sm btn-warning"
                                       title="Modifier"
                                       style="width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-edit" style="font-size:14px;"></i>
                                    </a>

                                    <!-- Toggle status -->
                                    <form action="{{ route('inventaire.fournisseurs.toggle-status', $fournisseur->id) }}"
                                          method="POST"
                                          style="display:inline-flex;align-items:center;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="btn btn-sm {{ $fournisseur->statut == 'actif' ? 'btn-secondary' : 'btn-success' }} confirm-action"
                                            title="{{ $fournisseur->statut == 'actif' ? 'Désactiver' : 'Activer' }}"
                                            data-confirm="Êtes-vous sûr de vouloir {{ $fournisseur->statut == 'actif' ? 'désactiver' : 'activer' }} ce fournisseur ?"
                                            style="width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas {{ $fournisseur->statut == 'actif' ? 'fa-toggle-off' : 'fa-toggle-on' }}"
                                               style="font-size:14px;"></i>
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form action="{{ route('inventaire.fournisseurs.destroy', $fournisseur->id) }}"
                                          method="POST"
                                          style="display:inline-flex;align-items:center;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm btn-danger confirm-delete"
                                            title="Supprimer"
                                            data-confirm="Êtes-vous sûr de vouloir supprimer ce fournisseur ?"
                                            style="width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-trash" style="font-size:14px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-truck fa-2x mb-3"></i>
                                    <p>Aucun fournisseur trouvé</p>
                                    @if(request()->hasAny(['search', 'type', 'statut', 'evaluation']))
                                        <a href="{{ route('inventaire.fournisseurs.index') }}" class="btn btn-primary mt-2">
                                            Réinitialiser les filtres
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($fournisseurs->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Affichage de {{ $fournisseurs->firstItem() }} à {{ $fournisseurs->lastItem() }} sur {{ $fournisseurs->total() }} résultats
                </div>
                <nav aria-label="Page navigation">
                    {{ $fournisseurs->withQueryString()->links() }}
                </nav>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .badge i.fa-circle {
        font-size: 0.7em;
        margin-right: 5px;
    }
    
    .table tbody tr:hover {
        background-color: rgba(0,0,0,0.02);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Confirmation pour les actions
        $('.confirm-action, .confirm-delete').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const message = $(this).data('confirm') || 'Êtes-vous sûr de vouloir effectuer cette action ?';
            
            Swal.fire({
                title: 'Confirmation',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0351BC',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Confirmer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
        
        // Initialiser Select2 pour les filtres
        $('select[name="type"], select[name="statut"], select[name="evaluation"]').select2({
            theme: 'bootstrap-5',
            placeholder: 'Sélectionnez...',
            allowClear: true
        });
    });
</script>
@endpush