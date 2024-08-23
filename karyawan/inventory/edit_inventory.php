<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "assetmanagement";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update data inventory
    $asset_id = $_POST['asset_id'];
    $ip_address = $_POST['ip_address'];
    $mac_address = $_POST['mac_address'];
    $location = $_POST['location'];
    $notes = $_POST['notes'];

    $sql = "UPDATE inventory_details SET 
                ip_address = ?, 
                mac_address = ?, 
                location = ?, 
                notes = ? 
            WHERE asset_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $ip_address, $mac_address, $location, $notes, $asset_id);

    if ($stmt->execute()) {
        header("Location: data_inventory.php?message=success");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    // Cek apakah 'id' ada di URL (id dari tabel assets)
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $asset_id = $_GET['id'];
        
        // Ambil data inventory dari database berdasarkan asset_id di inventory_details
        $sql = "SELECT * FROM inventory_details WHERE asset_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $asset_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Cek apakah data inventory ditemukan
        if ($result->num_rows > 0) {
            $data_inventory = $result->fetch_assoc();
        } else {
            echo "Data inventory tidak ditemukan.";
            exit;
        }
    } else {
        echo "Parameter 'id' tidak ditemukan dalam URL.";
        exit;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Inventory</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Edit Inventory</h2>
        <form method="POST" action="">
            <input type="hidden" name="asset_id" value="<?php echo htmlspecialchars($data_inventory['asset_id']); ?>">
            <div class="form-group">
                <label for="ip_address">IP Address:</label>
                <input type="text" class="form-control" id="ip_address" name="ip_address" value="<?php echo htmlspecialchars($data_inventory['ip_address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="mac_address">MAC Address:</label>
                <input type="text" class="form-control" id="mac_address" name="mac_address" value="<?php echo htmlspecialchars($data_inventory['mac_address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($data_inventory['location']); ?>" required>
            </div>
            <div class="form-group">
                <label for="notes">Catatan:</label>
                <textarea class="form-control" id="notes" name="notes" required><?php echo htmlspecialchars($data_inventory['notes']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
