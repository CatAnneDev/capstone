<?php
    // redirect non-users to login
    if ($_SESSION["loggedin"] != true)
    {
        header("Location: index.php");
    }
?>


<?=nav_header("Confirmation")?>


<div class="content-wrapper">
    <div class="flex-wrapper">
        <h1 class="h1-align">Confirmation</h1>
        <p>Order logged in history.</p>
    </div>
</div>


<?=footer()?>
