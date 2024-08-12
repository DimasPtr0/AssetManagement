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

// Set the number of records per page
$records_per_page = 10;

// Get the current page number from the URL, default to 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $records_per_page;

// Query to get the total number of records
$total_records_query = "SELECT COUNT(*) FROM assets";
$total_records_result = $conn->query($total_records_query);
$total_records_row = $total_records_result->fetch_row();
$total_records = $total_records_row[0];

// Calculate the total number of pages
$total_pages = ceil($total_records / $records_per_page);

// Query to get the records for the current page
$sql = "SELECT a.*, i.ip_address, i.mac_address, i.location, i.notes 
        FROM assets a 
        LEFT JOIN inventory_details i ON a.id = i.asset_id
        LIMIT $start_from, $records_per_page";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Asset</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h2 {
            color: #343a40;
        }
        table {
            margin-bottom: 20px;
        }
        .thead-dark {
            background-color: #343a40;
            color: #ffffff;
        }
        .pagination {
            justify-content: center;
        }
        .page-link {
            color: #343a40;
        }
        .page-item.active .page-link {
            background-color: #343a40;
            border-color: #343a40;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Data Asset</h2>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Category ID</th>
                    <th>Name</th>
                    <th>Serial Number</th>
                    <th>People Name</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                    <th>IP Address</th>
                    <th>MAC Address</th>
                    <th>Location</th>
                    <th>Notes</th>
                    <th>Purchase Date</th>
                    <th>Warranty End Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['category_id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['serial_number'] . "</td>";
                        echo "<td>" . $row['people_name'] . "</td>";
                        echo "<td>" . $row['jabatan'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td>" . $row['ip_address'] . "</td>";
                        echo "<td>" . $row['mac_address'] . "</td>";
                        echo "<td>" . $row['location'] . "</td>";
                        echo "<td>" . $row['notes'] . "</td>";
                        echo "<td>" . $row['purchase_date'] . "</td>";
                        echo "<td>" . $row['warranty_end_date'] . "</td>";
                        echo "<td><a class='btn btn-warning btn-sm' href='edit_asset.php?id=" . $row['id'] . "'>Edit</a> <a class='btn btn-danger btn-sm' href='delete_asset.php?id=" . $row['id'] . "'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='14'>No assets found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <nav>
            <ul class="pagination">
                <?php
                // Generate pagination links
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active = $i == $page ? 'active' : '';
                    echo "<li class='page-item $active'><a class='page-link' href='data_asset.php?page=" . $i . "'>" . $i . "</a></li>";
                }
                ?>
            </ul>
        </nav>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
