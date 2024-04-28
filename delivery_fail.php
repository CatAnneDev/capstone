<?php
    $site_title = "Confirmation";
    // redirect non-users to login
    if ($_SESSION["loggedin"] != true)
    {
        header("Location: index.php");
    }
    elseif ($_SESSION["permission"] == "Inquiry")
    {
        nav_inquiry($site_title);
    }
    elseif ($_SESSION["permission"] == "Employee")
    {
        nav_employee($site_title);
    }
    elseif ($_SESSION["permission"] == "Manager")
    {
        nav_manager($site_title);
    }
    elseif ($_SESSION["permission"] == "GroupAdmin")
    {
        nav_groupadmin($site_title);
    }
?>


<div class="content-wrapper">
    <div class="flex-wrapper">
        <h1 class="h1-align">Notice</h1>
    </div>
    <p style="text-align:center">Delivery not sent. Inadequate product amount.</p>
</div>


<?=footer()?>
