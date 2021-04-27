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

        public static function find_recipe($title){

        }


        
    }
    