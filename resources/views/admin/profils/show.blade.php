@extends('layouts.admin')

@section('title', 'Détails du profil')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

@section('page-title', 'Détails du profil')
@push('styles')
<style>
    :root {
        --primary-color: #0351BC;
        --primary-light: #4a7fd4;
        --primary-dark: #023a8a;
        --black: #000000;
        --dark-gray: #1a1a1a;
        --medium-gray: #333333;
        --light-gray: #f5f5f5;
        --white: #ffffff;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --card-bg: #ffffff;
    }

    .avatar-circle-lg {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 32px;
    }
    
    .avatar-circle-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 500;
        font-size: 14px;
    }
    
    .badge-container {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    
    .module-icon {
        width: 24px;
        text-align: center;
    }
    
    .permission-icon {
        width: 20px;
        text-align: center;
    }
    
    .empty-state {
        text-align: center;
        padding: 30px 20px;
    }
    
    /* Timeline styles */
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--light-gray);
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 0;
        width: 30px;
        height: 30px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--light-gray);
    }
    
    .timeline-content {
        background: white;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid var(--light-gray);
    }
    
    .accordion-button:not(.collapsed) {
        background-color: rgba(3, 81, 188, 0.05);
        color: var(--primary-color);
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: var(--primary-light);
    }
    
    .card-header {
        border-bottom: 2px solid rgba(0,0,0,0.1);
    }
    
    @media (max-width: 768px) {
        .avatar-circle-lg {
            width: 60px;
            height: 60px;
            font-size: 24px;
        }
        
        .timeline {
            padding-left: 20px;
        }
        
        .timeline:before {
            left: 10px;
        }
        
        .timeline-marker {
            left: -20px;
            width: 20px;
            height: 20px;
            font-size: 12px;
        }
        
        .timeline-content {
            padding: 10px;
        }
        
        .btn-group {
            flex-wrap: wrap;
            gap: 5px;
        }
    }
</style>
@endpush


@section('page-actions')
<a href="{{ route('admin.profils.index') }}" class="btn-return">
    <i class="fas fa-arrow-left"></i> Retour à la liste
</a>
@endsection

@section('content')
<div class="row">
    <!-- Informations du profil -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-id-card me-2"></i> Informations du profil
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-circle-lg bg-primary mx-auto mb-3">
                        {{ strtoupper(substr($profil->nom_profil, 0, 1)) }}
                    </div>
                    <h4 class="mb-1">{{ $profil->nom_profil }}</h4>
                    <div class="badge-container justify-content-center mb-3">
                        @if($profil->is_default)
                            <span class="badge bg-info">Profil par défaut</span>
                        @endif
                        @if($profil->nom_profil == 'admin')
                            <span class="badge bg-danger">Administrateur</span>
                        @endif
                        <span class="badge bg-secondary">ID: {{ $profil->id }}</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <div class="card bg-light border-0 p-3">
                        <p class="mb-0">{{ $profil->description ?? 'Aucune description' }}</p>
                    </div>
                </div>
                
                <div class="row text-center mb-4">
                    <div class="col-6">
                        <div class="card border-primary border-1">
                            <div class="card-body py-3">
                                <h3 class="text-primary mb-1">{{ $profil->utilisateurs_count }}</h3>
                                <small class="text-muted">Utilisateurs</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-secondary border-1">
                            <div class="card-body py-3">
                                <h3 class="text-secondary mb-1">{{ $profil->permissions->count() }}</h3>
                                <small class="text-muted">Permissions</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Statistiques</label>
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Créé le</span>
                            <span class="fw-semibold">{{ $profil->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Dernière modification</span>
                            <span class="fw-semibold">{{ $profil->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($profil->deleted_at)
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-danger bg-opacity-10">
                            <span>Désactivé le</span>
                            <span class="fw-semibold text-danger">{{ $profil->deleted_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.profils.edit', $profil) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i> Modifier le profil
                    </a>
                
                    @if($profil->nom_profil != 'admin' && $profil->utilisateurs_count == 0)
                        <form action="{{ route('admin.profils.destroy', $profil) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger confirm-delete"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce profil ?')">
                                <i class="fas fa-trash me-2"></i> Supprimer le profil
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Permissions -->
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-key me-2"></i> Permissions accordées
                    </h5>
                    <span class="badge bg-light text-primary">
                        {{ $profil->permissions->count() }} permission(s)
                    </span>
                </div>
            </div>
            <div class="card-body">
                @if($profil->permissions->count() > 0)
                    <div class="accordion" id="permissionsAccordion">
                        @foreach($permissionsParModule as $module => $permissions)
                            @php
                                $moduleName = $modules[$module] ?? ucfirst($module);
                                $permissionCount = $permissions->count();
                            @endphp
                            
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $module }}">
                                    <button class="accordion-button collapsed" type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#collapse{{ $module }}"
                                            aria-expanded="false" 
                                            aria-controls="collapse{{ $module }}">
                                        <div class="d-flex w-100 align-items-center">
                                            <span class="module-icon me-3">
                                                @switch($module)
                                                    @case('administration')
                                                        <i class="fas fa-cogs text-primary"></i>
                                                        @break
                                                    @case('utilisateurs')
                                                        <i class="fas fa-users text-info"></i>
                                                        @break
                                                    @case('profils')
                                                        <i class="fas fa-user-tag text-warning"></i>
                                                        @break
                                                    @case('comptes')
                                                        <i class="fas fa-user-cog text-success"></i>
                                                        @break
                                                    @case('inventaire')
                                                        <i class="fas fa-boxes text-danger"></i>
                                                        @break
                                                    @case('stock')
                                                        <i class="fas fa-warehouse text-secondary"></i>
                                                        @break
                                                    @case('maintenance')
                                                        <i class="fas fa-tools text-info"></i>
                                                        @break
                                                    @case('rapports')
                                                        <i class="fas fa-chart-bar text-success"></i>
                                                        @break
                                                    @case('configuration')
                                                        <i class="fas fa-cog text-dark"></i>
                                                        @break
                                                    @case('formation')
                                                        <i class="fas fa-graduation-cap text-warning"></i>
                                                        @break
                                                    @default
                                                        <i class="fas fa-cube text-muted"></i>
                                                @endswitch
                                            </span>
                                            <div class="flex-grow-1">
                                                <strong>{{ $moduleName }}</strong>
                                                <div class="text-muted small">
                                                    {{ $permissionCount }} permission(s)
                                                </div>
                                            </div>
                                            <span class="badge bg-primary rounded-pill ms-2">
                                                {{ $permissionCount }}
                                            </span>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{ $module }}" 
                                     class="accordion-collapse collapse" 
                                     aria-labelledby="heading{{ $module }}"
                                     data-bs-parent="#permissionsAccordion">
                                    <div class="accordion-body">
                                        <div class="row">
                                            @foreach($permissions as $permission)
                                                <div class="col-md-6 col-lg-4 mb-2">
                                                    <div class="card border-0 bg-light">
                                                        <div class="card-body py-2">
                                                            <div class="d-flex align-items-center">
                                                                <div class="permission-icon me-2">
                                                                    @switch($permission->action)
                                                                        @case('view')
                                                                            <i class="fas fa-eye text-success"></i>
                                                                            @break
                                                                        @case('create')
                                                                            <i class="fas fa-plus-circle text-primary"></i>
                                                                            @break
                                                                        @case('edit')
                                                                            <i class="fas fa-edit text-warning"></i>
                                                                            @break
                                                                        @case('delete')
                                                                            <i class="fas fa-trash text-danger"></i>
                                                                            @break
                                                                        @case('export')
                                                                            <i class="fas fa-download text-info"></i>
                                                                            @break
                                                                        @case('import')
                                                                            <i class="fas fa-upload text-secondary"></i>
                                                                            @break
                                                                        @case('print')
                                                                            <i class="fas fa-print text-dark"></i>
                                                                            @break
                                                                        @case('manage')
                                                                            <i class="fas fa-cog text-success"></i>
                                                                            @break
                                                                        @case('validate')
                                                                            <i class="fas fa-check-circle text-primary"></i>
                                                                            @break
                                                                        @case('transfer')
                                                                            <i class="fas fa-exchange-alt text-info"></i>
                                                                            @break
                                                                        @case('order')
                                                                            <i class="fas fa-shopping-cart text-warning"></i>
                                                                            @break
                                                                        @case('receive')
                                                                            <i class="fas fa-box-open text-success"></i>
                                                                            @break
                                                                        @default
                                                                            <i class="fas fa-check text-muted"></i>
                                                                    @endswitch
                                                                </div>
                                                                <div>
                                                                    <div class="fw-semibold">
                                                                        {{ ucfirst($permission->action) }}
                                                                    </div>
                                                                    <small class="text-muted">
                                                                        {{ $permission->created_at->format('d/m/Y') }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-key fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune permission</h5>
                            <p class="text-muted mb-3">Ce profil n'a aucune permission attribuée.</p>
                            <a href="{{ route('admin.profils.permissions.edit', $profil) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Ajouter des permissions
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Liste des utilisateurs -->
@if($profil->utilisateurs_count > 0)
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-users me-2"></i> Utilisateurs avec ce profil
            </h5>
            <span class="badge bg-light text-primary">
                {{ $profil->utilisateurs_count }} utilisateur(s)
            </span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nom complet</th>
                        <th>Matricule</th>
                        <th>Email</th>
                        <th>Fonction</th>
                        <th>Statut</th>
                        <th>Dernière connexion</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profil->utilisateurs as $utilisateur)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle-sm bg-primary me-2">
                                    {{ strtoupper(substr($utilisateur->prenom, 0, 1) . substr($utilisateur->nom, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</strong>
                                    <div class="text-muted small">{{ $utilisateur->login }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $utilisateur->matricule }}</td>
                        <td>{{ $utilisateur->email }}</td>
                        <td>{{ $utilisateur->fonction }}</td>
                        <td>
                            <span class="badge bg-{{ $utilisateur->statut == 'actif' ? 'success' : ($utilisateur->statut == 'inactif' ? 'warning' : 'danger') }}">
                                {{ ucfirst($utilisateur->statut) }}
                            </span>
                        </td>
                        <td>
                            @if($utilisateur->last_login_at)
                                {{ $utilisateur->last_login_at->format('d/m/Y H:i') }}
                            @else
                                <span class="text-muted">Jamais</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.comptes.show', $utilisateur) }}" 
                                   class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.comptes.edit', $utilisateur) }}" 
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Activités récentes -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-history me-2"></i> Activités récentes sur ce profil
        </h5>
    </div>
    <div class="card-body">
        @php
            $activites = \App\Models\LogActivite::where('id_element', $profil->id)
                ->where('module', 'administration')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        @endphp
        
        @if($activites->count() > 0)
            <div class="timeline">
                @foreach($activites as $activite)
                <div class="timeline-item">
                    <div class="timeline-marker">
                        @switch($activite->action)
                            @case('creation_profil')
                                <i class="fas fa-plus-circle text-success"></i>
                                @break
                            @case('modification_profil')
                                <i class="fas fa-edit text-warning"></i>
                                @break
                            @case('modification_permissions')
                                <i class="fas fa-key text-primary"></i>
                                @break
                            @case('duplication_profil')
                                <i class="fas fa-copy text-info"></i>
                                @break
                            @default
                                <i class="fas fa-history text-muted"></i>
                        @endswitch
                    </div>
                    <div class="timeline-content">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1">{{ $activite->details }}</h6>
                            <small class="text-muted">{{ $activite->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <p class="mb-1 small text-muted">
                            <i class="fas fa-user me-1"></i>
                            {{ $activite->utilisateur->prenom ?? 'Utilisateur' }} {{ $activite->utilisateur->nom ?? '' }}
                        </p>
                        <small class="text-muted">
                            <i class="fas fa-globe me-1"></i>
                            {{ $activite->adresse_ip }}
                        </small>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-3">
                <p class="text-muted mb-0">Aucune activité récente</p>
            </div>
        @endif
    </div>
</div>
@endsection


@push('scripts')
<script>
    $(document).ready(function() {
        // Initialiser les tooltips
        $('[title]').tooltip();
        
        // Ouvrir le premier accordéon
        $('#permissionsAccordion .accordion-collapse:first').addClass('show');
        $('#permissionsAccordion .accordion-button:first').removeClass('collapsed');
        
        // Confirmation de suppression
        $('.confirm-delete').on('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce profil ? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
        
        // Animation pour les timelines
        $('.timeline-item').each(function(index) {
            $(this).css('animation-delay', (index * 0.1) + 's');
        });
    });
</script>
@endpush