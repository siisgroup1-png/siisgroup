<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion - SIIS</title>
        <!-- Icons -->
        <link href="./assets/img/logo.png" class="logo icon" rel="icon">

        <link href="./assets/vendor/fontawesome-free-6.7.2-web/css/all.min.css" rel="stylesheet">
        <link href="./assets/vendor/icofont/icofont.min.css" rel="stylesheet">
        <link href="./assets/css/style.css" rel="stylesheet">
    </head>
    <body>

        <div class="background">
            <div class="shape orange"></div>
            <div class="shape blue"></div>
        </div>

        <div class="login-box">

            <img src="./assets/img/logo.png" class="logo">

            <h2>Connexion</h2>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" class="php-form text-center" id='connecter'>
                <div class="erreur"></div>
                <div class="input-box">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="login" required placeholder="User name"
                    >
                </div>

                <div class="input-box">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" required placeholder="Password" id="password">
                    <i class="fa-solid fa-eye eye" id="togglePassword"></i>
                </div>

                <button class="loading" type="submit">
                    Login
                </button>

            </form>

        </div>

        <script src="./assets/vendor/jquery/jquery.min.js"></script>
        <script src="./assets/vendor/noBack/noBack.js"></script>

        <script>
        const password = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");

        togglePassword.addEventListener("click", function () {

            if (password.type === "password") {
                password.type = "text";
                this.classList.remove("fa-eye");
                this.classList.add("fa-eye-slash");
            } else {
                password.type = "password";
                this.classList.remove("fa-eye-slash");
                this.classList.add("fa-eye");
            }

        });
        </script>

        <script>
    $('#connecter').on('submit', function(e) {
        e.preventDefault();
        $('button.loading').addClass('show-loader').prop('disabled', true);

        var postdata = {
            login: $('input[name="login"]').val(),
            password: $('input[name="password"]').val()
        };

        $.ajax({
            type: 'POST',
            url: '/api-siis/routes/auth.php',
            data: JSON.stringify(postdata),
            contentType: "application/json",
            dataType: "json",
            success: function(result) {
                $('button.loading').removeClass('show-loader').prop('disabled', false);

                if(result.success && result.token) {
                    // Stocker le token et redirection après 1s
                    localStorage.setItem('token', result.token);
                    $('.erreur').html('<div class="custom-alert success-alert"><i class="icofont-check" style="margin-right: 10px; font-weight: bold;"></i>Vous êtes connecté</div>').delay(500).hide(function(){ 
                      window.location.href = './dashboard.php';
                    })  
                }
                else{
                  $('.erreur').hide().html('<div class="custom-alert error-alert"><i class="icofont-close" style="margin-right: 10px; font-weight: bold;"></i>Information incorrect  </div>').slideDown(500);
                }
            },
            error: function(xhr, status, error) {
                $('button.loading').removeClass('show-loader').prop('disabled', false);
                // On peut afficher un message générique d'erreur serveur
                $('.erreur').hide().html(
                    '<div class="alert alert-block alert-danger">' +
                    '<i class="icofont-close" style="margin-right: 10px; font-weight: bold;"></i>' +
                    'Erreur serveur : ' + error +
                    '</div>'
                ).slideDown();
            }
        });
    });

  </script>
    </body>
</html>