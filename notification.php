
<?php
    require_once("db.php");
    $notifications = (new db())->getAllNotifications();
    // var_dump($notifications);
    $not_count = count($notifications);
    if( $not_count ) {
        $ncount = "visible";
        $alm = "red";
    } else {
        $ncount = "hidden";
        $alm = "black";
    }
?>


<div class='nav-item notification' onclick="" style='padding-top: 3px;'>

    <span style='width: 20px;display: inline-block;position: relative;border: none;top: 4px;left:-5px;'>
        <svg viewBox='0 0 24 24' preserveAspectRatio='xMidYMid meet' focusable='false' class='style-scope yt-icon' style='pointer-events: none; display: block; width: 100%; height: 100%;'>
            <g class='style-scope yt-icon'>
                <path style='fill:<?php echo $alm ?>;' d='M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z' class='style-scope yt-icon'>
                </path>
            </g>
        </svg>
    </span>Notification <span class='not_count' style='visibility:<?php echo $ncount ?>!important;/* display:none; */font-weight:bold;padding:0px 5px;border-radius:3px;background:indigo;color:white;box-shadow: 0px 0px 10px #ffffffc2;'>
        <?php echo $not_count ?>
    </span>
    <div class='notification-list' id='not'>    
        <div class=notification-list-header>
            notification
            <span class="notification-close">X</span>        
        </div>

        <div class="notification-list-body">
        <?php 
            foreach( $notifications as $notification ) {
                if( $notification["notification_action"] == "newVote" ) {
                    $notification_name = "<span style='margin:4px 2px;padding:0px 4px;background:green;border-radius:3px;font-size:13px;color:white'>New Vote</span> <i style='color:black'>on the poll</i><br><br>";
                    $notification_name .= "<a class='not-poll-link' style='' href='poll.php?pollid=".$notification["poll_id"]."&nitification=".$notification["notification_id"]."&optionid=".$notification["option_id"]."'>".$notification["poll_name"]."</a>";
                    $notification_name .= "<small style='color:black;!important'>  by  <a style='font-style:italic;color: #1a1a1a;text-shadow: 0px 0px 1px #000000a3;font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;!important' href='user.php?userid=".$notification["user_id"]."'>".$notification["user_name"]."</a></small>";
                }
                if( $notification["notification_action"] == "newComment" ) {
                    $notification_name = "<span style='margin:4px 2px;padding:0px 4px;background:cornflowerblue;border-radius:3px;font-size:13px;color:white'>New Comment</span> <i style='color:black'>on the poll</i><br><br>";
                    $notification_name .= "<a class='not-poll-link' style='' href='poll.php?pollid=".$notification["poll_id"]."&nitification=".$notification["notification_id"]."&commentid=".$notification["comment_id"]."'>".$notification["poll_name"]."</a>";
                    $notification_name .= "<small style='color:black;!important'>  by  <a style='font-style:italic;color: #1a1a1a;text-shadow: 0px 0px 1px #000000a3;font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;!important' href='user.php?userid=".$notification["user_id"]."'>".$notification["user_name"]."</a></small>";
                }
                if( $notification["notification_action"] == "newLike" ) {
                    $notification_name = "<span style='margin:4px 2px;padding:0px 4px;background:indigo;border-radius:3px;font-size:13px;color:white'>New Like</span> <i style='color:black'>on the poll</i><br><br>";
                    $notification_name .= "<a class='not-poll-link' style='' href='poll.php?pollid=".$notification["poll_id"]."&nitification=".$notification["notification_id"]."&likeid=".$notification["like_id"]."'>".$notification["poll_name"]."</a>";
                    $notification_name .= "<small style='color:black;!important'>  by  <a style='font-style:italic;color: #1a1a1a;text-shadow: 0px 0px 1px #000000a3;font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;!important' href='user.php?userid=".$notification["user_id"]."'>".$notification["user_name"]."</a></small>";
                }
                if( $notification["notification_action"] == "newDislike" ) {
                    $notification_name = "<span style='margin:4px 2px;padding:0px 4px;background:#c95050;border-radius:3px;font-size:13px;color:white'>New Disike</span> <i style='color:black'>on the poll</i><br><br>";
                    $notification_name .= "<a class='not-poll-link' style='' href='poll.php?pollid=".$notification["poll_id"]."&nitification=".$notification["notification_id"]."&likeid=".$notification["dislike_id"]."'>".$notification["poll_name"]."</a>";
                    $notification_name .= "<small style='color:black;!important'>  by  <a style='font-style:italic;color: #1a1a1a;text-shadow: 0px 0px 1px #000000a3;font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;!important' href='user.php?userid=".$notification["user_id"]."'>".$notification["user_name"]."</a></small>";
                }
                if( $notification["notification_action"] == "newOptionRequest" ) {
                    $notification_name = "<span style='margin:4px 2px;padding:0px 4px;background:#c95050;border-radius:3px;font-size:13px;color:white'>New Option Request</span> <i style='color:black'>on the poll</i><br><br>";
                    $notification_name .= "<a class='not-poll-link' style='' href='poll.php?pollid=".$notification["poll_id"]."&nitification=".$notification["notification_id"]."'>".$notification["poll_name"]."</a>";
                    $notification_name .= "<span class='not-new-option-name' style>".$notification["poption"]."</a>";
                    $notification_name .= "<small style='color:black;!important'>  by  <a style='font-style:italic;color: #1a1a1a;text-shadow: 0px 0px 1px #000000a3;font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;!important' href='user.php?userid=".$notification["user_id"]."'>".$notification["user_name"]."</a></small>";
                }
                // var_dump( $notification );
                echo "
                    <div class='single-notification'>
                        <span class='delete-notification' onclick='deleteNotification(this,".$notification["notification_id"].")'>
                            X
                        </span>
                        $notification_name
                    </div>";
            }
        ?>
        </div>
    </div>
</div>