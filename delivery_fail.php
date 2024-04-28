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
        <h1 class="h1-align">Notice</h1>
    </div>
    <p style="text-align:center">Delivery not sent. Inadequate product amount.</p>
</div>


<?=footer()?>
