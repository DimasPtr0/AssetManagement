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

// Retrieve category data
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM categories WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Category not found</div>";
        exit;
    }
}

// Handle form submission for updating category
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Sanitize input to prevent SQL injection
    $name = $conn->real_escape_string($name);
    $description = $conn->real_escape_string($description);

    $sql = "UPDATE categories SET name='$name', description='$description' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Category updated successfully</div>";
        header("Location: data_categories.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: $sql<br>{$conn->error}</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Category</h2>
        
        <form method="POST" action="edit_categories.php?id=<?php echo $category['id']; ?>">
            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $category['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" required><?php echo $category['description']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</body>
</html>