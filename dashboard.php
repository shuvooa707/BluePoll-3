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
            <div class="menu">
                <div class="header">
                    MENU
                </div>
                <div class="body">
                    <div class="menu-item active" onClick="changeTab(this)" data-tab-name="polls">Polls</div>
                    <div class="menu-item" onClick="changeTab(this)" data-tab-name="comments">Comments</div>
                    <div class="menu-item" onClick="changeTab(this)" data-tab-name="requests">Option Requests</div>
                    <div class="menu-item" onClick="changeTab(this)" data-tab-name="reports">Reports</div>
                    <div class="menu-item" onClick="changeTab(this)" data-tab-name="votes">Votes</div>
                    <div class="menu-item" onClick="changeTab(this)" data-tab-name="likes">Likes / Dislikes </div>
                </div>
            </div> 
            <div class="body">
                <div class="tab polls show">                    
                    <h3>Polls</h3>
                    <table class="polls-table">
                        <thead>
                            <tr>
                                <th>Polls</th>
                                <th>Detail</th>
                                <th>Tools</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                        <tr  class='poll' data-poll-id='".$poll['poll_id']."'>
                                            <td>
                                                <a href='poll.php?pollid=".$poll['poll_id']."'>
                                                    ".$poll['poll_name']."
                                                </a>
                                            </td>
                                            <td>
                                                <span class='detail-element votes'>129 Votes</span>
                                                <span class='detail-element options'>99 Options</span><br>
                                                <span class='detail-element comments'>999 Comments</span> 
                                                <span class='detail-element likes'>999 Likes</span><br>
                                                <span class='detail-element dislikes'>999 Dislikes</span>
                                                <span class='detail-element views'>999 Views</span>
                                                <span class='more'>More...</span>
                                            </td>
                                            <td>
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
                    <h2>Comments</h2>
                </div>
                <div class="tab requests">
                    <h2>Option Requests</h2>
                </div>
                <div class="tab reports">
                    <h2>Reports</h2>
                </div>
                <div class="tab votes">
                    <h2>Votes</h2>
                </div>
                <div class="tab likes">
                    <h2>Likes</h2>
                </div>
            </div>                        
        </div>
        <?php require_once("components/modals.php") ?>
    </body>
</html>