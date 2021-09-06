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
    if (isset($_POST['submit'])) {
      /*to be able to notify the user of any empty fields they submitted*/
      $missing_data = array();

      if(empty($_POST['username']))
        $missing_data[] = 'Username';
      else
        $username = trim($_POST['username']);

      if(empty($_POST['password']))
        $missing_data[] = 'Password';
      else
        $password = trim($_POST['password']);

      if(empty($_POST['email']))
        $missing_data[] = 'Email Address';
      else
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
  <!-- USER NOTIFICATION -->


<?php
      echo '<p style="text-align:center;margin-top:3em;margin-left:auto;margin-right:auto;max-width:50%;">';

      /*the data is sent off to the database iff there's no empty fields*/
      if(empty($missing_data)) {
        if (count($database->ExistingUser($email)) != 0)
          echo 'We seem to already have an account registered with that email address. If you don\'t remember making that account yourself, notify the DBA to inquire about a potential email breach';
        else
          echo $database->Register($username, $email, $password) ? 'Account created! Return to <a href="index.php">main page</a>' : 'Account creation unsuccessful! Contact the database administrator!';
        }
      else {
        echo 'Some information is missing: ';
        foreach ($missing_data as $missing)
          echo '<br>' . $missing;
        echo '<br>Go back to the previous page and try again<br>';
      }
    }
    else
      echo 'Database error!<br>';
    echo '</p>';
?>

    </main>
    <footer><br><br><br><br></footer>
  </body>
</html>
