
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
                  return $recipes;

                  //Runs if the options value is not null
                  //Sees if the option has any results in the database
                  //Sets session error if no results found


                }else{
                  
                  $sql = "SELECT * FROM recipes WHERE ";
                  $iterationCounter = 1;
                  $totalLength = 0;

                  foreach ($options as $option) {
                    foreach ($option as $o) {
                      $totalLength++;
                    }
                  }

                  foreach ($options as $key => $option) {
                    foreach ($option as $o) {
                      if ($iterationCounter == $totalLength) {
                        $sql .= $key." LIKE '%".$o."%'";
                      }else{
                        $sql .= $key." LIKE '%".$o."%' AND ";
                      }
                      $iterationCounter++;
                    }


                  }
                  
                  $fetchOptions = $pdo->prepare($sql);

                  $fetchOptions->execute();

                  if ($fetchOptions->rowCount() > 0) {
                    while($get = $fetchOptions->fetch()){
                          $recipe = new static($get['recipeName'], $get['ingredients'], $get['description'], $get['instructions'], $get['tag'], $get['averageRating']);
                          array_push($recipes, $recipe);
                    }
                  }else{
                    $_SESSION['errors'][] = "No Recipes Found!";
                  }

                  return $recipes;
                }
            }catch (PDOException $e){
                    $pdo->rollBack();
                    $_SESSION['errors'][] = "Database error please try again";
                    echo "DB Error";
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
                return true;
                
          }catch (PDOException $e){
                $pdo->rollBack();
                 $_SESSION['errors'][] = "Database error. Please try again";
                return false;
            }
      }

        //method that save a Recipe object into the db
        public function save(){
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
               $_SESSION['success'] = "Recipe successfully saved";
              return true;
              
          }catch (PDOException $e){
                $pdo->rollBack();
                return false;
          }
            
    }

    //method that updates ratings - currently average ratings are not properly displayed
    public function updateRating($rating){
      try{
            
              
        $db = new DBHandler();
        $pdo = $db->getInstance();
    
        //Starts a transaction
        $pdo->beginTransaction();

        $update = $pdo->prepare("UPDATE recipes SET averageRating = (averageRating + :rating )/2 WHERE recipeName = :recipeName");

        $update->bindValue(":rating",$rating,PDO::PARAM_INT);
        $update->bindValue(":recipeName",$this->title,PDO::PARAM_STR);
        
        $update->execute();

        $pdo->commit();
        return true;

      }catch (PDOException $e){
        $pdo->rollBack();
        $_SESSION['errors'][] = "DB Error";
        return false;
      }
    }
        
  }
    