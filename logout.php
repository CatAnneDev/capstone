<?php
    session_destroy();
    // redirect to the login page when logged out:
    header("Location: index.php");
?>
