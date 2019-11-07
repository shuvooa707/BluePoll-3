<?php session_start(); require_once("db.php");
    if ( isset($_GET["pollid"]) ) {
        $pollid = $_GET["pollid"];
        $poll = (new db())->single("polls",$pollid)->fetch(PDO::FETCH_OBJ);
        $options = (new db())->singlePollOptions("options",$pollid);
        $comments = (new db())->allPollComments("comments",$pollid);
    }
    // echo $polls;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
        <link rel="stylesheet" href="css/common.css">
        <link rel="stylesheet" href="css/index.css">

        <script src="js/common.js" defer></script>
        <script src="js/index.js" defer></script>
        <title>Poll Site</title>
    </head>
    <body>
        <!-- if signup was successful -->
        <?php
            if( isset($_SESSION["login_successful"]) &&  $_SESSION["login_successful"] == "Signup Successfull" ) {
                echo "<script>
                          alert('congratulations Signup Successfull!! ');
                      </script>";
                $_SESSION["login_successful"] = null;
            }
        ?>
        <!-- /if signup was successful -->

        <!-- This is navbar of the page -->
        <?php
            require_once("navbar.php");
        ?>
        <!-- /This is navbar of the page -->

        <!-- This is body of the page -->
        <?php 
            require_once("body.php");
        ?>
        <!-- This is /body of the page -->


        
    <?php require_once("components\modals.php"); ?>
    </body>
</html>