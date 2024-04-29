<?php
$site_title = "Purchase Form";
$username = $_SESSION["username"];
nav_header($site_title, $username);

// prep purchase order
$groupname = $_SESSION["groupname"];
$sql = "SELECT * FROM form_purchase WHERE groupname = ?";
$form_rows = $conn->execute_query($sql, [$groupname]);
?>


<div class="content-wrapper">
	<div class="flex-wrapper">
		<h1 class="h1-align">Purchase Form</h1>
		
		<!-- Add Purchase Item Modal -->
		<button id='modal-open'>Add Item</button>
		<div id="modal-box" class="modal">
			<div class="modal-content">
				<span class="modal-close">&times;</span>
				<div class="form-content">
					<h1>Add Purchase Item</h1>
					<form action="index.php?page=form_purchase" method="post" autocomplete="off">
						<input type="text" name="product_name" placeholder="Product Name" id="product_name" required>
						<input type="number" name="quantity" placeholder="Quantity" id="quantity" min="1" required>
						<input type="submit" value="Add Item">
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Table of Items to Purchase -->
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
			<?php $product_id = $row['id']; ?>
			<td><a href="delete_purchase_item.php?id=<?= $row['id']?>">Delete</a></td>
		</tr>
		<?php endforeach ?>
	</table>
</div>
<!-- Submit Purchase Order -->
<?php
if ($form_rows->num_rows  > 0)
{
	?>
	<div class="content-wrapper">
		<div class="flex-wrapper">
			<div class="submit_order">
				<form action="index.php?page=log_order" method="post" autocomplete="off">
					<input type="hidden" id="order_type" name="order_type" value="Purchase">
					<button type="submit">Order Purchase</button>
				</form>
			</div>
		</div>
	</div>
	<?php
}
?>

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

// check if data submitted exists from purchase form
if (!isset($_POST["product_name"], $_POST["quantity"])) 
{
	exit("");
}
// product name is 3 to 30 characters log
if (strlen($_POST["product_name"]) < 3 || strlen($_POST["product_name"]) > 30) 
{
	exit("Product Name must be 3-30 characters long.");
}

// prepare statement to avoid injection
if ($sql_query = $con->prepare("SELECT id, product_name, quantity, groupname FROM form_purchase WHERE product_name = ?")) 
{
	// bind parameters (s = string)
	$sql_query->bind_param("s", $_POST["product_name"]);
	$sql_query->execute();
	$sql_query->store_result();

	// check if inputted purchase product_name already exist
	if ($sql_query->num_rows > 0) 
	{
		echo "This product already exists, please choose another.";
	} 
	else 
	{
		// product doesn't exist, insert new product
		if ($sql_query = $con->prepare("INSERT INTO form_purchase (product_name, quantity, groupname) VALUES (?, ?, ?)")) 
		{
			$sql_query->bind_param("sis", $_POST["product_name"], $_POST["quantity"], $_SESSION["groupname"]);
			$sql_query->execute();

			header("Location: index.php?page=form_purchase");
		} 
		else 
		{
			echo "MySQL statement failed to prepare";
		}
	}
	$sql_query->close();
} 
else 
{
	echo "MySQL statement failed to prepare";
}
$con->close();
?>
