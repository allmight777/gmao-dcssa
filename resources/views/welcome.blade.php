@extends('layouts.welcome')

@section('content')
    <!-- Hero Section -->
    <section id="hero" class="hero section light-background">

        <img src="{{ asset('images/1.webp') }}" alt="DCSSA - Service de Santé des Armées" data-aos="fade-in">

        <div class="container position-relative">

            <div class="welcome position-relative" data-aos="fade-down" data-aos-delay="100">
                <h2>SYSTÈME DE GESTION DES MATÉRIELS DE SANTÉ</h2>
                <p>
                    Une solution intégrée de GMAO dédiée à la Direction centrale du service de santé
                    des armées des Forces armées béninoises
                </p>
            </div><!-- End Welcome -->

            <div class="content row gy-4">

                <!-- Why Box -->
                <div class="col-lg-4 d-flex align-items-stretch">
                    <div class="why-box" data-aos="zoom-out" data-aos-delay="200">
                        <h3>GMAO ?</h3>
                        <p>
                            La GMAO de la DCSSA vise à renforcer la traçabilité, la sécurité et la
                            disponibilité des équipements biomédicaux, techniques et logistiques
                            déployés au sein des formations sanitaires militaires, en temps de paix
                            comme en situation opérationnelle.
                        </p>
                        <div class="text-center">
                            <a href="#about" class="more-btn">
                                <span>En savoir plus</span> <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div><!-- End Why Box -->

                <!-- Icon Boxes -->
                <div class="col-lg-8 d-flex align-items-stretch">
                    <div class="d-flex flex-column justify-content-center">
                        <div class="row gy-4">

                            <div class="col-xl-4 d-flex align-items-stretch">
                                <div class="icon-box" data-aos="zoom-out" data-aos-delay="300">
                                    <i class="bi bi-clipboard-data"></i>
                                    <h4>Traçabilité et inventaire</h4>
                                    <p>
                                        Suivi exhaustif des équipements, de leur acquisition à leur réforme,
                                        avec historisation complète des mouvements et interventions.
                                    </p>
                                </div>
                            </div><!-- End Icon Box -->

                            <div class="col-xl-4 d-flex align-items-stretch">
                                <div class="icon-box" data-aos="zoom-out" data-aos-delay="400">
                                    <i class="bi bi-shield-lock"></i>
                                    <h4>Sécurité et contrôle</h4>
                                    <p>
                                        Accès hiérarchisé, journalisation des actions et conformité aux
                                        exigences de sécurité des systèmes d’information militaires.
                                    </p>
                                </div>
                            </div><!-- End Icon Box -->

                            <div class="col-xl-4 d-flex align-items-stretch">
                                <div class="icon-box" data-aos="zoom-out" data-aos-delay="500">
                                    <i class="bi bi-gear-wide-connected"></i>
                                    <h4>Maintenance opérationnelle</h4>
                                    <p>
                                        Planification, suivi et optimisation des maintenances préventives,
                                        correctives et des contrôles qualité sur l’ensemble du territoire.
                                    </p>
                                </div>
                            </div><!-- End Icon Box -->

                        </div>
                    </div>
                </div><!-- End Icon Boxes -->

            </div><!-- End Content -->

        </div>

    </section><!-- /Hero Section -->


    <!-- About Section -->
    <section id="about" class="about section">

        <div class="container">

            <div class="row gy-4 gx-5">

                <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="200">
                    <img src="{{ asset('images/2.webp') }}" class="img-fluid" alt="">
                    <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox pulsating-play-btn"></a>
                </div>

                <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
                    <h3>À propos du projet</h3>
                    <p>
                        La Direction centrale du service de santé des armées (DCSSA) est un organisme interarmées
                        des Forces armées béninoises, placé sous l’autorité du Chef d’état-major général. Elle est
                        chargée de l’administration, de la gestion et de la mise en œuvre opérationnelle du service
                        de santé des armées, en appui à l’Armée de terre, à l’Armée de l’air, à la Marine nationale
                        et à la Garde nationale, aussi bien en temps de paix qu’en situation de crise ou de conflit.
                    </p>
                    <ul>
                        <li>
                            <i class="fa-solid fa-vial-circle-check"></i>
                            <div>
                                <h5>Modernisation de la gestion des équipements de santé</h5>
                                <p>
                                    Le projet vise la mise en place d’un logiciel intégré de GMAO permettant une
                                    gestion centralisée, sécurisée et traçable des équipements biomédicaux,
                                    techniques, logistiques et informatiques au sein de toutes les formations
                                    sanitaires de la DCSSA.
                                </p>
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-pump-medical"></i>
                            <div>
                                <h5>Soutien sanitaire et continuité opérationnelle</h5>
                                <p>
                                    La solution permettra d’assurer la disponibilité permanente des équipements, le
                                    suivi des interventions de maintenance, la gestion des stocks et des contrats,
                                    contribuant ainsi à la continuité et à la qualité des soins au profit des Forces
                                    armées béninoises.
                                </p>
                            </div>
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </section><!-- /About Section -->

    <!-- Stats Section -->
    <section id="stats" class="stats section light-background">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4">

                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                    <i class="fa-solid fa-user-doctor"></i>
                    <div class="stats-item">
                        <span data-purecounter-start="0" data-purecounter-end="85" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Doctors</p>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                    <i class="fa-regular fa-hospital"></i>
                    <div class="stats-item">
                        <span data-purecounter-start="0" data-purecounter-end="18" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Departments</p>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                    <i class="fas fa-flask"></i>
                    <div class="stats-item">
                        <span data-purecounter-start="0" data-purecounter-end="12" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Research Labs</p>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
                    <i class="fas fa-award"></i>
                    <div class="stats-item">
                        <span data-purecounter-start="0" data-purecounter-end="150" data-purecounter-duration="1"
                            class="purecounter"></span>
                        <p>Awards</p>
                    </div>
                </div><!-- End Stats Item -->

            </div>

        </div>

    </section><!-- /Stats Section -->





    <!-- Faq Section -->
    <section id="faq" class="faq section light-background">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Foire Aux Questions</h2>
            <p>Réponses aux questions fréquentes sur le système de gestion et de maintenance des équipements de la
                DCSSA</p>
        </div><!-- End Section Title -->

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">

                    <div class="faq-container">

                        <div class="faq-item faq-active">
                            <h3>Quel est l’objectif principal de ce système de gestion ?</h3>
                            <div class="faq-content">
                                <p>
                                    Le système centralise la gestion des équipements médicaux, techniques et
                                    logistiques de la DCSSA, assurant le suivi des maintenances et la disponibilité
                                    opérationnelle des matériels de santé au profit des Forces armées béninoises.
                                </p>
                            </div>
                            <i class="faq-toggle bi bi-chevron-right"></i>
                        </div><!-- End Faq item-->

                        <div class="faq-item">
                            <h3>Quels types d’équipements sont concernés par la plateforme ?</h3>
                            <div class="faq-content">
                                <p>
                                    La plateforme couvre les équipements biomédicaux, informatiques et logistiques
                                    utilisés dans les hôpitaux militaires, infirmeries et unités médicales
                                    déployées.
                                </p>
                            </div>
                            <i class="faq-toggle bi bi-chevron-right"></i>
                        </div><!-- End Faq item-->

                        <div class="faq-item">
                            <h3>Comment la sécurité des données est-elle assurée ?</h3>
                            <div class="faq-content">
                                <p>
                                    Les données sont protégées via un système d’authentification sécurisé, des
                                    sauvegardes régulières et des accès restreints selon les rôles des utilisateurs.
                                </p>
                            </div>
                            <i class="faq-toggle bi bi-chevron-right"></i>
                        </div><!-- End Faq item-->

                        <div class="faq-item">
                            <h3>Qui peut accéder au système ?</h3>
                            <div class="faq-content">
                                <p>
                                    L’accès est réservé aux personnels autorisés de la DCSSA, incluant les
                                    responsables de maintenance, les gestionnaires de matériel et les
                                    administrateurs.
                                </p>
                            </div>
                            <i class="faq-toggle bi bi-chevron-right"></i>
                        </div><!-- End Faq item-->

                        <div class="faq-item">
                            <h3>Comment sont planifiées les maintenances ?</h3>
                            <div class="faq-content">
                                <p>
                                    Les maintenances sont planifiées automatiquement par le système selon les dates,
                                    les cycles d’utilisation et les recommandations des fabricants.
                                </p>
                            </div>
                            <i class="faq-toggle bi bi-chevron-right"></i>
                        </div><!-- End Faq item-->

                        <div class="faq-item">
                            <h3>Comment signaler un incident ou une panne ?</h3>
                            <div class="faq-content">
                                <p>
                                    Les utilisateurs peuvent créer un ticket via la plateforme, qui sera traité par
                                    le service maintenance. Chaque intervention est tracée pour suivi et analyse.
                                </p>
                            </div>
                            <i class="faq-toggle bi bi-chevron-right"></i>
                        </div><!-- End Faq item-->

                    </div>

                </div><!-- End Faq Column-->

            </div>

        </div>

    </section><!-- /Faq Section -->




    <!-- Gallery Section -->
    <section id="gallery" class="gallery section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Gallery</h2>
            <p>Plongez dans notre univers</p>

        </div><!-- End Section Title -->

        <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">

            <div class="row g-0">

                <div class="col-lg-3 col-md-4">
                    <div class="gallery-item">
                        <a href="{{ asset('images/9.webp') }}" class="glightbox" data-gallery="images-gallery">
                            <img src="{{ asset('images/9.webp') }}" alt="" class="img-fluid">
                        </a>
                    </div>
                </div><!-- End Gallery Item -->

                <div class="col-lg-3 col-md-4">
                    <div class="gallery-item">
                        <a href="{{ asset('images/2.webp') }}" class="glightbox" data-gallery="images-gallery">
                            <img src="{{ asset('images/2.webp') }}" alt="" class="img-fluid">
                        </a>
                    </div>
                </div><!-- End Gallery Item -->

                <div class="col-lg-3 col-md-4">
                    <div class="gallery-item">
                        <a href="{{ asset('images/3.webp') }}" class="glightbox" data-gallery="images-gallery">
                            <img src="{{ asset('images/3.webp') }}" alt="" class="img-fluid">
                        </a>
                    </div>
                </div><!-- End Gallery Item -->

                <div class="col-lg-3 col-md-4">
                    <div class="gallery-item">
                        <a href="{{ asset('images/4.webp') }}" class="glightbox" data-gallery="images-gallery">
                            <img src="{{ asset('images/4.webp') }}" alt="" class="img-fluid">
                        </a>
                    </div>
                </div><!-- End Gallery Item -->

                <div class="col-lg-3 col-md-4">
                    <div class="gallery-item">
                        <a href="{{ asset('images/5.webp') }}" class="glightbox" data-gallery="images-gallery">
                            <img src="{{ asset('images/5.webp') }}" alt="" class="img-fluid">
                        </a>
                    </div>
                </div><!-- End Gallery Item -->

                <div class="col-lg-3 col-md-4">
                    <div class="gallery-item">
                        <a href="{{ asset('images/6.webp') }}" class="glightbox" data-gallery="images-gallery">
                            <img src="{{ asset('images/6.webp') }}" alt="" class="img-fluid">
                        </a>
                    </div>
                </div><!-- End Gallery Item -->

                <div class="col-lg-3 col-md-4">
                    <div class="gallery-item">
                        <a href="{{ asset('images/7.webp') }}" class="glightbox" data-gallery="images-gallery">
                            <img src="{{ asset('images/7.webp') }}" alt="" class="img-fluid">
                        </a>
                    </div>
                </div><!-- End Gallery Item -->

                <div class="col-lg-3 col-md-4">
                    <div class="gallery-item">
                        <a href="{{ asset('images/8.webp') }}" class="glightbox" data-gallery="images-gallery">
                            <img src="{{ asset('images/8.webp') }}" alt="" class="img-fluid">
                        </a>
                    </div>
                </div><!-- End Gallery Item -->

            </div>

        </div>

    </section><!-- /Gallery Section -->


    <!-- Contact Section -->
    <section id="contact" class="contact section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Contact</h2>
            <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
        </div><!-- End Section Title -->

        <div class="mb-5" data-aos="fade-up" data-aos-delay="200">
            <iframe style="border:0; width: 100%; height: 270px;"
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48389.78314118045!2d-74.006138!3d40.710059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a22a3bda30d%3A0xb89d1fe6bc499443!2sDowntown%20Conference%20Center!5e0!3m2!1sen!2sus!4v1676961268712!5m2!1sen!2sus"
                frameborder="0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div><!-- End Google Maps -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4">

                <div class="col-lg-4">
                    <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                        <i class="bi bi-geo-alt flex-shrink-0"></i>
                        <div>
                            <h3>Location</h3>
                            <p>A108 Adam Street, New York, NY 535022</p>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                        <i class="bi bi-telephone flex-shrink-0"></i>
                        <div>
                            <h3>Call Us</h3>
                            <p>+1 5589 55488 55</p>
                        </div>
                    </div><!-- End Info Item -->

                    <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
                        <i class="bi bi-envelope flex-shrink-0"></i>
                        <div>
                            <h3>Email Us</h3>
                            <p>info@example.com</p>
                        </div>
                    </div><!-- End Info Item -->

                </div>

                <div class="col-lg-8">
                    <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up"
                        data-aos-delay="200">
                        <div class="row gy-4">

                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" placeholder="Your Name"
                                    required="">
                            </div>

                            <div class="col-md-6 ">
                                <input type="email" class="form-control" name="email" placeholder="Your Email"
                                    required="">
                            </div>

                            <div class="col-md-12">
                                <input type="text" class="form-control" name="subject" placeholder="Subject"
                                    required="">
                            </div>

                            <div class="col-md-12">
                                <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                            </div>

                            <div class="col-md-12 text-center">
                                <div class="loading">Loading</div>
                                <div class="error-message"></div>
                                <div class="sent-message">Your message has been sent. Thank you!</div>

                                <button type="submit">Send Message</button>
                            </div>

                        </div>
                    </form>
                </div><!-- End Contact Form -->

            </div>

        </div>

    </section><!-- /Contact Section -->
@endsection
