<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .table thead {
            background-color: #343a40;
            color: #fff;
        }

        .btn-primary,
        .btn-secondary {
            margin-bottom: 10px;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4">Inventory Details</h1>

        <!-- Pesan status operasi -->
        <?php if (isset($_GET['message'])) : ?>
            <div class="alert alert-<?php echo $_GET['message'] == 'success' ? 'success' : 'danger'; ?>">
                <?php
                echo $_GET['message'] == 'success' ? "Operation successful" : "An error occurred";
                ?>
            </div>
        <?php endif; ?>

        <!-- Tautan ke halaman tambah data -->
        <a href="add_inventory.php" class="btn btn-primary">Add Inventory</a>
        <a href="/PLNproject/asset_management/home_karyawan.php" class="btn btn-secondary mb-3">Home</a>

        <!-- Tabel Inventory Details -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Asset ID</th>
                        <th>IP Address</th>
                        <th>MAC Address</th>
                        <th>Location</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
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
                    // Fetch all inventory details from the database
                    $query = "SELECT * FROM inventory_details";
                    $result = mysqli_query($conn, $query);

                    // Check if there are any records in the database
                    if (mysqli_num_rows($result) > 0) {
                        // Loop through each record and display the data
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['asset_id'] . "</td>";
                            echo "<td>" . $row['ip_address'] . "</td>";
                            echo "<td>" . $row['mac_address'] . "</td>";
                            echo "<td>" . $row['location'] . "</td>";
                            echo "<td>" . $row['notes'] . "</td>";
                            echo "<td>";
                            echo "<a href='edit_inventory.php?id=" . $row['asset_id'] . "' class='btn btn-warning btn-sm'>Edit</a> ";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No records found</td></tr>";
                    }

                    // Close the database connection
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
