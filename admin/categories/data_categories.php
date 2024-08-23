<!DOCTYPE html>
<html>
<head>
    <title>Categories</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Categories</h2>
        <!-- add categories -->
        <a href="add_categories.php" class="btn btn-primary mb-3">Add New Category</a>
        <a href="/PLNproject/asset_management/home_admin.php" class="btn btn-secondary">Home</a>
        <!-- Tabel Data Categories -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Koneksi ke database
                    $conn = new mysqli('localhost', 'root', '', 'assetmanagement');
                    
                    // Cek koneksi
                    if ($conn->connect_error) {
                        die("Connection failed: {$conn->connect_error}");
                    }
                    
                    // Ambil data dari tabel 'categories'
                    $sql = "SELECT * FROM categories";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['description'] . "</td>";
                            echo "<td>" . $row['created_at'] . "</td>";
                            echo "<td>" . $row['updated_at'] . "</td>";
                            echo "<td>";
                            echo "<a href='edit_categories.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a> ";
                            echo "<a href='delete_categories.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No categories found</td></tr>";
                    }
                    
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
