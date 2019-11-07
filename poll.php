<?php require_once("db.php"); require_once("helper.php");
    if ( isset($_GET["pollid"]) ) {
        $pollid = $_GET["pollid"];
        $poll = (new db())->single("polls",$pollid)->fetch(PDO::FETCH_OBJ);
        if( !isset($poll->poll_id) ) {
            header("Location:index.php");
        }
        $options = (new db())->singlePollOptions("options",$pollid);
        $comments = (new db())->allPollComments("comments",$pollid);
    } else {
        header("Location:index.php");
    }
    // echo $polls;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
        <link rel="stylesheet" href="css/poll.css">
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

        <div class="main-content">
            <?php
                if( checkAuthority( $pollid ) ) {
                    $editButton = "<div style='display:inline-block;padding:10px;text-align:right;padding-right:10px;' title='click to edit this poll' class='edit-poll'>
                                      <a style='padding:3px 6px;background:black;color:white;border-radius:3px;' href='#' onclick='editConf($poll->poll_id)'>Edit</a>
                                   </div>
                                   <div onclick='deletePollConf()' style='display:inline-block;text-align:right;padding-right:10px;' title='click to delete this poll' class='delete-poll'>
                                      Delete
                                   </div>                                   
                                   ";
                } else {
                    $editButton = "";
                }
                    echo 
                        "<div class='poll' data-poll-id='$poll->poll_id'>
                            $editButton
                            <div class='poll-header'>
                                $poll->poll_name <a href='poll.php?pollid=$poll->poll_id' title='Click expand this poll'><span class='expand-poll'>⇱</span></a>
                            </div>
                            <div class='poll-body'>";
                                while( $option = $options->fetch(PDO::FETCH_OBJ) ) {
                                    echo 
                                        "<div class='option' data-option-id='$option->option_id' data-option-vote='$option->option_votes'>
                                            <div class='option-checkbox'>
                                                <input type='checkbox' name='' id='$option->option_id'>
                                            </div>
                                            <div class='option-name' style='background-size: 0%;'>
                                                <div class='name' style='display:inline-block; width:65%;'>$option->option_name</div>
                                                <div class='vote' style='display:inline-block; width:30%;'><strong style='color: #686868;position:absolute;right:5px;' class='vote-percentage'></strong></div>
                                            </div>
                                        </div>";
                                }
                            echo 
                            "<div class='poll-comments'>
                                <div class='poll-comments-header' style='position:relative;'>
                                </div>
                                <div class='poll-comments-body'>
                                    <div class='new-comment'>
                                        <textarea type='text' name='comment-box' id='comment-box' class='comment-box'></textarea>
                                        <button onclick='shootComment(this.parentElement)'>Shoot</button>                        
                                    </div>
                                    <div class='comments'>
                                        <div style='font-family: verdana;word-spacing: -3px;font-size:15px;text-align:left;margin:10px 4px' class=''><span style='padding:3px;background:black;color:white;border-radius:4px;'>Comments</span></div>
                                    ";
                                        while( $comment = $comments->fetch(PDO::FETCH_OBJ) ) {
                                            echo 
                                            "<!-- comment sample -->
                                            <div class='comment' data-comment-id='$comment->comment_id'>
                                            <a href='user.php?userid=$comment->comment_poll_id'>
                                                <div class='commentor-avatar'>
                                                        <img width='60px' height='60px' src='img/profile/$comment->commentor_id.jpg' alt='Commentor Picture'>
                                                </div>
                                            </a>
                                                <div class='comment-body'>
                                                    <a href='user.php?userid=$comment->commentor_id' style='' class='user-link'>$comment->comment_user_fullname</a>
                                                    $comment->comment_content
                                                </div>
                                            </div>
                                            <!-- /comment sample -->";
                                        }
                                    echo 
                                    "</div>
                                </div>
                            </div>
                        </div>
                        <!-- /sample poll -->
                    </div>";
        ?>

    </div>
    
    <div class="sidebar">
        
    </div>
        
    <?php require_once("components\modals.php"); ?>
    </body>
</html>