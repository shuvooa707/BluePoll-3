<?php session_start(); require_once("db.php"); require_once("helper.php");

    if( !issLoggedIn() ) {
        header("Location:index.php");
    }
    // getting all the votes that this users has given
    $conn = (new db())->conn;
    $user_id = $_SESSION["pollsite_user_id"];
    $sql = "SELECT * FROM polls WHERE poll_user_id=".$user_id;

    $polls = $conn->query($sql);
    
    $sql = "SELECT *, (SELECT polls.poll_name FROM polls WHERE polls.poll_id = options.option_belongsto limit 1) AS poll_name, (SELECT poll_id FROM polls WHERE polls.poll_id = options.option_belongsto limit 1) AS poll_id FROM options JOIN votes ON options.option_id = votes.vote_option_id WHERE votes.vote_given_by = $user_id";
    // print_r($sql);
    $voted_polls = $conn->query($sql);


    $sql = "SELECT DISTINCT poll_id, poll_name, proposed_option_name, proposed_option_poll_added_by,proposed_option_id, (SELECT CONCAT(users.user_firstname, ' ',users.user_lastname) FROM users WHERE users.user_id = proposed_option.proposed_option_poll_added_by) AS option_requested_by FROM proposed_option,polls,users WHERE proposed_option_poll_id = polls.poll_id AND polls.poll_user_id = $user_id";
    // print_r($sql);
    $option_requests = $conn->query($sql);
    // echo $polls;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
        <!-- this file contains all the link tags for common CSS files -->
        <?php
            require_once("common_stylesheet_links.php");
        ?>
        
        <link rel="stylesheet" href="css/dashboard.css">        


        <script src="js/common.js" defer></script>
        <script src="js/dashboard.js" defer></script>
        <title>Dashboard</title>
    </head>
    <body>

        <!-- This is navbar of the page -->
        <?php
            require_once("navbar.php");
        ?>
        <!-- /This is navbar of the page -->
        <div class="container">
            <!-- Side navbar -->
            <div class="sidenav">
                
            </div>
            <!-- /Side navbar -->
            <!-- this is a list contains all the polls that you have Created -->
            <div id="created-poll-list">
                <div class="cpl-header">
                    Polls You Have Created
                </div>
                <div class="cpl-body">
                    <?php
                        while( $poll = $polls->fetch(PDO::FETCH_ASSOC) ) {
                            $poll_status = $poll["poll_status"];
                            if ( $poll_status == 1 ) {
                                $bgcolor = "green";
                                $poll_status = "Public";
                            } else {
                                $bgcolor = "lightcoral";
                                $poll_status = "Private";
                            }
                            echo "
                                <div class='poll' data-poll-id='".$poll['poll_id']."'>
                                    <div class='poll-name'>
                                        <strong><a href='poll.php?pollid=".$poll['poll_id']."'>".$poll['poll_name']."</a></strong>                                        
                                    </div>
                                    <div class='poll-stats'>
                                        <small  style='padding:3px 0px;display:inline-block; margin-right:5px;color:green; width:70px; border-right:2px solid #aaa;'>
                                            
                                        </small> 
                                        <small style='color:blueviolet;'>
                                            
                                        </small><br>
                                        <small  style='padding:3px 0px;display:inline-block; margin-right:5px;color:dodgerblue; width:70px; border-right:2px solid #aaa;'>
                                             
                                        </small> 
                                        <small style='color:lightcoral;'>
                                            
                                        </small>
                                    </div>
                                    <div class='poll-control'>
                                        <button class='delete' onclick='deletePollConf(this.parentElement.parentElement)'>Delete</button>
                                        <button class='private' style='width:74px; padding:5px 0px;background:$bgcolor;' onclick='changeVisibility(this.parentElement.parentElement,this)'>".$poll_status."</button>
                                        <button class='edit' onclick='editConf()'>Edit</button>
                                        <button class='analyze' onclick='analyze(this.parentElement.parentElement)'>Analyze</button>
                                    </div>
                                </div> ";
                        }
                    ?>
                </div>
            </div>
            <!-- /this is a list contains all the polls that you have Created -->

            <!-- this is a list contains all the proposed options for your poll -->
                <div class="proposed-poll-option">
                    <div class="ppo-header">
                        Options Request
                    </div>
                    <div class="ppo-body">
                        <?php 
                            while ( $row = $option_requests->fetch(PDO::FETCH_ASSOC) ) {
                                echo "
                                <div class='poption' data-poption-id='".$row["proposed_option_id"]."'>
                                    <strong><a href='poll.php?pollid=".$row["poll_id"]."'>".$row["poll_name"]."</a></strong>
                                    <div class='poption-name' style='text-indent:10px;color:#646464;'>".$row["proposed_option_name"]."</div>
                                    <div class='poption-added-by' style='text-align:right;color:#646464;'>
                                        by <a href='user.php?userid=".$row["proposed_option_poll_added_by"]."' style='color:#1e90ff8c;'>".$row["option_requested_by"]."</a>
                                    </div>
                                    <div class='poption-added-at' style='text-align:right;color:#64646485;'>
                                        20-1-2019
                                    </div>
                                        <div style='padding: 0px 5px;font-size:14px;font-style:italic;'>
                                            <button style='background:dodgerblue' onclick='allowOption(this.parentElement.parentElement)'>Allow</button>
                                            <button style='background:lightcoral' onclick='deleteOption(this.parentElement.parentElement)'>Delete</button>
                                        </div>
                                </div>
                                ";
                            }
                        ?>
                    </div>                    
                </div>
            <!-- /this is a list contains all the  proposed options for your poll -->
            
            

            <!-- this is a list contains all the polls that you have votted -->
            <div id="votted-poll-list">
                <div class="vpl-header">
                    Polls You Have Votted <span title="click" style='transform:rotate(0deg)' onclick='mmVottedList(this)'>▼</span>
                </div>
                <div class="vpl-body">
                    <?php
                        while( $v = $voted_polls->fetch(PDO::FETCH_OBJ) ) {
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
       
            <?php require_once("components/modals.php") ?>
        </div>
    </body>
</html>