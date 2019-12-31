       
    <div id="snackbar">
        The Hidden Polls Can Be Found In Control Section of Your Profile Page 
    </div>

<div class="container">
    <div class="main-content">
        <div id="onload-overlay">
            <div class="loader-text">
                Loading More Polls...
            </div>
        </div> 

        <?php require_once("polls.php"); ?>
        
        <div class="load-more-button" style="display:block;" onClick="loadMorePolls()">
            Load More
        </div>
    </div>
    <?php require_once("sidebar.php"); ?>
    
</div>


<script>
    function checkIsLoggedIn() {
        return <?php if( issLoggedIn() ) echo 1; else echo 0;  ?>
    }
</script>

<!-- Who Votted List Popup Window -->
        <div class="whoVotted-overlay">
            <div class="whoVotted-container">
                <div class="whoVotted-header">
                    Voters List
                    <span title='close' class="close-whoVotted-container" onclick="closeVotterList()">X</span>
                </div>
                <div class="voters">
                    <!-- <div class="voter">
                        <a href="user.php?userid=4">
                            <img width="40px" height="40px" src="img\profile\3.jpg" alt="">
                        </a>
                        <strong title='Shuvo Sarker'><a href="user.php?userid=4">Shuvo Sarker</a></strong>
                    </div> -->
                </div>
            </div>
        </div>
<!-- /Who Votted List Popup Window -->
