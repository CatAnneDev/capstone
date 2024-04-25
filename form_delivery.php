<?php
# choose purchase orders or delivery orders
# add item to purchase/deliver
# fill out quantity
# ability to add more items
# submit

# redirect non-users to login
if ($_SESSION["loggedin"] != true)
{
    header("Location: index.php");
}

# prep purchase order form

# prep delivery form
$groupname = $_SESSION["groupname"];
$sql = "SELECT * FROM form_delivery WHERE groupname = ?";
$form_rows = $conn->execute_query($sql, [$groupname]);
?>

<?=nav_header("Delivery Form")?>


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
				<h1 class="h1-align">Delivery Form</h1>
				
				<!-- Customer Access: Add Delivery Item Modal -->
				<?php
				if ($_SESSION["permission"] == "customer" || $_SESSION["permission"] == "groupadmin")
				{
					echo "<button id='modal-open'>Add Item</button>";
				}
				?>
				<div id="user-modal" class="modal">
					<div class="modal-content">
						<span class="modal-close">&times;</span>
						<div class="form-content">
							<h1>Add Delivery Item</h1>
							<form action="index.php?page=form_delivery" method="post" autocomplete="off">
								<input type="text" name="product_name" placeholder="Product Name" id="product_name" required autocomplete="on">
								<input type="number" name="quantity" placeholder="Quantity" id="quantity" required>
								<input type="submit" value="Add Item">
							</form>
						</div>
					</div>
				</div>
			</div>

            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
					<th> </th>
                </tr>
                <?php foreach($form_rows as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= htmlspecialchars($row['quantity']) ?></td>
					<td><a href="delete_delivery.php?id=$product_id">Delete</a></td>
                </tr>
                <?php endforeach ?>
            </table>
        </div>
        <div class="flex-wrapper">
            <div class="submit_order">
                <form action="index.php?page=log_order" method="post" autocomplete="off">
                    <?php
                    // in progress
                    $_POST['order_type'] = 'delivery';
                    ?>
                    <button type="submit" value="Order">Order</button>
                </form>
            </div>
        </div>
	</body>
</html>

<?=footer()?>

<script type="text/JavaScript">
// modal script
var modal = document.getElementById("user-modal"); // modal
var open_button = document.getElementById("modal-open"); // open modal button
var close_button = document.getElementsByClassName("modal-close")[0]; // close modal button

// when user clicks button, open the modal 
open_button.onclick = function() 
{
  modal.style.display = "block";
}

// when user clicks on x, close modal
close_button.onclick = function() 
{
  modal.style.display = "none";
}

// when user clicks anywhere outside of modal, close it
window.onclick = function(event) 
{
  if (event.target == modal) 
  {
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
if (!isset($_POST["product_name"], $_POST["quantity"])) 
{
	exit("");
}


// FORM VALIDATION
// product name is 3 to 30 characters log
if (strlen($_POST["product_name"]) < 3 || strlen($_POST["product_name"]) > 30) 
{
	exit("Product Name must be 3-30 characters long.");
}

// check if account with inputted username already exists
if ($sql_query = $con->prepare("SELECT id, product_name, quantity, groupname FROM form_delivery WHERE product_name = ?")) {
	// bind parameters (s = string), 
	$sql_query->bind_param("s", $_POST["product_name"]);
	$sql_query->execute();
	$sql_query->store_result();

	// check if an account already exists under inputted username
	if ($sql_query->num_rows > 0) 
	{
		echo "This username already exists, please choose another.";
	} 
	else 
	{
		// Product doesn't exist, insert new product
		if ($sql_query = $con->prepare("INSERT INTO form_delivery (product_name, quantity, groupname) VALUES (?, ?, ?)")) 
		{
			$sql_query->bind_param("sis", $_POST["product_name"], $_POST["quantity"], $_SESSION["groupname"]);
			$sql_query->execute();

			echo '<p class="php-notice">New product added.</p>';
			
		} 
		else 
		{
			// Something is wrong with the SQL statement, so you must check to make sure your accounts table exists with all three fields.
			echo "MySQL statement failed to prepare";
		}
	}
	$sql_query->close();
} 
else 
{
	// Something is wrong with the SQL statement, so you must check to make sure your accounts table exists with all 3 fields.
	echo "MySQL statement failed to prepare";
}
$con->close();
?>

