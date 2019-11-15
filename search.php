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
        
        <link rel="stylesheet" href="css/common.css">
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
                <?php
                    if( (isset($_GET["searchkey"]) && strlen($_GET["searchkey"]) > 0) || $_GET["searchkey"] == "*" ) {
                        $conn = (new db())->conn;
                        $key = $_GET["searchkey"] == "*" ? "" : $_GET["searchkey"];
                        $r = $conn->query("SELECT * FROM polls WHERE poll_name LIKE '%$key%' OR poll_category LIKE '%$key%'");
                        while( $result = $r->fetch(PDO::FETCH_ASSOC) ) {
                            $poll_name = $result["poll_name"];
                            $poll_id = $result["poll_id"];
                            $poll_category = $result["poll_category"];
                            $poll_date = date_format(date_create(explode(" ",$result["poll_created_at"])[0]),"d-m-y ");
                            echo "
                                <h3 class='result'>
                                    <a class='poll-link' href='poll.php?pollid=$poll_id'>$poll_name</a>
                                    <small class='poll-link-cat' style='color: #858585;font-size: 14px;text-indent:10px;'>category : $poll_category</small> <small>|  </small>
                                    <small class='poll-link-date' style='color: #858585;font-size: 14px;text-indent:10px;'>Date : $poll_date</small>
                                </h3>";
                        }
                    }
                ?>
            </div>
        </div>
        
    <?php require_once("components\modals.php"); ?>
    </body>
</html>