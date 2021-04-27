<!-- TODO: php classes -->

<?php
    session_start();
    //Recipe class

    class Recipe{

        //attributes
        public $title;
        public $ingredients;
        public $averageRating;
        public $description;
        public $tags;

        public function __construct($title,$ingredients,$description,$tags){
            $this->title = $title;
            $this->ingredients = $ingredients;
            $this->description = $description;
            $this->tags = $tags;
        }

        //static methods called using Recipe::function_name()

        /**static method that fetches all recipes(maybe limit to 200?) and returns an array of Recipe objects
         * 
         * optional argument options could be used for slight filtering, i.e. all italian recipes etc.
         * */
        public static function fetchAll($options=null){

            //return array of recipe objects

        }


        //method for finding a single recipe by title
        public function findRecipe($title){

            //populate current Recipe objects attributes with data found in db

            //return true if found
        }

        //method that save a Recipe object into the db
        public function save(){

            //return true if insertion into db is valid
            
        }


        
    }
    