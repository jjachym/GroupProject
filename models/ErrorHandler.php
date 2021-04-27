<?php

    //Error handling subroutines, with flash messages

    //associated CSS for flash messages

    echo "<style>    
    .error{
        width: 90%;
        margin: auto;
        padding: 10px;
        background: red;
        color: white;
        font-family: Helvetica;
    }

    .success{
        width: 90%;
        margin: auto;
        padding: 10px;
        background: green;
        color: white;
        font-family: Helvetica;
    }
    </style>";

    //flash error messages

    if (isset($_SESSION['errors'])){
        foreach ($_SESSION['errors'] as $error) {
            echo "<div class='error'> <p>",$error,"</p></div>";
        }
    }

    if (isset($_SESSION['success']) && $_SESSION['success'] != ""){
        echo "<div class='success'> <p>",$_SESSION['success'],"</p></div>";
        $_SESSION['success'] = "";
    }

    //unset all errors

    $_SESSION['errors'] = array();

    //error handler
    function errorHandler($message){
        $_SESSION['errors'][] = $message;
    }


?>