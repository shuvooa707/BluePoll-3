<?php
    if ( isset($_GET["userid"]) ) {
        $user_id = $_GET["userid"];        
        $conn = new mysqli("localhost","root","","bluepoll");
        $sql = "SELECT 
                    CONCAT(user_firstname,' ',user_lastname) AS user_name,
                    user_username,
                    user_email,
                    user_phone,
                    user_age,
                    user_gender,
                    user_bloodgroup,
                    user_education,
                    user_profession,
                    user_current_city,
                    user_from,
                    user_roll
                FROM users WHERE user_id=$user_id";
        $result = $conn->query($sql);
        // var_dump($user);
        $user = $result->fetch_object();
        // fetching all the poll of this user
        $sql = "SELECT 
                    poll_id,
                    poll_name,
                    poll_category
                FROM polls WHERE polls.poll_user_id = $user_id";
        $polls = $conn->query($sql);
        // var_dump($user);
        // $polls = $result;



        if ( $result->num_rows == 0 ) {
            header("Location:index.php");      
        }
    } else {
        header("Location:index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/user.css">
    <title><?php echo $user->user_name; ?></title>
    <script src="js\common.js" defer></script>
    <script src="js\user.js" defer></script>
</head>
<body>

    <!-- This is navbar of the page -->
    <?php
        require_once("navbar.php");
    ?>
    <!-- /This is navbar of the page -->

    <div class="container">
        <div class="tab-container">
            <div class="tab-drawer">
                    <div data-target='.tab-about' class="tab-switcher tab-switcher-about active">About</div>
                    <div data-target='.tab-polls' class="tab-switcher tab-switcher-polls">Polls</div>
                    <div data-target='.tab-bio' class="tab-switcher tab-switcher-bio">Bio</div>
            </div>
            <div class="tab tab-about show">
                
            <?php
                    echo " <h4>Who is <strong>$user->user_name</strong></h4>
                        <div class='about-row'><strong>Name : </strong>$user->user_name</div>
                        <div class='about-row'><strong>Age : </strong>$user->user_age</div>
                        <div class='about-row'><strong>Email : </strong>$user->user_email</div>
                        <div class='about-row'><strong>Phone : </strong>$user->user_phone</div>
                        <div class='about-row'><strong>Gender : </strong>$user->user_gender</div>
                        <div class='about-row'><strong>Blood Group : </strong>$user->user_bloodgroup</div>
                        <div class='about-row'><strong>Education : </strong>$user->user_education</div>
                        <div class='about-row'><strong>Profession : </strong>$user->user_profession</div>
                        <div class='about-row'><strong>Current City : </strong>$user->user_current_city</div>
                        <div class='about-row'><strong>From : </strong>$user->user_from</div>
                        <div class='about-row'><strong>Roll : </strong>$user->user_roll</div>
                    ";
            ?>
            </div>
            <div class="tab tab-polls">
                <h4>Polls of <a href="user.php?userid=<?php echo $user_id ?>" style='color:dodgerblue;'><?php echo $user->user_name; ?></a></h4>
                <?php 
                    while( $poll = $polls->fetch_object() ) {
                        echo "                            
                            <div class='poll'>
                                <a href='poll.php?pollid=$poll->poll_id'>$poll->poll_name</a>
                            </div>                            
                        ";
                    }
                ?>
            </div>
            <div class="tab tab-bio">
                Bio's Tab
            </div>
        </div>
    </div>

</body>
</html>