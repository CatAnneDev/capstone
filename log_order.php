<?php
    // redirect non-users to login
    if ($_SESSION["loggedin"] != true)
    {
        header("Location: index.php");
    }
    
    require_once('index.php');
    
    // determine if order was delivery or purchase
    if($_POST['order_type'] == 'Delivery')
    {
        $sql_query = mysqli_query($conn, "SELECT * FROM form_delivery");
        $order_rows = $sql_query->fetch_all(MYSQLI_ASSOC);
    }
    elseif ($_POST['order_type'] == 'Purchase')
    {
        $sql_query = mysqli_query($conn, "SELECT * FROM form_purchase");
        $order_rows = $sql_query->fetch_all(MYSQLI_ASSOC);
    }
    
    // generate batch number
    $random_number = rand(0, 99);
    $batch_number = "P" . date("Y") . date("m") . date("d") . "R" . (string) $random_number;
    $status = "Pending";

    // for each row from the order, add it to history
    foreach($order_rows as $row)
    {
        if ($sql_query = $conn->prepare("INSERT INTO history (groupname, product_name, batch_number, order_type, status, quantity) VALUES (?, ?, ?, ?, ?, ?)"))
        {
            $sql_query->bind_param("sssssi", $_SESSION["groupname"], $row["product_name"], $batch_number, $_POST['order_type'], $status, $row["quantity"]);
            $sql_query->execute();
        }
    }

    // remove rows from order
    if($_POST['order_type'] == 'Delivery')
    {
        $sql_delete = $conn->prepare('TRUNCATE TABLE form_delivery;');
        $sql_delete->execute();
    }
    elseif ($_POST['order_type'] == 'Purchase')
    {
        $sql_delete = $conn->prepare('TRUNCATE TABLE form_purchase;');
        $sql_delete->execute();
    }

    // redirect if successful
    header("Location: index.php?page=log_confirmation");
?>
