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
# for unique bath nuber in groupnae, order by largest ID first
#   for unique entry in results
#   print
#   add pending -> coplete button that updates inentoryphp
# spae out

?>

<?=shop_header("History")?>

<div class="content-wrapper">
    <h1 class="h1-align">History</h1>
</div>

<?=footer()?>
