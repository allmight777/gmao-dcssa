@extends('layouts.welcome')

@section('content')
    <div class="user-dashboard">
        <!-- Header avec informations utilisateur -->
        <div class="dashboard-header">
            <div class="user-info">
                <div class="avatar-container">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="user-details">
                    <h2>Bienvenue, Dr. {{ Auth::user()->prenom }} {{ Auth::user()->nom }}</h2>
                    <p class="user-role">{{ Auth::user()->service->libelle ?? 'Service médical' }}</p>
                    <p class="user-matricule">Matricule: {{ Auth::user()->matricule }}</p>
                </div>
            </div>
            <div class="quick-stats">
                <div class="stat-card">
                    <i class="fas fa-tools stat-icon intervention"></i>
                    <div class="stat-info">
                        <span class="stat-number">3</span>
                        <span class="stat-label">Demandes en cours</span>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-check-circle stat-icon completed"></i>
                    <div class="stat-info">
                        <span class="stat-number">12</span>
                        <span class="stat-label">Interventions validées</span>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-exclamation-triangle stat-icon urgent"></i>
                    <div class="stat-info">
                        <span class="stat-number">1</span>
                        <span class="stat-label">Urgences</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grille des fonctionnalités -->
        <div class="dashboard-grid">
            <!-- Demande d'intervention -->
            <!-- Demande d'intervention -->
            <a href="{{ route('user.demandes.index') }}" class="feature-card">
                <div class="feature-icon intervention">
                    <i class="fas fa-file-medical"></i>
                </div>
                <div class="feature-content">
                    <h3>Demande d'intervention</h3>
                    <p>Créer une nouvelle demande de maintenance pour un équipement</p>
                    <span class="feature-badge">UC-USR-01</span>
                </div>
                <i class="fas fa-arrow-right feature-arrow"></i>
            </a>

            <!-- Suivi des demandes -->
            @php
                $user = Auth::user();
            @endphp

            @if ($user && method_exists($user, 'isChefDivision') && $user->isChefDivision())
                <!-- Suivi des demandes -->
                <a href="{{ route('chef-division.demandes.index') }}" class="feature-card">
                    <div class="feature-icon follow">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Gestion des demandes</h3>
                        <p>Valider les demandes d'intervention de votre service</p>
                        <span class="feature-badge">Chef de division</span>
                    </div>
                    <i class="fas fa-arrow-right feature-arrow"></i>
                </a>
            @endif


            <!-- Disponibilité équipements -->
            <a href="{{ route('user.equipements.index') }}" class="feature-card">
                <div class="feature-icon availability">
                    <i class="fas fa-search"></i>
                </div>
                <div class="feature-content">
                    <h3>Disponibilité équipements</h3>
                    <p>Vérifier la disponibilité des équipements en temps réel</p>
                    <span class="feature-badge">UC-USR-03</span>
                </div>
                <i class="fas fa-arrow-right feature-arrow"></i>
            </a>

            <!-- Prêt d'équipement -->
            <a href="" class="feature-card">
                <div class="feature-icon loan">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="feature-content">
                    <h3>Demande de prêt</h3>
                    <p>Demander un équipement en prêt pour votre service</p>
                    <span class="feature-badge">UC-USR-04</span>
                </div>
                <i class="fas fa-arrow-right feature-arrow"></i>
            </a>

            <!-- Commande consommables -->
            <a href="" class="feature-card">
                <div class="feature-icon order">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="feature-content">
                    <h3>Commande de consommables</h3>
                    <p>Commander des fournitures et consommables médicaux</p>
                    <span class="feature-badge">UC-USR-05</span>
                </div>
                <i class="fas fa-arrow-right feature-arrow"></i>
            </a>

            <!-- Signalement urgence -->
            <a href="" class="feature-card urgent">
                <div class="feature-icon emergency">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="feature-content">
                    <h3>Signalement d'urgence</h3>
                    <p>Signaler une panne critique nécessitant intervention immédiate</p>
                    <span class="feature-badge">UC-USR-06</span>
                </div>
                <i class="fas fa-arrow-right feature-arrow"></i>
            </a>

            <!-- Validation interventions -->
            <a href="" class="feature-card">
                <div class="feature-icon validation">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="feature-content">
                    <h3>Validation interventions</h3>
                    <p>Valider les interventions terminées par les techniciens</p>
                    <span class="feature-badge">UC-USR-07</span>
                </div>
                <i class="fas fa-arrow-right feature-arrow"></i>
            </a>

            <!-- Documentation technique -->
            <a href="" class="feature-card">
                <div class="feature-icon documentation">
                    <i class="fas fa-book-medical"></i>
                </div>
                <div class="feature-content">
                    <h3>Documentation technique</h3>
                    <p>Accéder aux manuels et documentations des équipements</p>
                    <span class="feature-badge">UC-USR-08</span>
                </div>
                <i class="fas fa-arrow-right feature-arrow"></i>
            </a>

            <!-- Planning maintenance -->
            <a href="" class="feature-card">
                <div class="feature-icon planning">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="feature-content">
                    <h3>Planning de maintenance</h3>
                    <p>Consulter le calendrier des maintenances préventives</p>
                    <span class="feature-badge">UC-USR-09</span>
                </div>
                <i class="fas fa-arrow-right feature-arrow"></i>
            </a>

        <a href="{{ route('user.profile.view') }}" class="feature-card">
    <div class="feature-icon favorite">
        <i class="fas fa-star"></i>
    </div>
    <div class="feature-content">
        <h3>Mon profil</h3>
        <p>Accéder à votre profil</p>
        <span class="feature-badge">Personnalisé</span>
    </div>
    <i class="fas fa-arrow-right feature-arrow"></i>
</a>

            <!-- Historique des activités -->
            <a href="" class="feature-card">
                <div class="feature-icon history">
                    <i class="fas fa-history"></i>
                </div>
                <div class="feature-content">
                    <h3>Historique des activités</h3>
                    <p>Consulter l'historique complet de vos interactions</p>
                    <span class="feature-badge">Personnalisé</span>
                </div>
                <i class="fas fa-arrow-right feature-arrow"></i>
            </a>

            <!-- Rapport d'activité -->
            <a href="" class="feature-card">
                <div class="feature-icon report">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="feature-content">
                    <h3>Rapport d'activité</h3>
                    <p>Générer des rapports statistiques pour votre service</p>
                    <span class="feature-badge">Personnalisé</span>
                </div>
                <i class="fas fa-arrow-right feature-arrow"></i>
            </a>

            <!-- Support et assistance -->
            <a href="" class="feature-card">
                <div class="feature-icon support">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="feature-content">
                    <h3>Support technique</h3>
                    <p>Contacter le support pour assistance supplémentaire</p>
                    <span class="feature-badge">Personnalisé</span>
                </div>
                <i class="fas fa-arrow-right feature-arrow"></i>
            </a>
        </div>

        <!-- Section urgences/alertes -->
        <div class="alerts-section">
            <div class="section-header">
                <h3><i class="fas fa-bell me-2"></i> Alertes et notifications</h3>
                <a href="#" class="view-all">Voir tout</a>
            </div>
            <div class="alerts-container">
                <div class="alert-item urgent-alert">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h4>Maintenance préventive programmée</h4>
                        <p>Échographe #MED-123 - Demain à 10h00</p>
                        <span class="alert-time">Il y a 2 heures</span>
                    </div>
                </div>
                <div class="alert-item info-alert">
                    <div class="alert-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h4>Demande #D-456 validée</h4>
                        <p>Votre demande d'intervention a été approuvée</p>
                        <span class="alert-time">Il y a 1 jour</span>
                    </div>
                </div>
                <div class="alert-item success-alert">
                    <div class="alert-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h4>Équipement disponible</h4>
                        <p>Le défibrillateur #DEF-789 est maintenant disponible</p>
                        <span class="alert-time">Il y a 2 jours</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .user-dashboard {
            min-height: 100vh;
            background:
                url('{{ asset('images/1.webp') }}') no-repeat center center fixed;
            background-size: cover;
            padding: 20px;
        }

        /* Header */
        .dashboard-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .avatar-container {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            border: 3px solid white;
        }

        .user-details h2 {
            color: #2d3748;
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
        }

        .user-role {
            color: #4a5568;
            font-size: 1.1rem;
            margin: 5px 0;
            font-weight: 500;
        }

        .user-matricule {
            color: #718096;
            font-size: 0.9rem;
            margin: 0;
        }

        /* Quick Stats */
        .quick-stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 15px 20px;
            min-width: 180px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: #667eea;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 1);
        }

        .stat-icon {
            font-size: 28px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .stat-icon.intervention {
            background: rgba(255, 247, 237, 0.9);
            color: #ed8936;
        }

        .stat-icon.completed {
            background: rgba(240, 255, 244, 0.9);
            color: #38a169;
        }

        .stat-icon.urgent {
            background: rgba(255, 245, 245, 0.9);
            color: #fc8181;
        }

        .stat-number {
            display: block;
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d3748;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.5);
        }

        .stat-label {
            font-size: 0.9rem;
            color: #718096;
        }

        /* Grid des fonctionnalités */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .feature-card:hover::before {
            transform: translateX(100%);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border-color: rgba(102, 126, 234, 0.3);
            background: rgba(255, 255, 255, 1);
        }

        .feature-card.urgent {
            border-left: 5px solid #fc8181;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 5px 15px rgba(252, 129, 129, 0.2);
            }

            50% {
                box-shadow: 0 5px 25px rgba(252, 129, 129, 0.4);
            }
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }

        .feature-icon.intervention {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
        }

        .feature-icon.follow {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        }

        .feature-icon.availability {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
        }

        .feature-icon.loan {
            background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%);
        }

        .feature-icon.order {
            background: linear-gradient(135deg, #ed64a6 0%, #d53f8c 100%);
        }

        .feature-icon.emergency {
            background: linear-gradient(135deg, #fc8181 0%, #f56565 100%);
        }

        .feature-icon.validation {
            background: linear-gradient(135deg, #0bc5ea 0%, #00b5d8 100%);
        }

        .feature-icon.documentation {
            background: linear-gradient(135deg, #38b2ac 0%, #319795 100%);
        }

        .feature-icon.planning {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .feature-icon.favorite {
            background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
        }

        .feature-icon.history {
            background: linear-gradient(135deg, #4fd1c7 0%, #38b2ac 100%);
        }

        .feature-icon.report {
            background: linear-gradient(135deg, #805ad5 0%, #6b46c1 100%);
        }

        .feature-icon.support {
            background: linear-gradient(135deg, #68d391 0%, #48bb78 100%);
        }

        .feature-content {
            flex: 1;
            position: relative;
            z-index: 1;
        }

        .feature-content h3 {
            margin: 0 0 8px 0;
            color: #2d3748;
            font-size: 1.2rem;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.5);
        }

        .feature-content p {
            margin: 0;
            color: #718096;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .feature-badge {
            display: inline-block;
            background: rgba(226, 232, 240, 0.8);
            color: #4a5568;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            margin-top: 8px;
            font-weight: 500;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .feature-arrow {
            color: #a0aec0;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .feature-card:hover .feature-arrow {
            color: #667eea;
            transform: translateX(5px);
        }

        /* Section alertes */
        .alerts-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h3 {
            margin: 0;
            color: #2d3748;
            font-size: 1.3rem;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.5);
        }

        .view-all {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            background: rgba(102, 126, 234, 0.1);
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .view-all:hover {
            color: #764ba2;
            text-decoration: none;
            background: rgba(102, 126, 234, 0.2);
            transform: translateY(-1px);
        }

        .alerts-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .alert-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px;
            border-radius: 12px;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .alert-item:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .alert-item.urgent-alert {
            border-left-color: #fc8181;
            background: rgba(255, 245, 245, 0.8);
        }

        .alert-item.info-alert {
            border-left-color: #4299e1;
            background: rgba(235, 248, 255, 0.8);
        }

        .alert-item.success-alert {
            border-left-color: #48bb78;
            background: rgba(240, 255, 244, 0.8);
        }

        .alert-icon {
            font-size: 20px;
            margin-top: 2px;
        }

        .alert-item.urgent-alert .alert-icon {
            color: #fc8181;
        }

        .alert-item.info-alert .alert-icon {
            color: #4299e1;
        }

        .alert-item.success-alert .alert-icon {
            color: #48bb78;
        }

        .alert-content h4 {
            margin: 0 0 5px 0;
            color: #2d3748;
            font-size: 1rem;
            font-weight: 600;
        }

        .alert-content p {
            margin: 0;
            color: #718096;
            font-size: 0.9rem;
        }

        .alert-time {
            display: block;
            margin-top: 8px;
            font-size: 0.8rem;
            color: #a0aec0;
        }

        /* Effets de verre améliorés */
        @supports (backdrop-filter: blur(10px)) {

            .dashboard-header,
            .stat-card,
            .feature-card,
            .alerts-section,
            .alert-item {
                background: rgba(255, 255, 255, 0.7);
            }
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }

        @media (max-width: 992px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .quick-stats {
                width: 100%;
                justify-content: center;
            }

            .dashboard-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .user-dashboard {
                padding: 15px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .feature-card {
                padding: 20px;
            }

            .stat-card {
                min-width: 140px;
            }

            .avatar-container {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }

            .user-details h2 {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 480px) {
            .quick-stats {
                flex-direction: column;
                align-items: stretch;
            }

            .stat-card {
                min-width: auto;
            }

            .user-info {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
        }
    </style>

    <script>
        // Animation au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.feature-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Effet de survol amélioré
            document.querySelectorAll('.feature-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    const icon = this.querySelector('.feature-icon');
                    icon.style.transform = 'scale(1.1) rotate(5deg)';
                });

                card.addEventListener('mouseleave', function() {
                    const icon = this.querySelector('.feature-icon');
                    icon.style.transform = 'scale(1) rotate(0deg)';
                });
            });

            // Animation d'apparition des cartes
            const featureCards = document.querySelectorAll('.feature-card');
            featureCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            });

            // Effet de scintillement pour les cartes urgentes
            const urgentCards = document.querySelectorAll('.feature-card.urgent');
            urgentCards.forEach(card => {
                setInterval(() => {
                    card.style.boxShadow = card.style.boxShadow.includes(
                            '0 5px 25px rgba(252, 129, 129, 0.4)') ?
                        '0 5px 15px rgba(252, 129, 129, 0.2)' :
                        '0 5px 25px rgba(252, 129, 129, 0.4)';
                }, 2000);
            });
        });
    </script>
@endsection
