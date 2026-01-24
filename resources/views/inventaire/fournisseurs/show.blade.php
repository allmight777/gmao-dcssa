@extends('layouts.admin')

@section('title', 'Détails du fournisseur')

@section('page-title', 'Détails du fournisseur')

@section('page-actions')
<div class="btn-toolbar">
    <a href="{{ route('inventaire.fournisseurs.index') }}" class="btn-return">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
    
    <a href="{{ route('inventaire.fournisseurs.edit', $fournisseur->id) }}" class="btn btn-warning ms-2">
        <i class="fas fa-edit"></i> Modifier
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Informations principales -->
    <div class="col-xl-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle"></i> Informations du fournisseur
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Code Fournisseur:</th>
                                <td><code>{{ $fournisseur->code_fournisseur }}</code></td>
                            </tr>
                            <tr>
                                <th>Raison Sociale:</th>
                                <td><strong>{{ $fournisseur->raison_sociale }}</strong></td>
                            </tr>
                            <tr>
                                <th>Type:</th>
                                <td>
                                    @php
                                        $typeColor = match($fournisseur->type) {
                                            'fabricant' => 'badge bg-primary',
                                            'distributeur' => 'badge bg-info',
                                            'maintenance' => 'badge bg-warning',
                                            default => 'badge bg-secondary'
                                        };
                                    @endphp
                                    <span class="{{ $typeColor }}">{{ ucfirst($fournisseur->type) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Statut:</th>
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
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Évaluation:</th>
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
                            </tr>
                            <tr>
                                <th>1ère commande:</th>
                                <td>
                                    @if($fournisseur->date_premiere_commande)
                                        {{ $fournisseur->date_premiere_commande->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">Aucune</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Dernière commande:</th>
                                <td>
                                    @if($fournisseur->date_derniere_commande)
                                        {{ $fournisseur->date_derniere_commande->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">Aucune</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Créé le:</th>
                                <td>{{ $fournisseur->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coordonnées -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-address-book"></i> Coordonnées
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            @if($fournisseur->adresse)
                                <tr>
                                    <th width="40%">Adresse:</th>
                                    <td>{{ $fournisseur->adresse }}</td>
                                </tr>
                            @endif
                            @if($fournisseur->telephone)
                                <tr>
                                    <th>Téléphone:</th>
                                    <td>
                                        <a href="tel:{{ $fournisseur->telephone }}">{{ $fournisseur->telephone }}</a>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            @if($fournisseur->email)
                                <tr>
                                    <th width="40%">Email:</th>
                                    <td>
                                        <a href="mailto:{{ $fournisseur->email }}">{{ $fournisseur->email }}</a>
                                    </td>
                                </tr>
                            @endif
                            @if($fournisseur->contact_principal)
                                <tr>
                                    <th>Contact Principal:</th>
                                    <td>{{ $fournisseur->contact_principal }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($fournisseur->notes)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sticky-note"></i> Notes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="notes-content">
                        {!! nl2br(e($fournisseur->notes)) !!}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Statistiques -->
    <div class="col-xl-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar"></i> Statistiques
                </h5>
            </div>
            <div class="card-body">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $statistiques['total_equipements'] }}</div>
                            <div class="stat-label">Équipements totaux</div>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $statistiques['actifs'] }}</div>
                            <div class="stat-label">Équipements actifs</div>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ $statistiques['hors_service'] }}</div>
                            <div class="stat-label">Hors service</div>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">{{ number_format($statistiques['valeur_total'], 0, ',', ' ') }} F</div>
                            <div class="stat-label">Valeur totale</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Derniers équipements -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-desktop"></i> Derniers équipements
                </h5>
                <a href="{{ route('inventaire.equipements.index', ['fournisseur_id' => $fournisseur->id]) }}" 
                   class="btn btn-sm btn-outline-primary">
                    Voir tous
                </a>
            </div>
            <div class="card-body">
                @if($fournisseur->equipements && $fournisseur->equipements->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($fournisseur->equipements as $equipement)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $equipement->nom }}</h6>
                                        <small class="text-muted">
                                            {{ $equipement->modele }} • 
                                            @if($equipement->localisation)
                                                {{ $equipement->localisation->nom }}
                                            @endif
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $equipement->statut == 'actif' ? 'success' : 'danger' }}">
                                        {{ $equipement->statut }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-desktop fa-2x mb-3"></i>
                        <p>Aucun équipement associé</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-cogs"></i> Actions
        </h5>
    </div>
    <div class="card-body">
        <div class="d-flex gap-2">
            <!-- Toggle status -->
            <form action="{{ route('inventaire.fournisseurs.toggle-status', $fournisseur->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn {{ $fournisseur->statut == 'actif' ? 'btn-danger' : 'btn-success' }} confirm-action"
                        data-confirm="Êtes-vous sûr de vouloir {{ $fournisseur->statut == 'actif' ? 'désactiver' : 'activer' }} ce fournisseur ?">
                    <i class="fas {{ $fournisseur->statut == 'actif' ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                    {{ $fournisseur->statut == 'actif' ? 'Désactiver' : 'Activer' }}
                </button>
            </form>

            <!-- Delete -->
            <form action="{{ route('inventaire.fournisseurs.destroy', $fournisseur->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger confirm-delete"
                        data-confirm="Êtes-vous sûr de vouloir supprimer ce fournisseur ?">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-return {
        background: var(--white);
        color: var(--medium-gray);
        padding: 10px 20px;
        border: 2px solid var(--light-gray);
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    
    .btn-return:hover {
        background: var(--light-gray);
        border-color: var(--medium-gray);
        transform: translateY(-1px);
    }
    
    .table-borderless th {
        color: var(--medium-gray);
        font-weight: 600;
    }
    
    .notes-content {
        line-height: 1.6;
        white-space: pre-line;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .stat-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: var(--light-gray);
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .stat-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }
    
    .stat-info {
        flex: 1;
    }
    
    .stat-value {
        font-size: 24px;
        font-weight: 700;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 12px;
        color: var(--medium-gray);
        margin-top: 5px;
    }
    
    .list-group-item {
        border: none;
        border-bottom: 1px solid var(--light-gray);
        padding: 15px 0;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
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
    });
</script>
@endpush