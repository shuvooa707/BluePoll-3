<?php session_start(); require_once("db.php");
    if ( isset($_GET["searchkey"]) ) {
        $keyWord = $_GET["searchkey"];
    } else {
        $keyWord = "";
    }
    // echo $polls;
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
        <link rel="stylesheet" href="css/search.css">      

        <script src="js/common.js" defer></script>
        <script src="js/search.js" defer></script>
        <title>Poll Site</title>
    </head>
    <body>
        <!-- This is navbar of the page -->
        <?php
            require_once("navbar.php");
        ?>
        <!-- /This is navbar of the page -->

        <div class="container">
            <div class="search-box">
                <form action="search.php" method="get">
                    <input type="text" placeholder="search..." name="searchkey" id="searchkey" value='<?php echo $keyWord; ?>'>
                    <button type="submit">Search</button>
                </form>

            </div>
            <div class="search-result">
                <div class="search-result-header">
                    <select name="" id="" class='sort-order' onchange='sortSearchResult(this)'>
                        <option value="date" >Date</option>
                        <option value="name">Name</option>
                        <option value="view">Views</option>
                        <option value="vote">Votes</option>
                    </select>
                </div>
                <?php
                    if( (isset($_GET["searchkey"]) && strlen($_GET["searchkey"]) > 0) || $_GET["searchkey"] == "*" ) {
                        $conn = (new db())->conn;
                        $key = $_GET["searchkey"] == "*" ? "" : $_GET["searchkey"];
                        $r = $conn->query("SELECT *,(select count(*) from votes where vote_poll_id=polls.poll_id) as poll_votes FROM polls WHERE poll_name LIKE '%$key%' OR poll_category LIKE '%$key%'");
                        while( $result = $r->fetch(PDO::FETCH_ASSOC) ) {
                            $poll_name = $result["poll_name"];
                            $poll_id = $result["poll_id"];
                            $poll_category = $result["poll_category"];
                            $poll_votes = $result["poll_votes"];
                            $poll_date = date_format(date_create(explode(" ",$result["poll_created_at"])[0]),"d-m-y ");
                            echo "
                                <h3 class='result'>
                                    <a class='poll-link' href='poll.php?pollid=$poll_id'>$poll_name</a>
                                    <small class='poll-link-cat' style='color: #858585;font-size: 14px;text-indent:10px;'>category : $poll_category</small> <small>|  </small>
                                    <small class='poll-link-date' style='color: #858585;font-size: 14px;text-indent:10px;'>Date : $poll_date | </small>
                                    <small class='poll-link-vote' style='color: #858585;font-size: 14px;text-indent:10px;'>Votes : $poll_votes</small>
                                </h3>";
                        }
                    }
                ?>
            </div>
        </div>
        
    <?php require_once("components\modals.php"); ?>
    </body>
</html>