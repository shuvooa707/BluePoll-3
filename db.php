<?php
    class db {
        public $conn;
        public function __construct( $host="localhost",$dbname="bluepoll", $username="root", $password="") {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname",$username,$password);
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
            if( isset($table) ) {
                $sqlString = "SELECT * FROM $table WHERE poll_id=$id LIMIT 1";
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
                return $this->conn->query($sqlString);
            } else {
                return false;
            }
        }
        public function saveNewPoll( $pollTitle,$poll_user_id,$pollCategory,$pollOptions ) {
            if( isset($pollOptions) && isset($pollTitle) && isset($pollCategory)   ) {
                $conn = new mysqli("localhost","root","","bluepoll");
                $sqlString = "INSERT INTO `polls`(`poll_category`, `poll_user_id`, `poll_name`) VALUES ('$pollCategory', $poll_user_id,'$pollTitle')"; 
                $result = $conn->query($sqlString);
                // return $sqlString;
                $poll_id = $conn->insert_id;
                if( $result && $poll_id ) {
                    foreach( $pollOptions as $option ) {
                        $sqlString = "INSERT INTO `options`(`option_name`, `option_belongsto`, `option_addedby_id`) VALUES ('$option',$poll_id,$poll_user_id)";
                        $conn->query($sqlString);
                    }
                }
                return $poll_id;
            } else {
                return false;
            }
        }
        public function getAllPoll() {
            $conn = new mysqli("localhost","root","","bluepoll");
            $sqlString = "SELECT *,
                                (select concat(user_firstname,' ',user_lastname) FROM users where users.user_id = polls.poll_user_id) AS poll_creator_name,
                                (SELECT COUNT(*) FROM likes WHERE likes.like_poll_id = polls.poll_id) AS poll_likes,
                                (SELECT COUNT(*) FROM dislikes WHERE dislikes.dislike_poll_id = polls.poll_id) AS poll_dislikes
                            FROM polls";
            $results = $conn->query($sqlString);
            $tmpArr = array();
            $loggedInUser = isset($_SESSION["pollsite_user_id"]) ? $_SESSION["pollsite_user_id"] : "";

            while( $poll = $results->fetch_assoc() ) {
                $poll_id = $poll['poll_id'];
                $poll["options"]  = $conn->query( "SELECT option_id,option_name,option_belongsto,option_addedby_id,option_created_at,(select count(*) from votes where vote_option_id=option_id) AS option_votes FROM options WHERE option_belongsto = $poll_id" );
                $comments = $conn->query( "SELECT *,CONCAT(users.user_firstname, ' ',users.user_lastname) AS comment_user_fullname FROM comments JOIN users ON comment_user_id=users.user_id where comment_poll_id=".$poll['poll_id'] );

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

        public function updateOptionVote( $optionID,$checked,$user_id ) {
            $conn = new mysqli("localhost","root","","bluepoll");
            if( $checked ){
                $sqlString = "INSERT INTO `votes`(`vote_given_by`, `vote_option_id`) VALUES ($user_id,$optionID)";
                $conn->query($sqlString);
                echo $sqlString; 
            } else {
                $sqlString = "DELETE from votes where vote_option_id = $optionID AND vote_given_by=$user_id";
                // return $sqlString;
                return $conn->query($sqlString);
            }
        }

    }
?>