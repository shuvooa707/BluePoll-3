<?php session_start(); require_once("db.php"); require_once("helper.php");

    if( !issLoggedIn() ) {
        header("Location:index.php");
    }
    // getting all the votes that this users has given
    $conn = (new db())->conn;
    $user_id = $_SESSION["pollsite_user_id"];

    // fetching all the polls
    $sql = "SELECT *, (SELECT COUNT(*) FROM options WHERE options.option_belongsto = polls.poll_id) AS option_count, (SELECT COUNT(*) FROM votes WHERE votes.vote_poll_id = polls.poll_id) AS vote_count, (SELECT COUNT(*) FROM comments WHERE comments.comment_poll_id = polls.poll_id) AS comment_count, (SELECT COUNT(*) FROM likes WHERE likes.like_poll_id = polls.poll_id) AS like_count, (SELECT COUNT(*) FROM dislikes WHERE dislikes.dislike_poll_id = polls.poll_id) dislike_count FROM polls WHERE poll_user_id=".$user_id;
    $polls = $conn->query($sql);
    
    $sql = "SELECT *, (SELECT polls.poll_name FROM polls WHERE polls.poll_id = options.option_belongsto limit 1) AS poll_name, (SELECT poll_id FROM polls WHERE polls.poll_id = options.option_belongsto limit 1) AS poll_id FROM options JOIN votes ON options.option_id = votes.vote_option_id WHERE votes.vote_given_by = $user_id";
    $voted_polls = $conn->query($sql);


    $sql = "SELECT DISTINCT poll_id, poll_name, proposed_option_name, proposed_option_poll_added_by,proposed_option_id, (SELECT CONCAT(users.user_firstname, ' ',users.user_lastname) FROM users WHERE users.user_id = proposed_option.proposed_option_poll_added_by) AS option_requested_by FROM proposed_option,polls,users WHERE proposed_option_poll_id = polls.poll_id AND polls.poll_user_id = $user_id";
    $option_requests = $conn->query($sql);
    // echo $polls;

    // fetching all the comments
    $sql = "SELECT * , (SELECT users.user_firstname FROM users WHERE users.user_id = comments.comment_user_id ) as commentor_name FROM `comments` JOIN polls ON polls.poll_id = comments.comment_poll_id WHERE polls.poll_user_id = $user_id";
    $comments = $conn->query($sql);


    // fetching all the option requests
    $sql = "SELECT DISTINCT proposed_option.proposed_option_poll_created_at AS proposed_created_at, poll_id, poll_name, proposed_option_name, proposed_option_poll_added_by,proposed_option_id, (SELECT CONCAT(users.user_firstname, ' ',users.user_lastname) FROM users WHERE users.user_id = proposed_option.proposed_option_poll_added_by) AS option_requested_by FROM proposed_option,polls,users WHERE proposed_option_poll_id = polls.poll_id AND polls.poll_user_id = $user_id";
    $option_requests = $conn->query($sql);

    
    // get Saved Poll of this user  
    if( issLoggedIn() && $user_id == $_SESSION["pollsite_user_id"] ) {
        $sql = "SELECT * FROM polls JOIN saved_polls ON polls.poll_id = saved_polls.poll_id WHERE saved_polls.user_id = ".$_SESSION["pollsite_user_id"];
        $saved_polls = $conn->query($sql);
        // var_dump($saved_polls);
    }

    // get Hidden Poll of this user  
    if( issLoggedIn() && $user_id == $_SESSION["pollsite_user_id"] ) {
        $sql = "SELECT * FROM polls JOIN hidden_polls ON polls.poll_id = hidden_polls.poll_id WHERE hidden_polls.user_id = ".$_SESSION["pollsite_user_id"];
        $hidden_polls = $conn->query($sql);
    }

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
            <div class="menu">
                <div class="header">
                    MENU
                </div>
                <div class="body">
                    <div class="menu-item active" onClick="changeTab(this)" data-tab-name="polls">Polls</div>
                    <div class="menu-item" onClick="changeTab(this)" data-tab-name="comments">Comments</div>
                    <div class="menu-item" onClick="changeTab(this)" data-tab-name="requests">Option Requests</div>
                    <div class="menu-item" onClick="changeTab(this)" data-tab-name="notifications">Notifications </div>
                    <div class="menu-item" onClick="changeTab(this)" data-tab-name="saved">Saved Polls </div>
                    <div class="menu-item" onClick="changeTab(this)" data-tab-name="hidden">Hidden Polls </div>
                    <div class="menu-item" onClick="changeTab(this)" data-tab-name="reports">Reports</div>
                </div>
            </div> 
            <div class="body">
                <div class="tab polls show">  
                    <table class="polls-table">
                        <thead>
                            <tr>
                                <th  style='min-width:40px;'> 
                                    <input type="checkbox" name="" id="">
                                </th>
                                <th  style='min-width:40px;'> # </th>
                                <th  style="min-width:300px!important;max-width:300px!important">Polls</th>
                                <th>Date</th>
                                <th>Options</th>
                                <th>Votes</th>
                                <th>Comments</th>
                                <th>Likes</th>
                                <th>Dislikes</th>
                                <th style="max-width:200px!important">Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $poll_number = 0;
                                while( $poll = $polls->fetch(PDO::FETCH_ASSOC) ) {
                                    $poll_number++;
                                    $poll_status = $poll["poll_status"];
                                    if ( $poll_status == 1 ) {
                                        $bgcolor = "dodgerblue";
                                        $poll_status = "Public";
                                    } else {
                                        $bgcolor = "red";
                                        $poll_status = "Private";
                                    }
                                    echo "                                        
                                        <tr  class='poll' data-poll-id='".$poll['poll_id']."'>                                        
                                            <td>
                                                <input type='checkbox' >
                                            </td>
                                            <td style='max-width:70px;'>$poll_number</td>
                                            <td style='max-width:300px!important'>
                                                <a href='poll.php?pollid=".$poll['poll_id']."'>
                                                    ".$poll['poll_name']."
                                                </a>
                                            </td>
                                            <td>
                                                <span class='detail-element date'>". dateHelper( $poll["poll_created_at"] )."</span>
                                            </td>
                                            <td>
                                                <span class='detail-element options'>".$poll["option_count"]."</span>
                                            </td>
                                            <td>
                                                <span class='detail-element votes'>".$poll["vote_count"]."</span>
                                            </td>
                                            <td>
                                                <span class='detail-element commnets'>".$poll["comment_count"]."</span>
                                            </td>
                                            <td>
                                                <span class='detail-element likes'>".$poll["like_count"]."</span>
                                            </td>
                                            <td>
                                                <span class='detail-element dislikes'>".$poll["dislike_count"]."</span>
                                            </td>
                                            <td  style='max-width:200px!important'>
                                                <button class='delete' onclick='deletePollConf(this.parentElement.parentElement)'>Delete</button>   
                                                <button class='private' style='width:74px; padding:5px 0px;background:$bgcolor;' onclick='changeVisibility(this.parentElement.parentElement,this)'>".$poll_status."</button>
                                                <br>
                                                <button class='edit' onclick='editConf()'>Edit</button>
                                                <button class='analyze' onclick='analyze(this.parentElement.parentElement)'>Analyze</button>
                                            </td>
                                        </tr>
                                    ";
                                }

                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab comments">
                    <table class="comments-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style='width:400px'>Comment</th>
                                <th>User</th>
                                <th>Date</th>
                                <th>Poll</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr style="display:none;max-height:30px;width:400px;font-size: 24px;font-weight: bolder;font-family: fantasy;color: dodgerblue;/* transform: scale(2.5,.9); */">
                                <td> 
                                    <input type="checkbox" name="" id="">
                                </td>
                                <td style='width:400px'>↑</td>
                                <td>↑</td>
                                <td>↑</td>
                                <td>↑</td>
                                <td>↑</td>
                            </tr>
                            <?php 
                                while( $comment = $comments->fetch(PDO::FETCH_ASSOC) ) {
                                    $comment_name = $comment["comment_content"];
                                    $comment_id = $comment["comment_id"];
                                    $commentor_name = $comment["commentor_name"];
                                    $commentor_id = $comment["comment_user_id"];
                                    $date = dateHelper( $comment["comment_birthdate"] );
                                    $poll_name = $comment["poll_name"];
                                    $poll_id = $comment["poll_id"];
                                    echo "
                                        <tr data-comment-id='$comment_id' >
                                            <td>1</td>
                                            <td style='width:400px'>$comment_name</td>
                                            <td>
                                                <a href='user.php?userid=$commentor_id'>$commentor_name</a>
                                            </td>
                                            <td>$date</td>
                                            <td>
                                                <a href='poll.php?pollid=$poll_id'>$poll_name</a>
                                            </td>
                                            <td>
                                                <button class='delete' onclick='deleteComment(this.parentElement.parentElement)'>Delete</button>   
                                            </td>
                                        </tr>
                                    ";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab requests">
                    <table class="requests-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style='width:300px'>Requesting Option </th>
                                <th style="width:300px">On Poll</th>
                                <th>User</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                while ( $row = $option_requests->fetch(PDO::FETCH_ASSOC) ) {
                                    $proposed_option_name = $row["proposed_option_name"];
                                    $proposed_option_id = $row["proposed_option_id"];
                                    $poll_name = $row["poll_name"];
                                    $poll_id = $row["poll_id"];
                                    $option_requested_by = $row["option_requested_by"];
                                    $proposed_created_at = $row["proposed_created_at"];
                                    echo "
                                        <tr data-poption-id='$proposed_option_id'>
                                            <td>1</td>
                                            <td style='width:200px'>$proposed_option_name</td>
                                            <td>
                                                <a href='poll.php?pollid=$poll_id'>$poll_name</a>
                                            </td>
                                            <td>
                                                <a href='user.php?userid='>$option_requested_by</a>
                                            </td>
                                            <td>$proposed_created_at</td>
                                            <td>
                                                <button class='accept' onclick='allowOption(this.parentElement.parentElement,$proposed_option_id)'>Accept</button>   
                                                <button class='reject' onclick='deleteOption(this.parentElement.parentElement,$proposed_option_id)'>Reject</button>   
                                            </td>
                                        </tr>
                                    ";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab reports">
                    <h2>Reports</h2>
                </div>
                <div class="tab notifications">
                    <h2>Notifications</h2>
                </div>


                <div class="tab saved">
                    <h2>Saved Polls</h2>
                    <table class="saved-table">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="select-all-saved" id="select-all-saved">
                                </th>
                                <th>#</th>
                                <th style='width:200px'>Poll Name </th>
                                <th>Date Saved</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php                
                                if( issLoggedIn() && $user_id == $userid ) {  
                                    $poll_number = 0;
                                    while( $savedPoll = $saved_polls->fetch(PDO::FETCH_OBJ) ) {
                                        $poll_number++;
                                        echo 
                                            "<tr data-poll-id='$savedPoll->poll_id' >
                                                <td>
                                                    <input type='checkbox' >
                                                </td>
                                                <td>$poll_number</td>
                                                <td style='width:600px'>
                                                    <a href='poll.php?pollid=$savedPoll->poll_id'>$savedPoll->poll_name</a>
                                                </td>
                                                <td>$savedPoll->poll_created_at</td>
                                                <td>
                                                    <button class='delete' onclick='removeSavedPoll(this.parentElement.parentElement)'>Remove</button>   
                                                </td>
                                            </tr>";
                                    }   
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab hidden">
                    <h2>Hidden Polls</h2>
                    <table class="hidden-table">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="select-all-saved" id="select-all-saved">
                                </th>
                                <th>#</th>
                                <th style='width:600px'>Poll Name </th>
                                <th>Date Saved</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php                                    
                        if( issLoggedIn() && $user_id == $userid ) {                        
                            $poll_number = 0;
                            while( $hiddenPoll = $hidden_polls->fetch(PDO::FETCH_OBJ) ) {
                                $poll_number++;
                                echo 
                                    "<tr data-poll-id='$hiddenPoll->poll_id' >
                                        <td>
                                            <input type='checkbox' >
                                        </td>
                                        <td>$poll_number</td>
                                        <td style='width:200px'>
                                            <a href='poll.php?pollid=$hiddenPoll->poll_id'>$hiddenPoll->poll_name</a>
                                        </td>
                                        <td>$hiddenPoll->poll_created_at</td>
                                        <td>
                                            <button onClick='unHidePoll(this.parentElement.parentElement)' style='' class='unhide'>Unhide</button>
                                        </td>
                                    </tr>";
                            }
                        }
                    ?>     
                        </tbody>
                    </table>               
                </div>
            </div>                        
        </div>
        <?php require_once("components/modals.php") ?>
    </body>
</html>