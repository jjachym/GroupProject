<?php

  session_start();
  require_once '../models/User.php';
  require_once '../models/Recipe.php';
  include '../models/ErrorHandler.php';
  
  
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    foreach ($_REQUEST as $key => $value) {
      ${$key} = $value;
      $_SESSION[$key] = $value;
    }
    echo"for each ran";
    if($title != "" && $desc != "" && $ingredients != "" && $instructions != "" && $tags != ""){
      
          
      try{
            
          $db = new DBHandler();
          $pdo = $db->getInstance();
    
          //Starts a transaction
          $pdo->beginTransaction();
          
          //checks if the recipe name exists
          $suggestion=$pdo->prepare("insert into Suggestions (suggestionName, suggestionIngredients, suggestionDescription, suggestionInstructions, suggestionTags) values (:title, :ingredients, :description, :instructions, :tags)");
          $suggestion->bindValue(":title", $title, PDO::PARAM_STR);
          $suggestion->bindValue(":ingredients", $ingredients, PDO::PARAM_STR);
          $suggestion->bindValue(":description", $desc, PDO::PARAM_STR);
          $suggestion->bindValue(":instructions", $instructions, PDO::PARAM_STR);
          $suggestion->bindValue(":tags", $tags, PDO::PARAM_STR);
          
          $suggestion->execute();
          
          $pdo->commit();
          
          $_SESSION['success']="Suggestion Successfully added. It will be sent for admin approval";
    
    }catch (PDOException $e){
            $pdo->rollBack();
            $_SESSION['errors'][] = "Database error in Add Recipe. Please try again";
        }
      
          
        
    
    }else{
      errorHandler("Please fill out all values");
      }

    header("Location: AddRecipe.php");
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

        /* Extra styles for the cancel button */
        .cancelbtn {
            padding: 14px 20px;
            background-color: #f44336;
        }
      
      
        /* Float button and add an equal width */
        .submitButton, .cancelbtn {
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
          .submitButton, .cancelbtn {
            width: 100%;
          }
        }
      </style>

    <body>
        
        <iframe src="Master.html" width = "100%" height = "72" style="border:none;"></iframe>
        <form method="post" action="AddRecipe.php">
          <h1>Add Your Own Recipe</h1>
          <p>Think you have a recipe worth sharing? Just fill out our quick form and submit it to our database!</p>
  
          <hr>
              <p>Enter a name for your recipe</p>
              <!--TO BE ADDED TO RECIPE NAME FIELD IN DATABASE-->
              <label for="recipeName"><b>Recipe Name:</b></label>
              <div><input type="shortText" name="title"></div>
          </hr>
  
          <hr>
              <p>Add a description for your recipe</p>
              <!--TO BE ADDED TO RECIPE DESCRIPTION FIELD IN DATABASE-->
              <label for="recipeDesc"><b>Recipe Description:</b></label>
              <div><input type="longText" name="desc"></div>
          </hr>
          
          <hr>
              <p>Enter a list of ingredients along with their respected quantities</p>
              <!--TO BE ADDED TO INGRIDIENTS FIELD IN DATABASE-->
              <label for="ingredients"><b>Recipe Ingredients:</b></label>
              <div><input type="longText" name="ingredients"></div>
          </hr>
  
          <hr>
              <p>Add some instructions for how to make your recipe</p>
              <!--TO BE ADDED TO INSTRUCTIONS FIELD IN DATABASE-->
              <label for="instructions"><b>Instructions:</b></label>
              <div><input type="longText" name="instructions"></div>
          </hr>
  
          <hr>
              <p>Add any tags to your recipe. For example vegan, vegetarian, healthy, etc.</p>
              <!--TO BE ADDED TO TAG FIELD IN DATABASE-->
              <label for="tags"><b>Tags:</b></label>
              <div><input type="longText" name="tags"></div>
          </hr>
  
          <hr>
              <div class="clearfix">
                  <!--CANCEL RECIPE, MAYBE CLEAR SCREEN-->
                  <button type="button" class="cancelbtn">Cancel</button>
                  <!--SUBMIT. CHECK IF ALL FIELDS HAVE VALUES AND ADD THIS RECIPE TO DATABASE-->
                  <!--MAYBE INCLUDE SOME FORM OF ADMIN CHECK BEFORE ADDING TO DATABASE?-->
                  <button type="submit" class="submitButton" name="submit">Submit Recipe</button>
              </div>
          </hr>

        </form>      
      
    </body>

</html>