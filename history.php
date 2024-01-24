<?php
# options: purchase orders OR delivery orders
# show history
# export to xlsx

# redirect non-users to login
if ($_SESSION["loggedin"] != true)
{
    header("Location: index.php");
}

# purchase orders prep

# delivery orders prep

?>

<?=nav_header("History")?>

<div class="content-wrapper">
    <h1 class="h1-align">History</h1>
</div>

<?=footer()?>
