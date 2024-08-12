<?php
$servername = 'localhost';
$dbname = 'assetmanagement';
$username = 'root';
$password = '';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: {$conn->connect_error}");
}

// Get the id from the URL
$id = $_GET['asset_id'];

// Delete the record from the database
$query = "DELETE FROM inventory_details WHERE id = $id";

if (mysqli_query($conn, $query)) {
    header("Location: inventory_details.php?message=success");
} else {
    header("Location: inventory_details.php?message=error");
}

// Close the database connection
mysqli_close($conn);
?>
