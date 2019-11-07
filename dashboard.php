<?php session_start(); require_once("db.php"); require_once("helper.php");

    if( !issLoggedIn() ) {
        header("Location:index.php");
    }
    // getting all the votes that this users has given
    $conn = (new db())->conn;
    $user_id = $_SESSION["pollsite_user_id"];
    $sql = "SELECT \n"

        . "    \n"

        . "    options.option_name, \n"

        . "    (SELECT polls.poll_name FROM polls WHERE polls.poll_id = options.option_belongsto) AS poll_name,\n"

        . "    (SELECT polls.poll_id FROM polls WHERE polls.poll_id = options.option_belongsto) AS poll_id\n"

        . "FROM options JOIN votes on votes.vote_option_id = options.option_id \n"

        . "    WHERE votes.vote_given_by = $user_id";


    $r = $conn->query($sql);
    
    $sql = "SELECT *,(SELECT COUNT(*) FROM comments WHERE polls.poll_id = comments.comment_poll_id) AS poll_comment_num from polls where poll_user_id=$user_id";

    $cpolls = $conn->query($sql);
    // echo $polls;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
        <link rel="stylesheet" href="css/dashboard.css">
        <link rel="stylesheet" href="css/common.css">

        <script src="js/common.js" defer></script>
        <script src="js/dashboard.js" defer></script>
        <title>Poll Site</title>
    </head>
    <body>

        <!-- This is navbar of the page -->
        <?php
            require_once("navbar.php");
        ?>
        <!-- /This is navbar of the page -->
        <div class="container">
     
            <!-- this is a list contains all the polls that you have Created -->
            <div id="created-poll-list">
                <div class="cpl-header">
                    Polls You Have Created
                </div>
                <div class="cpl-body">
                    <?php
                        while( $cpoll = $cpolls->fetch(PDO::FETCH_OBJ) ) {

                            echo "
                                <div class='poll' data-vote-id='23'>
                                    <div class='poll-name'>
                                        <strong><a href='poll.php?pollid=$cpoll->poll_id'>$cpoll->poll_name</a></strong>                                        
                                    </div>
                                    <div class='poll-stats'>
                                        <small  style='padding:3px 0px;display:inline-block; margin-right:5px;color:green; width:70px; border-right:2px solid #aaa;'>
                                            1k Votes
                                        </small> 
                                        <small style='color:blueviolet;'>
                                            $cpoll->poll_comment_num Comment
                                        </small><br>
                                        <small  style='padding:3px 0px;display:inline-block; margin-right:5px;color:dodgerblue; width:70px; border-right:2px solid #aaa;'>
                                            100 Likes 
                                        </small> 
                                        <small style='color:lightcoral;'>
                                            2 Dislikes
                                        </small>
                                    </div>
                                </div> ";
                        }
                    ?>
                </div>
            </div>
            <!-- /this is a list contains all the polls that you have Created -->
            
            

            <!-- this is a list contains all the polls that you have votted -->
            <div id="votted-poll-list">
                <div class="vpl-header">
                    Polls You Have Votted
                </div>
                <div class="vpl-body">
                    <?php
                        while( $v = $r->fetch(PDO::FETCH_OBJ) ) {
                            if( !strlen($v->poll_name) ) {
                                continue;
                            }
                            echo "
                                <div class='vote' data-vote-id='23'>
                                    <strong><a href='poll.php?pollid=$v->poll_id'>$v->poll_name</a></strong>
                                        <div style='padding: 0px 20px;font-size:14px;font-style:italic;'>
                                            <input type='checkbox' name=' id=' checked='true' disabled='true'> 
                                            $v->option_name
                                        </div>
                                </div> ";
                        }
                    ?>
                </div>
            </div>
            <!-- /this is a list contains all the polls that you have votted -->
       

        </div>
    </body>
</html>