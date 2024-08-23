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

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $id = $_POST['id'];
        $category_id = $_POST['category_id'];
        $unit = $_POST['unit']; 
        $name = $_POST['name'];
        $serial_number = $_POST['serial_number'];
        $purchase_date = $_POST['purchase_date'];
        $warranty_end_date = $_POST['warranty_end_date'];
        $people_name = $_POST['people_name'];
        $jabatan = $_POST['jabatan'];
        $status = $_POST['status'];

        // Update asset data
        $sql = "UPDATE assets SET 
                    category_id='$category_id', 
                    unit='$unit',
                    name='$name', 
                    serial_number='$serial_number', 
                    purchase_date='$purchase_date', 
                    warranty_end_date='$warranty_end_date', 
                    people_name='$people_name', 
                    jabatan='$jabatan', 
                    status='$status'
                WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            header("Location: data_asset.php?message=success");
        } else {
            echo "Error: $sql<br>{$conn->error}";
        }
        break;

    default:
        $id = $_GET['id'];
        $sql = "SELECT * FROM assets WHERE id='$id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "Asset not found";
            exit;
        }
        break;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Asset</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Edit Asset</h2>
        <form method="POST" action="edit_asset.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <div class="form-group">
                <label for="category_id">Category ID:</label>
                <input type="text" class="form-control" id="category_id" name="category_id" value="<?php echo htmlspecialchars($row['category_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="unit">Unit:</label> 
                <input type="text" class="form-control" id="unit" name="unit" required>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($row['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="serial_number">Serial Number:</label>
                <input type="text" class="form-control" id="serial_number" name="serial_number" value="<?php echo htmlspecialchars($row['serial_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="purchase_date">Purchase Date:</label>
                <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?php echo htmlspecialchars($row['purchase_date'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="warranty_end_date">Warranty End Date:</label>
                <input type="date" class="form-control" id="warranty_end_date" name="warranty_end_date" value="<?php echo htmlspecialchars($row['warranty_end_date'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="people_name">People Name:</label>
                <input type="text" class="form-control" id="people_name" name="people_name" value="<?php echo htmlspecialchars($row['people_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="jabatan">Jabatan:</label>
                <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?php echo htmlspecialchars($row['jabatan'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="active" <?php if(isset($row['status']) && $row['status'] == 'active') echo 'selected'; ?>>Active</option>
                    <option value="maintenance" <?php if(isset($row['status']) && $row['status'] == 'maintenance') echo 'selected'; ?>>Maintenance</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Asset</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
