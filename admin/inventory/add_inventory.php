<?php
$servername = 'localhost';
$dbname = 'assetmanagement';
$username = 'root';
$password = '';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: {$conn->connect_error}");
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $asset_id = $_POST['asset_id'];
    $ip_address = $_POST['ip_address'];
    $mac_address = $_POST['mac_address'];
    $location = $_POST['location'];
    $notes = $_POST['notes'];

    // Validate the form data (you can add your own validation logic here)
    if (empty($asset_id) || empty($ip_address) || empty($mac_address) || empty($location)) {
        echo 'Error: All fields are required.';
        exit;
    }

    // Insert the inventory into the database
    $query = "INSERT INTO inventory_details (asset_id, ip_address, mac_address, location, notes) VALUES ('$asset_id', '$ip_address', '$mac_address', '$location', '$notes')";
    $result = mysqli_query($conn, $query);

    // Check if the insertion was successful
    if ($result) {
        // Redirect to the inventory details page
        header('Location: data_inventory.php');
        exit;
    } else {
        // Display an error message
        echo 'Error: ' . mysqli_error($conn);
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Inventory</title>
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
        <h1 class="mb-4">Add Inventory</h1>

        <!-- Form for adding inventory -->
        <form action="add_inventory.php" method="POST">
            <div class="form-group">
                <label for="asset_id">Asset ID</label>
                <input type="text" class="form-control" id="asset_id" name="asset_id" required>
            </div>
            <div class="form-group">
                <label for="ip_address">IP Address</label>
                <input type="text" class="form-control" id="ip_address" name="ip_address" required>
            </div>
            <div class="form-group">
                <label for="mac_address">MAC Address</label>
                <input type="text" class="form-control" id="mac_address" name="mac_address" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea class="form-control" id="notes" name="notes"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Inventory</button>
            <a href="data_inventory.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
