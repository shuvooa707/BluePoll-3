<?php require_once("db.php");
    if ( isset($_GET["pollid"]) ) {
        $pollid = $_GET["pollid"];
        $poll = (new db())->single("polls",$pollid)->fetch(PDO::FETCH_OBJ);
        $options = (new db())->singlePollOptions("options",$pollid);
        $comments = (new db())->allPollComments("comments",$pollid);
        // var_dump((new db())->conn);
    }
    // echo $polls;
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
        <link rel="stylesheet" href="css/addnewpoll.css">
        <link rel="stylesheet" href="css/common.css">

        <script src="js/addnewpoll.js" defer></script>
        <script src="js/common.js" defer></script>
        <title>Add New Poll</title>
    </head>
    <body>        
        <!-- This is navbar of the page -->
        <?php
            require_once("navbar.php");
            ?>
        <!-- /This is navbar of the page -->
    <div class="container">
        <div class="main-content">
            <div class='poll' data-poll-id=''>
                <div class='poll-header'>
                    <input type="text" name="newpollname" id="newpollname" placeholder="Poll Title...">
                </div>  
                
                <div class="poll-category-selector">
                    <select name="poll-category" id="poll-category">
                        <option value="">Choose Poll Category</option>
                        <?php
                            $category = (new db())->conn->query("select * from category");
                            while(  $cat = $category->fetch(PDO::FETCH_OBJ) ) {                                
                                echo "<option value='$cat->category_name'>$cat->category_name</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class='poll-body'>
                    <div class="options-list">
                        <div class='option' data-option-id='' data-option-vote=''>
                            <div class='new-option-name'>
                                <input type="text" name="newoption1" id="newoption1"  class='options' placeholder="Option 1...">
                                <span class="new-option-cancel" onclick="this.parentElement.remove()">x</span>
                            </div>
                            <div class='new-option-name'>
                                <input type="text" name="newoption2" id="newoption2" class='options' placeholder="Option 2...">
                                <span class="new-option-cancel" onclick="this.parentElement.remove()">x</span>
                            </div>
                        </div>
                    </div>
                    <div class="add-new-option-button" onclick="addnewoption()">
                        Add New Option <span>+</span>
                    </div>
                    <button onclick="saveNewPoll(this)" class="savebutton">Save</button>
                    <div class="saveStatus">

                    </div>
            </div>
        </div>
        <div class="sidebar">            
        </div>
    </div>
    <div id="slate" style="display:none;"></div>
    <?php require_once("components\modals.php"); ?>
    </body>
</html>