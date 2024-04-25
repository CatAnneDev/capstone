<?php
    require_once('index.php');
    
    $result = mysql_query("SELECT id, name FROM form_delivery");

    while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
        printf("ID: %s  Name: %s", $row[0], $row[1]);  
    }

    if ($sql_query = $conn->prepare("INSERT INTO history (groupname, product_name, batch_number, status) VALUES (?, ?, ?, ?)"))
    {
        $sql_query->bind_param("sssi", $_SESSION["groupname"], $_POST["product_name"], $_POST["batch_number"], $_POST['order_type']);
        $sql_query->execute();
    }

    if (mysqli_query($conn, $sql)) {
        header('Location: index.php?page=log_confirmation'); 
        exit;
    } else {
        echo "Error: adding history record fail";
    }
    
?>
