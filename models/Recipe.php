<!-- TODO: php classes -->

<?php
    session_start();
    //Recipe class
    
    require_once("DBHandler.php");
    
    
    class Recipe{

        //attributes
        public $title;
        public $ingredients;
        public $description;
        public $instructions;
        public $tags;
        public $averageRating;

        public function __construct($title="",$ingredients="",$description="",$instructions="",$tags="", $averageRating=""){
            $this->title = $title;
            $this->ingredients = $ingredients;
            $this->description = $description;
            $this->instructions = $instructions;
            $this->tags = $tags;
            $this->averageRating = $averageRating;
        }

        //static methods called using Recipe::function_name()

        /**static method that fetches all recipes(maybe limit to 200?) and returns an array of Recipe objects
         * 
         * optional argument options could be used for slight filtering, i.e. all italian recipes etc.
         * */
        public static function fetchAll($options=null){
            echo $options;
            //return array of recipe objects
            try{
            
                $db = new DBHandler();
                $pdo = $db->getInstance();
          
                //Starts a transaction
                $pdo->beginTransaction();
                
                $recipes = array();
                
                //checks the status of the options variable
                //gets the top 200 if the option variable is null
                if ($options==null){
                  $fetchAll=$pdo->prepare("select * from recipes limit 200");
                  $fetchAll->execute();
                  while($fetch =$fetchAll->fetch()){
                    $recipe = new Recipe($fetch['recipeName'], $fetch['ingredients'], $fetch['description'], $fetch['instructions'], $fetch['tag'], $fetch['averageRating']);
                    array_push($recipes, $recipe);
                  }
                   $_SESSION['errors'][] = "Recipes successfully found";
                  return $recipes;
                  //Runs if the options value is not null
                  //Sees if the option has any results in the database
                  //Sets session error if no results found
                }else{
                  $fetchOptions=$pdo->prepare("select count(*) as amount from recipes where recipeName like '%{$options}%' or description like '%{$options}%'");
                  $fetchOptions->execute();
                  while($fetch = $fetchOptions->fetch()){
                    echo $fetch['amount'];
                    if($fetch['amount'] < 1){
                      $_SESSION['errors'][] = "No recipe with that option has been found. Please try another";
                      return $recipes;
                      //Gets all recipes from the database where the name or the description holds the option variable string
                    }else{
                      $getOptions=$pdo->prepare("select * from recipes where recipeName like '%{$options}%' or description like '%{$options}%'");
                      $getOptions->execute();
                      while($get =$getOptions->fetch()){
                        $recipe = new Recipe($get['recipeName'], $get['ingredients'], $get['description'], $get['instructions'], $get['tag'], $get['averageRating']);
                        array_push($recipes, $recipe);
                      }
                    }
                    
                  }
                  return $recipes;
                   $_SESSION['errors'][] = "Recipes with that option successfully found";
                  }
            }catch (PDOException $e){
                    $pdo->rollBack();
                     $_SESSION['errors'][] = "Database error please try again";
                    return $recipes;
                }
      }


        //method for finding a single recipe by title
        public function findRecipe($title){

            //populate current Recipe objects attributes with data found in db

            //return true if found
            
            try{
                
                $db = new DBHandler();
                $pdo = $db->getInstance();
          
                //Starts a transaction
                $pdo->beginTransaction();
                
                //checks if the recipe name exists
                $findRecipe=$pdo->prepare("select count(recipeName) as amount from recipes where recipeName = :title");
                $findRecipe->bindValue(":title", $title, PDO::PARAM_STR);
                $findRecipe->execute();
                
                //Sees if there are one or more results, returns false if not and gets the recipe otherwise
                while ($find=$findRecipe->fetch()){
                  if ($find['amount'] < 1){
                    $_SESSION['errors'][] = "No recipe with that name has been found. Please try another";
                      return false;
                  }else{
                    $getRecipe=$pdo->prepare("select * from recipes where recipeName = :title");
                    
                    $getRecipe->bindValue(":title", $title, PDO::PARAM_STR);
                    
                    $getRecipe->execute();
                    
                    while($get=$getRecipe->fetch()){
                      $this->title=$get['recipeName'];
                      $this->ingredients=$get['ingredients'];
                      $this->description=$get['description'];
                      $this->instructions=$get['instructions'];
                      $this->tag=$get['tag'];
                      $this->averageRating=$get['averageRating'];
                    }
                  }
                }
                 $_SESSION['errors'][] = "Recipe successfully found";
                return true;
                
          }catch (PDOException $e){
                $pdo->rollBack();
                 $_SESSION['errors'][] = "Database error. Please try again";
                return false;
            }
      }

        //method that save a Recipe object into the db
        public function save(){
            echo"save ran";
            //return true if insertion into db is valid
            
            try{
            
              
              $db = new DBHandler();
              $pdo = $db->getInstance();
          
              //Starts a transaction
              $pdo->beginTransaction();
              
              //Check to see if the name of the recipe has already been saved in the database
              //Returns false if name already taken and inserts into the database otherwise
              $checkRecipeName=$pdo->prepare("select count(recipeName) as amount from recipes where recipeName = :rname");
              $checkRecipeName->bindValue(':rname', $this->title, PDO::PARAM_STR);
              $checkRecipeName->execute();
              
              while($check = $checkRecipeName->fetch()){
                  if($check['amount'] > 0){
                      $_SESSION['errors'][] = "Recipe name is already taken. Please choose another";
                      return false;
                  }else{
                    
                    $insertRecipe=$pdo->prepare("insert into recipes (recipeName, ingredients, instructions, description, tag, averageRating) values (:name, :ingredients, :instructions, :description, :tag, :averageRating)");
                    $insertRecipe->bindValue(":name", $this->title, PDO::PARAM_STR);
                    $insertRecipe->bindValue(":ingredients", $this->ingredients, PDO::PARAM_STR);                
                    $insertRecipe->bindValue(":instructions", $this->instructions, PDO::PARAM_STR);
                    $insertRecipe->bindValue(":description", $this->description, PDO::PARAM_STR);
                    $insertRecipe->bindValue(":tag", $this->tags, PDO::PARAM_STR);
                    $insertRecipe->bindValue(":averageRating", $this->averageRating, PDO::PARAM_STR);
                    
                    $insertRecipe->execute();
                    
                    $pdo->commit();
                    
                  }
              }
               $_SESSION['errors'][] = "Recipe successfully saved";
              return true;
              
          }catch (PDOException $e){
                $pdo->rollBack();
                return false;
            }
            
    }
        
    }
    