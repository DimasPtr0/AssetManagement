<?php
// Koneksi ke database
$servername = 'localhost';
$dbname = 'assetmanagement';
$username = 'root';
$password = '';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM assets WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: /PLNproject/asset_management/home_admin.php?message=succes deleted");
    } else {
        header("Location: /PLNproject/asset_management/home_admin.php?message=error");
    }
    exit;
}

$conn->close();
?>
