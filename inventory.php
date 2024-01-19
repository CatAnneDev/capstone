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

<?=shop_header("Inventory")?>

<div class="content-wrapper">
  <div class="flex-wrapper">
    <h1 class="h1-align">Inventory</h1>

    <!-- Trigger/Open The Modal -->
    <button id="modal-button">Add Item</button>

    <!-- The Modal -->
    <div id="myModal" class="modal">

      <!-- Modal content -->
      <div class="modal-content">
        <span class="close">&times;</span>
        <p>Some text in the Modal..</p>
      </div>
    </div>
  </div>
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
  