<?php
  session_start();
  require_once '../models/User.php';

  //flash error messages

  if (isset($_SESSION['errors'])){
    foreach ($_SESSION['errors'] as $error) {
        echo "<div class='error'> <p>",$error,"</p></div>";
    }
  }

  if (isset($_SESSION['success']) && $_SESSION['success'] != ""){
    echo "<div class='success'> <p>",$_SESSION['success'],"</p></div>";
    $_SESSION['success'] = "";
  }

  //unset all errors

  $_SESSION['errors'] = array();

  //error handler
  function errorHandler($message){
    $_SESSION['errors'][] = $message;
  }

  function checkFirstName($Fname){
    if(strlen($Fname) > 15){
      errorHandler("Sorry your first name is too long for our system. Please enter a shorter alternative");
      return false;
    }
    else{
      $_SESSION['fname'] = $Fname;
      return True;
    }
  }

  function checkLastName($Lname){
    if(strlen($Lname) > 30){
      errorHandler("Sorry your last name is too long for our system. Please enter a shorter alternative");
      return false;
    }
    else{
      $_SESSION['lname'] = $Lname;
      return true;
    }
  }

  function checkUsername($username){
    if(strlen($username) > 20){
      errorHandler("Sorry your username name is too long for our system. Please enter a shorter alternative");
      return false;
    }
    else{
      $_SESSION['username'] = $username;
      return true;
    }
  }


  //Checks the passwords match
  function checkPassword($password, $passwordRepeat){
    if (strlen($password) > 20){
      errorHandler("Your password is too long for our system, please choose another");
      return false;
    }
    else if($password != $passwordRepeat){
      errorHandler("Passwords do not match!");
      return false;
    }else{
      return true;
    }
  }

  if(isset($_POST['submit'])){
    foreach ($_POST as $key => $value) {
      ${$key} = $value;
    }

    if($username != "" && $Fname != "" && $Lname != "" && $psw != "" && $pswRepeat != ""){
      if(checkFirstName($Fname) && checkLastName($Lname) && checkUsername($username) && checkPassword($psw,$pswRepeat)){

        $user = new User($username,$Fname,$Lname);
  
        $hash = password_hash($psw,PASSWORD_DEFAULT);

        if($user->save($hash)){
          $_SESSION['success'] = "You have succesfully signed up";
          $_SESSION['user'] = $user;
        }else{
          errorHandler("Database error please try again later");
        }        
      }
    
    }else{
      errorHandler("Please fill out all values");
    }

    header("Location: SignUp.php");
  }

?>

<!DOCTYPE html>
<html>
  <style>
    body {font-family: Arial, Helvetica, sans-serif;}
    * {box-sizing: border-box}

    /* Full-width input fields */
    input[type=text], input[type=password] {
      width: 100%;
      padding: 15px;
      margin: 5px 0 22px 0;
      display: inline-block;
      border: none;
      background: #f1f1f1;
    }

    input[type=text]:focus, input[type=password]:focus {
      background-color: #ddd;
      outline: none;
    }

    hr {
      border: 1px solid #f1f1f1;
      margin-bottom: 25px;
    }

    /* Set a style for all buttons */
    button {
      background-color: #4CAF50;
      color: white;
      padding: 14px 20px;
      margin: 8px 0;
      border: none;
      cursor: pointer;
      width: 100%;
      opacity: 0.9;
    }

    button:hover {
      opacity:1;
    }

    /* Extra styles for the cancel button */
    .cancelbtn {
      padding: 14px 20px;
      background-color: #f44336;
    }

    /* Float cancel and signup buttons and add an equal width */
    .cancelbtn, .signupbtn {
      float: left;
      width: 50%;
    }

    /* Add padding to container elements */
    .container {
      padding: 16px;
    }

    /* Clear floats */
    .clearfix::after {
      content: "";
      clear: both;
      display: table;
    }

    /* Change styles for cancel button and signup button on extra small screens */
    @media screen and (max-width: 300px) {
      .cancelbtn, .signupbtn {
        width: 100%;
      }
    }

    .error{
        width: 80%;
        margin: auto;
        padding: 10px;
        background: red;
        color: white;
        font-family: Helvetica;
    }

    .success{
        width: 80%;
        margin: auto;
        padding: 10px;
        background: green;
        color: white;
        font-family: Helvetica;
    }
  </style>
<body>
  <form method= "post" style="border:1px solid #ccc">
    <div class="container">
      <h1>Sign Up to AI Foods</h1>
      <p>Please fill in this form to create an account.</p>
      <hr>

      <!--ADD THIS EMAIL TO USER TABLE-->
      <label for="username"><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="username" value="<?php if(isset($_SESSION['username'])){echo $_SESSION['username'];} ?>" required>
      
      
      <label for="Fname"><b>First Name</b></label>
      <input type="text" placeholder="Enter First Name" name="Fname" value="<?php if(isset($_SESSION['fname'])){echo $_SESSION['fname'];} ?>" required>
      
      <label for="Lname"><b>Last Name</b></label>
      <input type="text" placeholder="Enter Last Name" name="Lname" value="<?php if(isset($_SESSION['lname'])){echo $_SESSION['lname'];} ?>" required>

      <!--ADD THIS PASSWORD TO USER TABLE-->
      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="psw" required>

      <!--ADD THIS PASSWORD TO USER TABLE-->
      <label for="psw-repeat"><b>Re-enter Password</b></label>
      <input type="password" placeholder="Re-enter Password" name="pswRepeat" required>
      
      <div class="clearfix">
        <!--CANCEL SIGNUP, MAYBE CLEAR SCREEN-->
        <button type="button" class="cancelbtn">Cancel</button>
        <!--ADD EMAIL AND PASSWORD TO RELEVANT TABLES IF PASSWORDS MATCH AND EMAIL IS IN EMAIL FORMAT-->
        <button type="submit" value="click" name="submit">Sign Up</button>
      </div>
    </div>

  </form>
</body>
</html>