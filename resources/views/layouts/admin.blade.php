<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">

    <title>GMAO DCSSA - @yield('title', 'Administration')</title>
    <link rel="icon" type="image/webp" href="{{ asset('images/logo.webp') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Boxicons (pour plus d'icônes) -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    @stack('styles')
    
    <style>
        :root {
            --admin-color: #dc3545;
            --gestionnaire-color: #198754;
            --technicien-color: #0dcaf0;
            --magasinier-color: #6f42c1;
            --utilisateur-color: #6c757d;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            overflow-x: hidden;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.05);
            background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
            width: 260px;
            transition: all 0.3s ease;
        }
        
        .sidebar.collapsed {
            width: 80px;
        }
        
        .sidebar.collapsed .nav-text {
            display: none;
        }
        
        .sidebar .nav-link {
            font-weight: 500;
            color: #495057;
            padding: 0.75rem 1rem;
            margin: 0.25rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--admin-color);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.08);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link:hover::before {
            transform: scaleY(1);
        }
        
        .sidebar .nav-link.active {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.12);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        }
        
        .sidebar .nav-link.active::before {
            transform: scaleY(1);
            background: #0d6efd;
        }
        
        .sidebar .nav-link i {
            width: 24px;
            margin-right: 12px;
            text-align: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link.active i {
            transform: scale(1.1);
            color: #0d6efd;
        }
        
        .main-content {
            margin-left: 260px;
            padding: 20px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }
        
        .main-content.expanded {
            margin-left: 80px;
        }
        
        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
            }
            
            .sidebar .nav-text {
                display: none;
            }
            
            .main-content {
                margin-left: 80px;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                padding: 0;
                height: auto;
            }
            
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
        
        /* Badges améliorés */
        .badge-admin {
            background: linear-gradient(135deg, var(--admin-color), #e4606d);
            color: white;
            font-weight: 600;
            padding: 0.35em 0.8em;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(220, 53, 69, 0.3);
        }
        
        .badge-gestionnaire {
            background: linear-gradient(135deg, var(--gestionnaire-color), #20c997);
            color: white;
            font-weight: 600;
            padding: 0.35em 0.8em;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(25, 135, 84, 0.3);
        }
        
        .badge-technicien {
            background: linear-gradient(135deg, var(--technicien-color), #31d2f2);
            color: black;
            font-weight: 600;
            padding: 0.35em 0.8em;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(13, 202, 240, 0.3);
        }
        
        .badge-magasinier {
            background: linear-gradient(135deg, var(--magasinier-color), #8a6fd4);
            color: white;
            font-weight: 600;
            padding: 0.35em 0.8em;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(111, 66, 193, 0.3);
        }
        
        .badge-secondary {
            background: linear-gradient(135deg, var(--utilisateur-color), #868e96);
            color: white;
            font-weight: 600;
            padding: 0.35em 0.8em;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(108, 117, 125, 0.3);
        }
        
        .badge-success {
            background: linear-gradient(135deg, #198754, #20c997);
            color: white;
            font-weight: 600;
            padding: 0.35em 0.8em;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(25, 135, 84, 0.3);
        }
        
        .badge-danger {
            background: linear-gradient(135deg, #dc3545, #e4606d);
            color: white;
            font-weight: 600;
            padding: 0.35em 0.8em;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(220, 53, 69, 0.3);
        }
        
        .badge-warning {
            background: linear-gradient(135deg, #ffc107, #ffd454);
            color: black;
            font-weight: 600;
            padding: 0.35em 0.8em;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(255, 193, 7, 0.3);
        }
        
        /* Navigation améliorée */
        .navbar {
            background: linear-gradient(90deg, #2c3e50, #4a6572);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.75rem 1rem;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .navbar-brand i {
            color: #4dabf7;
            font-size: 1.8rem;
        }
        
        .user-dropdown .dropdown-toggle {
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .user-dropdown .dropdown-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .user-dropdown .dropdown-menu {
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            padding: 0.5rem;
            margin-top: 10px;
        }
        
        .user-dropdown .dropdown-item {
            border-radius: 6px;
            padding: 0.6rem 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
        }
        
        .user-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        
        .user-name {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .user-role {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        /* Page header amélioré */
        .page-header {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #0d6efd;
        }
        
        .page-header h1 {
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .page-header h1 i {
            color: #0d6efd;
            font-size: 2rem;
        }
        
        /* Alerts améliorés */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            border-left: 4px solid #198754;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            border-left: 4px solid #dc3545;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border-left: 4px solid #ffc107;
        }
        
        /* Cards améliorées */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background: linear-gradient(90deg, #f8f9fa, #e9ecef);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Footer sidebar */
        .sidebar-footer {
            background: rgba(0, 0, 0, 0.02);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem;
            margin-top: auto;
        }
        
        .user-profile-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.75rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #0d6efd, #4dabf7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .version-badge {
            background: linear-gradient(135deg, #6c757d, #868e96);
            color: white;
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 0.85rem;
            text-align: center;
        }
        
        /* Toggle sidebar button */
        .sidebar-toggle {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(13, 110, 253, 0.1);
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0d6efd;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .sidebar-toggle:hover {
            background: rgba(13, 110, 253, 0.2);
            transform: rotate(180deg);
        }
        
        /* Animation pour les icônes */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        /* Corrections pour les problèmes de défilement */
        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
        }
        
        .dataTables_scrollBody {
            max-height: 400px !important;
        }
        
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
            max-width: 100%;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <!-- Navigation principale -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
           
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown user-dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}{{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}
                            </div>
                            <div class="user-info ms-2 d-none d-lg-block">
                                <span class="user-name" style="color:white">{{ auth()->user()->nom_complet }}</span>
                                <span class="badge badge-{{ auth()->user()->badge_profil }}">
                                    {{ auth()->user()->profil_formate }}
                                </span>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <div class="dropdown-header px-3 py-2">
                                    <strong>{{ auth()->user()->nom_complet }}</strong>
                                    <div class="mt-1">
                                        <span class="badge badge-{{ auth()->user()->badge_profil }}">
                                            {{ auth()->user()->profil_formate }}
                                        </span>
                                        <span class="badge badge-{{ auth()->user()->badge_statut }}">
                                            {{ ucfirst(auth()->user()->statut) }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user-circle"></i> Mon profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cog"></i> Paramètres
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column" id="sidebar">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-chevron-left"></i>
        </button>
          <div class="user-profile-badge">
                
<div class="ms-2">
    <span class="badge bg-primary p-2 rounded">
        <i class="fas fa-tachometer-alt text-white"></i>
    </span>
</div>



            </div>
        
        <ul class="nav nav-pills flex-column mb-auto mt-4">
            @php
                $user = auth()->user();
                $isAdmin = $user->isAdmin();
                $isGestionnaireInventaire = $user->isGestionnaireInventaire();
                $isMagasinier = $user->isMagasinier();
            @endphp
            
            <!-- Menu pour les administrateurs -->
            @if($isAdmin)
            <li class="nav-item">
                <a href="{{ route('admin.profils.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.profils.*') ? 'active' : '' }}">
                    <i class="fas fa-user-tag"></i>
                    <span class="nav-text">Profils & permissions</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.services.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span class="nav-text">Services & Localisations</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.comptes.index') }}" 
                   class="nav-link {{ request()->routeIs('admin.comptes.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">Gérer les comptes</span>
                </a>
            </li>
            @endif
            
            <!-- Menu pour les gestionnaires d'inventaire -->
            @if($isGestionnaireInventaire)
            <li class="nav-item">
                <a href="{{ route('inventaire.dashboard') }}" 
                   class="nav-link {{ request()->routeIs('inventaire.*') && !request()->routeIs('inventaire.rapports.*') ? 'active' : '' }}">
                    <i class="fas fa-boxes"></i>
                    <span class="nav-text">Inventaire</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('inventaire.equipements.index') }}" 
                   class="nav-link {{ request()->routeIs('inventaire.equipements.*') ? 'active' : '' }}">
                    <i class="fas fa-tools"></i>
                    <span class="nav-text">Équipements</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('inventaire.types.index') }}" 
                   class="nav-link {{ request()->routeIs('inventaire.types.*') ? 'active' : '' }}">
                    <i class="fas fa-cogs"></i>
                    <span class="nav-text">Types d'équipement</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('inventaire.fournisseurs.index') }}" 
                   class="nav-link {{ request()->routeIs('inventaire.fournisseurs.*') ? 'active' : '' }}">
                    <i class="fas fa-truck"></i>
                    <span class="nav-text">Fournisseurs</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('inventaire.scanner.index') }}" 
                   class="nav-link {{ request()->routeIs('inventaire.scanner.*') ? 'active' : '' }}">
                    <i class="fas fa-barcode"></i>
                    <span class="nav-text">Scanner</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('inventaire.rapports.index') }}" 
                   class="nav-link {{ request()->routeIs('inventaire.rapports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span class="nav-text">Rapports</span>
                </a>
            </li>
            @endif
            
            <!-- Menu pour les magasiniers -->
          
            
        </ul>
        
        <!-- Footer du sidebar -->
        <div class="sidebar-footer">
          
            
            <div class="version-badge">
                <i class="fas fa-info-circle"></i> Version 1.0.0
            </div>
        </div>
    </div>
    
    <!-- Contenu principal -->
    <main class="main-content" id="mainContent">
        <div class="container-fluid">
            <!-- En-tête de page -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h1>
                        <i class="fas fa-cogs"></i>
                        @yield('page-title', 'Administration')
                    </h1>
                    <div class="btn-toolbar">
                        @yield('page-actions')
                    </div>
                </div>
            </div>
            
            <!-- Messages flash -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Succès !</h5>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Erreur !</h5>
                            <p class="mb-0">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Attention !</h5>
                            <p class="mb-0">{{ session('warning') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-2">Erreurs de validation :</h5>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- Contenu -->
            @yield('content')
        </div>
    </main>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Initialiser DataTables
        $(document).ready(function() {
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
                },
                responsive: true,
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                pageLength: 25,
                order: [[0, 'desc']],
                scrollY: '400px',
                scrollCollapse: true,
                paging: true
            });
            
            // Initialiser Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
            
            // Toggle sidebar
            $('#sidebarToggle').on('click', function() {
                $('#sidebar').toggleClass('collapsed');
                $('#mainContent').toggleClass('expanded');
                
                const icon = $(this).find('i');
                if ($('#sidebar').hasClass('collapsed')) {
                    icon.removeClass('fa-chevron-left').addClass('fa-chevron-right');
                } else {
                    icon.removeClass('fa-chevron-right').addClass('fa-chevron-left');
                }
            });
            
            // Confirmation pour les suppressions
            $('.confirm-delete').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                const itemName = $(this).data('item-name') || 'cet élément';
                
                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    html: `<strong>Cette action est irréversible !</strong><br>Vous allez supprimer ${itemName}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash"></i> Oui, supprimer !',
                    cancelButtonText: '<i class="fas fa-times"></i> Annuler',
                    reverseButtons: true,
                    background: '#f8f9fa',
                    backdrop: 'rgba(0,0,0,0.4)'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
            
            // Confirmation pour les actions critiques
            $('.confirm-action').on('click', function(e) {
                e.preventDefault();
                const button = $(this);
                const form = button.closest('form');
                const message = button.data('confirm') || 'Êtes-vous sûr de vouloir effectuer cette action ?';
                const buttonText = button.data('button-text') || 'Confirmer';
                
                Swal.fire({
                    title: 'Confirmation',
                    text: message,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `<i class="fas fa-check"></i> ${buttonText}`,
                    cancelButtonText: '<i class="fas fa-times"></i> Annuler',
                    reverseButtons: true,
                    background: '#f8f9fa'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert:not(.alert-permanent)').alert('close');
            }, 5000);
            
            // Animation pour les cartes au chargement
            $('.card').each(function(index) {
                $(this).css({
                    'opacity': '0',
                    'transform': 'translateY(20px)'
                }).delay(index * 100).animate({
                    'opacity': '1',
                    'transform': 'translateY(0)'
                }, 500);
            });
            
            // Tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Highlight active menu item on hover
            $('.nav-link').hover(
                function() {
                    $(this).addClass('hover-active');
                },
                function() {
                    $(this).removeClass('hover-active');
                }
            );
            
            // Responsive sidebar pour mobiles
            if ($(window).width() < 992) {
                $('#sidebar').addClass('collapsed');
                $('#mainContent').addClass('expanded');
                $('#sidebarToggle i').removeClass('fa-chevron-left').addClass('fa-chevron-right');
            }
            
            $(window).resize(function() {
                if ($(window).width() < 992) {
                    $('#sidebar').addClass('collapsed');
                    $('#mainContent').addClass('expanded');
                    $('#sidebarToggle i').removeClass('fa-chevron-left').addClass('fa-chevron-right');
                } else {
                    $('#sidebar').removeClass('collapsed');
                    $('#mainContent').removeClass('expanded');
                    $('#sidebarToggle i').removeClass('fa-chevron-right').addClass('fa-chevron-left');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>