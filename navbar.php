<!-- navbar -->
<nav>
    <div class="navbar">
        <a href="index.php"><div class="nav-item">Home</div></a>
            <?php require_once("helper.php");                
                if ( issLoggedIn() ) {
                    $username = $_SESSION["pollsite_username"];
                    $userid = $_SESSION["pollsite_user_id"];
                    echo "
                        
                        <a href='dashboard.php' title='click to visit dashboard'><div class='nav-item'>Dashboard</div></a>
                        <a href='addnewpoll.php' title='click to create a new poll'>
                            <div class='nav-item'>
                                Make a Poll <span style='
                                                        display: inline-block;
                                                        background: black;
                                                        color: white;
                                                        /* padding: 0px 6px; */
                                                        border-radius: 50%;
                                                        font-weight: bolder;
                                                        width: 25px;
                                                        height: 25px;
                                                        line-height: 21px;
                                                        text-align: center;                                                                    
                                                                    '>+</span>
                            </div>
                        </a>
                        <a class='user' data-user-id='$userid' data-user-name='$username' href='user.php?userid=$userid' title='click to visit your profile page'>
                            <div class='nav-item'>$username
                            </div>
                        </a>
                        <div class='nav-item' onclick=\"toggleNot()\">
                            <span style='width: 20px;display: inline-block;position: relative;border: none;top: 4px;left:-5px;'>
                                <svg viewBox='0 0 24 24' preserveAspectRatio='xMidYMid meet' focusable='false' class='style-scope yt-icon' style='pointer-events: none; display: block; width: 100%; height: 100%;'>
                                    <g class='style-scope yt-icon'>
                                        <path d='M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z' class='style-scope yt-icon'>
                                        </path>
                                    </g>
                                </svg>
                            </span>Notification <span class='' style='/* display:none; */font-weight:bold;padding:0px 5px;border-radius:3px;background:indigo;color:white;box-shadow: 0px 0px 10px #ffffffc2;'>4</span>
                            <div id='not'>

                            </div>
                        </div>
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
