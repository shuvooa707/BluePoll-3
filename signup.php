<?php require_once("db.php");
    if ( isset($_POST["firstname"]) &&  isset($_POST["lastname"]) &&  isset($_POST["username"]) &&  isset($_POST["email"]) &&  isset($_POST["age"]) &&  isset($_POST["gender"]) &&  isset($_POST["bloodgroup"]) &&  isset($_POST["education"]) &&  isset($_POST["currentcity"]) &&   isset($_POST["fromcity"]) &&  isset($_POST["password"])) {
        // include mysql connection file
        $user_firstname = ucfirst(htmlentities($_POST["firstname"]));
        $user_lastname = ucfirst(htmlentities($_POST["lastname"]));
        $user_username = $_POST["username"];
        $user_email = htmlentities($_POST["email"]);
        $user_age = htmlentities($_POST["age"]);
        $user_gender = ucfirst(htmlentities($_POST["gender"]));
        $user_bloodgroup = ucfirst(htmlentities($_POST["bloodgroup"]));
        $user_education = ucfirst(htmlentities($_POST["education"]));
        $user_profession = ucfirst(htmlentities($_POST["profession"]));
        $user_currentcity = ucfirst(htmlentities($_POST["currentcity"]));
        $user_fromcity = ucfirst(htmlentities($_POST["fromcity"]));
        $user_phone = ucfirst(htmlentities($_POST["phone"]));
        $user_password = password_hash($_POST["password"],PASSWORD_DEFAULT);
        $user_avater = $_FILES["avatarimage"]["name"];
        
        // sql for entering new user into the batadase
        $conn = (new db())->myconn;
        $sql = "INSERT INTO `users`(`user_firstname`,`user_lastname`,`user_username`, `user_password`, `user_email`, `user_phone`, `user_age`, `user_gender`, `user_bloodgroup`, `user_education`,  `user_profession`, `user_current_city`, `user_from`,`user_avater`) VALUES ('$user_firstname','$user_lastname','$user_username','$user_password','$user_email',$user_phone,$user_age,'$user_gender','$user_bloodgroup','$user_education','$user_profession','$user_currentcity','$user_fromcity','$user_avater')";        
        $r = $conn->query( $sql );
        // if registration done then set the session variable and redirect to dashboard.php
        if ( $r ) {
            move_uploaded_file($_FILES["avatarimage"]["tmp_name"],"img/profile/".$conn->insert_id.".jpg");
            rename($_FILES["avatarimage"]["name"],"img/profile/".$conn->insert_id.".jpg");
            if ( session_status() == 1 ) session_start();
            $_SESSION["pollsite_username"] = $user_username;
            $_SESSION["pollsite_user_id"] = $conn->insert_id;
            $_SESSION["pollsite_userfullname"] = "$user_firstname $user_lastname";
            $_SESSION["login_successful"] = "Signup Successfull";
            header("Location:index.php");
        } else {
            // echo ;
            echo "<script>alert('$sql')</script>";
        }

    }
    
?>
<!-- ------------------------------------------------------------------------------------------------------ -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
        <link rel="stylesheet" href="css/signup.css">
        <link rel="stylesheet" href="css/common.css">

        <script src="js/signup.js" defer></script>
        <script src="js/common.js" defer></script>
        <title>Document</title>
    </head>
    <body>        
        <!-- This is navbar of the page -->
        <?php
            require_once("navbar.php");
        ?>
        <!-- /This is navbar of the page -->
    <div class="container">
        <div class="main-content">
            <div id="signupbox">
                <h2>Signup form</h2>
                <form name="signupform" action="signup.php" method="post"  enctype="multipart/form-data">
                    <label for="firstname">Enter Your name</label>
                    <input type="text" name="firstname" id="firstname" placeholder="First Name" >
                    <input type="text" name="lastname" id="lastname" placeholder="Last Name" >
                    <label for="username">Choose Username</label>
                    <input type="text" name="username" id="username" placeholder="Username">
                    <label for="username" id="uniqueuser" style="color: red;">username not avialable</label>
                    <label for="email">Enter Your Email Address</label>
                    <input type="email" name="email" id="email" placeholder="Valid Email Address">
                    <label for="age">Enter Your Age </label> <label for="gender">Enter Your Gender</label>
                    <input type="text" name="age" id="age" placeholder="Age">
                    <input type="text" name="gender" id="gender" placeholder="Gender">
                    <label for="bloodgroup">Choose Your Blood Group</label>
                    <select name="bloodgroup" id="bloodgroup" placeholder="Blood group">
                        <option value="">  Choose Your Blood Group</option>
                        <option value="a+">A+</option>
                        <option value="a-">A-</option>
                        <option value="b+">B+</option>
                        <option value="b-">B-</option>
                        <option value="o+">O+</option>
                        <option value="o-">O-</option>
                        <option value="ab+">AB+</option>
                        <option value="ab-">AB-</option>
                    </select>
                    <label for="education">Enter Your Education</label>
                    <input type="text" name="education" id="education" placeholder="Education">
                    <label for="profession">Enter Your Profession</label>
                    <input type="text" name="profession" id="profession" placeholder="Profession">
                    <label for="currentcity">Enter Your Current City</label>
                    <input type="text" name="currentcity" id="currentcity" placeholder="Current City">
                    <label for="from">Enter Your Birth City</label>
                    <input type="text" name="fromcity" id="fromcity" placeholder="Birth City">
                    <label for="phone">Enter Your Phone Number</label>
                    <input type="number" name="phone" id="phone" placeholder="Phone Number">
                    <label for="password">Choose A Password</label>
                    <input type="password" name="password" id="password" placeholder="Password">
                    <label for="repassword">Re Enter Password</label>
                    <input type="password" name="repassword" id="repassword" placeholder="Password" onkeyup="repasswordcheck(this)">
                    <label for="passwordalert" id="unmatchalert"></label>
                    <label for="image">
                        <img src="img/upload.svg" alt="" widtd="100px" height="120px" id="signup-selected-thumb">                        
                    </label>
                    <label for="imageCap">Choose a profile picture</label>
                    <input type="file" name="avatarimage" id="image" onchange="showSelectedThumb(this)">
                    <input type="submit" value="Register" id="register">
                </form>
                <input type="button" value="Register"  onclick="register()" >
            </div>
    </div>
    
    </body>
</html>