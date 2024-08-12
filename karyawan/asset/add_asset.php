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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $serial_number = $_POST['serial_number'];
    $purchase_date = $_POST['purchase_date'];
    $warranty_end_date = $_POST['warranty_end_date'];
    $people_name = $_POST['people_name'];
    $jabatan = $_POST['jabatan'];

    // Cek apakah serial_number sudah ada di database
    $check_sql = "SELECT * FROM assets WHERE serial_number = '$serial_number'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // Jika serial_number sudah ada, tampilkan pesan error
        header("Location: data_asset.php?message=duplicate_serial_number");
    } else {
        // Jika serial_number belum ada, lakukan INSERT
        $sql = "INSERT INTO assets (category_id, name, serial_number, purchase_date, warranty_end_date, people_name, jabatan) VALUES ('$category_id', '$name', '$serial_number', '$purchase_date', '$warranty_end_date', '$people_name', '$jabatan')";

        if ($conn->query($sql) === TRUE) {
            header("Location: data_asset.php?message=success");
        } else {
            header("Location: data_asset.php?message=error");
        }
    }
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Aset</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Tambah Aset</h2>
        <form method="POST" action="add_asset.php">
            <div class="form-group">
                <label for="category_id">Category ID:</label>
                <input type="text" class="form-control" id="category_id" name="category_id" required>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="serial_number">Serial Number:</label>
                <input type="text" class="form-control" id="serial_number" name="serial_number" required>
            </div>
            <div class="form-group">
                <label for="purchase_date">Purchase Date:</label>
                <input type="date" class="form-control" id="purchase_date" name="purchase_date" required>
            </div>
            <div class="form-group">
                <label for="warranty_end_date">Warranty End Date:</label>
                <input type="date" class="form-control" id="warranty_end_date" name="warranty_end_date" required>
            </div>
            <div class="form-group">
                <label for="people_name">People Name:</label>
                <input type="text" class="form-control" id="people_name" name="people_name" required>
            </div>
            <div class="form-group">
                <label for="jabatan">Jabatan:</label>
                <input type="text" class="form-control" id="jabatan" name="jabatan" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>