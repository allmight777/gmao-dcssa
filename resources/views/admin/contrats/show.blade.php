@extends('layouts.admin')

@section('title', 'Détails du contrat ' . $contrat->Numero_Contrat)

@push('styles')
<style>
    .detail-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .detail-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px 10px 0 0;
        font-weight: 600;
        padding: 15px 20px;
    }
    .info-row {
        display: flex;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    .info-label {
        font-weight: 600;
        width: 200px;
        color: #666;
    }
    .info-value {
        flex: 1;
    }
    .status-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
    }
    .status-actif { background: linear-gradient(45deg, #28a745, #218838); color: white; }
    .status-expire { background: linear-gradient(45deg, #dc3545, #c82333); color: white; }
    .status-resilie { background: linear-gradient(45deg, #6c757d, #5a6268); color: white; }
    .status-renouvellement { background: linear-gradient(45deg, #ffc107, #e0a800); color: #212529; }
    .status-brouillon { background: linear-gradient(45deg, #17a2b8, #138496); color: white; }

    .document-preview {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        background: #f8f9fa;
    }
    .document-preview i {
        font-size: 3rem;
        color: #dc3545;
        margin-bottom: 10px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">
                <i class="fas fa-file-contract me-2"></i>Contrat {{ $contrat->Numero_Contrat }}
            </h1>
            <p class="text-muted mb-0">{{ $contrat->Libelle }}</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.contrats.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <a href="{{ route('admin.contrats.edit', $contrat->ID_Contrat) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            <a href="{{ route('admin.contrats.pdf', $contrat->ID_Contrat) }}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf me-2"></i>PDF
            </a>
            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-cog me-2"></i>Actions
            </button>
            <ul class="dropdown-menu">
                <li>
                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                        <i class="fas fa-exchange-alt me-2 text-warning"></i>Changer statut
                    </button>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('admin.contrats.destroy', $contrat->ID_Contrat) }}" method="POST"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce contrat ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <!-- Statut et jours restants -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card detail-card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Statut actuel</h6>
                    <h2>
                        <span class="status-badge status-{{ $contrat->Statut }}">
                            {{ $contrat->statut_avec_couleur['text'] }}
                        </span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card detail-card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Jours restants</h6>
                    @if($contrat->estActif() && $contrat->jours_restants)
                        @if($contrat->jours_restants <= 0)
                            <h2 class="text-danger">Expiré</h2>
                        @elseif($contrat->jours_restants <= 7)
                            <h2 class="text-warning">{{ $contrat->jours_restants }} jours</h2>
                            <small class="text-muted">Renouvellement urgent</small>
                        @else
                            <h2 class="text-success">{{ $contrat->jours_restants }} jours</h2>
                        @endif
                    @else
                        <h2 class="text-muted">-</h2>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card detail-card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Montant</h6>
                    <h2 class="text-primary">{{ $contrat->montant_formate }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card detail-card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Équipements couverts</h6>
                    <h2>{{ $contrat->equipements->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations générales -->
        <div class="col-md-6">
            <div class="card detail-card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i>Informations générales
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">Numéro contrat</div>
                        <div class="info-value">{{ $contrat->Numero_Contrat }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Libellé</div>
                        <div class="info-value">{{ $contrat->Libelle }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Type</div>
                        <div class="info-value">{{ ucfirst($contrat->Type) }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Fournisseur</div>
                        <div class="info-value">
                            <a href="{{ route('inventaire.fournisseurs.show', $contrat->fournisseur->id) }}">
                                {{ $contrat->fournisseur->raison_sociale }}
                            </a>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Périodicité</div>
                        <div class="info-value">{{ ucfirst($contrat->Periodicite_Interventions) }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Délai intervention</div>
                        <div class="info-value">{{ $contrat->Delai_Intervention_Garanti }} heures</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Période et montant -->
        <div class="col-md-6">
            <div class="card detail-card">
                <div class="card-header">
                    <i class="fas fa-calendar-alt me-2"></i>Période et montant
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">Date de début</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($contrat->Date_Debut)->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date de fin</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($contrat->Date_Fin)->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Montant</div>
                        <div class="info-value">{{ $contrat->montant_formate }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date alerte</div>
                        <div class="info-value">
                            @if($contrat->Date_Alerte_Renouvellement)
                                {{ \Carbon\Carbon::parse($contrat->Date_Alerte_Renouvellement)->format('d/m/Y') }}
                            @else
                                <span class="text-muted">Non définie</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Alerte envoyée</div>
                        <div class="info-value">
                            @if($contrat->Alerte_envoyee)
                                <span class="badge bg-success">Oui</span>
                                <small>({{ \Carbon\Carbon::parse($contrat->Date_derniere_alerte)->format('d/m/Y H:i') }})</small>
                            @else
                                <span class="badge bg-secondary">Non</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Couverture -->
        <div class="col-md-6">
            <div class="card detail-card">
                <div class="card-header">
                    <i class="fas fa-shield-alt me-2"></i>Couverture
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">Couverture pièces</div>
                        <div class="info-value">
                            @if($contrat->Couverture_Pieces)
                                <span class="badge bg-success">Oui</span>
                            @else
                                <span class="badge bg-danger">Non</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Couverture main d'œuvre</div>
                        <div class="info-value">
                            @if($contrat->Couverture_Main_Oeuvre)
                                <span class="badge bg-success">Oui</span>
                            @else
                                <span class="badge bg-danger">Non</span>
                            @endif
                        </div>
                    </div>
                    @if($contrat->Exclusions)
                        <div class="info-row">
                            <div class="info-label">Exclusions</div>
                            <div class="info-value">{{ $contrat->Exclusions }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dates création/modification -->
        <div class="col-md-6">
            <div class="card detail-card">
                <div class="card-header">
                    <i class="fas fa-history me-2"></i>Historique
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">Créé par</div>
                        <div class="info-value">
                            {{ $contrat->createur->prenom ?? '' }} {{ $contrat->createur->nom ?? 'N/A' }}
                            <br>
                            <small class="text-muted">le {{ \Carbon\Carbon::parse($contrat->created_at)->format('d/m/Y à H:i') }}</small>
                        </div>
                    </div>
                    @if($contrat->moderateur)
                        <div class="info-row">
                            <div class="info-label">Modifié par</div>
                            <div class="info-value">
                                {{ $contrat->moderateur->prenom }} {{ $contrat->moderateur->nom }}
                                <br>
                                <small class="text-muted">le {{ \Carbon\Carbon::parse($contrat->updated_at)->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Équipements couverts -->
    <div class="card detail-card">
        <div class="card-header">
            <i class="fas fa-tools me-2"></i>Équipements couverts ({{ $contrat->equipements->count() }})
        </div>
        <div class="card-body">
            @if($contrat->equipements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Localisation</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contrat->equipements as $equipement)
                                <tr>
                                    <td>{{ $equipement->code }}</td>
                                    <td>{{ $equipement->nom }}</td>
                                    <td>{{ $equipement->type->nom ?? 'N/A' }}</td>
                                    <td>{{ $equipement->localisation->nom ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $equipement->statut == 'operationnel' ? 'success' : 'warning' }}">
                                            {{ $equipement->statut }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center py-3">Aucun équipement associé à ce contrat</p>
            @endif
        </div>
    </div>

    <!-- Conditions et notes -->
    @if($contrat->Conditions_Particulieres || $contrat->Notes_Internes)
        <div class="row">
            @if($contrat->Conditions_Particulieres)
                <div class="col-md-6">
                    <div class="card detail-card">
                        <div class="card-header">
                            <i class="fas fa-file-signature me-2"></i>Conditions particulières
                        </div>
                        <div class="card-body">
                            {{ $contrat->Conditions_Particulieres }}
                        </div>
                    </div>
                </div>
            @endif

            @if($contrat->Notes_Internes)
                <div class="col-md-6">
                    <div class="card detail-card">
                        <div class="card-header">
                            <i class="fas fa-sticky-note me-2"></i>Notes internes
                        </div>
                        <div class="card-body">
                            {{ $contrat->Notes_Internes }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Document -->
    @if($contrat->chemin_document)
        <div class="card detail-card">
            <div class="card-header">
                <i class="fas fa-file-pdf me-2"></i>Document du contrat
            </div>
            <div class="card-body">
                <div class="document-preview">
                    <i class="fas fa-file-pdf"></i>
                    <h5>{{ $contrat->fichier_original }}</h5>
                    <a href="{{ Storage::url($contrat->chemin_document) }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-download me-2"></i>Télécharger
                    </a>
                    <a href="{{ Storage::url($contrat->chemin_document) }}" class="btn btn-outline-secondary" target="_blank">
                        <i class="fas fa-eye me-2"></i>Visualiser
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal changement de statut -->
<div class="modal fade" id="changeStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-exchange-alt me-2"></i>Changer le statut du contrat
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.contrats.changer-statut', $contrat->ID_Contrat) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="statut" class="form-label">Nouveau statut</label>
                        <select name="statut" id="statut" class="form-select" required>
                            <option value="">Sélectionner un statut</option>
                            <option value="actif" {{ $contrat->Statut == 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="expire" {{ $contrat->Statut == 'expire' ? 'selected' : '' }}>Expiré</option>
                            <option value="resilie" {{ $contrat->Statut == 'resilie' ? 'selected' : '' }}>Résilié</option>
                            <option value="renouvellement_attente" {{ $contrat->Statut == 'renouvellement_attente' ? 'selected' : '' }}>En attente de renouvellement</option>
                            <option value="brouillon" {{ $contrat->Statut == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Changer le statut
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
