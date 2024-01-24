<?php
# view inventory table
# edit and remove buttons on each row
# export to xlsx

# redirect non-users to login
if ($_SESSION["loggedin"] != true)
{
    header("Location: index.php");
}

# prep inventory table
$result = mysqli_query($conn, "SELECT * FROM inventory");
$inventory_rows = $result->fetch_all(MYSQLI_ASSOC);

# prep edit and remove buttons


?>

<?=nav_header("Inventory")?>

<div class="content-wrapper">
  <div class="flex-wrapper">
    <h1 class="h1-align">Inventory</h1>

    <!-- Manager+ Access: Add Inventory Modal -->
    <?php
				if ($_SESSION["permission"] == "manager" || $_SESSION["permission"] == "groupadmin")
				{
					echo "<button id='modal-open'>Add Item</button>";
				}
    ?>
    <div id="inventory-modal" class="modal">
      <div class="modal-content">
        <span class="modal-close">&times;</span>
        <div class="form-content">
          <h1>Add New Product</h1>
          <form action="index.php?page=inventory" method="post" autocomplete="off">
            <input type="text" name="product_name" placeholder="Product Name" id="product_name" required>
            <input type="number" name="quantity" placeholder="Quantity" id="quantity" required>
            <input type="submit" value="Add Product">
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Inventory Table -->
  <table>
      <tr>
          <th>ID</th>
          <th>Product Name</th>
          <th>Quantity</th>
      </tr>
      <?php foreach($inventory_rows as $row): ?>
      <tr>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['product_name']) ?></td>
          <td><?= htmlspecialchars($row['quantity']) ?></td>
      </tr>
      <?php endforeach ?>
  </table>
</div>

<?=footer()?>


<script type="text/JavaScript">
// modal script
var modal = document.getElementById("inventory-modal"); // modal
var open_btn = document.getElementById("modal-open"); // open modal button
var close_btn = document.getElementsByClassName("modal-close")[0]; // close modal button

// when user clicks button, open the modal 
open_btn.onclick = function() 
{
  modal.style.display = "block";
}

// when user clicks on x, close modal
close_btn.onclick = function() 
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

# if there is an error with the connection, exit and display error
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USERNAME, $DATABASE_PASSWORD, $DATABASE_NAME);
if (mysqli_connect_errno()) 
{
	exit("Failed to connect to MySQL: " . mysqli_connect_error());
}

# check if data submitted exists from register form
if (!isset($_POST["product_name"], $_POST["quantity"])) 
{
	exit("");
}

# FORM VALIDATION
# password is 5 to 20 characters long
if (strlen($_POST["product_name"]) < 5 || strlen($_POST["product_name"]) > 20) 
{
	exit("Product name must be 5-20 characters long.");
}

# check if account with inputted username already exists
if ($stmt = $con->prepare("SELECT id, quantity FROM inventory WHERE product_name = ?")) {
	# bind parameters (s = string), 
	$stmt->bind_param("s", $_POST["product_name"]);
	$stmt->execute();
	$stmt->store_result();

	# check if an account already exists under inputted username
	if ($stmt->num_rows > 0) 
	{
		echo "This product already exists.";
	} 
	else 
	{
		# Product doesn"t exists, insert new account
		if ($stmt = $con->prepare("INSERT INTO inventory (product_name, quantity) VALUES (?, ?)")) 
		{
			$stmt->bind_param("si", $_POST["product_name"], $_POST["quantity"]);
			$stmt->execute();

			echo '<p class="php-notice">New product created.</p>';
			
		} 
		else 
		{
			echo "MySQL statement failed to prepare";
		}
	}
	$stmt->close();
} 
else 
{
	echo "MySQL statement failed to prepare";
}
$con->close();
?>
