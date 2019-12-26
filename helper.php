<?php require_once("db.php");

    function issLoggedIn() {
        if( session_status() == 1 ) {
            session_start();
        }
        if( isset($_SESSION["pollsite_username"]) && isset($_SESSION["pollsite_user_id"]) ) {
            return 1;
        } else {
            return 0;
        }
    }

    function checkAuthority( $poll_id ) {
        $conn = (new db())->conn;
        if( isset($_SESSION["pollsite_user_id"]) )
            $user_id = $_SESSION["pollsite_user_id"];
        else 
            return 0;
        $r = $conn->query("SELECT COUNT(*) AS YES FROM polls WHERE polls.poll_user_id = $user_id AND polls.poll_id = $poll_id");
        if( $r ){
            return (int)$r->fetch(PDO::FETCH_ASSOC)["YES"];
        } else {
            return 0;
        }
    }

    function hasCommentDeleteAuthority( $comment_id ) {
        $user_id = $_SESSION["pollsite_user_id"];
        $conn = (new db())->conn;
        $ownPoll = $conn->query("SELECT polls.poll_user_id FROM polls WHERE polls.poll_id = (SELECT comments.comment_poll_id FROM comments WHERE comments.comment_id = $comment_id LIMIT 1)");
        $ownComment = $conn->query("SELECT comment_user_id FROM comments WHERE comment_id=$comment_id");
        
        if( $user_id == $ownPoll->fetch(PDO::FETCH_ASSOC)["poll_user_id"] || $ownComment->fetch(PDO::FETCH_ASSOC)["comment_user_id"] == $user_id  ) {
            return true;
        } else {
            return false;
        }
    }

    function hasSingleCommentDeleteAuthority( $comment_id ) {
        $user_id = $_SESSION["pollsite_user_id"];
        $conn = (new db())->conn;
        $r = $conn->query("SELECT polls.poll_user_id FROM polls WHERE polls.poll_id = (SELECT comments.comment_poll_id FROM comments WHERE comments.comment_id = $comment_id LIMIT 1)");
        if( $r ) {
            if( $user_id == $r->fetch(PDO::FETCH_ASSOC)["poll_user_id"] ) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
?>