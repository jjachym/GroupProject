<?php
    require_once 'Recipe.php';
    require_once 'DBHandler.php';
    include 'functions.php';

    //User class containg all the relevant user attributes and methods
    
    session_start();


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
                    $this->admin = $row['admin'];
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

                    update($recipe->title,$this->username,$rating);
                }else{
                    //Add new rating to db

                    rate($recipe->title,$this->username,$rating);
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

        private function get_ingredients_array($recipe){

            $cleanInstructions = preg_replace("/[^A-Za-z0-9 ]/", '', $recipe->instructions);

            $words = explode(" ",$cleanInstructions);
            $cleanedWords = [];

            foreach ($words as $word) {
                if (!isset($cleanedWords[strtolower($word)])) {
                    $cleanedWords[strtolower($word)] = 1;
                }else{
                    $cleanedWords[strtolower($word)]++;
                }
            }

            return $cleanedWords;
        }

        private function similarity($recipeA,$recipeB){

            $s = 0;

            foreach ($recipeA as $key => $val) {
                if (isset($recipeB[$key])) {
                    $s+= $val + $recipeB[$key];
                }
            }

            return $s;
        }
        
        

        public function get_user_ratings($username){
            try{
                
                $db = new DBHandler();
                $pdo = $db->getInstance();

                $recipes = array();
                
                $getRecipes = $pdo->prepare("SELECT * FROM userRatings where userUsername=:username");
                $getRecipes->bindValue(':username', $this->username, PDO::PARAM_STR);
                
                $getRecipes->execute();

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
            
            $userRatedRecipes = $this->get_user_ratings($this->username);
            $highestRating = 0;

            if (!isset($userRatedRecipes[0])) {
                return false;
            }

            foreach ($userRatedRecipes as $r) {
                if ($r[1] > $highestRating) {
                    $highestRatedRecipe = $r[0];
                }
            }

            $highestIngList = $this->get_ingredients_array($highestRatedRecipe);

            $allRecipes = Recipe::fetchAll();

            $allRecipeIngredients = [];

            $s = 0;

            $mostSimilar = [];
            $mostSimilarVals = [0,0,0];

            foreach ($allRecipes as $recipe) {
                $ingList = $this->get_ingredients_array($recipe);

                $similarity = $this->similarity($highestIngList,$ingList);

                if ($similarity >= $s && $recipe->title != $highestRatedRecipe->title) {
                    
                    //find pos of insert

                    for ($i=0; $i < 3; $i++) { 
                        if ($similarity > $mostSimilarVals[$i]) {
                            $pos = $i;
                            break;
                        }
                    }

                    array_splice($mostSimilarVals,$pos,0,$similarity);
                    array_splice($mostSimilar,$pos,0,$recipe->title);

                    $s = $mostSimilarVals[2];
                }

            }

            return $mostSimilar;
        }
        
        
    }
    