<?php
    $conn = (new db())->myconn;
    
    // Top Polls
    $sql = "SELECT *, (SELECT count(*) FROM votes WHERE votes.vote_poll_id = polls.poll_id) AS vote_count, (SELECT COUNT(*) FROM likes WHERE likes.like_poll_id = polls.poll_id) as like_count FROM polls ORDER BY vote_count DESC LIMIT 5";
    $topPolls = $conn->query($sql);
    // Recent Polls
    $sql = "SELECT *, (SELECT count(*) FROM votes WHERE votes.vote_poll_id = polls.poll_id) AS vote_count, (SELECT COUNT(*) FROM likes WHERE likes.like_poll_id = polls.poll_id) as like_count FROM polls ORDER BY poll_created_at DESC LIMIT 5";
    $recentPolls = $conn->query($sql);

?>

    <div class="sidebar">
        <form action="search.php">
            <div style='background: linear-gradient(-69deg, #ccc, #eee);padding: 10px;height: 39px;position: relative;'>
                    <label for="#searchkey">Search  </label>
            </div>
            <div class="search-box">
                
                <input type="text" placeholder="search..." name="searchkey" id="searchkey">
                <button type="submit">Search <span style='' class='flaticon-magnifying-glass'></span> </button>
            </div>
        </form>

        <!-- Top Polls -->
        <div class="top-polls-container">
            <div class="tpc-header" style='padding: 10px;height: 39px;position: relative;'>
                Top Polls
                <span></span>
            </div>
            <div class="tpc-body">
            <?php

                while($topPoll = $topPolls->fetch_assoc()) {
                    $date = date('y-m-d', strtotime($topPoll['poll_created_at']));
                    $date = date_diff(date_create($date),date_create())->d;
                    if( $date > 9 ) {
                        $date = date('d/m/y', strtotime($topPoll['poll_created_at']));
                    } else if( $date == 1 ) {
                        $date = " Today";
                    } else if( $date > 1 ) {
                        $date .= " days";
                    }
                    else {
                        $date .= " day";
                    }
                    echo "
                        <div class='top-poll'>
                            <a href='poll.php?pollid=".$topPoll["poll_id"]."'><div class='top-poll-title'>".$topPoll["poll_name"]."</div></a>
                            <div class='top-poll-info' style='position:relative; font-size:12px;'>
                                <i style='position:absolute;left:5px;' class='flaticon-tag'></i> 
                                <span style='background:#eee;border:1px solid #aaa;border-radius:17px;padding:0px 7px;position: absolute;left: 30px;'>$date</span>                                       
                                <span style='background:#e0f0ff;border:1px solid #aaa; border-radius:17px; padding:0px 7px;'>".$topPoll["like_count"]." likes</span> 
                                <span style='background:#ffe0e0;border:1px solid #aaa; border-radius:17px; padding:0px 7px;'>".$topPoll["vote_count"]." votes</span>
                            </div>
                        </div>  
                    ";                    
                }
            ?>  
            </div>
        </div>
        <!-- /Top Polls -->

        <!-- Recent Polls -->
        <div class="recent-polls-container">
            <div class="rpc-header" style='padding: 10px;height: 39px;position: relative;'>
                Recent Polls
                <span></span>
            </div>
            <div class="rpc-body">
            <?php
                while($recentPoll = $recentPolls->fetch_assoc()) {
                    $date = date('y-m-d', strtotime($recentPoll['poll_created_at']));
                    $date = date_diff(date_create($date),date_create())->d;
                    if( $date > 9 ) {
                        $date = date('d/m/y', strtotime($recentPoll['poll_created_at']));
                    } else if( $date == 1 ) {
                        $date = " Today";
                    } else if( $date == 2 ) {
                        $date .= " yesterday";
                    }
                    else {
                        $date .= " days";
                    }
                    // $date = print_r(date_create());
                    echo "
                        <div class='recent-poll'>
                            <a href='poll.php?pollid=".$recentPoll["poll_id"]."'>
                                <div class='recent-poll-title'>".$recentPoll["poll_name"]."</div>
                            </a>
                            <div class='recent-poll-info' style='position:relative; font-size:12px;'>
                                <i style='position:absolute;left:5px;' class='flaticon-tag'></i>
                                    <span style='background:#eee;border:1px solid #aaa;border-radius:17px;padding:0px 7px;position: absolute;left: 30px;'>$date</span>                                       
                                    <span style='background:#e0f0ff;border:1px solid #aaa; border-radius:17px; padding:0px 7px;'>".$recentPoll["like_count"]." likes</span>  
                                    <span style='background:#ffe0e0;border:1px solid #aaa; border-radius:17px; padding:0px 7px;'>".$recentPoll["vote_count"]." votes</span>
                            </div>
                        </div>  
                    ";                    
                }
            ?>  
            </div>
        </div>
        <!-- /Recent Polls -->


    </div>