<?php
    require_once 'Recipe.php';

    //User class containg all the relevant user attributes and methods

    session_start();

    require_once("DBHandler.php");

    class User
    {
        public $username;
        public $firstName;
        public $lastName;
        public $email;
        public $id;
        public $admin;
        public $authenticated = false;

        public $aiObject;

        public function __construct($username="",$firstName="",$lastName="",$admin="false"){
            $this->username = $username;
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            //$this->email = $email;
            $this->admin = $admin;

        }
        
        public function admin($user){
          if($user->admin == "true"){
            return true;
          }else{
            return false;
          }
        }

        //authenticate users credentials
        public function authenticate($pwd){

            //query db and check pwd

            try{
                $db = new DBHandler();
                $pdo = $db->getInstance();

                $sql = "SELECT userPassword FROM User WHERE userUsername = ?";

                $auth = $pdo->prepare($sql);
                $auth->execute([$this->username]);

                $row = $auth->fetch();

                //verify if hashes of passwords match
                if(password_verify($pwd,$row['userPassword'])){
                    $valid = true;
                }

            }catch(PDOException $e){
                $valid = false;
            }
            

            if($valid){
                $this->authenticated = true;
            }

            return $valid;           

        }

        //find user by unique username in the db
        public function find_user($username){

            try{
                $this->username = $username;
    
                $db = new DBHandler();
                $pdo = $db->getInstance();
    
                $sql = "SELECT * FROM User WHERE userUsername = ?";
    
                $auth = $pdo->prepare($sql);
                $auth->execute([$this->username]);
    
                $row = $auth->fetch();

                if(isset($row['userID'])){
                    $this->firstName = $row['userFirstName'];
                    $this->lastName = $row['userLastName'];
                    $this->id = $row['userID'];
                }else{
                    return false;
                }
            }catch(PDOException $e){
                return false;
            }

            return true;

        }

        //rate recipe
        public function rate_recipe($recipe,$rating){
            try{
                
                $db = new DBHandler();
                $pdo = $db->getInstance();
                
                //Starts a transaction
                $pdo->beginTransaction();
                
                $checkRating = $pdo->prepare("SELECT * FROM userRatings WHERE recipeName = :recipeName AND userUsername = :username");
                $checkRating->bindParam(':recipeName', $recipe->title);
                $checkRating->bindParam(':username', $this->username);

                $checkRating->execute();

                //Check if the user rated this recipe already
                if($checkRating->rowCount() > 0){

                    //Update rating for this user

                    $updateRating = $pdo->prepare("UPDATE userRatings SET rating = :rating WHERE recipeName = :recipeName AND userUsername = :username");
                    $updateRating->bindParam(':rating',$rating);
                    $updateRating->bindParam(':recipeName', $recipe->title);
                    $updateRating->bindParam(':username', $this->username);

                    $updateRating->execute();
                }else{
                    //Add new rating to db

                    $saveRating = $pdo->prepare("INSERT INTO userRatings(recipeName,userUsername,rating) VALUES ( :recipeName, :username, :rating)");

                    $saveRating->bindParam(':rating',$rating);
                    $saveRating->bindParam(':recipeName',$recipe->title);
                    $saveRating->bindParam(':username',$this->username);

                    $saveRating->execute();

                }

                return true;
            }catch (PDOException $e){
                $_SESSION['errors'][] = "DB error please try later!";
                $pdo->rollBack();
                return false;
            }
        }
        
        //save new user into db
        public function save($password){
            try{
                
                $db = new DBHandler();
                $pdo = $db->getInstance();
                
                //Starts a transaction
                $pdo->beginTransaction();
                
                $checkUsername=$pdo->prepare("select count(userUsername) as amount from User where userUsername = :username");
                $checkUsername->bindValue(':username', $this->username, PDO::PARAM_STR);
                $checkUsername->execute();
                
                while($check = $checkUsername->fetch()){
                    if($check['amount'] > 0){
                        $_SESSION['errors'][] = "Username is already taken. Please choose another!";
                        return false;
                    }else{
                        $addUser = $pdo->prepare("insert into User (userFirstName, userLastName, userUsername, userPassword, admin) values (:fName, :lName, :uName, :psw, :admin)");
                        
                        $addUser->bindValue(':fName', $this->firstName, PDO::PARAM_STR);
                        $addUser->bindValue(':lName', $this->lastName, PDO::PARAM_STR);
                        $addUser->bindValue(':uName', $this->username, PDO::PARAM_STR);
                        $addUser->bindValue(':psw', $password, PDO::PARAM_STR);
                        $addUser->bindValue(':admin', $this->admin, PDO::PARAM_STR);

                        $addUser->execute();
                        
                        $pdo->commit();
                    }
                }
                
                return true;
            }catch (PDOException $e){
                $pdo->rollBack();
                return false;
            }
        }

        private function getNullMatrix($recipes){
            $nullMatrix = array();
            
            foreach ($recipes as $recipe) {
                
                //Parse ingredients into an array
                
                $ingredientsArray = array();
                
                $cleanIngredients = preg_replace('/[^A-Za-z "]/', '', $recipe->ingredients);
                
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
                
                //Build large vector containing all possible ingredients
                
                foreach ($ingredientsArray as $ingredient) {
                    $lowerIngredient = strtolower($ingredient);
                    $nullMatrix[$lowerIngredient] = 0;
                }
                
            }

            return $nullMatrix;
        }
        
        private function getRecipeMatrix($recipe,$nullMatrix){
            
            //Parse ingredients into an array
                
            $ingredientsArray = array();
                
            $cleanIngredients = preg_replace('/[^A-Za-z "]/', '', $recipe->ingredients);
            
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
            
            //Build large vector containing all possible ingredients
            
            foreach ($ingredientsArray as $ingredient) {
                $lowerIngredient = strtolower($ingredient);
                $nullMatrix[$lowerIngredient] = 1;
            }

            return $nullMatrix;
        }

        public function get_user_ratings(){
            try{
                
                $db = new DBHandler();
                $pdo = $db->getInstance();

                $recipes = array();
                
                $getRecipes = $pdo->prepare("SELECT * FROM userRatings");
                //$getRecipes->bindValue(':username', $this->username, PDO::PARAM_STR);
                
                $getRecipes->execute();
                
                print_r($getRecipes);

                while ($entry = $getRecipes->fetch()) {

                    $recipe = new Recipe();
                    $recipe->findRecipe($entry['recipeName']);

                    $recipeAndRating = [$recipe,$entry['rating']];

                    array_push($recipes,$recipeAndRating);
                }

                
                return $recipes;
            }catch (PDOException $e){
                echo "DB Error";
                return false;
            }
        }
        
        public function predict(){
            
            //fetch possible recipes for prediction
            $recipes = Recipe::fetchAll();
            
            $nullMatrix = $this->getNullMatrix($recipes);
            $predictionMatrix = array();
            
            //convert recipes into matrix form
            foreach ($recipes as $recipe) {
                $i = $this->getRecipeMatrix($recipe,$nullMatrix);
                array_push($predictionMatrix,$i);
            }
            
            //fetch user rated recipes

            $ratedRecipes = $this->get_user_ratings($this->username);
            $ratedRecipesMatrix = array();

            foreach ($ratedRecipes as $r) {
                $ratedRecipe = $r[0];

                $i = $this->getRecipeMatrix($ratedRecipe,$nullMatrix);
                array_push($ratedRecipesMatrix,[$i,$r[1]]);
            }

            /**
             * 
             * Pass prediction Matrix into python script
             * 
             */

            return true;
        }
        
        
        
    }
    