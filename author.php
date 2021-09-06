<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="bootstrap.css">
    <meta charset="utf-8">
    <title>Book Author</title>
  </head>

  <?php
    require_once('databasehelper.php');
    $database = new DatabaseHelper();

    $author_id = 0;
    if(isset($_POST['author_id'])){
      $author_id = trim($_POST['author_id']);
    }
  ?>

  <body>
<!-- NAVBAR -->
    <div class='container-fluid'>

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
      <!-- AUTHOR SECTION -->


            <div class="author">
            <?php

            $result = $database->Author($author_id);
            $result2 = $database->Influencer($author_id);
            $result3 = $database->Influenced($author_id);

            echo '<div class="row justify-content-center"><div class="col-md-8 "><div id="authorname">' .
            $result[0]['AUTHOR_NAME'] . '</div></div></div><div class="row justify-content-center"><div class="col-md-8 "><img src = "Media/image.png"/></div></div>
            <div class="row justify-content-center"><div class="col-md-8 ">Birthdate: ' .
            $result[0]['AUTHOR_BIRTH'] .
            '<br>Influenced by: ';
            foreach($result3 as $key => $row) {
              echo '<form method="post" action="author.php"><input hidden type="text" name="author_id" value="' .
              $row['INFLUENCER'] .
              '"><input type="submit" name="author" value="' .
              $row['AUTHOR_NAME'] .
               '"></form>';
              if ($key < sizeof($result3) - 1)
                echo ', ';
            }
            unset($row);
            echo '<br>Influenced: ';
            foreach($result2 as $key => $row) {
              echo '<form method="post" action="author.php"><input hidden type="text" name="author_id" value="' .
              $row['INFLUENCED'] .
              '"><input type="submit" name="author" value="' .
              $row['AUTHOR_NAME'] .
               '"></form>';
              if ($key < sizeof($result2) - 1)
                echo ', ';
            }
            unset($row);
            echo '<br>Books: ';
            foreach($result as $key => $row) {
              echo '<form method="post" action="item.php"><input hidden type="text" name="item_id" value="' .
              $row['ITEM_ID'] . '"><input type="submit" name="author" value="' . $row['ITEM_TITLE'] . '"></form>';
              if ($key < sizeof($result) - 1)
                echo ', ';
            }
            unset($row);
            echo '<br></div></div>';
            ?>
          </div>


      </main>
    <footer><br><br><br><br></footer>
  </div>


  </body>
</html
