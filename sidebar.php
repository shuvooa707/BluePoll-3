<?php
    $conn = (new db())->myconn;
    $sql = "SELECT *, (SELECT count(vote_id) FROM votes WHERE votes.vote_poll_id = polls.poll_id) AS vote_count, (SELECT COUNT(*) FROM likes WHERE likes.like_poll_id = polls.poll_id) as like_count FROM polls ORDER BY vote_count DESC LIMIT 6";
    $r = $conn->query($sql);

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
        <div class="top-polls-container">
            <div class="tpc-header" style='padding: 10px;height: 39px;position: relative;'>
                Top Polls
            </div>
            <div class="tpc-body">
            <?php

                while($row = $r->fetch_assoc()) {
                    echo "
                        <div class='top-poll'>
                            <a href='poll.php?pollid=".$row["poll_id"]."'><div class='top-poll-title'>".$row["poll_name"]."</div></a>
                            <div class='top-poll-info' style='position:relative;'>
                                <i style='position:absolute;left:5px;' class='flaticon-tag'></i> ".$row["like_count"]." likes | ".$row["vote_count"]." votes
                            </div>
                        </div>  
                    ";                    
                }
            ?>  
            </div>
        </div>
    </div>