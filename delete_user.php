<?php
    $id = $_GET['id'];
    require_once('index.php');
    // sql to delete a record where GET id = accounts id
    $sql = "DELETE FROM accounts WHERE id = $id"; 

    if (mysqli_query($conn, $sql)) {
        header('Location: index.php?page=users'); 
        exit;
    } else {
        echo "Error: deleting record fail";
    }
?>
