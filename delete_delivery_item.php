<?php
    // redirect non-users to login
    if ($_SESSION["loggedin"] != true)
    {
        header("Location: index.php");
    }
    
    $target_id = $_GET['id'];
    require_once('index.php');
    // sql to delete a user from table accounts where $_GET id = accounts id
    $sql_query = "DELETE FROM form_delivery WHERE delivery_id = $target_id"; 

    if (mysqli_query($conn, $sql_query)) 
    {
        header('Location: index.php?page=form_delivery'); 
        exit;
    }
    else 
    {
        echo "Error: deleting sales order item fail";
    }
?>
