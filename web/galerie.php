<?php
  require_once './../api-siis/models/Gallery.php';
  $galleryModel = new Gallery();
  $gallery = $galleryModel->getAllGallery();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Canaan corporate consulting</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <!-- Favicons -->
  <link href="./assets/img/logo.png" rel="icon">


  <!-- Vendor CSS Files -->
  <link href="./assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="./assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="./assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="./assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="./assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="./assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="./assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="./assets/vendor/owl.carousel/owl.carousel.min.css" rel="stylesheet">
  <link href="./assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="./assets/css/site.css" rel="stylesheet">
</head>

<body>
  <!-- ======= Header ======= -->
  <header id="header">
    <div class="container">

      <div class="logo d-flex align-items-center">
        <img src="./assets/img/logo.png" class="img-fluid" alt="SIIS Logo">

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

      <nav>
        <div class="breadcrumbs">
          <a href="./../index.php">Index</a>
          <span>/</span>
          <span>Galerie</span>
        </div>
      </nav>

    </div>
  </header>


  <main class="mt-5 mb-5 container">

  <!-- Images -->
  <div class="row portfolio-container">
    <?php
    foreach ($gallery as $e) {
      $image = json_decode($e['picture'], true);
      $imgUrl = $image[0] ?? '';
      echo '<div class="col-lg-4 mt-4 portfolio-item" data-aos="fade-up">';
      echo '  <img src="' . htmlspecialchars($imgUrl) . '" class="img-fluid" alt="">';
      echo '<h4 class="title mt-2">' . strip_tags($e['description']) . '</h4>';
      echo '</div>';
    }
    ?>

  </div>
</main>

<!-- End Hero -->


  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

   <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="./assets/vendor/jquery/jquery.min.js"></script>
  <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="./assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="./assets/vendor/jquery-sticky/jquery.sticky.js"></script>
  <script src="./assets/vendor/venobox/venobox.min.js"></script>
  <script src="./assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="./assets/vendor/counterup/counterup.min.js"></script>
  <script src="./assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="./assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="./assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="./assets/vendor/aos/aos.js"></script>
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
  <script src="./assets/js/main.js"></script>

</body>

</html>