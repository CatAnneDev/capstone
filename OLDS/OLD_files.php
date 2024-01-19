<?php
if ($_SESSION["loggedin"] != true)
{
    header("Location: index.php");
}
?>

<?=shop_header("Files")?>

<div class="recentlyadded content-wrapper">
    <h1>Inventory Forms</h2>
    <div class="products">
        <h3>Spreadsheets</h3>
        <h3>Forms</h3>
    </div>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Viewer</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <h1>CSV Viewer</h1>
        <!-- File Upload Form -->
        <form action="index.php?page=files" method="post" enctype="multipart/form-data">
            <label for="csvFile">Upload CSV File:</label>
            <input type="file" name="csvFile" id="csvFile" accept=".csv" required>
            <button type="submit">Upload</button>
        </form>

        <?php
            // Handle file upload
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {
                $uploadedFile = $_FILES['csvFile'];

                if ($uploadedFile['error'] === UPLOAD_ERR_OK) {
                    $uploadPath = 'uploads/';
                    $uploadedFileName = $uploadPath . basename($uploadedFile['name']);

                    if (move_uploaded_file($uploadedFile['tmp_name'], $uploadedFileName)) {
                        echo '<p>File uploaded successfully!</p>';
                    } else {
                        echo '<p>Error uploading file.</p>';
                    }
                } else {
                    echo '<p>Error uploading file. Please try again.</p>';
                }
            }
        ?>

        <!-- File Selection Dropdown -->
        <form action="index.php?page=files" method="post">
            <label for="selectFile">Select CSV File:</label>
            <select name="selectFile" id="selectFile" required>
                <?php
                    // List all CSV files in the 'uploads' directory
                    $uploadPath = 'uploads/';
                    $files = glob($uploadPath . '*.csv');
                    
                    foreach ($files as $file) {
                        echo '<option value="' . basename($file) . '">' . basename($file) . '</option>';
                    }
                ?>
            </select>
            <button type="submit">View</button>
        </form>

        <?php
            // Display selected CSV file
            if (isset($_POST['selectFile'])) {
                $selectedFile = $_POST['selectFile'];
                $selectedFilePath = $uploadPath . $selectedFile;
                echo $selectedFilePath;
                
                if (file_exists($selectedFilePath)) {
                    // Display the selected CSV file
                    if (($handle = fopen($selectedFilePath, 'r')) !== FALSE) {
                        echo '<table>';
                        $isHeader = true;

                        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                            echo '<tr>';
                            
                            foreach ($data as $value) {
                                if ($isHeader) {
                                    echo '<th>' . htmlspecialchars($value) . '</th>';
                                } else {
                                    echo '<td>' . htmlspecialchars($value) . '</td>';
                                }
                            }

                            echo '</tr>';
                            $isHeader = false;
                        }

                        echo '</table>';
                        fclose($handle);
                    } else {
                        echo '<p>Error opening the CSV file</p>';
                    }
                } else {
                    echo '<p>Selected file does not exist</p>';
                }
            }
        ?>
    </div>
</body>
</html>

<?=footer()?>

