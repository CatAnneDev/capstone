<?php
    $id = $_GET['id'];
    require_once('index.php');
    // sql to delete a user from table accounts where $_GET id = accounts id
    $sql_query = "DELETE FROM form_purchase WHERE id = $id"; 

    if (mysqli_query($conn, $sql_query)) 
    {
        header('Location: index.php?page=form_purchase'); 
        exit;
    }
    else 
    {
        echo "Error: deleting purchase item fail";
    }
?>
