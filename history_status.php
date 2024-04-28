<?php
    // redirect non-users to login
    if ($_SESSION["loggedin"] != true)
    {
        header("Location: index.php");
    }
    
    // add history product to inventory
    // if exists
    $groupname = $_SESSION["groupname"];
    $history_product_name = $_POST['history_product_name'];
    $history_quantity = $_POST['history_quantity'];
    $history_id = $_POST['history_id'];
    if ($sql_query = $conn->prepare("SELECT id FROM inventory WHERE groupname = '$groupname' AND product_name = ?"))
    {
        $sql_query->bind_param('s', $_POST["history_product_name"]);
        $sql_query->execute();
        $sql_query->store_result();
        
    
        if ($sql_query->num_rows > 0) 
        {
            $sql_query->bind_result($inventory_id);
            $sql_query->fetch();

            // get quantity of existing inventory item
            if ($sql_query_get_quantity = $conn->prepare("SELECT quantity FROM inventory WHERE groupname = '$groupname' AND id = ?"))
            {
                $sql_query_get_quantity->bind_param('s', $inventory_id);
                $sql_query_get_quantity->execute();
                $sql_query_get_quantity->store_result();
                $sql_query_get_quantity->bind_result($inventory_quantity);
                $sql_query_get_quantity->fetch();
        
                // if purchase, add
                if ($_POST['history_order_type'] == "Purchase")
                {
                    $new_quantity = $inventory_quantity + $history_quantity;
                    echo $new_quantity;
                    // update inventory table
                    if($conn->query("UPDATE inventory SET quantity = $new_quantity WHERE groupname = '$groupname' AND product_name = '$history_product_name'"))
                    {
                        echo "Purchased. Inventory updated.";
                    }
                }
                // if delivery / sales order, subtract
                else
                {
                    // fail if not enough to deliver
                    if($inventory_quantity < $history_quantity)
                    {
                        header("Location: index.php?page=delivery_fail");
                        exit();
                    }
                    else
                    {
                        $new_quantity = $inventory_quantity - $history_quantity;
                        // update inventory table
                        if($conn->query("UPDATE inventory SET quantity = $new_quantity WHERE groupname = '$groupname' AND product_name = '$history_product_name'"))
                        {
                            echo "Delivered. Inventory updated.";
                        }
                    }
                }
            }
        }
        // if product name not exists
        else 
        {
            echo "No rows";
            // if purchase, insert new row into table
            if ($_POST['history_order_type'] == "Purchase")
            {
                if ($sql_query_insert = $conn->prepare("INSERT INTO inventory (product_name, quantity, groupname) VALUES (?, ?, ?)"))
                {
                    $sql_query_insert->bind_param("sis", $history_product_name, $history_quantity, $groupname);
                    $sql_query_insert->execute();
                    $sql_query_insert->close();
                }
            }
            // if delivery / sales order, fail
            else
            {
                header("Location: index.php?page=delivery_fail");
                exit();
            }
        }
    
        // change status to received / delivered
        // redirect to history.php
        if($_POST['history_order_type'] == 'Delivery')
        {
            if($conn->query("UPDATE history SET status = 'Delivered' WHERE id = '$history_id'"))
            {
                echo "History updated.";
            }
        }
        elseif ($_POST['history_order_type'] == 'Purchase')
        {
            if($conn->query("UPDATE history SET status = 'Purchased' WHERE id = '$history_id'"))
            {
                echo "History updated.";
            }
        }
    
        // redirect if successful
        header("Location: index.php?page=history");
    }


?>
