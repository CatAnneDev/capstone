<?php
if ($_SESSION["loggedin"] != true)
{
    header("Location: index.php");
}
?>

<?=shop_header("Management")?>

<div class="recentlyadded content-wrapper">
    <h1>Management</h1>
</div>

<?=shop_footer()?>
