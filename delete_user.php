<?php
    // redirect non-users to login
    if ($_SESSION["loggedin"] != true)
    {
        header("Location: index.php");
    }
    
    $id = $_GET['id'];
    require_once('index.php');
    // sql to delete a user from table accounts where $_GET id = accounts id
    $sql_query = "DELETE FROM accounts WHERE id = $id"; 

    if (mysqli_query($conn, $sql_query)) 
    {
        header('Location: index.php?page=users'); 
        exit;
    }
    else 
    {
        echo "Error: deleting user fail";
    }
?>
