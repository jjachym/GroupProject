<?php
    require_once '../models/User.php';
    require_once '../models/Recipe.php';
    include 'Master.php';
    include '../models/ErrorHandler.php';

    session_start();

    if (!isset($_SESSION['user'])) {
        errorHandler("Please log in!");
        header("Location: Login.php");
        return;
    }

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

    $user = $_SESSION['user'];

    $preds = $user->predict();

    if ($preds == false) {
        errorHandler("Please rate at least 1 recipe!");
        header("Location: SearchRecipes.php");
        return;
    }else{
        $recipe1 = new Recipe();
        $recipe1->findRecipe($preds[0]);
    
        $recipe2 = new Recipe();
        $recipe2->findRecipe($preds[1]);
    
        $recipe3 = new Recipe();
        $recipe3->findRecipe($preds[2]);
    }
?>

<!DOCTYPE html>

<html>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
        * {
        box-sizing: border-box;
        }
        
        body {
        font-family: Arial, Helvetica, sans-serif;
        }
        
        /* Float four columns side by side */
        .column {
        float: left;
        width: 33%;
        padding: 0 10px;
        }
        
        /* Remove extra left and right margins, due to padding */
        .row {margin: 0 -5px;}
        
        /* Clear floats after the columns */
        .row:after {
        content: "";
        display: table;
        clear: both;
        }
        
        /* Responsive columns */
        @media screen and (max-width: 600px) {
        .column {
            width: 100%;
            display: block;
            margin-bottom: 20px;
        }
        }
        </style>
    </head>

    <body>
        
        <h1>Get Recipe Recommendations</h1>
        <p>Using our AI algorithm we will provide you with a unique top 3 recipe recommendations based on previous recipes you rated highly.</p>

        <hr>
            <p>We do not need any extra information from you, below are your top 3 recipe recommendations!</p>
        </hr>

        <div class="container">
            <div class="card" style="width: 100%;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $recipe1->title; ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><small>Average Rating: <?php echo number_format($recipe1->averageRating,2); ?>☆</small></h6>
                    <hr>
                    <p class="card-text"><h5>Description</h5><?php echo $recipe1->description;?></p>
                    <hr>
                    <p class="card-text">
                    <h5>Ingredients</h5>
                    <?php 
                        echo "<ul>";
                        foreach (parse_ingredients($recipe1->ingredients) as $ingredient) {
                            echo "<li>$ingredient</li>";
                        }
                        echo "</ul>";
                    ?>
                    </p>
                    <hr>
                    <p class="card-text"><h5>Instructions</h5><?php echo $recipe1->instructions;?></p>
                    <hr>
                </div>
            </div>

            <br>

            <div class="card" style="width: 100%;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $recipe2->title; ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><small>Average Rating: <?php echo number_format($recipe2->averageRating,2); ?>☆</small></h6>
                    <hr>
                    <p class="card-text"><h5>Description</h5><?php echo $recipe2->description;?></p>
                    <hr>
                    <p class="card-text">
                    <h5>Ingredients</h5>
                    <?php 
                        echo "<ul>";
                        foreach (parse_ingredients($recipe2->ingredients) as $ingredient) {
                            echo "<li>$ingredient</li>";
                        }
                        echo "</ul>";
                    ?>
                    </p>
                    <hr>
                    <p class="card-text"><h5>Instructions</h5><?php echo $recipe2->instructions;?></p>
                    <hr>
                </div>
            </div>

            <br>

            <div class="card" style="width: 100%;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $recipe3->title; ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><small>Average Rating: <?php echo number_format($recipe3->averageRating,2); ?>☆</small></h6>
                    <hr>
                    <p class="card-text"><h5>Description</h5><?php echo $recipe3->description;?></p>
                    <hr>
                    <p class="card-text">
                    <h5>Ingredients</h5>
                    <?php 
                        echo "<ul>";
                        foreach (parse_ingredients($recipe3->ingredients) as $ingredient) {
                            echo "<li>$ingredient</li>";
                        }
                        echo "</ul>";
                    ?>
                    </p>
                    <hr>
                    <p class="card-text"><h5>Instructions</h5><?php echo $recipe3->instructions;?></p>
                    <hr>
                </div>
            </div>
        </div>
 
    </body>

</html>