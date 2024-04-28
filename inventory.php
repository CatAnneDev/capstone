<?php
require_once('index.php');

$site_title = "Inventory";
nav_header($site_title);


// prep inventory table
$result = mysqli_query($conn, "SELECT * FROM inventory");
$inventory_rows = $result->fetch_all(MYSQLI_ASSOC);
?>


<div class="content-wrapper">
    <div class="flex-wrapper">
        <h1 class="h1-align">Inventory</h1>
        
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
            $sql_query = "SELECT * FROM inventory WHERE CONCAT(id,product_name) LIKE '%$filtervalues%' ";
            $sql_query_run = mysqli_query($conn, $sql_query);

            if(mysqli_num_rows($sql_query_run) > 0)
            {
                ?>
                <table>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                </tr>
                <?php
                    foreach($sql_query_run as $row)
                    {
                        ?>
                        <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                        </tr>
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
            ?>
            <!-- Inventory Table if No Search -->
            <table>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                </tr>
                <?php foreach($inventory_rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                </tr>
                <?php endforeach ?>
            </table>
            <?php
        }
    ?>
    </table>
    <div class="flex-wrapper">
        <div class="submit_order">
            <form action="index.php?page=export_csv" method="post" autocomplete="off">
                <input type="hidden" id="export_table_name" name="export_table_name" value="inventory">
                <button type="submit">Export as CSV</button>
            </form>
        </div>
    </div>
</div>


<?=footer()?>
