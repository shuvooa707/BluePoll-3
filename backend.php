<?php session_start(); require_once("helper.php"); require_once("db.php");


    if( isset($_POST["operation"]) && $_POST["operation"] == "login" ) {
        $conn = new mysqli("localhost","root","","bluepoll");

        $username = $_POST["username"];
        $password = $_POST["password"];
        $sql = "SELECT `user_password`,`user_id` from `users` Where `user_username`='$username' LIMIT 1";
        $r = $conn->query($sql)->fetch_assoc();
        $fetched_pass = $r["user_password"];
        if( password_verify($password, $fetched_pass) ){            
            $_SESSION["pollsite_username"] = $username;          
            $_SESSION["pollsite_user_id"] = $r["user_id"]; 
            echo "login success";
            // echo $r["user_id"];
        } else {
            echo "$sql";
        }
        $conn->close();

        exit(0);
    }

    if( isset($_POST["operation"]) && isset($_SESSION["pollsite_user_id"]) && $_POST["operation"] == "saveNewComment"  ) {
        
        $poll_id = (int)$_POST["poll_id"];
        $comment_content = htmlentities( $_POST["comment_content"] );
        $user_id = $_SESSION["pollsite_user_id"];

        $conn = new mysqli("localhost","root","","bluepoll");
        $sql = "INSERT INTO `comments`(`comment_poll_id`, `comment_content`, `comment_user_id`, `comment_order`) VALUES ($poll_id,'$comment_content',$user_id,1)";
        $r = $conn->query($sql);

        if( $r ) {
            echo $conn->insert_id;
        } else {
            echo "commentNotSaved";
        }
        
    }

    if( isset($_POST["operation"]) && $_POST["operation"] == "saveNewPoll" ) {
        $optins = explode("|",$_POST["pollOptions"]);
        $pollTitle = filter_var($_POST["pollTile"], FILTER_SANITIZE_STRING);
        $pollCategory = filter_var($_POST["pollCategory"], FILTER_SANITIZE_STRING);
        $poll_user_id = $_SESSION["pollsite_user_id"];
        $db = new db();
        $result = $db->saveNewPoll( $pollTitle,$poll_user_id,$pollCategory,$optins );
        if( $result ) {
            echo $result;
        } else {
            echo 0;
        }
    }

    if( isset($_POST["operation"]) && $_POST["operation"] == "updateOptionVote" ) {
        sleep(2);
        $optionID = $_POST["optionID"];
        $checked = $_POST["checked"];
        $db = new db();
        $result = $db->updateOptionVote( $optionID,$checked,$_SESSION["pollsite_user_id"] );
        if( $result ) {
            echo $result;
        } else {
            echo 0;
        }
    }
    if( isset($_GET["operation"]) && $_GET["operation"] == "getVotedOptions" ) {
        // echo "xckgmhkx".issLoggedIn();
        if( !issLoggedIn() ) {
            echo "not logged in";
            exit(0);
        }
        $conn = new mysqli("localhost","root","","bluepoll");
        $user_id = $_SESSION["pollsite_user_id"];
        $r = $conn->query("SELECT * from votes where vote_given_by=$user_id");
        $arr = array();
        // print_r($r);
        // exit(0);
        // if( !$r->num_rows() ) {
        //     echo $r->num_rows();
        //     exit(0);
        // }
        // var_dump($r);
        // echo $r->num_rows;
        if( $r->num_rows !=0 ) {
            while( $row = $r->fetch_assoc() ) {
                array_push($arr,$row["vote_option_id"]);
            }
            echo json_encode( $arr );
        } else {
            echo "not found";
        }
        exit(0);
    }

    if( isset($_GET["operation"]) && $_GET["operation"] == "deletePoll" ) {        
        $conn = new mysqli("localhost","root","","bluepoll");
        $poll_user_id = $_SESSION["pollsite_user_id"];
        $poll_id = $_GET["pollid"];
        $r = $conn->query("DELETE FROM polls WHERE poll_id=$poll_id AND poll_user_id=$poll_user_id");
        if( $r ) {
            echo "pollDeleted";
        } else {
            echo "DELETE FROM polls WHERE poll_id=$poll_id AND poll_user_id=$poll_user_id";
        }
    }

    if( isset($_POST["operation"]) && $_POST["operation"] == "updatePoll" ) {                
        $pollTitle = filter_var($_POST["pollTile"], FILTER_SANITIZE_STRING);
        $pollCategory = filter_var($_POST["pollCategory"], FILTER_SANITIZE_STRING);
        $poll_user_id = $_SESSION["pollsite_user_id"];
        $pollOptions = explode("|",$_POST["pollOptions"]);
        $poll_id =  $_POST["pollId"];

        $conn = new mysqli("localhost","root","","bluepoll");
        $r = $conn->query("DELETE FROM polls WHERE poll_id=$poll_id AND poll_user_id=$poll_user_id");
        $r = $conn->query("DELETE FROM options WHERE option_belongsto=$poll_id");

        if( $r ) {
            $r = $conn->query( "INSERT INTO `polls`(`poll_id`,`poll_category`, `poll_user_id`, `poll_name`) VALUES ('$poll_id','tech', $poll_user_id,'$pollTitle')" ); 
            $poll_id = $conn->insert_id;

            if ( $r ) {
                // echo "pollUpdated";
                if( $r && $poll_id ) {
                    foreach( $pollOptions as $option ) {
                        $sqlString = "INSERT INTO `options`(`option_name`, `option_belongsto`, `option_addedby_id`) VALUES ('$option',$poll_id,$poll_user_id)";
                        $conn->query($sqlString);
                    }
                }
                echo $poll_id;
            } else {
                echo "pollNotUpdated";
            }
        } else {
                echo "pollNotUpdated";
        }   
    }

    if( isset($_GET["operation"]) && $_GET["operation"] == "getVoterList" && isset($_GET["optionid"]) ) {
        // echo "fgjkhdr";
        $optionID = $_GET["optionid"];
        $conn = new mysqli("localhost","root","","bluepoll");
        $r = $conn->query("SELECT concat(user_firstname,' ',user_lastname) AS user_name, user_id FROM users JOIN votes ON votes.vote_given_by = users.user_id WHERE votes.vote_option_id = $optionID");

        $arr = array();
        if( $r->num_rows > 0 ) {
            while ( $row = $r->fetch_assoc() ) {
                $arr[] = $row;
            }
            echo json_encode($arr);
        } else {
            echo "no votes";
        }
    }

    if( isset($_GET["operation"]) && $_GET["operation"] == "likePoll" && isset($_GET["pollid"]) && issLoggedIn() ) {
        // echo "fdgjhfd";
        $conn = (new db())->conn;
        $poll_id = $_GET["pollid"];
        $poll_liker_id = $_SESSION["pollsite_user_id"];
        $sql = "INSERT INTO likes (`like_poll_id`, `like_liker_id`) VALUES($poll_id,$poll_liker_id)";        
        $r = $conn->query( $sql );
        if( $r ) {
            echo "liked";
        } else {
            echo $sql;
        }
        exit(0);
    }
    if( isset($_GET["operation"]) && $_GET["operation"] == "dislikePoll" && isset($_GET["pollid"]) && issLoggedIn() ) {
        $conn = (new db())->conn;
        $poll_id = $_GET["pollid"];
        $poll_disliker_id = $_SESSION["pollsite_user_id"];
        $sql = "INSERT INTO dislikes (`dislike_poll_id`, `dislike_disliker_id`) VALUES($poll_id,$poll_disliker_id)";        
        $r = $conn->query( $sql );
        if( $r ) {
            echo "diliked";
        } else {
            echo "$sql";
        }
        exit(0);
    }
    if( isset($_GET["operation"]) && $_GET["operation"] == "deleteComment" && isset($_GET["commentid"]) && issLoggedIn() && hasCommentDeleteAuthority($_GET["commentid"]) ) {
        // echo "dljfgsdk";
        sleep(2);
        $conn = (new db())->conn;
        $commentid = $_GET["commentid"];
        $sql = "DELETE FROM comments WHERE comment_id = $commentid";        
        $r = $conn->query( $sql );
        if( $r ) {
            echo "commentDeleted";
        } else {
            echo "$sql";
        }
        exit(0);
    }

?>