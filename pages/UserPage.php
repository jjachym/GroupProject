<!DOCTYPE html>

<head>
  <iframe src="Master.html" width = "100%" height = "72" style="border:none;"></iframe>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
<?php

  function buildPage() {
    //Deals with the first line, with the edit button being present if the logged user is viewing their own page. 
    echo "<div class='container'>";
    echo "<div class='row'>";
    echo "<div class='col-lg-11'></div>";
    echo "<div class='col-lg-1'>";
    if ($user == $userProf) {
      echo "<a class='btn btn-primary' href='editUserPage.php' role='button'>Link</a>";
    }
    echo "</div>";
    echo "</div>";
    
    //Deals with the About section (username, real name, desc, etc.)
    echo "<div class='container'>";
    echo "<div class='row'>";
    echo "<div class='col-lg-1'></div>";
    echo "<div class='panel panel-default'>";
    echo "<div class='panel-head'>";
    echo "<h3>  About $userProf->username: </h3>";
    echo "</div>";
    echo "<div class='panel-body'>";
    echo "<p>";
    echo "Name: $userProf->firstName $userProf->lastName.";
    echo"</p>";
    echo "</div>";
    echo "<div class='col-lg-1'></div>";
    echo "</div>";
    
    //Deals with the part of the page responsible for showing the recipe reviews a user has made.
    echo "<div class='container'>";
    echo "<div class='row'>";
    echo "<div class='col-lg-1'></div>";
    echo "<div class='panel panel-default'>";
    echo "<div class='panel-head'>"; 
    echo "<h3> Reviews by $user: </h3>";
    echo "</div>";
    echo "<div class='panel-body'>";
    echo "<p>";
    echo "</p>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
  }
  
  //Displays the corresponding page if the user passed to the page does not exist.
  function displayMissingProf() {
    echo "<div class='container'>";
    echo "<div class='panel panel-default'>";
    echo "<div class='panel-head'>";
    echo "<h3>User not found.</h3>";
    echo "</div>";
    echo "<div class='panel-body'>";
    echo "<p> The user $user could not be found.</p>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
  }
  
  //Displays the corresponding page if there is no user passed to the page.
  function displayError() {
    echo "<div class='container'>";
    echo "<div class='panel panel-default'>";
    echo "<div class='panel-head'>";
    echo "<h3>User not found.</h3>";
    echo "</div>";
    
    echo "<div class='panel-body'>";
    echo "<p> No user was passed.</p>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
  }

  require_once '../models/User.php';
  include '../models/ErrorHandler.php';
  
  //Checks to see if the user's logged in.
  if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
  }
  
  //Displays different versions of the page depending on the set variables.
  if (isset($_POST['user'])) {
    $userProf = new User();
    $e = $userProf->find_user();
    if (!$e) {
      buildPage();
    } else {
      displayMissingProf();
    }
  } else {
    displayError();
  }
  
?>

</body>
</html>