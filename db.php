<?php
    class db {
        public $conn;
        public $myconn;
        public function __construct( $host="localhost",$dbname="bluepoll", $username="root", $password="") {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname",$username,$password);
            $this->myconn = new mysqli("localhost","root","","bluepoll");
        }

        public function getAllNotifications(  ) {
            $user_id = isset($_SESSION["pollsite_user_id"]) ? $_SESSION["pollsite_user_id"] : "";
            $sqlString = "SELECT * FROM `notifications` JOIN polls ON polls.poll_id = notification_poll_id WHERE polls.poll_user_id = ".$user_id;
            $result = $this->conn->query($sqlString);
            $notifications = array();
            while ( $notification = $result->fetch(PDO::FETCH_ASSOC) ) {
                if( $notification["notification_action"] == "newVote" ) {
                    $sqlString = "SELECT users.user_id, CONCAT( users.user_firstname, ' ', users.user_lastname ) AS user_name, ( SELECT poll_name FROM polls WHERE polls.poll_id = votes.vote_poll_id ) AS poll_name, vote_poll_id AS poll_id, (SELECT option_id FROM options WHERE options.option_id = votes.vote_option_id) as option_id FROM users, votes WHERE users.user_id = votes.vote_given_by AND vote_id = ".$notification["notification_object_id"];
                    $result1 = $this->conn->query($sqlString);
                    if( $result1 = $result1->fetch(PDO::FETCH_ASSOC) ) {   
                        if( $result1["user_id"] == $user_id ) {
                            continue;
                        }                             
                        $notification["user_id"] = $result1["user_id"];
                        $notification["user_name"] = $result1["user_name"];  
                        $notification["poll_name"] = $result1["poll_name"];  
                        $notification["poll_id"] = $result1["poll_id"];
                        $notification["option_id"] = $result1["option_id"];
                        $notifications[] = $notification;                   
                    }
                }
                if( $notification["notification_action"] == "newComment" ) {
                    $sqlString = "SELECT users.user_id, CONCAT( users.user_firstname, ' ', users.user_lastname ) AS user_name, ( SELECT poll_name FROM polls WHERE polls.poll_id = comments.comment_poll_id ) AS poll_name, comment_poll_id AS poll_id FROM users, comments WHERE users.user_id = comments.comment_user_id AND comments.comment_id = ".$notification["notification_object_id"];
                    $result2 = $this->conn->query($sqlString);
                       if ( $result2 = $result2->fetch(PDO::FETCH_ASSOC) ) {                                
                            if( $result2["user_id"] == $user_id ) {
                                continue;
                            }            
                            $notification["user_id"] = $result2["user_id"];
                            $notification["user_name"] = $result2["user_name"];
                            $notification["poll_name"] = $result2["poll_name"];
                            $notification["poll_id"] = $result2["poll_id"];
                            $notification["comment_id"] = $notification["notification_object_id"]; 
                            $notifications[] = $notification;
                    }                   
                }
                if( $notification["notification_action"] == "newLike") {
                    $sqlString = "SELECT poll_name, poll_id, like_id, (SELECT concat(users.user_firstname,' ',users.user_lastname) FROM users where users.user_id = likes.like_liker_id) AS user_name, (SELECT users.user_id FROM users where users.user_id = likes.like_liker_id) AS user_id FROM polls,users,likes WHERE like_id = ".$notification["notification_object_id"];
                    $result3 = $this->conn->query($sqlString);
                       if ( $result3 = $result3->fetch(PDO::FETCH_ASSOC) ) {  
                           if( $result3["user_id"] == $user_id ) {
                                continue;
                            }                              
                            $notification["user_id"] = $result3["user_id"];
                            $notification["user_name"] = $result3["user_name"];
                            $notification["poll_name"] = $result3["poll_name"];
                            $notification["poll_id"] = $result3["poll_id"];
                            $notification["like_id"] = $notification["notification_object_id"]; 
                            $notifications[] = $notification;
                    }                   
                }
                if( $notification["notification_action"] == "newDislike") {
                    $sqlString = "SELECT poll_name, poll_id, dislike_id, (SELECT concat(users.user_firstname,' ',users.user_lastname) FROM users where users.user_id = dislikes.dislike_disliker_id) AS user_name, (SELECT users.user_id FROM users where users.user_id = dislikes.dislike_disliker_id) AS user_id FROM polls,users,dislikes WHERE dislike_id = ".$notification["notification_object_id"];
                    $result3 = $this->conn->query($sqlString);
                       if ( $result3 = $result3->fetch(PDO::FETCH_ASSOC) ) {  
                           if( $result3["user_id"] == $user_id ) {
                                continue;
                            }                              
                            $notification["user_id"] = $result3["user_id"];
                            $notification["user_name"] = $result3["user_name"];
                            $notification["poll_name"] = $result3["poll_name"];
                            $notification["poll_id"] = $result3["poll_id"];
                            $notification["dislike_id"] = $notification["notification_object_id"]; 
                            $notifications[] = $notification;
                    }                   
                }
            }

            return $notifications;
        }

        public static function getSingleColumn( $table,$columnName,$returnType = "sqlArray" ) {
            $conn = new PDO("mysql:host=$host;dbname=$dbname",$username,$password);
            $sqlString = "SELECT $columnName FROM $table";
            $r = $conn->query($sqlString);
            if ( $returnType == "sqlArray" ) {
                $arr = array();
                while ( $row = $r->fetch(PDO::FETCH_ASSOC) ) {
                    $arr[] = $row;
                }
                return $arr;
            } else {
                return $r;
            }
        }

        public function all( $table ) {
            if( isset($table) ) {
                $sqlString = "SELECT * FROM $table";
                return $this->conn->query($sqlString);
            } else {
                return false;
            }
        }
        public function single( $table,$id ) {
            session_start();
            if( isset($table) ) {
            if( isset($_SESSION["pollsite_user_id"]) ) {
                $user_id = $_SESSION["pollsite_user_id"];
                    $sqlString = "SELECT *,                    
                                    (SELECT COUNT(*) FROM likes WHERE likes.like_poll_id = polls.poll_id AND likes.like_liker_id = $user_id) AS liked,
                                    (SELECT COUNT(*) FROM dislikes WHERE dislikes.dislike_poll_id = polls.poll_id AND dislikes.dislike_disliker_id = $user_id) AS disliked,
                                    (select concat(user_firstname,' ',user_lastname) FROM users where users.user_id = polls.poll_user_id) AS poll_creator_name,
                                    (SELECT COUNT(*) FROM likes WHERE likes.like_poll_id = polls.poll_id) AS poll_likes,
                                    (SELECT COUNT(*) FROM dislikes WHERE dislikes.dislike_poll_id = polls.poll_id) AS poll_dislikes
                                FROM polls WHERE poll_id=$id LIMIT 1";
                } else {
                    $sqlString = "SELECT *,
                                (select concat(user_firstname,' ',user_lastname) FROM users where users.user_id = polls.poll_user_id) AS poll_creator_name,
                                (SELECT COUNT(*) FROM likes WHERE likes.like_poll_id = polls.poll_id) AS poll_likes,
                                (SELECT COUNT(*) FROM dislikes WHERE dislikes.dislike_poll_id = polls.poll_id) AS poll_dislikes
                            FROM polls WHERE poll_id=$id LIMIT 1";

                }
                // return $sqlString;
                session_abort();
                return $this->conn->query($sqlString);
            } else {
                return false;
            }
        }
        public function singleColumn( $table,$column,$user_id ) {
            if( isset($table) ) {
                $sqlString = "SELECT $column,poll_name FROM polls WHERE poll_user_id = $user_id";
                // return $sqlString;
                return $this->conn->query($sqlString);
            } else {
                return false;
            }
        }
        public function singlePollOptions( $table="options",$pollid ) {
            if( isset($table) ) {
                $sqlString = "SELECT option_id,option_name,option_belongsto,option_addedby_id,option_created_at,(select count(*) from votes where vote_option_id=option_id) AS option_votes FROM options WHERE option_belongsto = $pollid";
                // return $sqlString;
                return $this->conn->query($sqlString);
            } else {
                return false;
            }
        }
        public function allPollComments( $table="comments",$pollid ) {
            if( isset($table) ) {
                $sqlString = "SELECT *,concat(users.user_firstname,' ',users.user_lastname) AS comment_user_fullname,users.user_id AS commentor_id  FROM comments join users on users.user_id=comments.comment_user_id WHERE comment_poll_id = $pollid ";
                // return $sqlString;
                $r = $this->conn->query($sqlString);
                $arr = array();   
                session_start();         
                $user_id = isset($_SESSION["pollsite_user_id"]) ? $_SESSION["pollsite_user_id"] : "" ;
                $conn = new PDO("mysql:host=localhost;dbname=bluepoll","root","");
                while ( $row = $r->fetch(PDO::FETCH_ASSOC) ) {
                    $comment_id = $row["comment_id"];
                    $ownPoll = $conn->query("SELECT polls.poll_user_id FROM polls WHERE polls.poll_id = $pollid");
                    $ownComment = $conn->query("SELECT comment_user_id FROM comments WHERE comment_id = $comment_id");
                    
                    if( $user_id == $ownPoll->fetch(PDO::FETCH_ASSOC)["poll_user_id"] || $ownComment->fetch(PDO::FETCH_ASSOC)["comment_user_id"] == $user_id  ) {
                        $row["canDelete"] = true;
                    } else {
                        $row["canDelete"] = false;
                    }
                    array_push($arr,$row);
                }
                return $arr;
            } else {
                return false;
            }
        }
        public function saveNewPoll( $pollTitle,$poll_user_id,$pollCategory,$pollOptions ) {
            if( isset($pollOptions) && isset($pollTitle) && isset($pollCategory)   ) {
                // $conn = new mysqli("localhost","root","","bluepoll");
                $sqlString = "INSERT INTO `polls`(`poll_category`, `poll_user_id`, `poll_name`) VALUES ('$pollCategory', $poll_user_id,'$pollTitle')"; 
                $result = $this->myconn->query($sqlString);
                // return $sqlString;
                $poll_id = $this->myconn->insert_id;
                if( $result && $poll_id ) {
                    foreach( $pollOptions as $option ) {
                        $sqlString = "INSERT INTO `options`(`option_name`, `option_belongsto`, `option_addedby_id`) VALUES ('$option',$poll_id,$poll_user_id)";
                        $this->myconn->query($sqlString);
                    }
                }
                return $poll_id;
            } else {
                return false;
            }
        }

        // get all the Public polls from the database
        public function getAllPoll() {
            $conn = new mysqli("localhost","root","","bluepoll");
            if( isset($_SESSION["pollsite_user_id"]) ) {
                $user_id = $_SESSION["pollsite_user_id"];
                $sqlString = "SELECT *,
                                (SELECT COUNT(*) FROM likes WHERE likes.like_poll_id = polls.poll_id AND likes.like_liker_id = $user_id) AS liked,
                                (SELECT COUNT(*) FROM dislikes WHERE dislikes.dislike_poll_id = polls.poll_id AND dislikes.dislike_disliker_id = $user_id) AS disliked,
                                (select concat(user_firstname,' ',user_lastname) FROM users where users.user_id = polls.poll_user_id) AS poll_creator_name,
                                (SELECT COUNT(*) FROM likes WHERE likes.like_poll_id = polls.poll_id) AS poll_likes,
                                (SELECT COUNT(*) FROM dislikes WHERE dislikes.dislike_poll_id = polls.poll_id) AS poll_dislikes
                            FROM polls WHERE  poll_status = 1";
            } else {
                $sqlString = "SELECT *,
                                (select concat(user_firstname,' ',user_lastname) FROM users where users.user_id = polls.poll_user_id) AS poll_creator_name,
                                (SELECT COUNT(*) FROM likes WHERE likes.like_poll_id = polls.poll_id) AS poll_likes,
                                (SELECT COUNT(*) FROM dislikes WHERE dislikes.dislike_poll_id = polls.poll_id) AS poll_dislikes
                            FROM polls WHERE  poll_status = 1";
            }
            $results = $this->myconn->query($sqlString);
            $tmpArr = array();
            $loggedInUser = isset($_SESSION["pollsite_user_id"]) ? $_SESSION["pollsite_user_id"] : "";

            while( $poll = $results->fetch_assoc() ) {
                $poll_id = $poll['poll_id'];
                $poll["options"]  = $this->myconn->query( "SELECT option_id,option_name,option_belongsto,option_addedby_id,option_created_at,(select count(*) from votes where vote_option_id=option_id) AS option_votes FROM options WHERE option_belongsto = $poll_id" );
                $comments = $this->myconn->query( "SELECT *,CONCAT(users.user_firstname, ' ',users.user_lastname) AS comment_user_fullname FROM comments JOIN users ON comment_user_id=users.user_id where comment_poll_id=".$poll['poll_id'] );

                // if( hasSingleCommentDeleteAuthority($comments["comment_user_id"] ) {
                //     $comments["owned"] = true;
                // } else {
                //     $comments["owned"] = false;
                // }

                $poll["comments"] = $comments;
                if ( $poll["poll_user_id"] == $loggedInUser ) {
                    $poll["owned"] = true;
                } else {
                    $poll["owned"] = false;

                }
                array_push($tmpArr,$poll);
            }

          return $tmpArr;
        }

        public function updateOptionVote( $optionID,$poll_id,$checked,$user_id ) {
            $conn = new mysqli("localhost","root","","bluepoll");
            if( $checked ){
                $sqlString = "INSERT INTO `votes`(`vote_given_by`, `vote_option_id`, `vote_poll_id`) VALUES ($user_id,$optionID,$poll_id)";                
                $r = $this->myconn->query($sqlString);
                if ( $r ) {
                    // $sqlString = "INSERT INTO `notifications`(`notification_actor_id`, `notification_action`, `notification_poll_id`, `notification_object_id`) VALUES ($user_id,'vote',$poll_id,$optionID)";
                    return $myconn->insert_id;
                }

                // return 
            } else {
                $sqlString = "DELETE from votes where vote_option_id = $optionID AND vote_given_by=$user_id";
                // return $sqlString;
                return $this->myconn->query($sqlString);
            }
        }

    }
?>