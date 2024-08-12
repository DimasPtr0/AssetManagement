<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "assetmanagement";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: {$conn->connect_error}");
}

// Handle form submission for adding category
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Sanitize input to prevent SQL injection
    $name = $conn->real_escape_string($name);
    $description = $conn->real_escape_string($description);

    $sql = "INSERT INTO categories (name, description) VALUES ('$name', '$description')";

    if ($conn->query($sql) === TRUE) {
        header("Location: data_categories.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: $sql<br>{$conn->error}</div>";
    }
}

// Retrieve categories data
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Add Category</h2>
        
        <form method="POST" action="add_categories.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>

        <h2 class="mt-5">Categories List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['description']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No categories found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html> 