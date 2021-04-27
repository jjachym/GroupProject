<?php

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

        public function __construct($username="",$firstName="",$lastName="",$admin=false){
            $this->username = $username;
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            //$this->email = $email;
            $this->admin = $admin;

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

        public function predict(){

        }

        public function rate_recipe($recipe_id,$rating){

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
                        $_SESSION['errors'][] = "Username is already taken. Please choose another";
                        return false;
                    }else{
                        $addUser = $pdo->prepare("insert into User (userFirstName, userLastName, userUsername, userPassword) values (:fName, :lName, :uName, :psw)");
                        
                        $addUser->bindValue(':fName', $this->firstName, PDO::PARAM_STR);
                        $addUser->bindValue(':lName', $this->lastName, PDO::PARAM_STR);
                        $addUser->bindValue(':uName', $this->username, PDO::PARAM_STR);
                        $addUser->bindValue(':psw', $password, PDO::PARAM_STR);
                        
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




    }
 