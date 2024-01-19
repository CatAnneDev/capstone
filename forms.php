<?php
# choose purchase orders or delivery orders
# add item to purchase/deliver
# fill out quantity
# ability to add more items
# submit

# redirect non-users to login
if ($_SESSION["loggedin"] != true)
{
    header("Location: index.php");
}

# prep purchase order form

# prep delivery form

?>

<?=shop_header("Order Forms")?>

<div class="content-wrapper">
    <h1 class="h1-align">Order Forms</h1>
</div>

<?=footer()?>
