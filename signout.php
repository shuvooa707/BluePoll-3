<?php
    
    if( session_status() == 1 ) {
        session_start();
    }
    unset($_SESSION);
    session_destroy();
    header("Location:index.php");
?>