<?php 

  
  require_once '../models/User.php';
  include '../models/ErrorHandler.php';
  include 'Master.php';

  session_start();

?>

<!DOCTYPE html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <?php
    if (!isset($_SESSION['user'])) {
      header("Location: LogIn.php");
    }
  ?>
</head>

<body>
<?php

  function buildPage() {
    if ($_SESSION['user'] == $userProf) {
      $ownProfile = true;
    }
    
    //Deals with the first line, with the edit button being present if the logged user is viewing their own page. 
    //Also deals with the Admin link, to approve/deny recipe requests
    echo "<div class='container'>";
    echo "<div class='row'>";
    echo "<div class='col-lg-8'></div>";
    
    if ($ownProfile) {
      echo "<a class='btn btn-primary' href='editUserPage.php' role='button'>Edit Details</a>";
    }
    
    echo "<div class='col-lg-1'>";
    
    if ($ownProfile && $_SESSION['user']->admin) {
      echo "<a class='btn btn-primary' href='Admin.php' role='button'>Manage Recipe Suggestions</a>";
    } else {
      echo "<div class='col-lg-1'></div>";
    }

    echo "</div>";
    echo "</div>";
    
    //Deals with the About section (username, real name, desc, etc.)
    /*
    echo "<div class='container'>";
    echo "<div class='row'>";
    echo "<div class='col-lg-1'></div>";
    echo "<div class='panel panel-default'>";
    echo "<div class='panel-head'>";
    echo "<h3>  About User ".$_SESSION['user']->username.": </h3>";
    echo "</div>";
    echo "<div class='panel-body'>";
    echo "<p>";
    echo "Name:".$_SESSION['user']->firstname." ".$_SESSION['user']->lastname;
    echo"</p>";
    echo "<p>";
    echo "Email:".$_SESSION['user']->email."";
    echo "<p>";
    echo "Admin? ".$_SESSION['user']->admin;
    echo "</p>";
    echo "</div>";
    echo "<div class='col-lg-1'></div>";
    echo "</div>";
    */
    
    //Deals with the part of the page responsible for showing the recipe reviews a user has made.
    echo "<div class='container'>";
      echo "<div class='row'>";
      echo "<div class='col-lg-1'></div>";
      echo "<div class='panel panel-default'>";
      echo "<div class='panel-head'>"; 
      echo "<h3> Reviews by :".$_SESSION['user']->username." </h3>";
      echo "</div>";
      echo "<div class='panel-body'>";
      try {
          foreach($_SESSION['user']->get_user_ratings() as $rating) {
            echo "<div class='panel panel-default'>";
            echo "<div class='panel-header'>".$rating[0]."</div>";
            echo "<div class='panel-body'>";
            echo "<p> Rated ";
            for ($i = 0; $i < $rating[1]; $i++) {
              echo "<span class='glyphicon glyphicon-star'>";
            }
            echo "</p>"  ;                                                
            echo "</div>";                              
            echo "</div>";
          }
      } catch (PDOException $e) {
        
      }
      echo "</div>";
      echo "</div>";
      echo "</div>";
  
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
  
  //Checks to see if the user's logged in.
  if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
  }
  
  //Displays different versions of the page depending on the set variables.
  buildPage($_SESSION["user"]);
  
?>

</body>
</html>
