<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ERP Entreprise</title>
</head>
<body>

<div class="tabs">
    <button class="tab-button active" data-tab="france">SIIS FRANCE</button>
    <button class="tab-button" data-tab="inde">SIIS INDE</button>
    <button class="tab-button" data-tab="chine">SIIS CHINE</button>
</div>

<div id="france" class="tab-content active">
    <div class="row"> 
      <div class="card shadow mb-4 col-lg-12">
        <div class="card-header py-3">
          <h6 class="m-0 fw-bold fs-4">SIIS FRANCE</h6>
        </div>
        <div>
          <div class="messages" id="messages">

    <!-- MESSAGE REÇU -->
    <div class="message received">

        <div class="message-avatar received-avatar">
            🇫🇷
        </div>

        <div class="message-wrapper">

            <div class="message-meta">
                <strong>SIIS France</strong>
                <span>10:32</span>
            </div>

            <div class="message-bubble received-bubble">
                Bonjour SIIS Cameroun 👋<br>
                Voici le nouveau document.
            </div>

            <div class="message-file received-file">
                📎
                <div>
                    <strong>contrat.pdf</strong>
                    <small>Document PDF</small>
                </div>

                <button type="button">⬇</button>
            </div>

        </div>

    </div>


    <!-- MESSAGE ENVOYÉ -->
    <div class="message sent">

        <div class="message-wrapper">

            <div class="message-meta sent-meta">
                <span>10:35</span>
                <strong>SIIS Cameroun</strong>
            </div>

            <div class="message-bubble sent-bubble">
                Bien reçu, merci beaucoup 👍
            </div>

            <div class="message-status">
                Envoyé ✓✓
            </div>

        </div>

        <div class="message-avatar sent-avatar">
            🇨🇲
        </div>

    </div>


    <!-- MESSAGE REÇU -->
    <div class="message received">

        <div class="message-avatar received-avatar">
            🇫🇷
        </div>

        <div class="message-wrapper">

            <div class="message-meta">
                <strong>SIIS France</strong>
                <span>10:37</span>
            </div>

            <div class="message-bubble received-bubble">
                Parfait. Nous restons disponibles si nécessaire.
            </div>

        </div>

    </div>


</div>
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" class="php-form" id='message' >
            <div>
                <button type="button" class="file-button" onclick="document.getElementById('fileInput').click()">📎</button>
                <input type="file" id="fileInput" style="display:none">

                <textarea id="messageInput" class="message-input mb-3" rows="10" placeholder="write a message..."></textarea>

                <button class="loading" type="submit">
                    SEND ➤
                </button>
            </div>
        </form>

        </div>
      </div>
    </div>
</div>

<div id="inde" class="tab-content">
    <button class="btn-achievement btn btn-light mb-2">Add Achievement</button>
    <div class="row"> 
      <div class="card shadow mb-4 col-lg-12">
        <div class="card-header py-3">
          <h6 class="m-0 fw-bold fs-4">ACHIEVEMENT LIST</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered dataTable info-achievement" width="100%" cellspacing="0">
              <thead class="text-light">
                  <tr>
                      <th>Picture</th>
                      <th>Libel</th>
                      <th>Description</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody id="tbodyAchievement"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade modal-gallery" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title m-0 font-weight-bold" id="modalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" class="php-form text-center" id='gallery'>
            <center class="mb-2">
                <img id="picture" src='' class="img-fluid mb-3">
                <div class="cam"></div>
                <label for="imgInp" class="btn-img">Select picture</label>             
                <input type="file" name="picture" accept=".png, .jpg, .jpeg, .gif, .ico" id="imgInp">
            </center>

            <div class="input-box">
              <textarea type="text" name="description" required placeholder="Description"></textarea>
            </div>

            <input type="hidden" name="id">

            <button class="loading" type="submit">Add</button>
          </form>  
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade modal-achievement" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title m-0 font-weight-bold" id="modalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" class="php-form text-center" id='achievementForm'>
            <center class="mb-2">
                <img id="picture2" src='' class="img-fluid mb-3">
                <div class="cam"></div>
                <label for="imgInp2" class="btn-img">Select picture</label>             
                <input type="file" name="picture" accept=".png, .jpg, .jpeg, .gif, .ico" id="imgInp2">
            </center>

            <div class="input-box">
              <input type="text" name="libel" required placeholder="Libel" class="input">
            </div>

            <div class="input-box">
              <textarea type="text" name="description" required placeholder="Description"></textarea>
            </div>

            <input type="hidden" name="id">

            <button class="loading" type="submit">Add</button>
          </form>  
        </div>
      </div>
    </div>
  </div>
</body>
</html>