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

      $password = '';
      if(isset($_POST['password']))
        $password = trim($_POST['password']);
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
        <!-- USER BORROWED BOOKS -->
<?php
        /*verify login credentials*/
        if (count($database->Login($email, $password)) == 1) {
          $mybooks = $database->MyBooks($email);
          /*has the user even borrowed any books?*/
          if (count($mybooks) == 0)
            echo '<p style="text-align:center;max-width:50%;margin-left:auto;margin-right:auto;">You haven\'t got any books yet! Borrow some <a href="index.php">here</a>.</p>';
            /*address the user with their personal username and provide a brief description*/
          else {
            echo '<p style="text-align:center;max-width:50%;margin-left:auto;margin-right:auto;">Welcome to your personal library of books, ' . $mybooks[0]['USERNAME'] . '.<br><br>Below is an overview of all the  books that are currently in your possession.<br><br>Don\'t forget to return
            them before their overdue dates (see below) and remember: a book is initially borrowed for the duration of ' . DatabaseHelper::return_date . ' days, moreover, you can renew it ' . DatabaseHelper::renew_allowed . ' days before its due date at the earliest and when you do so, you are given an additional ' . DatabaseHelper::renew_incr . ' days.<br><br></p>';
            /*begin unloading the books borrowed by this user*/
            foreach ($mybooks as $row) {
              echo '<div class="mybooks"><div class="row justify-content-center"><div class="col-md-6">';
              echo $row['ITEM_TITLE'] . '<br><br>';
              echo 'Language: ' . $row['LANG_NAME'] . '<br><br>';
              /*each book's authors are queried separately using SelectAuthors()*/
              $authors = $database->SelectAuthors($row['ITEM_ID']);
              echo 'Authors: ';
              foreach ($authors as $key => $author) {
                echo $author['AUTHOR_NAME'];
                echo ($key < count($authors) - 1 ? ', ' : '<br>');
              }
              unset($author);
              /*provide the dates for each borrowed book, that is, the borrow date and the overdue date*/
              echo '</div><div class="col-md-2 text-center">';
              echo 'Borrowed on: ' . $row['BORROW_DATE'] . '<br>';
              /*check if this book is overdue; if that's the case, the user will not be given the option to renew it, instead they must return it*/
              if ($row['OVERDUE'] == 1)
                echo '<p style="color:red;font-weight:bold;">This book is overdue, return it ASAP!<br><br></p>';
              else {
                /*otherwise, the user is told the due date when they have to return this book*/
                echo 'Return by: ' . $row['OVERDUE_DATE'] . '<br><br>';
                /*in order to prevent cumulative renewals the user is given a certain time window during which they can renew their book i.e. a number of days before the book's planned return date; check DatabaseHelper::renew_allowed in databasehelper.php*/
                if ($row['RENEW'] == 1) {
                  echo '<span class="renew"><form method="post" action="renew.php"><input hidden type="text" name="item_id" value="' .                 $row['ITEM_ID'] . '">';
                  echo '<input hidden type="text" name="email" value="' .
                  $email . '">';
                  echo '<input hidden type="text" name="lang_id" value="' .
                  $row['LANG_ID'] . '">' . '<input type="submit" name="renew" value="Renew"></form></span><br>';
                }
              }
              echo '<span class="return"><form method="post" action="return.php"><input hidden type="text" name="item_id" value="' .
              $row['ITEM_ID'] . '">';
              echo '<input hidden type="text" name="email" value="' .
              $email . '">';
              echo '<input hidden type="text" name="lang_id" value="' .
              $row['LANG_ID'] . '">' . '<input type="submit" name="return" value="Return"></form></span>';
              echo '</div></div></div>';
            }
            unset($row);
          }
          echo '<br><br><form style="float:right;margin-right:20%;"id="delete" action="deleteaccount.php" method="post"><input hidden type="text" name="email" value="'. $email . '"><input type="submit" name="deletebutton" value="Delete Account"></form>';
        }
        else
          echo '<p style="text-align:center;max-width:50%;margin-left:auto;margin-right:auto;">Your login credentials are invalid, go back to the previous page and try again or if you don\'t have an account yet, register <a href="register.php">Here</a></p><br>';
?>

      </main>
      <footer><br><br><br><br></footer>
    </body>
  </html>
