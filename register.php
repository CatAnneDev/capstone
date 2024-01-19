<?=unregistered_header("Register")?>

<html>
	<head>
		<meta charset="utf-8">
		<title>Register</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
		<div class="register">
			<h1>Register Group</h1>
			<p class="form-redirect">  Already have an account? <a href="index.php?page=login">Login here</a></p>
			<form action="index.php?page=register" method="post" autocomplete="off">
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<label for="groupname">
					<i class="fas fa-users"></i>
				</label>
				<input type="groupname" name="groupname" placeholder="Group Name" id="groupname" required>
				<input type="submit" value="Register">
			</form>
		</div>
	</body>
</html>

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

// FORM VALIDATION
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
if ($stmt = $con->prepare("SELECT id, password, permission, groupname FROM accounts WHERE username = ?")) {
	// bind parameters (s = string), 
	$stmt->bind_param("s", $_POST["username"]);
	$stmt->execute();
	$stmt->store_result();

	// check if an account already exists under inputted username
	if ($stmt->num_rows > 0) 
	{
		echo "This username already exists, please choose another.";
	} 
	else 
	{
		// Username doesn"t exists, insert new account
		if ($stmt = $con->prepare("INSERT INTO accounts (username, password, permission, groupname) VALUES (?, ?, ?, ?)")) 
		{
			// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
			$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
			$permission = "groupadmin";
			$stmt->bind_param("ssss", $_POST["username"], $password, $permission, $_POST["groupname"]);
			$stmt->execute();
			echo "You have successfully registered. You can now login.";
		} 
		else 
		{
			// Something is wrong with the SQL statement, so you must check to make sure your accounts table exists with all three fields.
			echo "MySQL statement failed to prepare";
		}
	}
	$stmt->close();
} 
else 
{
	// Something is wrong with the SQL statement, so you must check to make sure your accounts table exists with all 3 fields.
	echo "MySQL statement failed to prepare";
}
$con->close();
?>
