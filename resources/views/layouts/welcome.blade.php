<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>DCSSA</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-..." crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- AOS (Animate On Scroll) -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- GLightbox -->
    <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">

    <!-- Swiper -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@9.4.1/swiper-bundle.min.css" rel="stylesheet">


    <!-- Main CSS File -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">


    <!-- =======================================================
  * Template Name: Medilab
  * Template URL: https://bootstrapmade.com/medilab-free-medical-bootstrap-theme/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

    <header id="header" class="header sticky-top">

        <div class="topbar d-flex align-items-center">
            <div class="container d-flex justify-content-center justify-content-md-between">
                <div class="contact-info d-flex align-items-center">
                    <i class="bi bi-envelope d-flex align-items-center"><a
                            href="mailto:contact@example.com">contact@example.com</a></i>
                    <i class="bi bi-phone d-flex align-items-center ms-4"><span>+1 5589 55488 55</span></i>
                </div>
                <div class="social-links d-none d-md-flex align-items-center">
                    <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div><!-- End Top Bar -->

        <div class="branding d-flex align-items-center">

            <div class="container position-relative d-flex align-items-center justify-content-between">
                <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto">
                    <!-- Uncomment the line below if you also wish to use an image logo -->
                    <!-- <img src="assets/img/logo.png" alt=""> -->
                    <h1 class="sitename">DCSSA</h1>
                </a>

                <nav id="navmenu" class="navmenu">
                    <ul>
                        <li><a href="{{ url('/') }}" class="active">Home<br></a></li>
                        <li><a href="#about">About</a></li>






                        <li><a href="#contact">Contact</a></li>
                    </ul>
                    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
                </nav>

                <!-- Si l'utilisateur n'est pas connecté -->
                @guest
                    <a class="cta-btn d-none d-sm-block" href="{{ route('login') }}">Connexion</a>
                    <a class="cta-btn d-none d-sm-block" href="{{ route('register') }}">Inscription</a>
                @endguest

                <!-- Si l'utilisateur est connecté -->
                @auth

                    <a class="cta-btn d-none d-sm-block" href="{{ route('UserSimleDashboard') }}"
                        style="background-color: rgb(0, 255, 98);">Acceuil</a>

                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary text-white rounded-pill px-4 py-2">
                                Se déconnecter
                            </button>
                        </form>

                @endauth



            </div>

        </div>

    </header>

    <main class="main">

        @yield('content')

    </main>


    <footer id="footer" class="footer light-background">

        <div class="container footer-top">
            <div class="row gy-4">

                <!-- À propos de la DCSSA -->
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="index.html" class="logo d-flex align-items-center">
                        <span class="sitename">DCSSA</span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p>Direction centrale du service de santé des armées</p>
                        <p>Forces armées béninoises, Bénin</p>
                        <p class="mt-3"><strong>Phone:</strong> <span>+229 21 00 00 00</span></p>
                        <p><strong>Email:</strong> <span>contact@dcssa.bj</span></p>
                    </div>
                    <div class="social-links d-flex mt-4">
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <!-- Liens utiles -->
                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Liens utiles</h4>
                    <ul>
                        <li><a href="#">Accueil</a></li>
                        <li><a href="#">À propos</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                        <li><a href="#">Conditions d'utilisation</a></li>
                    </ul>
                </div>

                <!-- Services DCSSA -->
                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Nos Services</h4>
                    <ul>
                        <li><a href="#">Gestion des équipements</a></li>
                        <li><a href="#">Maintenance préventive</a></li>
                        <li><a href="#">Suivi des interventions</a></li>
                        <li><a href="#">Planification logistique</a></li>
                        <li><a href="#">Formations du personnel</a></li>
                    </ul>
                </div>

                <!-- Documentation et réglementaire -->
                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Documentation</h4>
                    <ul>
                        <li><a href="#">Guides utilisateurs</a></li>
                        <li><a href="#">Procédures maintenance</a></li>
                        <li><a href="#">Contrats fournisseurs</a></li>
                        <li><a href="#">Plans d’investissement</a></li>
                        <li><a href="#">Rapports statistiques</a></li>
                    </ul>
                </div>

                <!-- Autres informations -->
                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Contact & Support</h4>
                    <ul>
                        <li><a href="#">Support technique</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Formulaire de demande</a></li>
                        <li><a href="#">Coordonnées internes</a></li>
                        <li><a href="#">Ressources DCSSA</a></li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">DCSSA</strong> <span>Tous droits réservés</span>
            </p>
            <div class="credits">
                 <a href="{{ url('/') }}">Conçu pour les Forces armées béninoises</a>
            </div>
        </div>

    </footer>


    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Bootstrap JS Bundle (Popper inclus) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-..."
        crossorigin="anonymous"></script>

    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <!-- GLightbox JS -->
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9.4.1/swiper-bundle.min.js"></script>

    <!-- FontAwesome JS (optionnel, si tu as besoin des icônes dynamiques) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" integrity="sha512-..."
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Ton JS principal -->
    <script src="{{ asset('js/main.js') }}"></script>


    <!-- Main JS File -->
    <script src="{{ asset('js/main.js') }}"></script>


</body>

</html>
