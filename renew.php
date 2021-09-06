<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="bootstrap.css">
    <title>Library Database</title>
  </head>

  <?php
    //include DatabaseHelper.php file
    require_once('databasehelper.php');
    //instantiate DatabaseHelper class
    $database = new DatabaseHelper();

    //Grab variables from POST request
      $email = '';
      if(isset($_POST['email']))
        $email = trim($_POST['email']);

      $item_id = 0;
      if(isset($_POST['item_id']))
        $item_id = trim($_POST['item_id']);

      $language = 0;
      if(isset($_POST['lang_id']))
        $language = trim($_POST['lang_id']);
    ?>

    <body>

      <div class='container-fluid'>
          <!-- NAVBAR -->
        <header>
          <div class='row justify-content-center align-items-center'>
            <div class='col-md-6 col-sm-0'>
            </div>
            <div class='col-md-2 col-sm-3'>
              <a href='index.php'>
                Browse
              </a>
            </div>
            <div class='col-md-2 col-sm-4'>
              <a href='mybookslogin.php'>
                MyBooks
              </a>
            </div>
            <div class='col-md-2 col-sm-3'>
              <a href='register.php'>
                Register
              </a>
            </div>
          </div>

        </header>

      <main>
        <!-- PAGE TITLE -->
        <div class = 'row justify-content-center title'>
          <div class='col-12'>
            <h1>[ Library Database ]</h1>
          </div>
        </div>

        <div class = 'row justify-content-center line'>
          <div class='col-8'>
            <hr>
          </div>
        </div>
      <!-- USER NOTIFICATION -->
  <?php
        if ($database->Renew($item_id, $language, $email) == 1)
          echo '<p style="text-align:center;max-width:50%;margin-left:auto;margin-right:auto;">The book you selected has been renewed successfully! Return to the <a href="index.php">homepage</</a>.</p><br><br>';
        else
          echo 'The renewal of your book has been unsuccessful! Consult the DBA.<br>Back to the <a href="index.php">homepage</a>.</p><br><br>';
  ?>
      </main>
      <footer><br><br><br><br></footer>
    </body>
  </html>
