<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "assetmanagement";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user data
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>User not found</div>";
        exit;
    }
}

// Handle form submission for updating user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Sanitize input to prevent SQL injection
    $username = $conn->real_escape_string($username);
    $role = $conn->real_escape_string($role);

    // Update query
    $sql = "UPDATE users SET username='$username', role='$role'";

    // If password is provided, update it
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $sql .= ", password='$password'";
    }

    $sql .= " WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>User updated successfully</div>";
        header("Location: data_user.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: $sql<br>{$conn->error}</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit User</h2>
        
        <form method="POST" action="edit_user.php?id=<?php echo $user['id']; ?>">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <input type="text" class="form-control" id="role" name="role" value="<?php echo $user['role']; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">New Password (leave blank if not changing)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>