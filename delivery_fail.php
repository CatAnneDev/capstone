<?php
    $site_title = "Confirmation";
    $username = $_SESSION["username"];
    nav_header($site_title, $username);
?>


<div class="content-wrapper">
    <div class="flex-wrapper">
        <h1 class="h1-align">Notice</h1>
    </div>
    <p style="text-align:center">Sales order not sent. Inadequate product amount.</p>
</div>


<?=footer()?>
