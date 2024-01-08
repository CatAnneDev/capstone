<?php
if ($_SESSION["loggedin"] != true)
{
    header("Location: index.php");
}
?>

<?=shop_header("Files")?>

<div class="recentlyadded content-wrapper">
    <h1>Inventory Forms</h2>
    <div class="products">
        <h3>Spreadsheets</h3>
        <h3>Forms</h3>
    </div>
</div>

<?=shop_footer()?>
