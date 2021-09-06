<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="bootstrap.css">
    <title>Library Item</title>
  </head>

  <?php
    require_once('databasehelper.php');
    $database = new DatabaseHelper();

    $item_id = 0;
    if(isset($_POST['item_id'])){
      $item_id = trim($_POST['item_id']);
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

<!-- query results i.e. item data-->
      <div class="itempage">
        <div class="row justify-content-center">
          <div class="col-md-2 col-sm-4 image">
            <img src = "Media/image.png"/>
          </div>
          <div class="col-md-6 col-sm-8 well">
<?php
            /*fetching all the data into three arrays*/
            $itemdata = $database->ItemData($item_id);
            $itemaudio = $database->ItemAudio($item_id);
            $itemgenres = $database->ItemGenres($item_id);
            $itemphysdig = $database->ItemPhysDig($item_id);
            $authors = $database->SelectAuthors($item_id);
            if (count($itemdata)) {
              /*general info about the item*/
              echo '<div class="row">Medium: ' . $itemdata[0]['MEDIUM_NAME'] . '</div>';
              echo '<div class="row">Title: ' . $itemdata[0]['ITEM_TITLE'] . '</div>';
              echo '<div class="row">Edition: ' . $itemdata[0]['ITEM_EDITION'] . '</div>';
              echo '<div class="row">Publisher: ' . $itemdata[0]['ITEM_PUBLISHER'] . '</div>';
              echo '<div class="row">Publication year: ' . $itemdata[0]['ITEM_PUB_YEAR'] . '</div>';
              echo '<div class="row">Pages: ' . $itemdata[0]['PAGE_COUNT'] . '</div>';

              echo '<div class="row authors">Authors: ';

              /*the item's authors, each links to the corresponding author's page*/
              foreach ($authors as $key => $row) {
                echo '<form method="post" action="author.php"><input hidden type="text" name="author_id" value="' .
                $row['AUTHOR_ID'] .
                '"><input type="submit" name="author" value="' .
                $row['AUTHOR_NAME'] .
                 '"></form>';
                if ($key < sizeof($authors) - 1)
                  echo ', ';
              }
              echo '</div>';
              unset($row);

              /*the item's genres, just plain text*/
              echo '<div class="row">Genres: ';
              foreach ($itemgenres as $key => $row)
                echo $row['GENRE_NAME'] . ($key < sizeof($itemgenres) - 1 ? ', ' : '');
              echo '</div>';
              unset($row);

              /*the item as an audiobook; if it exists, it is provided as a download link*/
              if (sizeof($itemaudio)) {
                echo '<div class="row audiobooks">Audiobook versions: ';
                foreach($itemaudio as $key => $row)
                  echo '<a href="Media/sample.wav">' . $row['LANG_NAME'] . ' </a>-(' . $row['NARRATOR'] . ')' . ($key < sizeof($itemdata) - 1 ? ', ' : ' ');
                echo '</div>';
                unset($row);
              }

              /*the item in its physical and/or digital form; digital is simply hyperlinked // to a pdf scan of the item, physical links to the borrowing page*/
              if (sizeof($itemphysdig)) {
                echo '<div class="row scans">PDF Scans: ';
                foreach ($itemphysdig as $row)
                  if ($row['DIG'] == 'Y')
                    echo '<a href="Media/Lorem_ipsum.pdf">' . $row['LANG_NAME'] . ' </a>' . ' | ';
                unset($row);
                echo '</div>';

                if (count($itemphysdig) > 0) {
                  echo '<div class="row borrowform"><form method="post" action="borrowlogin.php"><input type="text" hidden name="item_id" value="' . $itemphysdig[0]['ITEM_ID'] . '"/><label for="languagelabel">Language: </label><select id="language" name="language">';
                  $nonempty = 0;
                  foreach($itemphysdig as $row)
                    if ($row['BOOK_QTY'] > 0) {
                      $nonempty = 1;
                      echo '<option value="' . $row['LANG_ID'] . '">' . $row['LANG_NAME'] . '</option>';
                    }
                  echo '</select>';
                  echo ($nonempty ? '<span id="borrowlink"><input type="submit" value="Borrow Book"></span>' : '<p style="color:red;">This book is already in somebody\'s posession. Come see us at the library to request its return.</p>');
                  echo '</form></div>';
                }
                else
                  echo '<div class="row"><p style="color:red;">This book is currently unavailable, check back soon!</p></div>';
            }
          }
        else
          echo '<p style="text-align:center;max-width:50%;margin-left:auto;margin-right:auto;">This book may have been borrowed by somebody a split second before you clicked on it; press Ctrl + F5 to refresh this page with the most recent data.</p>'


?>
          </div>
        </div>
      </div>
    </main>


    <footer><br><br><br></footer>

  </body>
</html>
