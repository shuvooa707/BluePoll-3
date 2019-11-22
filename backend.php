<?php session_start(); require_once("helper.php"); require_once("db.php");


    if( isset($_POST["operation"]) && $_POST["operation"] == "login" ) {
        sleep(2);
        $conn = new mysqli("localhost","root","","bluepoll");
        $username = $_POST["username"];
        $password = $_POST["password"];
        $sql = "SELECT `user_password`,`user_id`,CONCAT(user_firstname,' ', user_lastname) AS user_fullname from `users` Where `user_username`='$username' LIMIT 1";
        $r = $conn->query($sql)->fetch_assoc();
        $fetched_pass = $r["user_password"];
        if( password_verify($password, $fetched_pass) ){            
            $_SESSION["pollsite_username"] = $username;          
            $_SESSION["pollsite_user_id"] = $r["user_id"]; 
            $_SESSION["pollsite_userfullname"] = $r["user_fullname"];
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
        // $comment_content = $_POST["comment_content"] ;
        $comment_content = htmlentities( $_POST["comment_content"] );
        $user_id = $_SESSION["pollsite_user_id"];
        $conn = new mysqli("localhost","root","","bluepoll");
        $sql = "INSERT INTO `comments`(`comment_poll_id`, `comment_content`, `comment_user_id`, `comment_order`) VALUES ($poll_id,'$comment_content',$user_id,1)";
        $r = $conn->query($sql);
        if( $r ) {
            
            registerNotification($user_id,"newComment",$poll_id,$conn->insert_id);

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

    // registering a new vote
    if( issLoggedIn() && isset($_POST["operation"]) && $_POST["operation"] == "updateOptionVote" ) {
        sleep(2);
        $pollID = $_POST["pollID"];
        $optionID = $_POST["optionID"];
        $checked = $_POST["checked"];
        $db = new db();
        $result = $db->updateOptionVote( $optionID,$pollID,$checked,$_SESSION["pollsite_user_id"] ); // = insert_id
        if( $result ) {
            registerNotification($_SESSION["pollsite_user_id"],"newVote",$pollID,$result);
            echo "vote registered";
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
        // sleep(2);
        $conn = new mysqli("localhost","root","","bluepoll");
        $poll_user_id = $_SESSION["pollsite_user_id"];
        $poll_id = $_GET["pollid"];
        $r = $conn->query("DELETE FROM polls WHERE poll_id=$poll_id AND poll_user_id=$poll_user_id");
        if( $r ) {
            echo json_encode( array("is_poll_deleted"=>1) );
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
            $r = $conn->query( "INSERT INTO `polls`(`poll_id`,`poll_category`, `poll_user_id`, `poll_name`) VALUES ('$poll_id','$pollCategory', $poll_user_id,'$pollTitle')" ); 
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
        // $conn = (new db())->conn;
        $conn = new mysqli("localhost","root","","bluepoll");
        $poll_id = $_GET["pollid"];
        $poll_liker_id = $_SESSION["pollsite_user_id"];
        $sql = "SELECT count(*) AS found FROM likes WHERE like_poll_id=$poll_id AND like_liker_id=$poll_liker_id";
        $r = $conn->query($sql)->fetch_assoc()["found"];
        // print_r($r->num_rows);
        if( $r ) {
            echo "Poll Can Be Liked Only Once";
            exit(0);
        }
        $sql = "INSERT INTO likes (`like_poll_id`, `like_liker_id`) VALUES($poll_id,$poll_liker_id)";        
        $r = $conn->query( $sql );
        $like_id = $conn->insert_id;
        if( $r ) {
            registerNotification($_SESSION["pollsite_user_id"],"newLike",$poll_id,$like_id,null);
            $r = $conn->query( "SELECT COUNT(*) AS likes , (SELECT COUNT(*) FROM dislikes WHERE dislikes.dislike_poll_id = $poll_id) AS dislikes FROM likes WHERE likes.like_poll_id = $poll_id" )->fetch_object();
            // print_r($r->likes);
            echo json_encode( array("status"=>"liked","likes"=>$r->likes,"dislikes"=>$r->dislikes) );
            // print_r();
        } else {
            echo $sql;
        }
        exit(0);
    }
    if( isset($_GET["operation"]) && $_GET["operation"] == "dislikePoll" && isset($_GET["pollid"]) && issLoggedIn() ) {
        // $conn = (new db())->conn;
        $conn = new mysqli("localhost","root","","bluepoll");
        $poll_id = $_GET["pollid"];
        $poll_liker_id = $_SESSION["pollsite_user_id"];
        $sql = "SELECT count(*) AS found FROM dislikes WHERE dislike_poll_id=$poll_id AND dislike_disliker_id=$poll_liker_id";
        $r = $conn->query($sql)->fetch_assoc()["found"];
        if( $r ) {
            echo "Poll Can Be DisLiked Only Once";
            exit(0);
        }

        $poll_disliker_id = $_SESSION["pollsite_user_id"];
        $sql = "INSERT INTO dislikes (`dislike_poll_id`, `dislike_disliker_id`) VALUES($poll_id,$poll_disliker_id)";        
        $r = $conn->query( $sql );
        $dislike_id = $conn->insert_id;
        if( $r ) {
            registerNotification($_SESSION["pollsite_user_id"],"newDislike",$poll_id,$dislike_id,null);
            $r = $conn->query( "SELECT COUNT(*) AS likes , (SELECT COUNT(*) FROM dislikes WHERE dislikes.dislike_poll_id = $poll_id) AS dislikes FROM likes WHERE likes.like_poll_id = $poll_id" )->fetch_object();
            // print_r($r->likes);
            echo json_encode( array("status"=>"disliked","likes"=>$r->likes,"dislikes"=>$r->dislikes) );
            // print_r();
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

    if( isset($_POST["operation"]) && $_POST["operation"] == "deleteNotification" && issLoggedIn() ) {
        sleep(2);
        $notificationid = $_POST["notificationid"];
        $conn = (new db())->conn;
        $r = $conn->query("delete from notifications where notification_id=".$notificationid);
        if( $r ) {
            echo "n deleted";
        } else {
            echo $r;
        }
        exit(0);
    }

    if( isset($_POST["operation"]) && $_POST["operation"] == "changeVisibility" && issLoggedIn() ) {
        sleep(2);
        $pollID = $_POST["poll_id"];
        $conn = (new db())->conn;
        for ($i=0; $i < 1; $i++) { 
            $initial_status = $conn->query("SELECT poll_status FROM polls WHERE poll_id=".$pollID)->fetch(PDO::FETCH_ASSOC)["poll_status"];
            $begin = $conn->query("BEGIN");
            $q = $conn->query("UPDATE polls SET poll_status = !poll_status WHERE poll_id=".$pollID);
            $final_status = $conn->query("SELECT poll_status FROM polls WHERE poll_id=".$pollID)->fetch(PDO::FETCH_ASSOC)["poll_status"];

            if( $initial_status != $final_status ) {
                $com = $conn->query("COMMIT;");
                echo json_encode( array("status"=>"visibilityChanged","poll_is"=>$final_status) );
                break;
            } else {
                $rb = $conn->query("ROLLBACK;");
            }                                    
        }

        exit(0);
    }

    if( isset($_POST["operation"]) && $_POST["operation"] == "changeAllowOption" && issLoggedIn() ) {
        // sleep(2);
        $user_id = $_SESSION["pollsite_user_id"];
        $poptionid_id = filter_var($_POST["poptionid_id"], FILTER_VALIDATE_INT);
        $conn = (new db())->conn;
        $sql = "SELECT poll_user_id FROM polls where polls.poll_id = (select proposed_option_poll_id from proposed_option where proposed_option_id = ".$poptionid_id." LIMIT 1)";
        $u = $conn->query($sql)->fetch(PDO::FETCH_ASSOC)["poll_user_id"];
        // poll owner and the allower is the same
        if ( $u == $user_id ) {
            for ($i=0; $i < 3; $i++) { 
                $conn->query("BEGIN");
                $sql = "SELECT * FROM proposed_option where proposed_option_id = ".$poptionid_id." LIMIT 1";
                // echo $sql;
                $r = $conn->query($sql);
                if( $r ) {
                    $r = $r->fetch(PDO::FETCH_ASSOC);
                    $sql = "INSERT INTO options VALUES (null,'".$r['proposed_option_name']."',".$r['proposed_option_poll_id'].",".$r['proposed_option_poll_added_by'].",null,0,null)";
                    $r = $conn->query($sql);
                    if( $r ) {
                        $sql = "DELETE FROM `proposed_option` WHERE proposed_option_id = ".$poptionid_id;
                        $r = $conn->query($sql);
                        if( $r ) {
                            $conn->query("COMMIT");
                            echo "option added";
                            break;
                        }
                    } else {
                        $conn->query("ROLLBACK");
                        echo "option not added";
                    }
                }
            }
        }
        // echo $sql;            

        exit(0);
    }
    
    if( isset($_POST["operation"]) && $_POST["operation"] == "deleteRequestedOption" && issLoggedIn() ) {
        sleep(2);
        $conn = (new db())->conn;    
        $poptionid_id = filter_var($_POST["poptionid_id"], FILTER_VALIDATE_INT);
        $sql = "DELETE FROM `proposed_option` WHERE proposed_option_id = ".$poptionid_id;
        $r = $conn->query($sql);
        if ( $r ) {
            echo "option deleted";
        } else {
            echo "option not deleted";
        }
    }

    if( isset($_POST["operation"]) && $_POST["operation"] == "requestNewOption" && issLoggedIn() ) {
        // $optionid = $_POST["optionid"];
        $user_id = $_SESSION["pollsite_user_id"];  
        $pollid = $_POST["pollid"];
        $optionname = htmlentities($_POST["optionname"]);
        
        $conn = (new db())->conn;  
        $sql = "INSERT INTO `proposed_option` VALUES (null,'".$optionname."',".$pollid.",".$user_id.",null)";
        $r = $conn->query($sql);  
        if ( $r ) {
            echo "option requested";
            // print_r( $conn->lastInsertId() );
            registerNotification($user_id,'newOptionRequest',$pollid,$conn->lastInsertId());
        } else {
            echo $sql;
        }
    }

?>


<?php
    function registerNotification($user_id,$type,$pollID,$objectID) {
        $conn = (new db())->conn;
        if ( $type == "newVote" ) {
            $sqlString = "INSERT INTO notifications VALUES(null,$user_id,'$type',$pollID,$objectID,null)";            
        } else if( $type == "newComment" ) {            
            $sqlString = "INSERT INTO notifications VALUES(null,$user_id,'$type',$pollID,$objectID,null)";
        } else if( $type == "newLike" ) {
            $sqlString = "INSERT INTO notifications VALUES(null,$user_id,'$type',$pollID,$objectID,null)";
        } else if( $type == "newDislike" ) {
            $sqlString = "INSERT INTO notifications VALUES(null,$user_id,'$type',$pollID,$objectID,null)";
        } else if( $type == "newOptionRequest" ){
            $sqlString = "INSERT INTO notifications VALUES(null,$user_id,'$type',$pollID,$objectID,null)";
        }
        $r = $conn->query($sqlString);
        if( !$r )
            return $sqlString;
        return $r;
    }
?>