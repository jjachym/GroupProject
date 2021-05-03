<?php
  include 'Master.php';
  include '../models/ErrorHandler.php';

  session_start();

?>

<!DOCTYPE html>
<html>
  
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
  </head>
  
  <body>
    <?php 
    
      function validName($name) {
        if (preg_match("/\A[^a-z']|-{2,}|'{2,}|\z[^a-z']|[^a-z'-]/i",$name) != 0) {
          return true;
        } else {
          return false;
        }
      }
      
      function validEmail($email) {
        if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
          return true;
        } else {
          return false;
        }
      }
      if (!empty($_POST['username'])) {
        $username = $_POST('username');
      }
      
      if (!empty($_POST['firstname'])) {
        $firstname = $_POST('firstname');
      }
      
      if (!empty($_POST['lastname'])) {
        $lastname = $_POST('lastname');
      }
      
      if (!empty($_POST['email'])) {
        $email = $_POST('email');
      }
    
      $submitted = isset($_POST['submitButton']);
      $valid = true;
      
      
      echo "<div class='panel panel-default'>";
      echo "<div class='panel-body'>";
      echo "<form class = 'form-inline' name='editUserForm' action='editUserPage.php'>";
      echo "<div class='form-group'>";
      echo "<label>Username: <input type='text' name='name' value='$username' ></label>";
      
      if ($submitted && $username == '') {
        echo "<div class='alert alert-danger' role='alert'>";
        echo "Please enter a username!";
        echo "</div>";
        $valid = false;
      } elseif ($submitted && !validName($username)) {
        echo "<div class='alert alert-danger' role='alert'>";
        echo "Please enter a valid username!";
        echo "</div>";
        $valid = false;
      } elseif (!$submitted) {
        
      }
      
      echo "</div>";
      echo "<div class='form-group'>";
      echo "<label for='firstname'> First Name:</label>";
      echo "<input type='text' id='firstname' name='firstname' value='$firstname' >";
      
      if ($submitted && $firstname == '') {
        echo "<div class='alert alert-danger' role='alert'>";
        echo "Please enter a first name!";
        echo "</div>";
        $valid = false;
      } elseif ($submitted && !validName($firstname)) {
        echo "<div class='alert alert-danger' role='alert'>";
        echo "Please enter a valid first name!";
        echo "</div>";
        $valid = false;
      }
      
      echo "</div>";
      echo "<div class='form-group'>";
      echo "<label for='lastname'> Last Name:</label>";
      echo "<input type='text' id='lastname' name='lastname' value='$lastname' >";
      
      if ($submitted && $lastname == '') {
        echo "<label>Please enter a </label>";
        $valid = false;
      } elseif ($submitted && !validName($lastname)) {
        echo "<div class='alert alert-danger' role='alert'>";
        echo "Please enter a valid last name!";
        echo "</div>";
        $valid = false;
      }
      
      echo "</div>";
      echo "<div class='form-group'>";
      echo "<label for='email'> Email:</label>";
      echo "<input type='text' id='email' name='email' value='$email' >";
      
      if ($submitted && $email == '') {
        echo "<div class='alert alert-danger' role='alert'>";
        echo "Please enter an Email address!";
        echo "</div>";
        $valid = false;
      } elseif ($submitted && !validEmail($email)) {
        echo "<label>Error: Please enter a valid email </label>";
        $valid = false;
      }
    
      if ($submitted && $valid) {
        $db = new DBHandler();
        $pdo = $db->getInstance();
        
        $saveData = $pdo->prepare("update User set userFirstName =:firstname, userLastName =:lastname, userEmail =:email where userUsername =:username");
        $saveData->bindValue(':firstname',$_POST['firstname']);
        $saveData->bindValue(':lastname',$_POST['lastname']);
        $saveData->bindValue(':email',$_POST['email']);
        $saveData->bindValue(':username',$_POST['username']);
        $saveData->execute();
      }
      
      echo "</div>";
      echo "<div class='form-group'>";
      echo "<input id='submitButton' type='submit' value='Submit'>";
      echo "</div>";
      echo "</form>";
      echo "</div>";
      echo "</div>";
    
    ?>
  </body>
</html>
