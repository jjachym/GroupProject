<?php
session_start();

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
</style>
<body>


<?php

echo'
<form method= "post" action="SignUp.php" style="border:1px solid #ccc">
  <div class="container">
    <h1>Sign Up to AI Foods</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>

    <!--ADD THIS EMAIL TO USER TABLE-->
    <label for="username"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="username" required>
    
    
    <label for="Fname"><b>First Name</b></label>
    <input type="text" placeholder="Enter First Name" name="Fname" required>
    
    <label for="Lname"><b>Last Name</b></label>
    <input type="text" placeholder="Enter Last Name" name="Lname" required>

    <!--ADD THIS PASSWORD TO USER TABLE-->
    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>

    <!--ADD THIS PASSWORD TO USER TABLE-->
    <label for="psw-repeat"><b>Re-enter Password</b></label>
    <input type="password" placeholder="Re-enter Password" name="psw-repeat" required>
    
    <div class="clearfix">
      <!--CANCEL SIGNUP, MAYBE CLEAR SCREEN-->
      <button type="button" class="cancelbtn">Cancel</button>
      <!--ADD EMAIL AND PASSWORD TO RELEVANT TABLES IF PASSWORDS MATCH AND EMAIL IS IN EMAIL FORMAT-->
      <button type="submit" value="click" name="submit">Sign Up</button>
    </div>
  </div>

</form>
';

//connection to database
$db_hostname = "studdb.csc.liv.ac.uk";
$db_database = "sgoperr2";
$db_username = "sgoperr2";
$db_password = "IzzyPippa55";
$db_charset = "utf8mb4";
$dsn = "mysql:host=$db_hostname;dbname=$db_database;charset=$db_charset";
$opt = array(
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
PDO::ATTR_EMULATE_PREPARES => false
);

  //error handler
function errorHandler($message){
  echo"Error: $message <br> <br>";
  return false;
}
  
  
  //TO DO!!!!!!
  //Adds the users details to the database
function addToUsers($pdo,$username, $Fname, $Lname, $password){
  try{
  
  //Starts a transaction
  $pdo->beginTransaction();
  
  $checkUsername=$pdo->prepare("select count(userUsername) as amount from User where userUsername = :username");
  $checkUsername->bindValue(':username', $username, PDO::PARAM_STR);
  $checkUsername->execute();
  
  while($check = $checkUsername->fetch()){
    if($check['amount'] > 0){
      errorHandler("Username is already taken. Please choose another");
    }else{
      $addUser = $pdo->prepare("insert into User (userFirstName, userLastName, userUsername, userPassword) values (:fName, :lName, :uName, :psw)");
      
      $addUser->bindValue(':fName', $Fname, PDO::PARAM_STR);
      $addUser->bindValue(':lName', $Lname, PDO::PARAM_STR);
      $addUser->bindValue(':uName', $username, PDO::PARAM_STR);
      $addUser->bindValue(':psw', $password, PDO::PARAM_STR);
      
      $addUser->execute();
      
      $pdo->commit();
      
      echo'Thank you, your sign up was successful';
      
      session_unset(); session_destroy();
    }
  }
}catch (PDOException $e){
  $pdo->rollBack();
  errorHandler("Sorry something went wrong somewhere. Please try again.");
  session_unset();
  
  
  }
}


function checkFirstName($Fname){
  if(strlen($Fname) > 15){
    errorHandler("Sorry your first name is too long for our system. Please enter a shorter alternative");
  }
  else{
    return True;
  }
}

function checkLastName($Lname){
  if(strlen($Lname) > 30){
    errorHandler("Sorry your last name is too long for our system. Please enter a shorter alternative");
  }
  else{
    return True;
  }
}

function checkUsername($username){
  if(strlen($username) > 20){
    errorHandler("Sorry your username name is too long for our system. Please enter a shorter alternative");
  }
  else{
    return True;
  }
}


  //Checks the passwords match
function checkPassword($password, $passwordRepeat){
  if (strlen($password) > 20){
    errorHandler("Your password is too long for our system, please choose another");
  }
  else if($password != $passwordRepeat){
    errorHandler("Passwords do not match!");
  }else{
    return True;
    }
}

//begins try statement
try {
//attempts to connect to database
$pdo = new PDO($dsn,$db_username,$db_password,$opt);


//Sets all the request variables to the session variables
if (isset($_REQUEST['username']) && $_REQUEST['username'] != "Enter Username"){
  $_SESSION['username'] = $_REQUEST['username'];
  echo"username =",$_SESSION['username'];
}

if (isset($_REQUEST['Fname']) && $_REQUEST['Fname'] != "Enter First Name"){
  $_SESSION['Fname'] = $_REQUEST['Fname'];
}

if (isset($_REQUEST['Lname']) && $_REQUEST['Lname'] != "Enter Last Name"){
  $_SESSION['Lname'] = $_REQUEST['Lname'];
}

if (isset($_REQUEST['psw']) && $_REQUEST['psw'] != "Enter Password"){
  $_SESSION['psw'] = $_REQUEST['psw'];
}

if (isset($_REQUEST['psw-repeat']) && $_REQUEST['psw-repeat'] != "Re-enter Password"){
  $_SESSION['psw-repeat'] = $_REQUEST['psw-repeat'];
}

//Converts each session variable to a variable
foreach ($_SESSION as $key => $value)
  ${key} = $value;


  //Assesses which function to run

  echo"Add Ran";
  if (isset($_SESSION['username']) && isset($_SESSION['Fname']) && isset($_SESSION['Lname']) && isset($_SESSION['psw']) && isset($_SESSION['psw-repeat'])){
    if (checkPassword($_SESSION['psw'], $_SESSION['psw-repeat']) && checkFirstName($_SESSION['Fname']) && checkLastName($_SESSION['Lname']) && checkUsername($_SESSION['username'])) {
      addToUsers($pdo, $_SESSION['username'], $_SESSION['Fname'], $_SESSION['Lname'], $_SESSION['psw']);
  }else{
    unset($_SESSION['psw']);
    unset($_SESSION['psw-repeat']);
    }
  }



//Sets the pdo variable to null
$pdo = NULL;

//Catch statement 
} catch (PDOException $e) {
exit("PDO Error: ".$e->getMessage()."<br>");
}


?>
  </body>
</html>