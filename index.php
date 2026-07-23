<?php
  require_once './api-siis/models/Achievement.php';
  $AchievementModel = new Achievement();
  $achievement = $AchievementModel->getAllAchievement();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>SIIS</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <!-- Favicons -->
  <link href="./web/assets/img/logo.png" rel="icon">

  <!-- Vendor CSS Files -->
  <link href="./web/assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="./web/assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="./web/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="./web/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="./web/assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="./web/assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="./web/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="./web/assets/vendor/owl.carousel/owl.carousel.min.css" rel="stylesheet">
  <link href="./web/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="./web/assets/vendor/fontawesome-free-6.7.2-web/css/all.min.css" rel="stylesheet">
  <link href="./web/assets/css/site.css" rel="stylesheet">
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header">
    <div class="container d-flex align-items-center justify-content-between">

      <div class="logo d-flex align-items-center">
        <img src="./web/assets/img/logo.png" class="img-fluid" alt="SIIS Logo">

        <div class="language-box">
          <button id="languageToggle">
            <i class="bi bi-translate"></i>
            Languages
          </button>

          <div id="languageDropdown" class="language-dropdown">
            <div id="google_translate_element"></div>
          </div>
        </div>
      </div>


      <nav class="nav-menu d-none d-lg-block">
        <ul>
          <li class="active">
            <a href="index.php">Accueil</a>
          </li>

          <li>
            <a href="#about">A propos</a>
          </li>

          <li>
            <a href="#service">Services</a>
          </li>

          <li>
            <a href="#realisation">Réalisation</a>
          </li>

          <li>
            <a href="./web/galerie.php">Galerie</a>
          </li>

          <!-- <li>
            <a href="./web/recrutement.php">Recrutement</a>
          </li> -->

          <li>
            <a href="#contact">Contact</a>
          </li>

        </ul>
      </nav>

    </div>
  </header><!-- End Header -->



<div class="popup" style="position: fixed; top: 70px; z-index: 1000; width: auto; display: none;      
"></div>

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="hero">

    <div id="heroCarousel" class="carousel slide carousel-fade" data-ride="carousel">

        <div class="carousel-inner">

            <div class="carousel-item active">

                <img src="./web/assets/img/bg.png" alt="SIIS">

                <div class="hero-overlay"></div>

                <div class="container h-100">

                    <div class="row h-100 align-items-center">

                        <div class="col-lg-12">

                            <span class="hero-tag">
                                Votre partenaire technologique
                            </span>

                            <h1>
                                System Information <br>
                                & Integrated Solution
                            </h1>

                            <p>
                                Nous concevons des solutions numériques innovantes
                                pour accompagner les entreprises dans leur
                                transformation digitale et améliorer leur performance.
                            </p>

                            <div class="hero-buttons">

                                <a href="#service" class="btn-hero-primary">
                                    Nos services
                                </a>

                                <a href="#contact" class="btn-hero-secondary">
                                    Nous contacter
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <a class="carousel-control-prev" href="#heroCarousel" data-slide="prev">
            <span class="carousel-control-prev-icon bi bi-chevron-left"></span>
        </a>

        <a class="carousel-control-next" href="#heroCarousel" data-slide="next">
            <span class="carousel-control-next-icon bi bi-chevron-right"></span>
        </a>

    </div>

</section><!-- End Hero -->

  <main id="main">

    <!-- ======= About Us Section ======= -->
    <section id="about" class="about">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 section-about mb-lg-0" data-aos="fade-up">
        <div class="about-image">
          <img src="./web/assets/img/dg.jpeg" class="img-fluid" alt="SIIS">
        </div>
      </div>
      <div class="col-lg-6 section-about" data-aos="fade-up">
        <div class="about-content">
          <h2 class="inner-title">
            Qui sommes nous
          </h2>
          <div class="title-line"></div>
          <div class="our-story text-justify">
            <p>
              <b>SIIS Group</b> est une entreprise intelligente, dynamique et innovante, fondée en 2020. Specialisée dans divers secteurs, notamment les prestations de services, les solutions informatiques, le BTP, l'Import/Export, les routes et travaux routiers, la constructon de batiments, ainsi que la vente et l'achat de vehicules.<br><b>SIIS Group</b> s'impose comme acteur incontournable dans son domaine, son objectif pricipale est de fournir des services de qualité, fiables et adaptés aux besoins de ses clients.
            </p>
          </div>
          <!-- <a href="#" class="btn-about">
            En savoir plus
          </a> -->
        </div>
      </div>
    </div>

    <div class="row align-items-center">
      <div class="col-lg-6 section-about mb-lg-0" data-aos="fade-up">
        <div class="about-content">
          <h2 class="inner-title">
            Notre Vision
          </h2>
          <div class="title-line"></div>
          <div class="our-story text-justify">
            <p>
                <span class="fa-solid fa-check"></span>
                Stimuler l’économie nationale
            </p>
            <p>
                <span class="fa-solid fa-check"></span>
                Créer des emplois directs et indirects
            </p>
            <p>
                <span class="fa-solid fa-check"></span>
                Réduire la dépendance informelle
            </p>
            <p>
                <span class="fa-solid fa-check"></span>
                lutter contre la contrebande
            </p>
            <p>
                <span class="fa-solid fa-check"></span>
                Soutenir la croissance inclusive</p>
          </div>
        </div>
      </div>
      <div class="col-lg-6 section-about" data-aos="fade-up">
        <div class="about-content">
          <h2 class="inner-title">
            Nos Valeurs
          </h2>
          <div class="title-line"></div>
          <div class="our-story text-justify">
            <p>
                <span class="fa-solid fa-check"></span>
                Intégrité
            </p>
            <p>
                <span class="fa-solid fa-check"></span>
                Innovation
            </p>
            <p>
                <span class="fa-solid fa-check"></span>
                Excellence
            </p>
            <p>
                <span class="fa-solid fa-check"></span>
                Responsabilité sociale
            </p>
            <p>
                <span class="fa-solid fa-check"></span>
                Esprit de collaboration
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12 section-about" data-aos="fade-up">
        <div class="about-content">
          <h2 class="inner-title">
           Nos produits
          </h2>
          <div class="title-line"></div>
          <div class="our-story text-justify">
            <p>
              <b>ROSNÖS INDUSTRIE :</b> est une succursale de SIIS spécialisée dans la conception, la fabrication, l’assemblage, la distribution et le service après-vente de solutions énergétiques, de systèmes d’alimentation électrique intégrés et d'appareils éléctriques de confort et de consommation.<br>
              L’entreprise développe à <b>Kribi le premier pôle industriel intégré</b> de la CEMAC+RDC dédié à l’assemblage CKD/SKD de six familles de produits critiques :
             <b>(groupes électrogènes, batteries solaires LiFePO₄, Powerbanks, Climatiseurs, Téléviseurs, Congélateurs).</b>

            <p>
              Ce projet industriel vise à renforcer la production locale, réduire la dépendance aux importations, créer des emplois qualifiés et soutenir l’industrialisation de la sous-région CEMAC+RDC ce qui cadre avec les objectifs actuels des autorités camerounaises notamment dans <b>l'import-subtitution</b> et le transfert de technologie.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section><!-- End About Us Section -->

    <!-- ======= Our recommandation Section ======= -->
  
    <section id="service" class="service">
      <div class="container">
        <div class="section-title">
          <h2>Nos services</h2>
          <div class="title-line"></div>
        </div>

        <div class="row">
          <div class="col-lg-4 col-md-6 mt-4" data-aos="fade-up" data-aos-delay="100">
            <div class="service-card">
              <div class="icon">
                <i class="bi bi-building"></i>
              </div>
              <h3>GENIE CIVIL</h3>
              <p>Construction des batiments et forages</p>
              <p>Entretien, aménagement et rehabilitation des infrastructure routieres</p>
              <p>Montage et productions des dossiers des DAO</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 mt-4" data-aos="fade-up" data-aos-delay="100">
            <div class="service-card">
              <div class="icon">
                <i class="bi bi-pc-display"></i>
              </div>
              <h3>SERVICE INFORMATIQUE</h3>
              <p>Achat et vente des équipements informatiques</p>
              <p>Conception et realisation des applications de gestion et site web</p>
              <p>Montage et réalisation des visuels</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 mt-4" data-aos="fade-up" data-aos-delay="100">
            <div class="service-card">
              <div class="icon">
                <i class="bi bi-lightning-charge"></i>
              </div>
              <h3>INSTALLATION DU MATERIELS PHOTOVOLTAIQUES</h3>
              <p>
                Pose et installation du materiels photovoltaiques.
              </p>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 mt-4" data-aos="fade-up" data-aos-delay="100">
            <div class="service-card">
              <div class="icon">
                <img src="./web/assets/img/rosnos.png" class="img-fluid">
              </div>
              <h3>ROSNÖS INDUSTRIE</h3>
              <p><b>Solution énergetique et système d'alimentation électrique intégré</b>: Groupes électrogènes essence, diesel, GPL et solutions hybrides solaire-batterie,  power bank, batteries</p>
                <p><b>Appareils électrique de confort et consommation</b>: téléviseur, congelateur, climatiseur</p>
            </div>
          </div>
        </div>
      </div>
    </section><!-- End Our Team Section -->

    <section id="realisation" class="realisation">
      <div class="container">
        <div class="section-title">
          <h2>Nos réalisations</h2>
          <div class="title-line"></div>
        </div>
      </div>
      <div class="container-fluid">
        <div class="row">
          <?php
            foreach ($achievement as $e) {
              echo"<div class='col-lg-4' data-aos='fade-up' data-aos-delay='100'>
                <div class='item'>
                  <h3><a href='./web/achievement.php?id=".$e["id_achievement"]."'>$e[libel]</a></h3>
                </div>
              </div>";
            }
          ?>
        </div>
      </div>
    </section>

    <!-- ======= Contact Us Section ======= -->
    <section id="contact" class="contact section">

    <div class="container">

        <div class="section-title">
            <h2>Contactez-nous</h2>
            <div class="title-line"></div>
        </div>

        <div class="row align-items-center">

            <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-up">

                <img src="./web/assets/img/contact.png" class="img-fluid contact-image" alt="Contact">

            </div>

            <div class="col-lg-6" data-aos="fade-up">

                <div class="contact-card">

                    <div class="contact-item">

                        <div class="contact-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>

                        <div>

                            <h4>Notre adresse</h4>

                            <p>montée Anne-rouge, face BGFI BANK , Yaoundé, Cameroon</p>

                        </div>

                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <div>
                            <h4>Téléphone</h4>
                            <p>
                                <a href="tel:+237222221825">
                                    +237 222221825
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div>
                            <h4>Email</h4>
                            <p>
                                <a href="mailto:contact@siis.com">
                                  contact@siis.com
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-facebook"></i>
                        </div>
                        <div>
                            <h4>Facebook</h4>
                            <p>
                                <a href="#" target="_blank">
                                    SIIS Officiel
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-linkedin"></i>
                        </div>
                        <div>
                            <h4>LinkedIn</h4>
                            <p>
                                <a href="#" target="_blank">
                                    SIIS
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
   <footer id="footer" class="footer">
    <div class="container footer-top" data-aos="fade-up" data-aos-delay="200">
<!--       <div class="row gy-4">
        <div class="col-lg-6">
          <img src="./web/assets/img/logo.png">
        </div>
    </div> -->
    <div>
      <h4>Ils nous font confiance</h4>
        <div class="owl-carousel">
            <div class="item">
                <img src="./web/assets/img/canon.png" alt="Rosnos">
            </div>
            <div class="item">
                <img src="./web/assets/img/apc.png" alt="Logo">
            </div>
            <div class="item">
                <img src="./web/assets/img/fortinet.png" alt="Logo">
            </div>
            <div class="item">
                <img src="./web/assets/img/crtv.png" alt="Logo">
            </div>
            <div class="item">
                <img src="./web/assets/img/camtel.png" alt="Logo">
            </div>
            <div class="item">
                <img src="./web/assets/img/patnuc.png" alt="Logo">
            </div>
            <div class="item">
                <img src="./web/assets/img/samsung.png" alt="Logo">
            </div>
            <div class="item">
                <img src="./web/assets/img/huawei.png" alt="Logo">
            </div>
            <div class="item">
                <img src="./web/assets/img/transport.png" alt="Logo">
            </div>
            <div class="item">
                <img src="./web/assets/img/sante.png" alt="Logo">
            </div>
            <div class="item">
                <img src="./web/assets/img/hp.png" alt="Logo">
            </div>
            <div class="item">
                <img src="./web/assets/img/canon.png" alt="Logo">
            </div>
            <div class="item">
                <img src="./web/assets/img/cisco.png" alt="Logo">
            </div>
        </div>
    </div>
    <hr>
    <div class="container-fuid copyright text-center">
      <p>© <span>Copyright 2026</span> <strong class="px-1 sitename">SIIS (System Information and Integrate Solution)</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        Improve your Future
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

   <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="./web/assets/vendor/jquery/jquery.min.js"></script>
  <script src="./web/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="./web/assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="./web/assets/vendor/jquery-sticky/jquery.sticky.js"></script>
  <script src="./web/assets/vendor/venobox/venobox.min.js"></script>
  <script src="./web/assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="./web/assets/vendor/counterup/counterup.min.js"></script>
  <script src="./web/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="./web/assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="./web/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="./web/assets/vendor/aos/aos.js"></script>
  <script>

    const toggle=document.getElementById("languageToggle");
    const dropdown=document.getElementById("languageDropdown");

    toggle.onclick=function(e){

        e.stopPropagation();

        dropdown.classList.toggle("show");

    }

    document.onclick=function(e){

        if(!toggle.contains(e.target) && !dropdown.contains(e.target)){

            dropdown.classList.remove("show");

        }

    }

    function googleTranslateElementInit(){

        new google.translate.TranslateElement({

            pageLanguage:'fr',

            includedLanguages:'fr,en,es,de,it,pt,ru,zh-CN,ja,ar',

            autoDisplay:false

        },'google_translate_element');

    }
  </script>

  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

  <!-- Template Main JS File -->
  <script src="./web/assets/js/main.js"></script>

</body>

</html>