<?php
  session_start();

  //Imports 
  require_once '../models/User.php';
  include '../models/ErrorHandler.php';

  //Check for a post request

  if (isset($_POST['login'])) {

    //Check for a non empty input string
    if (isset($_POST['username']) && isset($_POST['psw'])) {

      //create new user object
      $user = new User();

      $_SESSION['username'] = $_POST['username'];

      //find user in database by their username

      if($user->find_user($_POST['username'])){


        //authenticate user
        if($user->authenticate($_POST['psw'])){
          $_SESSION['user'] = $user;
          $_SESSION['success'] = "You have successfully logged on!";
        }else{
          errorHandler("Incorrect password!");
        }

      }else{
        errorHandler("Incorrect username!");
      }

    }

    header("Location: Login.php");
    return;
  }



?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    body {font-family: Arial, Helvetica, sans-serif;}
    form {border: 3px solid #f1f1f1;}

    input[type=text], input[type=password] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
    }

    button {
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
    }

    button:hover {
    opacity: 0.8;
    }

    .cancelbtn {
    width: auto;
    padding: 10px 18px;
    background-color: #f44336;
    }

    .imgcontainer {
    text-align: center;
    margin: 24px 0 12px 0;
    }

    img.avatar {
    width: 40%;
    border-radius: 50%;
    }

    .container {
    padding: 16px;
    }

    span.psw {
    float: right;
    padding-top: 16px;
    }

    /* Change styles for span and cancel button on extra small screens */
    @media screen and (max-width: 300px) {
    span.psw {
        display: block;
        float: none;
    }
    .cancelbtn {
        width: 100%;
    }
    }
</style>
</head>
<body>

<h2>Login To AI Foods</h2>

<form method="post">
  

  <div class="container">

    <!--CHECK THIS EMAIL IS IN USER TABLE-->
    <label for="username"><b>Username</b></label>
    <input type="text" placeholder="Username" name="username" value="<?php if(isset($_SESSION['username'])){echo $_SESSION['username'];} ?>" required>

    <!--CHECK THIS PASSWORD IS IN USER TABLE-->
    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>
      
    <!--WHEN PRESSED CHECK USER EMAIL AND PASSWORD AGAINST USER TABLE TO FIND MATCH, LOGIN IF MATCH FOUND-->
    <button type="submit" name="login">Login</button>
  </div>

  <div class="container" style="background-color:#f1f1f1">
    <!--CANCEL LOGIN, MAYBE CLEAR SCREEN-->
    <button type="button" class="cancelbtn">Cancel</button>
    <!--COULD TRY IMPLEMENT THIS ELEMENT BUT NOT NECESSARY AT THIS MOMENT-->
    <span class="psw">Forgot <a href="#">password?</a></span>
  </div>
</form>

</body>
</html>