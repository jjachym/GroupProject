<?php
  require_once '../models/Recipe.php';
  include '../models/ErrorHandler.php';

  session_start();
  
  //check if method is post
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    foreach ($_REQUEST as $key => $value) {
      ${$key} = addslashes($value);
      $_SESSION[$key] = addslashes($value);
    }


    if ($searchByName != null && $searchByIngr != null) {
      $options = array('recipeName' => $searchByName, 'ingredients' => $searchByIngr);

      $recipes = Recipe::fetchAll($options);
      $_SESSION['recipes'] = $recipes;

    }elseif ($searchByName != null) {

      $options = array('recipeName' => $searchByName);

      $recipes = Recipe::fetchAll($options);
      $_SESSION['recipes'] = $recipes;

    }elseif ($searchByIngr != null) {

      $options = array('ingredients' => $searchByIngr);

      $recipes = Recipe::fetchAll($options);
      $_SESSION['recipes'] = $recipes;

    }else{

      errorHandler("Please fill out at least one field!");     

    }

    header("Location: SearchRecipes.php");
    return;
  }

?>


<!DOCTYPE html>

<html>

    <style>
        body {font-family: Arial, Helvetica, sans-serif;}
        * {box-sizing: border-box}
      
        input[type=shortText] {
          width: 80%;
          padding: 15px;
          margin: 5px 0 22px 0;
          display: inline-block;
          border: none;
          background: #f1f1f1;
        }

        input[type=longText] {
          width: 80%;
          padding: 15px;
          margin: 5px 0 22px 0;
          display: inline-block;
          border: none;
          background: #f1f1f1;
          
        }
      
        input[type=shortText]:focus, input[type=longText]:focus {
          background-color: #ddd;
          outline: none;
        }
      
        hr {
          border: 1px solid #f1f1f1;
          margin-bottom: 25px;
        }
      
        /* Set a style for button */
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
      
      
        /* Float button and add an equal width */
        .searchButton {
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
      
        /* Change styles for button on extra small screens */
        @media screen and (max-width: 300px) {
          .searchButton {
            width: 100%;
          }
        }
      </style>

    <body>
        
        <iframe src="Master.html" width = "100%" height = "72" style="border:none;"></iframe>
          
        <form method="post">
          
          <h1>Search Our Recipes</h1>
          <p>Please fill out the relevant fields to search for a recipe, using either name, or ingredients, or both!</p>
  
          <hr>
              <p>Enter the name of a recipe to search</p>
              <!--THIS IS WHERE USER WILL ENTER RECIPE NAME-->
              <label for="sByName"><b>Search Recipe By Name:</b></label>
              <div><input type="shortText" name="searchByName" value="<?php if(isset($_SESSION['searchByName'])){echo $_SESSION['searchByName'];} ?>"></div>
          </hr>
          
          <hr>
              <p>Enter a list of ingredients, separated by a comma </p>
              <!--THIS IS WHERE USER ENTERS INGREDIENTS SEPARATED BY A COMMA. MANIPULATE THE USE OF COMMA TO SEARCH IN DATABASE-->
              <label for="sByIngredient"><b>Search Recipe By Ingredients:</b></label>
              <div><input type="longText" name="searchByIngr" value="<?php if(isset($_SESSION['searchByIngr'])){echo $_SESSION['searchByIngr'];} ?>"></div>
          </hr>
  
          <hr>
              <div class="clearfix">
                  <!--SEARCH. CHECK IF FIELDS HAVE VALUES AND SEARCH IN RELEVANT DATABASE TABLES-->
                  <button type="submit" class="searchButton" name="sumbit" value="submit">Search Recipe</button>
              </div>
          </hr>
  
          <!-- NO OUTPUT AT THE MOMENT. GET IT WORKING BACK END FIRST THEN WE CAN DECIDE ON OUTPUT-->
          <!-- Output is held in $_SESSION['recipes'] -->
        </form>
        <br>
        
    </body>

</html>