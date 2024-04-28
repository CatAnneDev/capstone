<?php
    // redirect non-users to login
    if ($_SESSION["loggedin"] != true)
    {
        header("Location: index.php");
    }
?>


<?=nav_header("History")?>


<div class="content-wrapper">
    <div class="flex-wrapper">
        <h1 class="h1-align">History</h1>
        <!-- Search Bar -->
        <div class="searchbar">
        <form action="" method="POST">
            <div>
                <input type="text" name="search" value="<?php if(isset($_POST['search'])){echo $_POST['search']; } ?>" placeholder="Search Inventory">
                <button type="submit">Search</button>
            </div>
        </form>
      </div>
    </div>

    <!-- Search Results and Show Results Table -->
    <?php 
        if(isset($_POST['search']))
        {
            $filtervalues = htmlspecialchars($_POST['search']);
            $sql_query = "SELECT * FROM history WHERE CONCAT(id,batch_number,product_name,quantity,status) LIKE '%$filtervalues%' ";
            $sql_query_run = mysqli_query($conn, $sql_query);

            if(mysqli_num_rows($sql_query_run) > 0)
            {
                foreach($sql_query_run as $row)
                {
                    ?>
                    <p class="flex-wrapper">B# <?= htmlspecialchars($row['batch_number']) ?></p>
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Status</th>
                        </tr>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                        </tr>
                    </table>
                    <?php
                }
            }
            else
            {
                ?>
                    <tr>
                        <td colspan="3">Searched Record Not Found</td>
                    </tr>
                <?php
            }
        }
        else
        {
            // History if No Search
            $groupname = $_SESSION["groupname"];
            $sql = "SELECT * FROM history WHERE groupname = ?";
            $history_orders = $conn->execute_query($sql, [$groupname]);
            foreach($history_orders as $row): ?>
                <!-- set prevvious batch nummber. If new == previous, make a new TD, else make new table -->
                <p class="flex-wrapper">B# <?= htmlspecialchars($row['batch_number']) ?></p>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                        <?php
                        if ($row['status'] == "Pending")
                        {
                            // pending --> completed button
                            ?>
                            <td>
                                <form action="index.php?page=history_status" method="post" autocomplete="off">
                                    <input type="hidden" id="history_order_type" name="history_order_type" value="<?=$row['order_type']?>">
                                    <input type="hidden" id="history_product_name" name="history_product_name" value="<?=$row['product_name']?>">
                                    <input type="hidden" id="history_quantity" name="history_quantity" value="<?=$row['quantity']?>">
                                    <input type="hidden" id="history_id" name="history_id" value="<?=$row['id']?>">
                                    <input class="buttonless" type="submit" value="Pending - Submit Order"></input>
                                </form>
                            </td>
                            <?php
                        }
                        else
                        {
                            ?>
                            <td>Complete - <?= htmlspecialchars($row['status']) ?></td>
                            <?php
                        }
                        ?>
                    </tr>
                </table>
            <?php endforeach ?>
            <?php
        }
        ?>
    </table>
    <div class="flex-wrapper">
        <div class="submit_order">
            <form action="index.php?page=export_csv" method="post" autocomplete="off">
                <input type="hidden" id="export_table_name" name="export_table_name" value="history">
                <button type="submit">Export as CSV</button>
            </form>
        </div>
    </div>
</div>


<?=footer()?>
