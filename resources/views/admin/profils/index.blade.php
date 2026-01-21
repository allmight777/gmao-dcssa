@extends('layouts.admin')

@section('title', 'Gestion des profils et permissions')

@section('page-title', 'Gestion des profils et permissions')

@section('page-actions')
<div class="btn-toolbar">
    <a href="{{ route('admin.profils.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Nouveau profil
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter"></i> Filtres de recherche
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.profils.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" 
                       placeholder="Nom du profil, description..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

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
                        <th>Utilisateurs</th>
                        <th>Permissions</th>
                        <th>Créé le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($profils as $profil)
                        <tr>
                            <td>
                                <strong>{{ $profil->nom_profil }}</strong>
                                @if($profil->is_default)
                                    <span class="badge bg-info ms-1">Par défaut</span>
                                @endif
                                @if($profil->nom_profil == 'admin')
                                    <span class="badge bg-danger ms-1">Administrateur</span>
                                @endif
                            </td>
                            <td>{{ $profil->description }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $profil->utilisateurs_count }}</span> utilisateur(s)
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $profil->permissions_count ?? 0 }}</span> permission(s)
                            </td>
                            <td>{{ $profil->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.profils.show', $profil) }}" 
                                       class="btn btn-sm btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.profils.edit', $profil) }}" 
                                       class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.profils.permissions.edit', $profil) }}" 
                                       class="btn btn-sm btn-secondary" title="Gérer les permissions">
                                        <i class="fas fa-key"></i>
                                    </a>
                                    
                                    <!-- Duplicate modal trigger -->
                                    <button type="button" class="btn btn-sm btn-success" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#duplicateModal{{ $profil->id }}"
                                            title="Dupliquer">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    
                                    <!-- Delete -->
                                    @if($profil->nom_profil != 'admin' && $profil->utilisateurs_count == 0)
                                        <form action="{{ route('admin.profils.destroy', $profil) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger confirm-delete"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-outline-danger" disabled 
                                                title="Ce profil ne peut pas être supprimé">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                                
                                <!-- Duplicate Modal -->
                                <div class="modal fade" id="duplicateModal{{ $profil->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.profils.duplicate', $profil) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Dupliquer le profil</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Vous allez dupliquer le profil : <strong>{{ $profil->nom_profil }}</strong></p>
                                                    <p>Toutes les permissions seront copiées.</p>
                                                    
                                                    <div class="mb-3">
                                                        <label for="nouveau_nom{{ $profil->id }}" class="form-label">Nom du nouveau profil *</label>
                                                        <input type="text" class="form-control" id="nouveau_nom{{ $profil->id }}" 
                                                               name="nouveau_nom" required
                                                               placeholder="Ex: {{ $profil->nom_profil }}_copie">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-primary">Dupliquer</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-user-tag fa-2x mb-3"></i>
                                    <p>Aucun profil trouvé</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($profils->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Page navigation">
                    {{ $profils->withQueryString()->links() }}
                </nav>
            </div>
        @endif
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-info-circle"></i> À propos des profils
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card border-primary">
                    <div class="card-body">
                        <h6 class="card-title text-primary">
                            <i class="fas fa-user-shield"></i> Profil Administrateur
                        </h6>
                        <p class="card-text small">Accès complet à toutes les fonctionnalités du système. Ne peut pas être supprimé.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-success">
                    <div class="card-body">
                        <h6 class="card-title text-success">
                            <i class="fas fa-clipboard-list"></i> Gestionnaire d'Inventaire
                        </h6>
                        <p class="card-text small">Gestion des équipements, inventaire, réformes et mouvements.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-info">
                    <div class="card-body">
                        <h6 class="card-title text-info">
                            <i class="fas fa-wrench"></i> Technicien
                        </h6>
                        <p class="card-text small">Interventions de maintenance, rapports techniques et demandes de pièces.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-focus sur le champ du nom dans les modals
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('input[type="text"]').first().focus();
        });
    });
</script>
@endpush