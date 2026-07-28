<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ERP Entreprise</title>
</head>
<body>


  <button class="btn-agency btn btn-light mb-2">Add agency</button>
  <div class="row"> 
    <div class="card shadow mb-4 col-lg-12">
      <div class="card-header py-3">
        <h6 class="m-0 fw-bold fs-4">AGENCY LIST</h6>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered dataTable info-agency" width="100%" cellspacing="0">
            <thead class="text-light">
                <tr>
                    <th>country</th>
                    <th>city</th>
                    <th>address</th>
                    <th>phone</th>
                    <th>email</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tbodyBranch"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>


<div class="modal fade modal-agency" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title m-0 font-weight-bold" id="modalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" role="form" class="php-form text-center" id='agency'>
            <div class="input-box">
              <input type="text" name="login" required placeholder="Login" class="input">
            </div>

            <div class="input-box">
              <input type="text" name="country" required placeholder="Country" class="input">
            </div>

            <div class="input-box">
              <input type="text" name="city" required placeholder="City" class="input">
            </div>

            <div class="input-box">
              <input type="text" name="address" required placeholder="Address" class="input">
            </div>

            <div class="input-box">
              <input type="text" name="phone" required placeholder="Phone" class="input">
            </div>

            <div class="input-box">
              <input type="text" name="email" required placeholder="Email" class="input">
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