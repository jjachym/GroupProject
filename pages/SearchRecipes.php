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

      <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
          /* Style the buttons that are used to open and close the accordion panel */
          .accordion {
            background-color: #eee;
            color: #444;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            text-align: left;
            border: none;
            outline: none;
            transition: 0.4s;
          }

          /* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
          .active, .accordion:hover {
            background-color: #ccc;
          }

          /* Style the accordion panel. Note: hidden by default */
          .panel {
            padding: 0 18px;
            background-color: white;
            display: none;
            overflow: hidden;
          }
        </style>
      </head>

    <body>
        
        <iframe src="Master.html" width = "100%" height = "72" style="border:none;"></iframe>
          
        <form method="post">
          
        <button class="accordion" type="button"><h1>Search Our Recipes</h1></button>

        <div class="panel">
        <p>Please fill out the relevant fields to search for a recipe, using either name, or ingredients, or both!</p>
        <hr>
            <p>Enter the name of a recipe to search</p>
            <!--THIS IS WHERE USER WILL ENTER RECIPE NAME-->
            <label for="sByName"><b>Search Recipe By Name:</b></label>
            <div><input type="shortText"></div>
        </hr>

        <hr>
            <p>Enter a list of ingredients, separated by a comma </p>
            <!--THIS IS WHERE USER ENTERS INGREDIENTS SEPARATED BY A COMMA. MANIPULATE THE USE OF COMMA TO SEARCH IN DATABASE-->
            <label for="sByIngredient"><b>Search Recipe By Ingredients:</b></label>
            <div><input type="longText"></div>
        </hr>

        <hr>
            <div class="clearfix">
                <!--SEARCH. CHECK IF FIELDS HAVE VALUES AND SEARCH IN RELEVANT DATABASE TABLES-->
                <button type="button" class="searchButton">Search Recipe</button>
            </div>
        </hr>
        </div>

        <script>
          var acc = document.getElementsByClassName("accordion");
          var i;

          for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
              /* Toggle between adding and removing the "active" class,
              to highlight the button that controls the panel */
              this.classList.toggle("active");

              /* Toggle between hiding and showing the active panel */
              var panel = this.nextElementSibling;
              if (panel.style.display === "block") {
                panel.style.display = "none";
              } else {
                panel.style.display = "block";
              }
            });
          }
        </script>
  
          <!-- NO OUTPUT AT THE MOMENT. GET IT WORKING BACK END FIRST THEN WE CAN DECIDE ON OUTPUT-->
          <!-- Output is held in $_SESSION['recipes'] -->
        </form>
        <br>
        
    </body>

</html>