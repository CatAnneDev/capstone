<?php
$DATABASE_HOST = "localhost";
$DATABASE_USERNAME = "root";
$DATABASE_PASSWORD = "";
$DATABASE_NAME = "capstone";

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USERNAME, $DATABASE_PASSWORD, $DATABASE_NAME);
// if connection error, exit and display error
if ( mysqli_connect_errno() ) 
{
	exit("Failed to connect to MySQL: " . mysqli_connect_error());
}

// check if data submitted exists from form
if ( !isset($_POST["username"], $_POST["password"]) ) 
{
	exit("Please fill both the username and password fields!");
}

// prepare SQL prevents injection
if ($sql_query = $con->prepare("SELECT account_id, password, groupname, permission FROM accounts WHERE username = ?")) 
{
	// bind parameters (s = string)
	$sql_query->bind_param('s', $_POST["username"]);
	$sql_query->execute();
	$sql_query->store_result();

    // check if account exists
    if ($sql_query->num_rows > 0) 
    {
        $sql_query->bind_result($id, $password, $groupname, $permission);
        $sql_query->fetch();
        // account exists. Check if password valid, then user is logged in and create a session
        if (password_verify($_POST["password"], $password)) 
        {
            session_regenerate_id();
            $_SESSION["loggedin"] = TRUE;
            $_SESSION["username"] = $_POST["username"];
            $_SESSION["id"] = $id;
            $_SESSION["permission"] = $permission;
            $_SESSION["groupname"] = $groupname;
            echo "Welcome " . $_SESSION["username"] . "!";
            header("location: index.php?page=inventory"); // redirect
        } 
        else 
        {
            // invalid password
            echo "Incorrect username and/or password!";
        }
    } 
    else 
    {
        // invalid username
        echo "Incorrect username and/or password!";
    }
	$sql_query->close();
}
?>
