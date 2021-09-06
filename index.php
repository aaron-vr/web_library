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
    $author_name = '';
    if(isset($_POST['author'])){
        $author_name = trim($_POST['author']);
    }
    $item_title = '';
    if(isset($_POST['title'])){
        $item_title = trim($_POST['title']);
    }
    $medium_id = 0;
    if(isset($_POST['medium'])){
        $medium_id = trim($_POST['medium']);
    }
    $genre_id = 0;
    if(isset($_POST['genre'])){
        $genre_id = trim($_POST['genre']);
    }
    $language = 0;
    if(isset($_POST['language'])){
        $language = trim($_POST['language']);
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

      <!-- WELCOME TEXT -->
      <div class = 'row justify-content-center description'>
        <div class='col-12'>
          <p>
            Welcome to our unified library database!<br><br>
            Browse our catalogue using the search fields below. You can fill in
            one or many of them, or simply none.<br><br>
            Nevertheless, try to be as accurate as possible with your searches but don't bother capitalising each word, that's done for you!<br><br>
            Hint: The fewer fields you fill in, the greater the number of results.
          </p>
        </div>
      </div>

      <div class = 'row justify-content-center line'>
        <div class='col-8'>
          <hr>
        </div>
      </div>

      <!-- SEARCH BAR -->
      <form id = "searchform" method = "post" action = "index.php">
        <div class='row justify-content-center align-items-center'>
          <div class='col-md-1 '>
            <label for = "authorlabel">Author:</label>
          </div>
          <div class='col-md-3 '>
            <input type="text" id = "author" name = "author" placeholder="Adam Smith">
          </div>
          <div class='col-md-1 '>
            <label for = "titlelabel">Title:</label>
          </div>
          <div class='col-md-3 '>
            <input type="text" id = "title" name = "title" placeholder="The Wealth of Nations">
          </div>
        </div>

        <div class='row justify-content-center align-items-center'>
          <div class='col-md-1 '>
            <label for = "mediumlabel">Medium:</label>
          </div>
          <div class='col-md-2 '>
            <select name="medium" id = "medium">
              <option value = "0">Any</option>
              <?php
                $result = $database->MediumDropdown();
                foreach ($result as $row)
                  echo '<option value = "' . $row['MEDIUM_ID'] . '"> ' . $row['MEDIUM_NAME'] . '</option>';
                unset($row);
              ?>
            </select>
          </div>
          <div class='col-md-1 '>
            <label for = "genrelabel">Genre:</label>
          </div>
          <div class='col-md-3 '>
            <select name="genre" id = "genre">
              <option value = "0">Any</option>
              <?php
                $result = $database->GenreDropdown();
                foreach ($result as $row)
                  echo '<option value = "' . $row['GENRE_ID'] . '"> ' . $row['GENRE_NAME'] . '</option>';
                unset($row);
              ?>
            </select>
          </div>

          <div class='col-md-2'>
            <label for = "languagelabel">Language:</label>
          </div>
          <div class='col-md-1'>
            <select name="language" id = "language">
              <option value = "">Any</option>
              <?php
                $result = $database->LanguageDropdown();
                foreach ($result as $row)
                  echo '<option value = "' . $row['LANG_ID'] . '"> ' . $row['LANG_NAME'] . '</option>';
                unset($row);
              ?>
            </select>
          </div>
        </div>
        <div class='row justify-content-center align-items-center'>
          <div class='col-md-5'>
            <input type = "submit" value ="search"/>
          </div>
        </div>
      </form>


      <div class = 'row justify-content-center line'>
        <div class='col-8'>
          <hr>
        </div>
      </div>

      <!-- SEARCH RESULTS -->
      <?php

        $result = $database->SearchQuery($author_name, $item_title, $medium_id, $genre_id, $language);
        if ($result){
          $temp = 0;
          foreach($result as $row) {
            //messy control blocks to check if there's multiple authors for this book and if that's the case, introduce them in a comma separated list rather than new lines - otherwise: introduce new row and give back control to the remaining entries i.e. books from the database
            if ($row['ITEM_ID'] == $temp['ITEM_ID']) {
              if ($row['AUTHOR_ID'] != $temp['AUTHOR_ID'])
                echo ', ' . '<form method="post" action="author.php"><input hidden type="text" id="author_id" name="author_id" value="' .
                $row['AUTHOR_ID'] .
                '"><input type="submit" name="author" value="' .
                $row['AUTHOR_NAME'] .
                 '"></form>';
              $temp = $row;
              continue;
              }
            if ($temp != 0) {
              echo '<br><span class="itemlink"><form method="post" action="item.php"><input hidden type="text" name="item_id" value="' .
              $temp['ITEM_ID'] . '"><input type="submit" name="item" value="Borrow!"></form></span><br>';
              echo '</div></div></div>';
            }

            echo '<div class = "item"><div class="row justify-content-center align-items-center"><div class="col-md-8 col-sm-8">';
            echo 'Title: ' . $row['ITEM_TITLE'] . '<br>';
            echo 'Edition: ' . $row['ITEM_EDITION'] . '<br>';
            echo 'Publication Year: ' . $row['ITEM_PUB_YEAR'] . '<br>';
            echo 'Description: ' . $row['ITEM_DESC'] . '<br>';
            echo 'Authors: <form method="post" action="author.php"><input hidden type="text" name="author_id" value="' .
            $row['AUTHOR_ID'] .
            '"><input type="submit" name="author" value="' .
            $row['AUTHOR_NAME'] .
             '"></form>';
            $temp = $row;
          }
          echo '<br><span class="itemlink"><form method="post" action="item.php"><input hidden type="text" name="item_id" value="' .
          $row['ITEM_ID'] . '"><input type="submit" name="item" value="Borrow!"></form></span><br>';
          echo '</div></div></div>';
          unset($row);
        }
        else{
            echo '<p style="text-align:center;">No Items Found!</p>';
        }
      ?>

    </main>
    <footer><br><br><br><br></footer>
  </div>


  </body>
</html
