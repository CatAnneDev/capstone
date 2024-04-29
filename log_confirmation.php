<?php
    $site_title = "Inventory";
    $username = $_SESSION["username"];
    nav_header($site_title, $username);
?>


<div class="content-wrapper">
    <div class="flex-wrapper">
        <h1 class="h1-align">Confirmation</h1>
    </div>
    <p style="text-align:center">Order logged in history.</p>
</div>


<?=footer()?>
