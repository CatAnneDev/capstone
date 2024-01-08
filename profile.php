<?php
if ($_SESSION["loggedin"] != true)
{
    header("Location: index.php");
}
?>

<?=shop_header("Profile")?>

<div class="recentlyadded content-wrapper">
    <h1>Profile</h1>
</div>

<?=shop_footer()?>
