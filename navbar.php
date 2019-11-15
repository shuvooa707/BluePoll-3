<!-- navbar -->
<nav>
    <div class="navbar">
        <a href="index.php"><div class="nav-item">Home</div></a>
            <?php require_once("helper.php"); require_once("db.php");                
                if ( issLoggedIn() ) {
                    $username = $_SESSION["pollsite_username"];
                    $userfullname = $_SESSION["pollsite_userfullname"];
                    $userid = $_SESSION["pollsite_user_id"];
                    echo "
                        
                        <a href='dashboard.php' title='click to visit dashboard'><div class='nav-item'>Dashboard</div></a>
                        <a href='addnewpoll.php' title='click to create a new poll'>
                            <div class='nav-item'>
                                Make a Poll <span class='add-new-poll-icon' style='                                                       
                                                        display: inline-block;
                                                        background: black;
                                                        color: white;
                                                        /* padding: 0px 6px; */
                                                        border-radius: 50%;
                                                        width: 21px;
                                                        height: 21px;
                                                        line-height: 18px;
                                                        text-align: center;                                                                                                                        
                                                                    '>✚</span>
                            </div>
                        </a>
                        <a class='user' data-user-id='$userid' data-user-name='$username' data-user-fullname='$userfullname' href='user.php?userid=$userid' title='click to visit your profile page'>
                            <div class='nav-item'>$username
                            </div>
                        </a>
                        ";
                        
                        require_once("notification.php");

                        echo "
                        <a class='user' href='messege.php' title='click to see all messege'>
                            <div class='nav-item' >Massege
                            </div>
                        </a>
                        <a href='signout.php' title='click to signout'>
                            <div class='nav-item'>
                                Sign Out
                            </div>
                        </a>
                        
                        ";
                } else {
                    echo "
                        <div class='nav-item' onclick='showLogin()'>                        
                            <span class='loginButton'>Login</span>
                        </div>                        
                    ";
                }
            ?>
    </div>
</nav>
