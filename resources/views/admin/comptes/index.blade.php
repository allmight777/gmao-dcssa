@extends('layouts.admin')

@section('title', 'Gestion des comptes utilisateurs')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">


@section('page-title', 'Gestion des comptes utilisateurs')
<br><br>
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
<div class="card">
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
                                    <span class="statut-actif">
                                        <i class="fas fa-circle"></i> Actif
                                    </span>
                                @elseif($utilisateur->statut == 'inactif')
                                    <span class="statut-inactif">
                                        <i class="fas fa-circle"></i> Inactif
                                    </span>
                                @else
                                    <span class="statut-suspendu">
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
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.comptes.show', $utilisateur->id) }}" 
                                       class="btn btn-sm btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.comptes.edit', $utilisateur->id) }}" 
                                       class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Toggle status -->
                                    <form action="{{ route('admin.comptes.toggle-status', $utilisateur->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-sm {{ $utilisateur->statut == 'actif' ? 'btn-danger' : 'btn-success' }} confirm-action"
                                                title="{{ $utilisateur->statut == 'actif' ? 'Désactiver' : 'Activer' }}"
                                                data-confirm="Êtes-vous sûr de vouloir {{ $utilisateur->statut == 'actif' ? 'désactiver' : 'activer' }} ce compte ?">
                                            <i class="fas {{ $utilisateur->statut == 'actif' ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                        </button>
                                    </form>
                                    
                                    <!-- Reset password modal trigger -->
                                    <button type="button" class="btn btn-sm btn-secondary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#resetPasswordModal{{ $utilisateur->id }}"
                                            title="Réinitialiser le mot de passe">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    
                                    <!-- Delete -->
                                    @if($utilisateur->id != auth()->id())
                                        <form action="{{ route('admin.comptes.destroy', $utilisateur->id) }}" 
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
                                
                                <!-- Reset Password Modal -->
                                <div class="modal fade" id="resetPasswordModal{{ $utilisateur->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.comptes.reset-password', $utilisateur->id) }}" method="POST">
                                                @csrf
                                                @method('POST')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Réinitialiser le mot de passe</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Vous allez réinitialiser le mot de passe de :</p>
                                                    <p><strong>{{ $utilisateur->nom_complet }}</strong> ({{ $utilisateur->matricule }})</p>
                                                    <p class="text-muted">Un email sera envoyé à l'utilisateur avec le nouveau mot de passe.</p>
                                                    
                                                    <div class="mb-3">
                                                        <label for="password{{ $utilisateur->id }}" class="form-label">Nouveau mot de passe</label>
                                                        <input type="password" class="form-control" id="password{{ $utilisateur->id }}" 
                                                               name="password" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="password_confirmation{{ $utilisateur->id }}" class="form-label">Confirmation</label>
                                                        <input type="password" class="form-control" 
                                                               id="password_confirmation{{ $utilisateur->id }}" 
                                                               name="password_confirmation" required>
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

@push('scripts')
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
    });
</script>
@endpush