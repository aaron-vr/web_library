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

          <p style="text-align:center;max-width:50%;margin-left:auto;margin-right:auto;">
        <!-- USER NOTIFICATION -->
<?php
        if (count($database->MyBooks($email)) == 0) {
          $database->DeleteAccount($email);
          if (count($database->ExistingUser($email)) == 0)
            echo 'Successfully deleted account!';
          else
            echo 'Account deletion unsuccessful. Contact the DBA.<br><br>';
          echo 'Return to the <a href="index.php">homepage</a>.';
        }
        else 
          echo 'You\'ve still got borrowed books, go back and return them first before terminating your account.<br>';
?>
        </p>
      </main>
      <footer><br><br><br><br></footer>
    </body>
  </html>
