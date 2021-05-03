<?php
  require_once '../models/User.php';
  require_once '../models/Recipe.php';
  include '../models/ErrorHandler.php';
  include 'Master.php';
  
  
  session_start();

  if (!isset($_SESSION['recipeDetail'])) {
      header("Location: SearchRecipes.php");
      return;
  }

  $recipeID = $_SESSION['recipeDetail'];
  $recipe = $_SESSION['recipes'][$recipeID];

  function parse_ingredients($ingredients){
      $ingredientsArray = array();
      
      $cleanIngredients = preg_replace('/[^A-Za-z0-9, "]/', '', $ingredients);

      $length = strlen($cleanIngredients);
      $active = false;

      for ($i=0; $i < $length; $i++) { 
          if ($cleanIngredients[$i] == '"') {
              if($active){
                if (strlen($substr) > 1) {
                    array_push($ingredientsArray,$substr);
                }
                $active = false;
              }else{
                $substr = "";
                $active = true;
              }
          }else{
            $substr .= $cleanIngredients[$i];
          }

      }

      return $ingredientsArray;

  }


  //rating form processing here

  if(isset($_POST['star'])){
    $rating = $_POST['star'];

    $recipe->updateRating($rating);    

    $user = $_SESSION['user'];

    $intRating = intval($rating);

    $user->rate_recipe($recipe,$rating);

    header("Location: RecipeDetail.php");
    return;
  }

?>

<!DOCTYPE html>

<html>
      <head>
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
            
            .button1 {
            background-color: blue;
            color: black;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 10%;
            opacity: 0.9;
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

            .txt-center {
                text-align: center;
            }
            .hide {
                display: none;
            }

            .clear {
                float: none;
                clear: both;
            }

            .rating {
                width: 90px;
                unicode-bidi: bidi-override;
                direction: rtl;
                text-align: center;
                position: relative;
            }

            .rating > label {
                float: right;
                display: inline;
                padding: 0;
                margin: 0;
                position: relative;
                width: 1.1em;
                cursor: pointer;
                color: #000;
            }

            .rating > label:hover,
            .rating > label:hover ~ label,
            .rating > input.radio-btn:checked ~ label {
                color: transparent;
            }

            .rating > label:hover:before,
            .rating > label:hover ~ label:before,
            .rating > input.radio-btn:checked ~ label:before,
            .rating > input.radio-btn:checked ~ label:before {
                content: "\2605";
                position: absolute;
                left: 0;
                color: #FFD700;
            }
        </style>
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
        
        <div class="container">
            <div class="card" style="width: 100%;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $recipe->title; ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><small>Average Rating: <?php echo number_format($recipe->averageRating,2); ?>☆</small></h6>
                    <hr>
                    <p class="card-text"><h5>Description</h5><?php echo $recipe->description;?></p>
                    <hr>
                    <p class="card-text">
                    <h5>Ingredients</h5>
                    <?php 
                        echo "<ul>";
                        foreach (parse_ingredients($recipe->ingredients) as $ingredient) {
                            echo "<li>$ingredient</li>";
                        }
                        echo "</ul>";
                    ?>
                    </p>
                    <hr>
                    <p class="card-text"><h5>Instructions</h5><?php echo $recipe->instructions;?></p>
                    <hr>
                    <?php
                    if (isset($_SESSION['user'])) {
                        echo '
                        <div>
                            <form name="starform" onChange="document.starform.submit()" method="post">
                                <div class="rating" style="width: 100%; text-align: right;">
                                    <input id="star5" name="star" type="radio" value="5" class="radio-btn hide" />
                                    <label for="star5" style="font-size:20px;">☆</label>
                                    <input id="star4" name="star" type="radio" value="4" class="radio-btn hide" />
                                    <label for="star4" style="font-size:20px;">☆</label>
                                    <input id="star3" name="star" type="radio" value="3" class="radio-btn hide" />
                                    <label for="star3" style="font-size:20px;">☆</label>
                                    <input id="star2" name="star" type="radio" value="2" class="radio-btn hide" />
                                    <label for="star2" style="font-size:20px;">☆</label>
                                    <input id="star1" name="star" type="radio" value="1" class="radio-btn hide" />
                                    <label for="star1" style="font-size:20px;">☆</label>
                                    <p style="font-size:20px;"> :Rate </p>
                                    <div class="clear"></div>
                                </div>
                            </form>
                        </div>';
                    }
                    ?>
                </div>
            </div>
        </div>

        

    </body>

</html>