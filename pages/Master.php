<?php    
    //require_once '../models/User.php';
    session_start();

?>

<!DOCTYPE html>

<head>
  <title>AI Foods</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse" style = "background-color: green;">
  <div class="container-full">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#" style = "color: white;">AI Foods</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="index.php" style = "color: white;">Home</a></li>
        <li><a href="SearchRecipes.php" style = "color: white;">Search Recipe</a></li>
        <li><a href="GetRecommendations.php" style = "color: white;">Get Recommendation</a></li>
        <li><a href="AddRecipe.php" style = "color: white;">Add Recipe</a></li>
        <li><a href="UserPage.php" style = "color: white;">User Area</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <?php
          if (!isset($_SESSION['user'])) {
            echo '<li><a href="Login.php" style = "color: white;">Login</a></li><li><a href="SignUp.php" style = "color: white;">Signup</a></li>';
          }else{
            echo '<li><a href="#" style = "color: white;">Hello '.$_SESSION['user']->username.'</a></li><li><a href="#" style = "color: white;">Sign Out</a></li>';
          }

        ?>
      </ul>
    </div>
  </div>
</nav>


</body>
</html>
