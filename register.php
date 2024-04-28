<?=unregistered_header("Register")?>


<div class="form-content">
	<h1>Register Group</h1>
	<p class="form-redirect">  Already have an account? <a href="index.php?page=login">Login here</a></p>
	<form action="index.php?page=register" method="post" autocomplete="off">
		<input type="text" name="username" placeholder="Username" id="username" required>
		<input type="password" name="password" placeholder="Password" id="password" required>
		<input type="groupname" name="groupname" placeholder="Group Name" id="groupname" required>
		<input type="submit" value="Register">
	</form>
</div>


<?=footer()?>


<?php


$DATABASE_HOST = "localhost";
$DATABASE_USERNAME = "root";
$DATABASE_PASSWORD = "";
$DATABASE_NAME = "capstone";

// if there is an error with the connection, exit and display error
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USERNAME, $DATABASE_PASSWORD, $DATABASE_NAME);
if (mysqli_connect_errno()) 
{
	exit("Failed to connect to MySQL: " . mysqli_connect_error());
}

// check if data submitted exists from register form
if (!isset($_POST["username"], $_POST["password"], $_POST["groupname"])) 
{
	exit("");
}
// a form value must not be empty
if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["groupname"])) 
{
	exit("Please complete the registration form");
}
// username only has alphabetical characters or numbers
if (preg_match("/^[a-zA-Z0-9]+$/", $_POST["username"]) == 0) 
{
    exit("Username has non-alphabetical or non-numeric characters.");
}
// username only has alphabetical characters or numbers
if (preg_match("/^[a-zA-Z0-9]+$/", $_POST["groupname"]) == 0) 
{
    exit("Group name has non-alphabetical or non-numeric characters.");
}
// password is 5 to 20 characters log
if (strlen($_POST["password"]) < 5 || strlen($_POST["password"]) > 20) 
{
	exit("Password must be 5-20 characters long.");
}

// check if account with inputted username already exists
if ($sql_query = $con->prepare("SELECT id, password, permission, groupname FROM accounts WHERE username = ?")) 
{
	// bind parameters (s = string), 
	$sql_query->bind_param("s", $_POST["username"]);
	$sql_query->execute();
	$sql_query->store_result();

	// check if an account already exists under inputted username
	if ($sql_query->num_rows > 0) 
	{
		echo "This username already exists, please choose another.";
	} 
	else 
	{
		// username does not exist, insert new account
		if ($sql_query = $con->prepare("INSERT INTO accounts (username, password, permission, groupname) VALUES (?, ?, ?, ?)")) 
		{
			// hash the passwords so they are not plaintext in database
			$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
			$permission = "GroupAdmin";
			$sql_query->bind_param("ssss", $_POST["username"], $password, $permission, $_POST["groupname"]);
			$sql_query->execute();
			echo "You have successfully registered. You can now login.";
		} 
		else 
		{
			// something is wrong with the SQL statement, check that MySQL table exists will all fields.
			echo "MySQL statement failed to prepare";
		}
	}
	$sql_query->close();
} 
else 
{
    // something is wrong with the SQL statement, check that MySQL table exists will all fields.
    echo "MySQL statement failed to prepare";
}
$con->close();
?>
