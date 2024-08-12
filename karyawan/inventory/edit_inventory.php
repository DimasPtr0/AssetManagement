<?php
// Include database connection file
$servername = 'localhost';
$dbname = 'assetmanagement';
$username = 'root';
$password = '';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $id = $_POST['id'];
    $asset_id = $_POST['asset_id'];
    $ip_address = $_POST['ip_address'];
    $mac_address = $_POST['mac_address'];
    $location = $_POST['location'];
    $notes = $_POST['notes'];

    // Update the record in the database
    $query = "UPDATE inventory_details SET asset_id = '$asset_id', ip_address = '$ip_address', mac_address = '$mac_address', location = '$location', notes = '$notes' WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        // Redirect to inventory details page
        header("Location: inventory_details.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inventory</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4">Edit Inventory</h1>
        
        <?php
        // Include database connection file
        include_once "db_connect.php";

        // Get the id from the URL
        $id = $_GET['id'];

        // Fetch the record from the database
        $query = "SELECT * FROM inventory_details WHERE id = $id";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        ?>

        <!-- Form for editing inventory -->
        <form action="process_edit_inventory.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
            <div class="form-group">
                <label for="asset_id">Asset ID</label>
                <input type="text" class="form-control" id="asset_id" name="asset_id" value="<?php echo htmlspecialchars($row['asset_id']); ?>" required>
            </div>
            <div class="form-group">
                <label for="ip_address">IP Address</label>
                <input type="text" class="form-control" id="ip_address" name="ip_address" value="<?php echo htmlspecialchars($row['ip_address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="mac_address">MAC Address</label>
                <input type="text" class="form-control" id="mac_address" name="mac_address" value="<?php echo htmlspecialchars($row['mac_address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($row['location']); ?>" required>
            </div>
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea class="form-control" id="notes" name="notes"><?php echo htmlspecialchars($row['notes']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Inventory</button>
            <a href="inventory_details.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
