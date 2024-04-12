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
$groupname = $_SESSION["groupname"];
$sql = "SELECT * FROM history WHERE groupname = ?";
$history_orders = $conn->execute_query($sql, [$groupname]);


?>

<?=nav_header("History")?>

<div class="content-wrapper">
    <h1 class="h1-align">History</h1>
    <?php foreach($history_orders as $row): ?>
        <!-- set prevvious batcch nummber. If new == previous, make a new TD, else mmake new table -->
        <p>B# <?= htmlspecialchars($row['batch_number']) ?></p>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Status</th>
            </tr>
            <tr>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
            </tr>
        </table>
    <?php endforeach ?>
</div>

<?=footer()?>
