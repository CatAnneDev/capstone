<?php
session_destroy();
// Redirect to the login page when logged out:
header("Location: index.php");
?>