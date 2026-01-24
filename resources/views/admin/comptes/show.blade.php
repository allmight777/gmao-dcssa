@extends('layouts.admin')

@section('title', 'Détails du compte')
<link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

@section('page-title', 'Détails du compte')

@section('page-actions')
    <div class="action-buttons">
        <a href="{{ route('admin.comptes.edit', $compte) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="{{ route('admin.comptes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
@endsection

@section('content')
<div class="compte-details-container">
    @if(session('success'))
        <div class="success-message">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="details-grid">
        <!-- Carte Informations Personnelles -->
        <div class="detail-card">
            <div class="card-header">
                <h3><i class="fas fa-id-card"></i> Informations Personnelles</h3>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">Matricule</span>
                    <span class="info-value badge-primary">{{ $compte->matricule }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nom & Prénom</span>
                    <span class="info-value">{{ $compte->nom }} {{ $compte->prenom }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Profil</span>
                    <span class="info-value">
                        <span class="badge-profil" style="background-color: {{ $compte->profil->couleur ?? '#0351BC' }}">
                            {{ $compte->profil->nom_profil ?? 'Non défini' }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Carte Informations Professionnelles -->
        <div class="detail-card">
            <div class="card-header">
                <h3><i class="fas fa-briefcase"></i> Informations Professionnelles</h3>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">Grade</span>
                    <span class="info-value">{{ $compte->grade ?? 'Non spécifié' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fonction</span>
                    <span class="info-value">{{ $compte->fonction }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Service</span>
                    <span class="info-value">{{ $compte->service->nom ?? 'Non affecté' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Statut</span>
                    <span class="info-value">
                        @php
                            $statusColors = [
                                'actif' => 'success',
                                'inactif' => 'secondary',
                                'suspendu' => 'danger'
                            ];
                            $color = $statusColors[$compte->statut] ?? 'secondary';
                        @endphp
                        <span class="badge badge-{{ $color }}">
                            {{ ucfirst($compte->statut) }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Carte Coordonnées -->
        <div class="detail-card">
            <div class="card-header">
                <h3><i class="fas fa-address-card"></i> Coordonnées</h3>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">
                        <a href="mailto:{{ $compte->email }}" class="email-link">
                            <i class="fas fa-envelope"></i> {{ $compte->email }}
                        </a>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Téléphone</span>
                    <span class="info-value">
                        @if($compte->telephone)
                            <a href="tel:{{ $compte->telephone }}" class="phone-link">
                                <i class="fas fa-phone"></i> {{ $compte->telephone }}
                            </a>
                        @else
                            Non spécifié
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date de création</span>
                    <span class="info-value">
                        <i class="fas fa-calendar"></i> {{ $compte->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Carte Authentification -->
        <div class="detail-card">
            <div class="card-header">
                <h3><i class="fas fa-key"></i> Authentification</h3>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">Login</span>
                    <span class="info-value code">{{ $compte->login }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Dernière connexion</span>
                    <span class="info-value">
                        @if($compte->last_login)
                            <i class="fas fa-clock"></i> {{ $compte->last_login->format('d/m/Y H:i') }}
                        @else
                            Jamais connecté
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">IP de connexion</span>
                    <span class="info-value code">{{ $compte->login_ip ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Historique des Activités -->
    <div class="activity-section">
        <div class="section-header">
            <h3><i class="fas fa-history"></i> Historique des Activités (50 dernières)</h3>
        </div>
        <div class="activity-container">
            @if($compte->logs->count() > 0)
                <div class="timeline">
                    @foreach($compte->logs as $log)
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                @switch($log->type)
                                    @case('connexion')
                                        <i class="fas fa-sign-in-alt text-success"></i>
                                        @break
                                    @case('modification')
                                        <i class="fas fa-edit text-warning"></i>
                                        @break
                                    @case('creation')
                                        <i class="fas fa-plus-circle text-primary"></i>
                                        @break
                                    @case('suppression')
                                        <i class="fas fa-trash-alt text-danger"></i>
                                        @break
                                    @default
                                        <i class="fas fa-info-circle text-info"></i>
                                @endswitch
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <span class="timeline-title">{{ $log->description }}</span>
                                    <span class="timeline-time">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($log->details)
                                    <div class="timeline-details">
                                        <small>{{ $log->details }}</small>
                                    </div>
                                @endif
                                @if($log->ip_address)
                                    <div class="timeline-meta">
                                        <span class="badge badge-light">
                                            <i class="fas fa-network-wired"></i> {{ $log->ip_address }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-history fa-3x"></i>
                    <p>Aucune activité enregistrée</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="action-section">
        <div class="action-buttons">
    
            
           

            <form action="{{ route('admin.comptes.destroy', $compte) }}" method="POST" class="d-inline" 
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce compte ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

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
        --info: #3b82f6;
    }

    .compte-details-container {
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.1);
        padding: 30px;
    }

    .success-message {
        background: var(--success);
        color: var(--white);
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        text-align: center;
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .detail-card {
        background: var(--white);
        border: 2px solid var(--light-gray);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .detail-card:hover {
        border-color: var(--primary-light);
        box-shadow: 0 10px 30px rgba(3, 81, 188, 0.1);
        transform: translateY(-5px);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: var(--white);
        padding: 20px;
        border-bottom: 2px solid var(--primary-dark);
    }

    .card-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body {
        padding: 25px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid var(--light-gray);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: var(--medium-gray);
        font-size: 14px;
    }

    .info-value {
        color: var(--dark-gray);
        font-size: 14px;
        text-align: right;
        max-width: 60%;
    }

    .badge-primary {
        background: var(--primary-color);
        color: var(--white);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-profil {
        color: var(--white);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .email-link, .phone-link {
        color: var(--primary-color);
        text-decoration: none;
        transition: color 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .email-link:hover, .phone-link:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    .code {
        font-family: 'Courier New', monospace;
        background: var(--light-gray);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 13px;
    }

    /* Activity Section */
    .activity-section {
        margin-top: 40px;
        border-top: 2px solid var(--light-gray);
        padding-top: 30px;
    }

    .section-header {
        margin-bottom: 25px;
    }

    .section-header h3 {
        color: var(--primary-color);
        font-size: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }

    .activity-container {
        background: var(--white);
        border: 2px solid var(--light-gray);
        border-radius: 16px;
        padding: 25px;
        max-height: 500px;
        overflow-y: auto;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 19px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--light-gray);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
        display: flex;
        gap: 15px;
    }

    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 0;
        width: 40px;
        height: 40px;
        background: var(--white);
        border: 2px solid var(--light-gray);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }

    .timeline-content {
        flex: 1;
        background: var(--white);
        border: 1px solid var(--light-gray);
        border-radius: 12px;
        padding: 15px;
        transition: all 0.3s ease;
    }

    .timeline-content:hover {
        border-color: var(--primary-light);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .timeline-title {
        font-weight: 600;
        color: var(--dark-gray);
        font-size: 14px;
    }

    .timeline-time {
        font-size: 12px;
        color: var(--medium-gray);
    }

    .timeline-details {
        margin-top: 8px;
        color: var(--medium-gray);
        font-size: 13px;
        line-height: 1.4;
    }

    .timeline-meta {
        margin-top: 10px;
        display: flex;
        gap: 10px;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-success { background: #d1fae5; color: #065f46; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .badge-secondary { background: #e5e7eb; color: #374151; }
    .badge-info { background: #dbeafe; color: #1e40af; }
    .badge-light { background: #f3f4f6; color: #6b7280; }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: var(--medium-gray);
    }

    .empty-state i {
        color: var(--light-gray);
        margin-bottom: 15px;
    }

    /* Action Section */
    .action-section {
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid var(--light-gray);
    }

    .action-section .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: var(--white);
    }

    .btn-secondary {
        background: var(--light-gray);
        color: var(--medium-gray);
        border: 2px solid var(--light-gray);
    }

    .btn-success {
        background: var(--success);
        color: var(--white);
    }

    .btn-warning {
        background: var(--warning);
        color: var(--white);
    }

    .btn-danger {
        background: var(--danger);
        color: var(--white);
    }

    .btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .compte-details-container {
            padding: 15px;
        }

        .details-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }

        .info-value {
            max-width: 100%;
            text-align: left;
        }

        .action-section .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }

        .btn {
            justify-content: center;
        }

        .timeline {
            padding-left: 20px;
        }

        .timeline::before {
            left: 9px;
        }

        .timeline-marker {
            left: -20px;
            width: 30px;
            height: 30px;
        }

        .timeline-marker i {
            font-size: 14px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function resetPassword() {
        if (confirm('Êtes-vous sûr de vouloir réinitialiser le mot de passe de ce compte ?')) {
            
            alert('Fonctionnalité à implémenter : Réinitialisation du mot de passe');
        }
    }
</script>
@endpush