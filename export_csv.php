<?php
// redirect non-users to login
if ($_SESSION["loggedin"] != true)
{
    header("Location: index.php");
}

// receive MySQL table name in post
$table_name = $_POST['export_table_name'];
$sql_query = "SELECT * FROM $table_name";
$sql_query_run = mysqli_query($conn, $sql_query);

// generate the number of columns and have the first row (header) be the names of the fields from the MySQL table
$number_of_cols = mysqli_num_fields($sql_query_run);
$headers = array();
for ($i = 0; $i < $number_of_cols; $i++) 
{
    $headers[] = mysqli_field_name($sql_query_run , $i);
}

// write and download the file
$fp = fopen('php://output', 'w');
if ($fp && $sql_query_run) 
{
    header('Content-Type: text/csv');
    $file_name = $table_name . "_export.csv";
    header('Content-Disposition: attachment; filename='. $file_name);
    header('Pragma: no-cache');
    header('Expires: 0');
    fputcsv($fp, $headers);
    while ($row = $sql_query_run->fetch_array(MYSQLI_NUM)) 
    {
        fputcsv($fp, array_values($row));
    }
    die;
}

function mysqli_field_name($sql_query_run, $field_offset)
{
    $properties = mysqli_fetch_field_direct($sql_query_run, $field_offset);
    return is_object($properties) ? $properties->name : null;
}
?>
