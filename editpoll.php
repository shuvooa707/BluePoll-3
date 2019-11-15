<?php require_once("db.php"); require_once("helper.php");

    if ( isset($_GET["pollid"])  ) {
        $pollid = $_GET["pollid"];
        $poll = (new db())->single("polls",$pollid)->fetch(PDO::FETCH_OBJ);
        $options = (new db())->singlePollOptions("options",$pollid);
        $comments = (new db())->allPollComments("comments",$pollid);
    }    
    
    if( !issLoggedIn() || !checkAuthority( $pollid ) ) {
        header("Location:index.php");
    }
    // echo $polls;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
        <link rel="stylesheet" href="css/editpoll.css">
        <link rel="stylesheet" href="css/common.css">

        <script src="js/editpoll.js" defer></script>
        <script src="js/common.js" defer></script>
        <title>Edit Poll</title>
    </head>
    <body>        
        <!-- This is navbar of the page -->
        <?php
            require_once("navbar.php");
        ?>
        <!-- /This is navbar of the page -->
    <div class="container">
        <div class="main-content">
            <div class='poll' data-poll-id='<?php echo $pollid ?>'>
                <div class='poll-header'>
                    <input 
                        type="text" 
                        name="newpollname" 
                        id="newpollname" 
                        placeholder="Poll Title..."
                        value="<?php echo $poll->poll_name?>"
                    >
                </div>  

                <div class="poll-category-selector">
                    <select name="poll-category" id="poll-category">
                        <option value="">Choose Poll Category</option>
                        <?php
                            $category = (new db())->conn->query("select * from category");
                            while(  $cat = $category->fetch(PDO::FETCH_OBJ) ) {                                
                                if ( $poll->poll_category == $cat->category_name ) {
                                    echo "<option value='$cat->category_name' selected>$cat->category_name</option>";                                
                                    continue;
                                }
                                echo "<option value='$cat->category_name' >$cat->category_name</option>";
                            }
                        ?>
                        <option value='AddNew' >Add New + </option>
                    </select>
                </div>
                <div class='poll-body'>
                    <div class="options-list">
                        <div class='option' data-option-id='' data-option-vote=''>
                            <?php
                                while( $option = $options->fetch(PDO::FETCH_ASSOC) ) {
                                    $option_id = $option['option_id'];
                                    $option_name = $option['option_name'];
                                    echo "
                                        <div class='new-option-name'>
                                            <input type='text' name='option$option_id' id='option$option_id'  class='options' value='$option_name'>
                                            <span  class='new-option-cancel' onclick='this.parentElement.remove()'>x</span>
                                        </div>";
                                }
                            ?>
                            <div class='new-option-name'>
                                <input type="text" name="newoption2" id="newoption2" class='options' placeholder="Option 2...">
                                <span class="new-option-cancel" onclick="this.parentElement.remove()">x</span>
                            </div>
                        </div>
                    </div>
                    <div class="add-new-option-button" onclick="addnewoption()">
                        Add New Option <span>+</span>
                    </div>
                    <button onclick="updatePoll(this)" class="savebutton">Update</button>
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