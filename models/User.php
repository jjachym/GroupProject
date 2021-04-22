<?php

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

        public function __construct($firstName="",$lastName="",$email="",$admin=false){
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->email = $email;
            $this->admin = $admin;

            //save new user and retrieve id for that user
            if($this->firstName != ""){

                $this->authenticated = true;

            }
        }

        //authenticate users credentials
        public function authenticate($pwd){
            $valid = false;

            //query db and check pwd
            

            if($valid){
                $this->authenticated = true;
            }

            return $valid;           

        }

        //find user by unique username in the db
        public function find_user($username){

        }

        public function predict(){

        }

        public function rate_recipe($recipe_id,$rating){

        }




    }
 