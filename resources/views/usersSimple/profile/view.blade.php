@extends('layouts.welcome')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-xxl-10 col-xl-11 col-lg-12">

            <!-- En-tête -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="header-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1 fw-bold text-white">
                                    <i class="fas fa-user-circle me-2"></i>
                                    Mon Profil
                                </h4>
                                <p class="mb-0 text-white-50">
                                    Consultez et gérez vos informations personnelles
                                </p>
                            </div>
                            <a href="{{ route('user.profile.modifier') }}" class="btn btn-light-custom">
                                <i class="fas fa-edit me-2"></i>Modifier
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4 g-4">
                <div class="col-xl-4 col-md-6">
                    <div class="stats-card">
                        <div class="stats-card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="stats-label">Demandes Total</p>
                                    <h3 class="stats-value">{{ $demandesTotal }}</h3>
                                </div>
                                <div class="stats-icon bg-primary">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6">
                    <div class="stats-card">
                        <div class="stats-card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="stats-label">Demandes en Cours</p>
                                    <h3 class="stats-value text-warning">{{ $demandesEnCours }}</h3>
                                </div>
                                <div class="stats-icon bg-warning">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6">
                    <div class="stats-card">
                        <div class="stats-card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="stats-label">Équipements Service</p>
                                    <h3 class="stats-value text-info">{{ $equipementsTotal }}</h3>
                                </div>
                                <div class="stats-icon bg-info">
                                    <i class="fas fa-laptop-medical"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Informations personnelles -->
                <div class="col-xl-8">
                    <div class="card-modern h-100">
                        <div class="card-header-modern">
                            <h6 class="mb-0">
                                <i class="fas fa-id-card me-2 text-primary"></i>
                                Informations Personnelles
                            </h6>
                        </div>
                        <div class="card-body-modern">
                            <div class="row align-items-start">
                                <!-- Photo de profil -->
                                <div class="col-md-4 col-lg-3 text-center mb-4 mb-md-0">
                                    <div class="profile-avatar">
                                        <div class="avatar-circle bg-primary mb-3">
                                            <span class="avatar-text">{{ substr($utilisateur->prenom, 0, 1) }}{{ substr($utilisateur->nom, 0, 1) }}</span>
                                        </div>
                                        <h5 class="fw-bold mb-2">{{ $utilisateur->nomComplet }}</h5>
                                        <span class="badge-custom bg-{{ $utilisateur->badgeProfil }}">
                                            {{ $utilisateur->profilFormate }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Détails -->
                                <div class="col-md-8 col-lg-9">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="info-line">
                                                <span class="info-label">
                                                    <i class="fas fa-hashtag me-2 text-muted"></i>Matricule
                                                </span>
                                                <span class="info-value fw-bold">{{ $utilisateur->matricule }}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-line">
                                                <span class="info-label">
                                                    <i class="fas fa-briefcase me-2 text-muted"></i>Fonction
                                                </span>
                                                <span class="info-value fw-bold">{{ $utilisateur->fonction }}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-line">
                                                <span class="info-label">
                                                    <i class="fas fa-graduation-cap me-2 text-muted"></i>Grade
                                                </span>
                                                <span class="info-value">{{ $utilisateur->grade ?? 'Non spécifié' }}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-line">
                                                <span class="info-label">
                                                    <i class="fas fa-envelope me-2 text-muted"></i>Email
                                                </span>
                                                <span class="info-value">{{ $utilisateur->email }}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-line">
                                                <span class="info-label">
                                                    <i class="fas fa-phone me-2 text-muted"></i>Téléphone
                                                </span>
                                                <span class="info-value">{{ $utilisateur->telephone ?? 'Non spécifié' }}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-line">
                                                <span class="info-label">
                                                    <i class="fas fa-user-tag me-2 text-muted"></i>Login
                                                </span>
                                                <span class="info-value">{{ $utilisateur->login }}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="info-line">
                                                <span class="info-label">
                                                    <i class="fas fa-building me-2 text-muted"></i>Service
                                                </span>
                                                <span class="info-value fw-bold">{{ $utilisateur->service->nom ?? 'Non affecté' }}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-line">
                                                <span class="info-label">
                                                    <i class="fas fa-calendar-alt me-2 text-muted"></i>Dernière connexion
                                                </span>
                                                <span class="info-value">
                                                    @if($utilisateur->date_derniere_connexion)
                                                        {{ $utilisateur->date_derniere_connexion->format('d/m/Y H:i') }}
                                                    @else
                                                        Jamais connecté
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-line">
                                                <span class="info-label">
                                                    <i class="fas fa-user-shield me-2 text-muted"></i>Statut
                                                </span>
                                                <span class="badge-custom bg-{{ $utilisateur->badgeStatut }}">
                                                    {{ ucfirst($utilisateur->statut) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="col-xl-4">
                    <div class="card-modern h-100">
                        <div class="card-header-modern">
                            <h6 class="mb-0">
                                <i class="fas fa-cogs me-2 text-success"></i>
                                Actions
                            </h6>
                        </div>
                        <div class="card-body-modern">
                            <div class="d-grid gap-3">
                                <a href="{{ route('user.profile.modifier') }}" class="btn-action-primary">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Modifier mon profil
                                </a>

                                <a href="{{ route('user.profile.modifier') }}#mot-de-passe" class="btn-action-warning">
                                    <i class="fas fa-key me-2"></i>
                                    Changer mon mot de passe
                                </a>

                                <a href="{{ route('user.equipements.index') }}" class="btn-action-info">
                                    <i class="fas fa-laptop-medical me-2"></i>
                                    Disponibilité d'un équipement
                                </a>

                                <a href="{{ route('UserSimleDashboard') }}" class="btn-action-secondary">
                                    <i class="fas fa-home me-2"></i>
                                    Retour au tableau de bord
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #4e73df;
    --success: #1cc88a;
    --danger: #e74a3b;
    --warning: #f6c23e;
    --info: #36b9cc;
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Header Card */
.header-card {
    background: var(--gradient-primary);
    padding: 25px 30px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    animation: slideInDown 0.6s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.btn-light-custom {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-light-custom:hover {
    background: white;
    color: var(--primary);
    transform: translateY(-2px);
}

/* Stats Cards */
.stats-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
    height: 100%;
    animation: fadeInUp 0.6s ease-out backwards;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stats-card:nth-child(1) { animation-delay: 0.1s; }
.stats-card:nth-child(2) { animation-delay: 0.2s; }
.stats-card:nth-child(3) { animation-delay: 0.3s; }

.stats-card-body {
    padding: 25px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.stats-label {
    color: #858796;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 8px;
    letter-spacing: 0.5px;
}

.stats-value {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: #2d3748;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

/* Modern Cards */
.card-modern {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    border: none;
    overflow: hidden;
    height: 100%;
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.card-header-modern {
    background: linear-gradient(135deg, #f6f8fb 0%, #e9ecf5 100%);
    padding: 20px 25px;
    border-bottom: 2px solid #e2e8f0;
}

.card-header-modern h6 {
    font-weight: 700;
    color: #2d3748;
    font-size: 1rem;
}

.card-body-modern {
    padding: 25px;
    height: calc(100% - 70px);
}

/* Avatar */
.profile-avatar {
    padding: 15px 0;
}

.avatar-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    font-weight: bold;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

.avatar-circle:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

.avatar-text {
    text-transform: uppercase;
}

/* Info Line */
.info-line {
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.info-line:hover {
    background-color: #f8f9fc;
    padding-left: 10px;
    padding-right: 10px;
    margin-left: -10px;
    margin-right: -10px;
    border-radius: 8px;
}

.info-line:last-child {
    border-bottom: none;
}

.info-label {
    color: #6c757d;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    min-width: 160px;
}

.info-value {
    color: #2d3748;
    font-size: 0.95rem;
    text-align: right;
    flex: 1;
    margin-left: 15px;
}

/* Badge Custom */
.badge-custom {
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.75rem;
    display: inline-block;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Buttons Actions */
.btn-action-primary,
.btn-action-warning,
.btn-action-info,
.btn-action-secondary {
    padding: 15px 20px;
    border-radius: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.btn-action-primary {
    background: var(--primary);
    color: white;
    border: 2px solid var(--primary);
}

.btn-action-warning {
    background: var(--warning);
    color: #2d3748;
    border: 2px solid var(--warning);
}

.btn-action-info {
    background: var(--info);
    color: white;
    border: 2px solid var(--info);
}

.btn-action-secondary {
    background: #f8f9fa;
    color: #2d3748;
    border: 2px solid #e2e8f0;
}

.btn-action-primary:hover,
.btn-action-warning:hover,
.btn-action-info:hover,
.btn-action-secondary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    color: inherit;
}

.btn-action-primary:hover {
    background: #2e59d9;
    border-color: #2e59d9;
}
.btn-action-warning:hover {
    background: #e4b73a;
    border-color: #e4b73a;
}
.btn-action-info:hover {
    background: #2aa1b1;
    border-color: #2aa1b1;
}
.btn-action-secondary:hover {
    background: #e2e6ea;
    border-color: #d4d8df;
}

/* Colors */
.bg-primary { background: var(--primary) !important; }
.bg-success { background: var(--success) !important; }
.bg-danger { background: var(--danger) !important; }
.bg-warning { background: var(--warning) !important; }
.bg-info { background: var(--info) !important; }
.bg-secondary { background: #858796 !important; }

/* Responsive */
@media (max-width: 1200px) {
    .col-xxl-10 {
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .header-card {
        padding: 20px;
    }

    .avatar-circle {
        width: 80px;
        height: 80px;
        font-size: 1.8rem;
    }

    .info-line {
        flex-direction: column;
        align-items: flex-start;
        padding: 10px 0;
    }

    .info-label {
        min-width: auto;
        margin-bottom: 5px;
    }

    .info-value {
        text-align: left;
        margin-left: 0;
        width: 100%;
    }

    .btn-action-primary,
    .btn-action-warning,
    .btn-action-info,
    .btn-action-secondary {
        padding: 12px 15px;
        font-size: 0.9rem;
    }

    .card-body-modern {
        padding: 20px;
    }
}

@media (max-width: 576px) {
    .stats-card-body {
        padding: 20px;
    }

    .stats-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }

    .stats-value {
        font-size: 1.5rem;
    }
}

/* Animation pour les cartes */
@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.card-modern:nth-child(2) {
    animation: slideInRight 0.6s ease-out 0.2s both;
}
</style>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: 'success',
        title: '{{ session('success') }}'
    });
});
</script>
@endif

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
});
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation au survol des cartes de stats
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.stats-icon');
            icon.style.transform = 'scale(1.1) rotate(5deg)';
        });

        card.addEventListener('mouseleave', function() {
            const icon = this.querySelector('.stats-icon');
            icon.style.transform = 'scale(1) rotate(0)';
        });
    });
});
</script>
@endsection
