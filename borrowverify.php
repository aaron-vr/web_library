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

      $language = 0;
      if(isset($_POST['language']))
        $language = trim($_POST['language']);

      $item_id = 0;
      if(isset($_POST['item_id']))
        $item_id = trim($_POST['item_id']);

      $password = '';
      if(isset($_POST['password']))
        $password = trim($_POST['password']);

      $email = '';
      if(isset($_POST['email']))
        $email = trim($_POST['email']);
      }
    else
      echo 'Database error! Consult the DBA!<br>';
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

        <!-- USER NOTIFICAITON -->
<?php

          echo '<p style="text-align:center;margin-top:3em;">';
          /*FIRST: check login credentials*/
          if (count($database->Login($email, $password)) == 1) {
            /*SECOND: check if the qty of the selected book is still >1 in case somebody borrowed the last copy of the book in the meantime
            (use CheckQty() sizeof == 1)*/
            if (count($database->CheckQty($item_id, $language)) == 1) {
              /*THIRD: check if the user has any overdue books, if that's the case, they can't borrow a new one
              (use OverdueCheck() )*/
              if ($database->OverdueCheck($email)[0]['COUNT'] == 0) {
                /*FOURTH: check if the user's already got this book, if that's the case, they can't borrow the same book again; note: if the language differs, i.e. it's a translation, it's no longer the same book in which case it can be borrowed by the said user
                (user AlreadyHasBookCheck() )*/
                if ($database->AlreadyHasBookCheck($email, $item_id, $language)[0]['COUNT'] == 0) {
                  /*only if all four of the conditions are fulfilled can the user take the book from the library via QtyDecrement() and borrow it via Borrow()*/
                  if ($database->Borrow($email, $language, $item_id) && $database->QtyDecrement($item_id, $language) == 1) {
                    $itemlocation = $database->ItemLocation($item_id, $language);
                    echo 'Successfully borrowed book! Come pick it up at the ' .  $itemlocation[0]['LIB_NAME'] . ' library, in section: ' . $itemlocation[0]['SECTION_NAME'] . ', ' . $itemlocation[0]['SECTION_ID'] . '. Full address:<br>' . $itemlocation[0]['LIB_STREET'] . '<br>' . $itemlocation[0]['LIB_POSTCODE'];
                    echo '<br>' . $itemlocation[0]['LIB_CITY'] . '<br>' . $itemlocation[0]['LIB_COUNTRY'];
                  }
                  else
                    echo 'Adding the book to your borrowed books was unsuccessful!Please go back to the previous page and try again! If, after trying again, you are told that you\'ve already got this book, notify the DBA';
                }
                else
                  echo 'You\'ve already got this book, you can\'t borrow the same book twice!';
                }
              else
                echo 'You\'ve got some overdue books, return them first! (by going to <a href="mybookslogin.php">My Books</a>)';
            }
            else
              echo 'This book is no longer available, we apologise! Someone may have reserved it in the meantime!';
          }
          else
            echo 'Your login credentials are invalid, go back to the previous page and try again or if you don\'t have an account yet, register <a href="register.php">Here</a>';


          echo '<input type="text" name="item_id" hidden value="' . $item_id . '"><input type="text" name="language" hidden value ="' . $language . '">';
      echo '</p>';
?>
      </main>
      <footer><br><br><br><br></footer>
    </body>
  </html>
