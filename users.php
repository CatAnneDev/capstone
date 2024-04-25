<?php
// redirect non-users to login
if ($_SESSION["loggedin"] != true)
{
    header("Location: index.php");
}

// (manager) view a table of users
// (groupadmin) add or remove users below groupadmin
if ($_SESSION["permission"] == "customer")
{
    header("Location: index.php?page=forms");
}
if ($_SESSION["permission"] == "inquiry")
{
    header("Location: index.php?page=inventory");
}

// prep users table
$groupname = $_SESSION["groupname"];
$sql = "SELECT * FROM accounts WHERE groupname = ?";
$account_rows = $conn->execute_query($sql, [$groupname]);
?>


<?=nav_header("Users")?>


<html>
	<head>
		<meta charset="utf-8">
		<title>Users</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
        <div class="content-wrapper">
			<div class="flex-wrapper">
				<h1 class="h1-align">Users</h1>
				
				<!-- Groupadmin Access: Add User Modal -->
				<?php
				if ($_SESSION["permission"] == "groupadmin")
				{
					echo "<button id='modal-open'>Add User</button>";
				}
				?>
				<div id="modal-box" class="modal">
					<div class="modal-content">
						<span class="modal-close">&times;</span>
						<div class="form-content">
							<h1>Create New Group User</h1>
							<form action="index.php?page=users" method="post" autocomplete="off">
								<input type="text" name="username" placeholder="Username" id="username" required>
								<input type="text" name="password" placeholder="Password" id="password" required>
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
					<?php if($_SESSION["permission"] == "groupadmin"){echo "<th> </th>";} ?>
                </tr>
                <?php foreach($account_rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['permission']) ?></td>
					<?php 
					if($_SESSION["permission"] == "groupadmin")
						{
							$user_id = $row['id'];
							if($row['permission'] != "groupadmin")
							{
								echo <<<EOT
								<td><a href="delete_user.php?id=$user_id">Delete</a></td>
								EOT;
							}
							else
							{
								echo "<td> </td>";
							}
						} 
					?>
                </tr>
                <?php endforeach ?>
            </table>
        </div>
	</body>
</html>

<?=footer()?>

<script type="text/JavaScript">
	// modal, open modal button, and close modal button
	var modal = document.getElementById("modal-box");
	var open_button = document.getElementById("modal-open");
	var close_button = document.getElementsByClassName("modal-close")[0];

	// open the modal on user click
	open_button.onclick = function() 
	{
		modal.style.display = "block";
	}
	// close modal on user clicks x
	close_button.onclick = function() 
	{
		modal.style.display = "none";
	}
	// close modal on user clicks outside of modal
	window.onclick = function(event) 
	{
		if (event.target == modal) 
		{
			modal.style.display = "none";
		}
	}
</script>


<?php
// PHP to add user to MySQL accounts table
$DATABASE_HOST = "localhost";
$DATABASE_USERNAME = "root";
$DATABASE_PASSWORD = "";
$DATABASE_NAME = "capstone";

// if there is an error with connection, exit and display error
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USERNAME, $DATABASE_PASSWORD, $DATABASE_NAME);
if (mysqli_connect_errno()) 
{
	exit("Failed to connect to MySQL: " . mysqli_connect_error());
}

// check if data submitted exists from user form
if (!isset($_POST["username"], $_POST["password"], $_POST["permission"])) 
{
	exit("");
}
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

// prepare statement to avoid injection
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
			// hash the password instead of storing plaintext in database
			$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
			$sql_query->bind_param("ssss", $_POST["username"], $password, $_POST["permission"], $_SESSION["groupname"]);
			$sql_query->execute();

			echo '<p class="php-notice">New user created.</p>';
		} 
		else 
		{
            // something is wrong with the SQL statement, check that MySQL table exists will all fields.
			echo "MySQL statement failed to prepare. Check that columns align with parameters.";
		}
	}
	$sql_query->close();
} 
else 
{
    // something is wrong with the SQL statement, check that MySQL table exists will all fields.
	echo "MySQL statement failed to prepare. Check that columns align with parameters.";
}
$con->close();
?>
