@extends('layouts.admin')

@section('title', 'Détails de l\'équipement')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

@section('page-title', 'Détails de l\'équipement')

@section('page-actions')
<a href="{{ route('inventaire.equipements.edit', $equipement) }}" class="btn-action">
    <i class="fas fa-edit"></i> Modifier
</a> &nbsp &nbsp
<a href="{{ route('inventaire.equipements.genererEtiquette', $equipement) }}" class="btn-action" target="_blank">
    <i class="fas fa-qrcode"></i> Étiquette
</a> &nbsp &nbsp
<a href="{{ route('inventaire.equipements.index') }}" class="btn-return">
    <i class="fas fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')
<div class="equipment-details-container">
    @if(session('success'))
        <div class="success-message">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- En-tête avec informations principales -->
    <div class="equipment-header">
        <div class="header-content">
            <div class="equipment-title">
                <h1>{{ $equipement->marque }} {{ $equipement->modele }}</h1>
                <div class="equipment-subtitle">
                    <span class="inventory-number">
                        <i class="fas fa-barcode"></i> {{ $equipement->numero_inventaire }}
                    </span>
                    @if($equipement->code_barres)
                        <span class="barcode">
                            <i class="fas fa-qrcode"></i> {{ $equipement->code_barres }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="equipment-status">
                @php
                    $etatClasses = [
                        'neuf' => 'status-new',
                        'bon' => 'status-good',
                        'moyen' => 'status-medium',
                        'mauvais' => 'status-bad',
                        'hors_service' => 'status-disabled'
                    ];
                    $etatLabels = [
                        'neuf' => 'Neuf',
                        'bon' => 'Bon état',
                        'moyen' => 'État moyen',
                        'mauvais' => 'Mauvais état',
                        'hors_service' => 'Hors service'
                    ];
                @endphp
                <span class="status-badge {{ $etatClasses[$equipement->etat] }}">
                    {{ $etatLabels[$equipement->etat] }}
                </span>
            </div>
        </div>
    </div>

    <div class="details-grid">
        <!-- Carte gauche: Informations principales -->
        <div class="details-card main-info">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Informations principales</h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Type d'équipement</span>
                        <span class="info-value">{{ $equipement->typeEquipement->libelle ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Numéro de série</span>
                        <span class="info-value">{{ $equipement->numero_serie ?? 'Non renseigné' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Classe</span>
                        <span class="info-value">{{ $equipement->classe_equipement ?? 'Non spécifiée' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Type de maintenance</span>
                        <span class="info-value">
                            @switch($equipement->type_maintenance)
                                @case('preventive') Préventive uniquement @break
                                @case('curative') Curative uniquement @break
                                @case('mixte') Mixte @break
                                @default {{ $equipement->type_maintenance }}
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte droite: Indicateurs -->
        <div class="details-card indicators">
            <div class="card-header">
                <h3><i class="fas fa-chart-line"></i> Indicateurs</h3>
            </div>
            <div class="card-body">
                <div class="indicators-grid">
                    <div class="indicator {{ $indicateurs['age'] > 5 ? 'warning' : 'success' }}">
                      
                        <div class="indicator-content">
                            <h4>Âge</h4>
                            <p>{{ $indicateurs['age'] }} an(s)</p>
                        </div>
                    </div>
                    
                    <div class="indicator {{ $indicateurs['est_sous_garantie'] ? 'success' : 'danger' }}">
                        <div class="indicator-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="indicator-content">
                            <h4>Garantie</h4>
                            <p>{{ $indicateurs['est_sous_garantie'] ? 'Active' : 'Expirée' }}</p>
                            @if($indicateurs['date_fin_garantie'])
                                <small>Jusqu'au {{ $indicateurs['date_fin_garantie'] }}</small>
                            @endif
                        </div>
                    </div>
                    
                    @if($indicateurs['temps_restant_vie'])
                        <div class="indicator {{ $indicateurs['temps_restant_vie'] < 12 ? 'warning' : 'success' }}">
                          
                            <div class="indicator-content">
                                <h4>Vie restante</h4>
                                <p>{{ $indicateurs['temps_restant_vie'] }} mois</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Carte: Acquisition -->
        <div class="details-card acquisition">
            <div class="card-header">
                <h3><i class="fas fa-shopping-cart"></i> Acquisition</h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Date d'achat</span>
                        <span class="info-value">{{ $equipement->date_achat->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date mise service</span>
                        <span class="info-value">
                            {{ $equipement->date_mise_service?->format('d/m/Y') ?? 'Non renseignée' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Prix d'achat</span>
                        <span class="info-value">
                            @if($equipement->prix_achat)
                                {{ number_format($equipement->prix_achat, 0, ',', ' ') }} FCFA
                            @else
                                Non renseigné
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Durée garantie</span>
                        <span class="info-value">
                            {{ $equipement->duree_garantie ? $equipement->duree_garantie . ' mois' : 'Non spécifiée' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Durée vie théorique</span>
                        <span class="info-value">
                            {{ $equipement->duree_vie_theorique ? $equipement->duree_vie_theorique . ' mois' : 'Non spécifiée' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fournisseur</span>
                        <span class="info-value">
                            {{ $equipement->fournisseur->raison_sociale ?? 'Non spécifié' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte: Localisation -->
        <div class="details-card location">
            <div class="card-header">
                <h3><i class="fas fa-map-marker-alt"></i> Localisation</h3>
            </div>
            <div class="card-body">
                <div class="location-info">
                    @if($equipement->localisation)
                        <div class="location-item">
                            <div class="location-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="location-content">
                                <h4>Localisation physique</h4>
                                <p>{{ $equipement->localisation->nom }}</p>
                                @if($equipement->localisation->adresse)
                                    <small>{{ $equipement->localisation->adresse }}</small>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    @if($equipement->serviceResponsable)
                        <div class="location-item">
                            <div class="location-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="location-content">
                                <h4>Service responsable</h4>
                                <p>{{ $equipement->serviceResponsable->nom }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($equipement->contrat)
                        <div class="location-item">
                            <div class="location-icon">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <div class="location-content">
                                <h4>Contrat de maintenance</h4>
                                <p>{{ $equipement->contrat->numero_contrat }}</p>
                                <small>{{ $equipement->contrat->libelle }}</small>
                                <small>Valide jusqu'au {{ $equipement->contrat->date_fin->format('d/m/Y') }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Carte: Historique des mouvements -->
        @if($equipement->mouvements && $equipement->mouvements->count() > 0)
            <div class="details-card history">
                <div class="card-header">
                    <h3><i class="fas fa-history"></i> Historique des mouvements</h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach($equipement->mouvements as $mouvement)
                            <div class="timeline-item">
                                <div class="timeline-marker">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <span class="timeline-date">
                                            {{ $mouvement->date_mouvement->format('d/m/Y H:i') }}
                                        </span>
                                        <span class="timeline-operator">
                                            Par {{ $mouvement->operateur->prenom ?? 'Utilisateur' }}
                                        </span>
                                    </div>
                                    <div class="timeline-body">
                                        <div class="movement-path">
                                            <span class="from">
                                                {{ $mouvement->ancienneLocalisation->nom ?? 'Non localisé' }}
                                            </span>
                                            <i class="fas fa-arrow-right"></i>
                                            <span class="to">
                                                {{ $mouvement->nouvelleLocalisation->nom ?? 'Non localisé' }}
                                            </span>
                                        </div>
                                        @if($mouvement->motif)
                                            <p class="timeline-motive">
                                                <strong>Motif:</strong> {{ $mouvement->motif }}
                                            </p>
                                        @endif
                                        @if($mouvement->commentaire)
                                            <p class="timeline-comment">
                                                {{ $mouvement->commentaire }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Carte: Commentaires -->
        @if($equipement->commentaires)
            <div class="details-card comments">
                <div class="card-header">
                    <h3><i class="fas fa-comment-alt"></i> Commentaires</h3>
                </div>
                <div class="card-body">
                    <div class="comment-content">
                        {!! nl2br(e($equipement->commentaires)) !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Carte: Métadonnées -->
        <div class="details-card metadata">
            <div class="card-header">
                <h3><i class="fas fa-database"></i> Métadonnées</h3>
            </div>
            <div class="card-body">
                <div class="metadata-grid">
                    <div class="metadata-item">
                        <span class="metadata-label">Créé le</span>
                        <span class="metadata-value">
                            {{ $equipement->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <div class="metadata-item">
                        <span class="metadata-label">Par</span>
                        <span class="metadata-value">
                            {{ $equipement->createur->prenom ?? 'Utilisateur' }}
                        </span>
                    </div>
                    @if($equipement->updated_at != $equipement->created_at)
                        <div class="metadata-item">
                            <span class="metadata-label">Modifié le</span>
                            <span class="metadata-value">
                                {{ $equipement->updated_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        <div class="metadata-item">
                            <span class="metadata-label">Par</span>
                            <span class="metadata-value">
                                {{ $equipement->editeur->prenom ?? 'Utilisateur' }}
                            </span>
                        </div>
                    @endif
                    @if($equipement->date_reforme)
                        <div class="metadata-item">
                            <span class="metadata-label">Réformé le</span>
                            <span class="metadata-value">
                                {{ $equipement->date_reforme->format('d/m/Y') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Actions supplémentaires -->
    <div class="additional-actions">
        <button type="button" class="btn-action btn-danger" onclick="showReformeModal()">
            <i class="fas fa-ban"></i> Marquer hors service
        </button>
    </div>
</div>

<!-- Modal de réforme -->
<div id="reformeModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Marquer comme hors service</h3>
            <button type="button" class="modal-close" onclick="closeReformeModal()">&times;</button>
        </div>
        <form method="POST" action="{{ route('inventaire.equipements.destroy', $equipement) }}" id="reformeForm">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <p>Vous allez marquer l'équipement <strong>{{ $equipement->numero_inventaire }}</strong> comme hors service.</p>
                
                <div class="form-group">
                    <label for="motif_reforme">Motif de la réforme *</label>
                    <textarea id="motif_reforme" name="motif_reforme" 
                              rows="3" 
                              placeholder="Décrivez le motif de la réforme..."
                              required></textarea>
                </div>

                <div class="form-group">
                    <label for="date_reforme">Date de réforme *</label>
                    <input type="date" id="date_reforme" name="date_reforme" 
                           value="{{ date('Y-m-d') }}" 
                           required>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeReformeModal()">
                    Annuler
                </button>
                <button type="submit" class="btn-danger">
                    <i class="fas fa-ban"></i> Confirmer la réforme
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --primary-color: #0351BC;
        --primary-light: #4a7fd4;
        --primary-dark: #023a8a;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #3b82f6;
        --black: #000000;
        --dark-gray: #1a1a1a;
        --medium-gray: #333333;
        --light-gray: #f5f5f5;
        --white: #ffffff;
        --card-bg: #ffffff;
        --modal-overlay: rgba(0, 0, 0, 0.5);
    }

    .equipment-details-container {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .success-message {
        background: linear-gradient(135deg, var(--success-color), #059669);
        color: var(--white);
        padding: 15px 25px;
        border-radius: 12px;
        text-align: center;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
    }

    /* En-tête */
    .equipment-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        border-radius: 20px;
        padding: 40px;
        color: var(--white);
        box-shadow: 0 20px 40px rgba(3, 81, 188, 0.3);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 20px;
    }

    .equipment-title h1 {
        margin: 0 0 15px 0;
        font-size: 28px;
        font-weight: 700;
    }

    .equipment-subtitle {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: center;
    }

    .inventory-number,
    .barcode {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 16px;
        opacity: 0.9;
    }

    .equipment-status {
        margin-top: 10px;
    }

    .status-badge {
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-new {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
        border: 2px solid rgba(16, 185, 129, 0.4);
    }

    .status-good {
        background: rgba(59, 130, 246, 0.2);
        color: var(--info-color);
        border: 2px solid rgba(59, 130, 246, 0.4);
    }

    .status-medium {
        background: rgba(245, 158, 11, 0.2);
        color: var(--warning-color);
        border: 2px solid rgba(245, 158, 11, 0.4);
    }

    .status-bad {
        background: rgba(239, 68, 68, 0.2);
        color: var(--danger-color);
        border: 2px solid rgba(239, 68, 68, 0.4);
    }

    .status-disabled {
        background: rgba(107, 114, 128, 0.2);
        color: #6b7280;
        border: 2px solid rgba(107, 114, 128, 0.4);
    }

    /* Grille des détails */
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 25px;
    }

    /* Cartes */
    .details-card {
        background: var(--card-bg);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .card-header {
        padding: 20px 25px;
        background: var(--light-gray);
        border-bottom: 2px solid var(--primary-color);
    }

    .card-header h3 {
        margin: 0;
        color: var(--dark-gray);
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body {
        padding: 25px;
    }

    /* Grille d'informations */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .info-label {
        font-size: 12px;
        color: var(--medium-gray);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 15px;
        color: var(--dark-gray);
        font-weight: 500;
    }

    /* Indicateurs */
    .indicators-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
    }

    .indicator {
        padding: 20px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: transform 0.3s ease;
    }

    .indicator:hover {
        transform: translateY(-5px);
    }

    .indicator.success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.2));
        border: 2px solid rgba(16, 185, 129, 0.3);
    }

    .indicator.warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.2));
        border: 2px solid rgba(245, 158, 11, 0.3);
    }

    .indicator.danger {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.2));
        border: 2px solid rgba(239, 68, 68, 0.3);
    }

    .indicator-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .indicator.success .indicator-icon {
        color: var(--success-color);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.2);
    }

    .indicator.warning .indicator-icon {
        color: var(--warning-color);
        box-shadow: 0 5px 15px rgba(245, 158, 11, 0.2);
    }

    .indicator.danger .indicator-icon {
        color: var(--danger-color);
        box-shadow: 0 5px 15px rgba(239, 68, 68, 0.2);
    }

    .indicator-content h4 {
        margin: 0 0 5px 0;
        font-size: 14px;
        color: var(--dark-gray);
    }

    .indicator-content p {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: var(--dark-gray);
    }

    .indicator-content small {
        font-size: 11px;
        color: var(--medium-gray);
    }

    /* Localisation */
    .location-info {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .location-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 15px;
        border-radius: 12px;
        background: var(--light-gray);
    }

    .location-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 18px;
    }

    .location-content h4 {
        margin: 0 0 5px 0;
        font-size: 15px;
        color: var(--dark-gray);
    }

    .location-content p {
        margin: 0 0 5px 0;
        font-size: 16px;
        font-weight: 600;
        color: var(--primary-color);
    }

    .location-content small {
        display: block;
        font-size: 12px;
        color: var(--medium-gray);
        line-height: 1.4;
    }

    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
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
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white);
        font-size: 10px;
    }

    .timeline-content {
        background: var(--light-gray);
        border-radius: 12px;
        padding: 15px;
    }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .timeline-date {
        font-size: 13px;
        font-weight: 600;
        color: var(--primary-color);
    }

    .timeline-operator {
        font-size: 12px;
        color: var(--medium-gray);
    }

    .movement-path {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .movement-path .from,
    .movement-path .to {
        padding: 5px 10px;
        background: var(--white);
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
    }

    .movement-path i {
        color: var(--primary-color);
    }

    .timeline-motive {
        margin: 10px 0 5px 0;
        font-size: 13px;
        color: var(--dark-gray);
    }

    .timeline-comment {
        margin: 0;
        font-size: 12px;
        color: var(--medium-gray);
        font-style: italic;
    }

    /* Commentaires */
    .comment-content {
        line-height: 1.6;
        color: var(--dark-gray);
        white-space: pre-wrap;
    }

    /* Métadonnées */
    .metadata-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .metadata-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .metadata-label {
        font-size: 11px;
        color: var(--medium-gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .metadata-value {
        font-size: 14px;
        color: var(--dark-gray);
        font-weight: 500;
    }

    /* Actions supplémentaires */
    .additional-actions {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        padding-top: 25px;
        border-top: 2px solid var(--light-gray);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color), #dc2626);
        color: var(--white);
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
    }

    /* Modal */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: var(--modal-overlay);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: var(--white);
        border-radius: 16px;
        width: 90%;
        max-width: 500px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        padding: 20px;
        border-bottom: 2px solid var(--light-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        color: var(--primary-color);
        margin: 0;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: var(--medium-gray);
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        color: var(--danger-color);
    }

    .modal-body {
        padding: 20px;
    }

    .modal-body .form-group {
        margin-bottom: 20px;
    }

    .modal-body label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark-gray);
    }

    .modal-body textarea,
    .modal-body input[type="date"] {
        width: 100%;
        padding: 10px;
        border: 2px solid var(--light-gray);
        border-radius: 8px;
        font-size: 14px;
    }

    .modal-body textarea:focus,
    .modal-body input[type="date"]:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(3, 81, 188, 0.1);
    }

    .modal-actions {
        padding: 20px;
        border-top: 2px solid var(--light-gray);
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn-secondary {
        background: var(--light-gray);
        color: var(--medium-gray);
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: var(--medium-gray);
        color: var(--white);
    }

    /* Page actions */
    .btn-action {
        background: var(--primary-color);
        color: var(--white);
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(3, 81, 188, 0.3);
    }

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
        color: var(--dark-gray);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .details-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .equipment-header {
            padding: 25px;
        }

        .header-content {
            flex-direction: column;
            gap: 15px;
        }

        .equipment-title h1 {
            font-size: 24px;
        }

        .info-grid,
        .indicators-grid,
        .metadata-grid {
            grid-template-columns: 1fr;
        }

        .modal-content {
            width: 95%;
            margin: 10px;
        }
    }

    @media (max-width: 576px) {
        .equipment-header {
            padding: 20px;
            border-radius: 15px;
        }

        .equipment-title h1 {
            font-size: 20px;
        }

        .inventory-number,
        .barcode {
            font-size: 14px;
        }

        .card-body {
            padding: 15px;
        }

        .timeline {
            padding-left: 20px;
        }

        .timeline-marker {
            left: -20px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Modal de réforme
    function showReformeModal() {
        document.getElementById('reformeModal').style.display = 'flex';
    }

    function closeReformeModal() {
        document.getElementById('reformeModal').style.display = 'none';
        document.getElementById('motif_reforme').value = '';
        document.getElementById('date_reforme').value = new Date().toISOString().split('T')[0];
    }

    // Fermer la modal en cliquant en dehors
    window.onclick = function(event) {
        const modal = document.getElementById('reformeModal');
        if (event.target === modal) {
            closeReformeModal();
        }
    };

    // Validation du formulaire de réforme
    document.getElementById('reformeForm').addEventListener('submit', function(e) {
        const motif = document.getElementById('motif_reforme').value.trim();
        const date = document.getElementById('date_reforme').value;
        
        if (!motif) {
            e.preventDefault();
            Swal.fire({
                title: 'Erreur',
                text: 'Veuillez saisir le motif de la réforme.',
                icon: 'error',
                confirmButtonColor: '#0351BC'
            });
            return;
        }
        
        if (!date) {
            e.preventDefault();
            Swal.fire({
                title: 'Erreur',
                text: 'Veuillez sélectionner une date de réforme.',
                icon: 'error',
                confirmButtonColor: '#0351BC'
            });
            return;
        }
        
        // Confirmation
        e.preventDefault();
        Swal.fire({
            title: 'Confirmer la réforme',
            html: `Êtes-vous sûr de vouloir marquer l'équipement <strong>{{ $equipement->numero_inventaire }}</strong> comme hors service ?<br><br>Cette action est irréversible.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, réformer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });

    // Animation des indicateurs
    document.addEventListener('DOMContentLoaded', function() {
        const indicators = document.querySelectorAll('.indicator');
        indicators.forEach((indicator, index) => {
            indicator.style.animationDelay = `${index * 0.1}s`;
            indicator.style.opacity = '0';
            indicator.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                indicator.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                indicator.style.opacity = '1';
                indicator.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endpush