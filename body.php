<div class="container">

    <div class="main-content">
    <?php 
        require_once("db.php");
        require_once("helper.php");

        $db = new db();
        $polls = $db->getAllPoll();
        foreach( $polls as $poll ) {
            // print_r($polls);
            $poll_id = $poll['poll_id'];
            $poll_name = $poll['poll_name'];
            $poll_id = $poll['poll_id'];
            $poll_options = $poll['options'];
            $poll_comments = $poll['comments'];
            $poll_user_id = $poll['poll_user_id'];
            $poll_creator_name = $poll['poll_creator_name'];
            $poll_created_at = date_format(date_create(explode(" ",$poll["poll_created_at"])[0]),"d-m-y ");
            // $pollLikes = ($poll["poll_likes"] / ($poll["poll_likes"] + $poll["poll_dislikes"])) * 100;
            $pollLikes = $poll["poll_likes"] == 0 ? 50 : $poll["poll_likes"];
            $pollDislikes = $poll["poll_dislikes"] == 0 ? 50 : $poll["poll_dislikes"];
            if ( 1 && $poll["owned"] ) {
                $deleteButton = "<span onclick='deleteComment(this.parentElement.parentElement.parentElement)' class='delete-comment' title='Delete This Comment'>⛔</span>";
            } else {
                $deleteButton = "";
            }
            if( $poll["poll_likes"] == 0 && $poll["poll_dislikes"] == 0 ) {
                $pollLikes = 90;
                $pollDislikes = 90;
            } else {
                $pollLikes = (int)(($poll["poll_likes"] / ($poll["poll_likes"] + $poll["poll_dislikes"]) ) * 180);
                $pollDislikes = (int)(($poll["poll_dislikes"] / ($poll["poll_likes"] + $poll["poll_dislikes"]) ) * 180);

            }
        echo "
            <div class='poll' data-poll-id='$poll_id'>
                <div class='poll-header'>
                    <a href='poll.php?pollid=$poll_id' title='Click expand this poll'><span class='expand-poll'>⇱</span></a> 
                    $poll_name 
                </div>
                <div class='poll-body'>";
                while( $option = $poll_options->fetch_assoc() ) {
                    $option_name = substr($option["option_name"],0,95);
                    if( strlen($option["option_name"]) > 100 ) {
                        $option_name = $option_name . "<strong class='show-more-option-name' onclick='showMoreOptionName(this)' style='color:dodgerblue; cursor:pointer;'> ....</strong>";
                        $option_name_full = "<span style='display:none'>".$option["option_name"]."</span>";
                    } else {
                        $option_name_full = "";
                    }
                    $option_id = $option["option_id"];
                    $option_votes = $option["option_votes"];
                    echo 
                        "<div class='option' data-option-id='$option_id' data-option-vote='$option_votes'>
                            <div class='option-checkbox'>
                                <input type='checkbox' name='option-checkbox' class='option-checkbox-element' disabled>
                            </div>
                            <div class='option-name' style='background-size:100%;'>
                                <div class='name' style='display:inline-block; width:65%;'>$option_name</div>
                                <div class='vote' style='display:inline-block;width:30%;position: absolute;top: 13px;'>
                                    
                                    <strong title='Click to See Who Votted' style='cursor:pointer;color:#1e90ffc2;position:absolute;right:5px;' class='vote-percentage' onclick='showVotersList(this.parentElement.parentElement.parentElement)'>
                                    </strong>
                                </div>
                            </div>
                        </div>";
                }
                echo "
                
                <div class='poll-addnew-option-box' style='display:none;'>
                    <input type='text'  class='add-new-option' >
                    <button  class='add-new-option-button'>+</button>
                </div>
                <div class='poll-info-box'>
                    <div class='left'>
                        <img src='img/profile/$poll_user_id.jpg' width='50px' height='50px' >
                        <a href='user.php?userid=$poll_user_id' style='font-size:12px;display:block;'>$poll_creator_name</a>
                    </div>
                    <div class='right'>
                        <div class='vote-line' style='border-left-width:".$pollLikes."px;border-right-width:".$pollDislikes."px;'></div>
                        <div class='vote-like-dislike-box'>
                            <div class='poll-like-button' onclick='likeDislike( \"like\",$poll_id,this.parentElement.previousElementSibling )'>Like</div>
                            <div class='poll-dislike-button' onclick='likeDislike( \"dislike\",$poll_id,this.parentElement.previousElementSibling )'>Dislike</div>
                            <small class='poll-birthdate'>$poll_created_at</small>
                        </div>                        
                    </div>
                </div>
                </div>

                <div class='poll-comments'>
                    <!---<div class='poll-comments-header' style='position:relative;'>
                    </div> -->
                    <div class='poll-comments-body'>
                        <div class='new-comment'>
                            <textarea placeholder='write a comment' type='text' name='comment-box' id='comment-box'></textarea>
                            <button onclick='shootComment(this.parentElement)' id='shootButton' style='background: -webkit-linear-gradient(top, #f8f8f8, #d8d8d8);'>SHOOT</button>                        
                        </div>
                        <div class='comments'>
                            <div style='font-family: verdana;word-spacing: -3px;background:corflowerblue;font-size:15px;text-align:left;margin:5px 4px' class=''>Comments</div>
                            <!-- comment sample -->";

                            while( $comment = $poll_comments->fetch_assoc() ) {
                                $comment_user_fullname = $comment["comment_user_fullname"];
                                $comment_content = $comment["comment_content"];
                                $comment_id = $comment["comment_id"];
                                $commentor_id = $comment["comment_user_id"];
                                if ( issLoggedIn() && ($comment["comment_user_id"] == $_SESSION["pollsite_user_id"]) ) {
                                    $deleteButton = "<span onclick='deleteComment(this.parentElement.parentElement.parentElement)' class='delete-comment' title='Delete This Comment'>⛔</span>";
                                } else if ( !$poll["owned"] ){
                                    $deleteButton = "";
                                } else {

                                }
                                echo 
                                    "<div class='comment' data-comment-id='$comment_id'>
                                        <div class='commentor-avatar'>
                                            <a href='user.php?userid=$commentor_id'>
                                                <img width='60px' height='60px' src='img/profile/$commentor_id.jpg' alt=''>
                                            </a>
                                        </div>
                                        <div class='comment-body'>
                                            <a href='user.php?userid=$commentor_id' style='' class='user-link'>$comment_user_fullname</a>
                                            <span class='comment-excerpt'>
                                                $comment_content
                                                <span class='show-more' title='click to read full comment'></span>
                                                $deleteButton
                                            </span>                                            
                                        </div>
                                    </div>";
                            }
                            echo "
                            <!-- /comment sample -->
                        </div>
                    </div>
                </div>
            </div>";

        }
    ?>
    </div>
    <div class="sidebar">
        
    </div>
    
</div>

<script>
    function checkIsLoggedIn() {
        return <?php if( issLoggedIn() ) echo 1; else echo 0;  ?>
    }
</script>

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
