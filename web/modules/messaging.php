<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP Entreprise</title>
  </head>
  <body>

    <div class="tabs" id="agencyTabs"></div>

    <div class="row">

    <!-- EN-TÊTE DE L'AGENCE SÉLECTIONNÉE -->
    <div class="card shadow mb-4 col-lg-12">
        <div class="card-header py-3">

            <h6
                class="m-0 fw-bold fs-4"
                id="agencyTitle"
            >
                Chargement...
            </h6>

            <div id="agencyInfo"></div>

        </div>
    </div>


    <!-- MESSAGES -->
    <div
        class="messages"
        id="messages"
    >
        <div class="loading-messages">
            Chargement des messages...
        </div>
    </div>


    <!-- FORMULAIRE D'ENVOI -->
    <form
        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"
        method="post"
        class="php-form"
        id="message"
    >

        <div>

            <button
                type="button"
                class="file-button"
                id="fileButton"
            >
                📎
            </button>

            <input
                type="file"
                id="fileInput"
                style="display:none"
            >

            <textarea
                id="messageInput"
                class="message-input mb-3"
                rows="10"
                placeholder="write a message..."
            ></textarea>

            <button
                class="loading"
                type="submit"
            >
                SEND ➤
            </button>

        </div>

    </form>

</div>
  </body>
</html>