<?php require_once("db.php"); require_once("helper.php");

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
        <link rel="stylesheet" href="css/messege.css">
        <link rel="stylesheet" href="css/common.css">

        <script src="js/poll.js" defer></script>
        <script src="js/common.js" defer></script>
        <title>Poll</title>
    </head>
    <body>        
        <!-- This is navbar of the page -->
        <?php
            require_once("navbar.php");
        ?>
        <!-- /This is navbar of the page -->
        <div class="container">
            <div class="left threads"></div>
            <div class="middle messege">
                <div class="messege-board">
                </div>
                <div class="new-messege-container">                    
                    <textarea name="" id="" cols="30" rows="10"></textarea>
                    <button>Send</button>
                </div>
            </div>
            <div class="right info"></div>
        </div>

    </body>
</html>
