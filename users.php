<?php
# table of users (view)
# table of users (edit and remove options)
# add user button

# redirect non-users to login
if ($_SESSION["loggedin"] != true)
{
    header("Location: index.php");
}

# prep users table
$groupname = $_SESSION["groupname"];
$sql = "SELECT * FROM accounts WHERE groupname = ?";
$account_rows = $conn->execute_query($sql, [$groupname]);

?>

<?=shop_header("Users")?>


<html>
	<head>
		<meta charset="utf-8">
		<title>Register</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
        <div class="content-wrapper">
			<div class="flex-wrapper">
				<h1 class="h1-align">Users</h1>

				<!-- Trigger/Open The Modal -->
				<button id="modal-button">Add User</button>

				<!-- The Modal -->
				<div id="myModal" class="modal">

					<!-- Modal content -->
					<div class="modal-content">
						<span class="close">&times;</span>
						<div>
							<h1>Create New Group User</h1>
							<form action="index.php?page=users" method="post" autocomplete="off">
								<label for="username">
									<i class="fas fa-user"></i>
								</label>
								<input type="text" name="username" placeholder="Username" id="username" required>
								<label for="password">
									<i class="fas fa-lock"></i>
								</label>
								<input type="text" name="password" placeholder="Password" id="password" required>
								<label for="permission">
									<i class="fas fa-users"></i>
								</label>
								<div class="col-md-4">
									<select id="permission" name="permission" class="form-control">
										<option value="groupadmin">Group Admin</option>
										<option value="manager">Manager</option>
										<option value="customer">Customer</option>
										<option value="inquiry">Inquiry</option>
									</select>
								</div>
								<input type="submit" value="Add User">
							</form>
						</div>
					</div>
				</div>
			</div>

            <table>
                <tr>
                    <th>Username</th>
                    <th>Permission</th>
                </tr>
                <?php foreach($account_rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['permission']) ?></td>
                </tr>
                <?php endforeach ?>
            </table>
        </div>
	</body>
</html>

<?=footer()?>

<script type="text/JavaScript">
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("modal-button");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
    modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
    modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
    }
</script>


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
if (!isset($_POST["username"], $_POST["password"], $_POST["permission"])) 
{
	exit("");
}

// FORM VALIDATION
// a form value must not be empty
if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["permission"])) 
{
	exit("Please complete the registration form");
}
// username only has alphabetical characters or numbers
if (preg_match("/^[a-zA-Z0-9]+$/", $_POST["username"]) == 0) 
{
    exit("Username has non-alphabetical or non-numeric characters.");
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
			$stmt->bind_param("ssss", $_POST["username"], $password, $_POST["permission"], $_SESSION["groupname"]);
			$stmt->execute();

			echo '<p class="php-notice">New user created.</p>';
			
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

