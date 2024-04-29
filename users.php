<?php
// redirect non-users to login
$site_title = "Users";
$username = $_SESSION["username"];
nav_header($site_title, $username);

// (Manager) view a table of users
// (GroupAdmin) add or remove users below GroupAdmin
if ($_SESSION["permission"] == "Employee")
{
    header("Location: index.php?page=forms");
}
if ($_SESSION["permission"] == "Inquiry")
{
    header("Location: index.php?page=inventory");
}

// prep users table
$groupname = $_SESSION["groupname"];
$sql = "SELECT * FROM accounts WHERE groupname = ?";
$account_rows = $conn->execute_query($sql, [$groupname]);
?>


<div class="content-wrapper">
	<div class="flex-wrapper">
		<h1 class="h1-align">Users</h1>
		
		<!-- GroupAdmin Access: Add User Modal -->
		<?php
		if ($_SESSION["permission"] == "GroupAdmin")
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
								<option value="GroupAdmin">Group Admin</option>
								<option value="Manager">Manager</option>
								<option value="Employee">Employee</option>
								<option value="Inquiry">Inquiry</option>
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
			<?php if($_SESSION["permission"] == "GroupAdmin"){echo "<th> </th>";} ?>
		</tr>
		<?php foreach($account_rows as $row): ?>
		<tr>
			<td><?= htmlspecialchars($row['username']) ?></td>
			<td><?= htmlspecialchars($row['permission']) ?></td>
			<?php 
			if($_SESSION["permission"] == "GroupAdmin")
				{
					$user_id = $row['id'];
					if($row['permission'] != "GroupAdmin")
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

			header("Location: index.php?page=users");
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
