<?php require_once("db.php"); require_once("helper.php");
    if ( isset($_GET["pollid"]) ) {
        $pollid = $_GET["pollid"];
        $poll = (new db())->single("polls",$pollid)->fetch(PDO::FETCH_OBJ);
        if ( $poll->poll_status == "private" ) {
            header("Location:index.php");
        }
        if( $poll->poll_likes == 0 && $poll->poll_dislikes == 0 ) {
                $pollLikes = 90;
                $pollDislikes = 90;
        } else {
            $pollLikes = (int)(($poll->poll_likes / ($poll->poll_likes + $poll->poll_dislikes) ) * 180);
            $pollDislikes = (int)(($poll->poll_dislikes / ($poll->poll_likes + $poll->poll_dislikes) ) * 180);
        }

        $have_liked = (isset( $poll->liked ) && $poll->liked == 1) ? "liked" : "";
        $have_disliked = (isset( $poll->disliked ) && $poll->disliked == 1 ) ? "disliked" : "";

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
<?php
    // delete seen notification
    if( issLoggedIn() && isset($_GET["nitification"]) ) {
        $notificationid = $_GET["nitification"];
        $conn = (new db())->conn;
        $conn->query("DELETE FROM notifications WHERE notification_id=".$notificationid);
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
        <link rel="stylesheet" href="css/poll.css">

        
        <script src="js/poll.js" defer></script>
        <script src="js/common.js" defer></script>
        <title><?php echo $poll->poll_name; ?></title>
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
                                      <a style='padding:3px 6px;background:black;color:white;border-radius:3px;' href='#' onclick='editConf($poll->poll_id)'>
                                        Edit<span style='' class='flaticon-edit'></span> 
                                      </a>
                                   </div>
                                   <div onclick='deletePollConf()' style='display:inline-block;text-align:right;padding-right:10px;' title='click to delete this poll' class='delete-poll'>
                                      Delete<span style='' class='flaticon-garbage'></span> 
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
                                                <div class='vote' style='display:inline-block; width:30%;'>
                                                    <strong title='Click to See Who Votted' style='cursor:pointer;position:absolute;right:5px;' class='vote-percentage' onclick='showVotersList(this.parentElement.parentElement.parentElement)'>50%</strong>
                                                </div>
                                            </div>
                                        </div>";
                                }

                            $poll_created_at = date_format(date_create(explode(" ",$poll->poll_created_at)[0]),"d - m - Y ");
                            echo 
                            "
                            
                            <div class='poll-info-box'>
                                <div class='left'>
                                    <img src='img/profile/$poll->poll_user_id.jpg' width='60px' height='60px' >
                                    <a href='user.php?userid=$poll->poll_user_id' style='font-size:12px;display:block;'>$poll->poll_creator_name</a>
                                </div>
                                <div class='right'>
                                    <div class='line'>
                                        <div class='left' style='width:".$pollLikes."px;'></div>
                                        <div class='right' style='width:".$pollDislikes."px;'></div>
                                    </div>   
                                    <div class='vote-like-dislike-box'>
                                        <div class='poll-like-button $have_liked' onclick='likeDislike( \"like\",$poll->poll_id,this.parentElement.previousElementSibling,this )'>
                                            <span style='' class='flaticon-like'></span>                                            
                                        </div>
                                    <div class='poll-dislike-button $have_disliked' onclick='likeDislike( \"dislike\",$poll->poll_id,this.parentElement.previousElementSibling,this )'>
                                            <span style='' class='flaticon-dislike'></span>                                        
                                    </div>
                                </div>
                                </div>                                
                                <div class='poll-tag-date'>
                                    <small class='poll-tags'><a  style='font-family:monospace;' href='search.php?searchkey=$poll->poll_category'>#$poll->poll_category</a></small>
                                    <small class='poll-birthdate'>$poll_created_at</small>
                                </div>
                            </div>
                            </div>
                                                                        
                            <div class='poll-comments'>
                                <div class='poll-comments-body'>
                                    <div class='new-comment'>
                                        <textarea type='text' name='comment-box' id='comment-box' class='comment-box'></textarea>
                                        <button onclick='shootComment(this.parentElement)'>Shoot</button>                        
                                    </div>
                                    <div class='comments'>
                                        <div class='comment-lebel'>Comments</span></div>
                                    ";
                                    // var_dump($comments);
                                        foreach( $comments as $comment ) {
                                            $comment_id = $comment["comment_id"];
                                            $comment_poll_id = $comment["comment_poll_id"];
                                            $commentor_id = $comment["commentor_id"];
                                            $comment_user_fullname = $comment["comment_user_fullname"];
                                            $comment_content = $comment["comment_content"];
                                            $comment_poll_id = $comment["comment_poll_id"];
                                            if( $comment["canDelete"] ) {
                                                $deleteButton = "<span onclick='deleteComment(this.parentElement.parentElement.parentElement)' class='delete-comment' title='Delete This Comment'>
                                                                    <span style='' class='flaticon-garbage'></span>
                                                                 </span>";                                    
                                            } else {
                                                $deleteButton = "";
                                            }
                                            echo 
                                            "<!-- comment sample -->
                                            <div class='comment' data-comment-id='$comment_id'>
                                            <a href='user.php?userid=$comment_poll_id'>
                                                <div class='commentor-avatar'>
                                                        <img width='60px' height='60px' src='img/profile/$commentor_id.jpg' alt='Commentor Picture'>
                                                </div>
                                            </a>
                                                <div class='comment-body'>
                                                    <a href='user.php?userid=$commentor_id' style='' class='user-link'>$comment_user_fullname</a>
                                                    <span class='comment-excerpt'>
                                                        $comment_content
                                                        <span class='show-more' title='click to read full comment'></span>
                                                        $deleteButton
                                                    </span> 
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

    <?php require_once("sidebar.php"); ?>        

    </div>
    <!-- Who Votted List Popup Window -->
        <div class="whoVotted-overlay">
            <div class="whoVotted-container">
                <div class="whoVotted-header">
                    Voters List
                    <span title='close' class="close-whoVotted-container" onclick="closeVotterList()">X</span>
                </div>
                <div class="voters">
                    <!-- <div class="voter">
                        <a href="user.php?userid=4">
                            <img width="40px" height="40px" src="img\profile\3.jpg" alt="">
                        </a>
                        <strong title='Shuvo Sarker'><a href="user.php?userid=4">Shuvo Sarker</a></strong>
                    </div> -->
                </div>
            </div>
        </div>
        <!-- /Who Votted List Popup Window -->


    <script>
        function checkIsLoggedIn() {
            return <?php if( issLoggedIn() ) echo 1; else echo 0;  ?>
        }
    </script>
        
    <?php require_once("components\modals.php"); ?>
    </body>
</html>