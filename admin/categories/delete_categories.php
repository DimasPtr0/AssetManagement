<?php
// Koneksi ke database
$servername = 'localhost';
$dbname = 'assetmanagement';
$username = 'root';
$password = '';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: {$conn->connect_error}");
}

// Proses Delete
$id = $_GET['id'] ?? null;
if ($id) {
    $sql = "DELETE FROM categories WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Data berhasil dihapus</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: $sql<br>{$conn->error}</div>";
    }
}

$conn->close();
header('Location: categories.php');
?>
