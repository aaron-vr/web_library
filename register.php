<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="bootstrap.css">
    <title>Library Database</title>
  </head>

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
      <!-- REGISTER FORM -->
      <form id = "registerform" method = "post" action = "regverify.php">

        <div class='row justify-content-center'>
          <div class='col-md-1 col-sm-12 text-center'>
            <label for = "email">Email: </label>
          </div>
          <div class='col-md-5 col-sm-12 text-center'>
            <input type="email" id = "email" name = "email">
          </div>
        </div>

        <div class='row justify-content-center'>
          <div class='col-md-1 col-sm-12 text-center'>
            <label for = "usernamelabel">Username: </label>
          </div>
          <div class='col-md-5 col-sm-12 text-center'>
            <input type="text" id = "username" name = "username">
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
            <input type="submit" name="submit" value="Register!"/>
          </div>
        </div>

      </form>
    </main>
    <footer><br><br><br><br></footer>
  </body>
</html>
