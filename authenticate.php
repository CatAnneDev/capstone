<?php

$DATABASE_HOST = "localhost";
$DATABASE_USERNAME = "root";
$DATABASE_PASSWORD = "";
$DATABASE_NAME = "capstone";

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USERNAME, $DATABASE_PASSWORD, $DATABASE_NAME);
# if connection error, exit and display error
if ( mysqli_connect_errno() ) 
{
	exit("Failed to connect to MySQL: " . mysqli_connect_error());
}

# check if data submitted exists from login form
if ( !isset($_POST["username"], $_POST["password"]) ) 
{
	exit("Please fill both the username and password fields!");
}

# prepare SQL prevents injection
if ($stmt = $con->prepare("SELECT id, password, groupname FROM accounts WHERE username = ?")) 
{
	# bind parameters (s = string)
	$stmt->bind_param('s', $_POST["username"]);
	$stmt->execute();
	$stmt->store_result();

    # check if account exists
    if ($stmt->num_rows > 0) 
    {
        $stmt->bind_result($id, $password, $groupname);
        $stmt->fetch();
        # account exists. Check if password valid, then user is logged in and create a session
        if (password_verify($_POST["password"], $password)) 
        {
            session_regenerate_id();
            $_SESSION["loggedin"] = TRUE;
            $_SESSION["name"] = $_POST["username"];
            $_SESSION["id"] = $id;
            $_SESSION["permission"] = $permission;
            $_SESSION["groupname"] = $groupname;
            echo "Welcome " . $_SESSION["name"] . "!";
            header("location: index.php?page=inventory"); # redirect
        } 
        else 
        {
            echo "Incorrect username and/or password!"; # invalid password
        }
    } 
    else 
    {
        echo "Incorrect username and/or password!"; # invalid username
    }

	$stmt->close();
}
?>
