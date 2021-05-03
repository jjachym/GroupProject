<?php
    require_once 'DBHandler.php';

    function rate($recipeName,$userUsername,$rating){

        try{

            $db = new DBHandler();
            $pdo = $db->getInstance();

            $saveRating = $pdo->prepare("INSERT INTO userRatings(recipeName,userUsername,rating) VALUES ( :recipeName, :username, :rating)");
    
            $saveRating->bindParam(':recipeName',$recipeName);
            $saveRating->bindParam(':username',$userUsername);
            $saveRating->bindParam(':rating',$rating);
    
            $saveRating->execute();

            return true;
        }catch(PDOException $e){
            $pdo->rollBack();
            return false;
        }
    }

    function update($recipeName,$userUsername,$rating){

        try{

            $db = new DBHandler();
            $pdo = $db->getInstance();

            //Update rating for this user
    
            $updateRating = $pdo->prepare("UPDATE userRatings SET rating = :rating WHERE recipeName = :recipeName AND userUsername = :username");
            $updateRating->bindParam(':rating',$rating);
            $updateRating->bindParam(':recipeName', $recipeName);
            $updateRating->bindParam(':username', $userUsername);
    
            $updateRating->execute();

            return true;
        }catch(PDOException $e){
            $pdo->rollBack();
            return false;
        }
    }




?>