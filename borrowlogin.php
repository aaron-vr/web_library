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
      $language = 0;
      if(isset($_POST['language']))
        $language = trim($_POST['language']);

      $item_id = 0;
      if(isset($_POST['item_id']))
        $item_id = trim($_POST['item_id']);
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
        <!-- REGISTER FORM -->
        <form id = "registerform" method = "post" action = "borrowverify.php">
<?php
          echo '<input type="text" name="item_id" hidden value="' . $item_id . '"><input type="text" name="language" hidden value ="' . $language . '">';
?>
          <div class='row justify-content-center'>
            <div class='col-md-1 col-sm-12 text-center'>
              <label for = "emaillabel">Email: </label>
            </div>
            <div class='col-md-5 col-sm-12 text-center'>
              <input type="email" id = "email" name = "email">
            </div>
          </div>

          <div class='row justify-content-center'>
            <div class='col-md-1 col-sm-12 text-center'>
              <label for = "passlabel">Password<br>(no spaces!): </label>
            </div>
            <div class='col-md-5 col-sm-12 text-center'>
              <input type="password" id = "password" name = "password">
            </div>
          </div>

          <div class='row justify-content-center'>
            <div class='col-md-4 col-sm-12 text-center'>
              <input type="submit" name="submit" value="Login and Borrow Book">
            </div>
          </div>

        </form>
      </main>
      <footer><br><br><br><br></footer>
    </body>
  </html>
