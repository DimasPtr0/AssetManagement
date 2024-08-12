<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $password, $role])) {
        // Redirect to index.php after successful registration
        header('Location: index.php?success=true');
        exit;
    } else {
        echo "User registration failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="dist/img/Logo_PLN.png">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/custom-login.css">
    <style>
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            font-size: 1.2em;
            padding: 15px 30px;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .login-box-body {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .login-logo img {
            margin-bottom: 20px;
        }

        .login-logo marquee {
            font-size: 1.5em;
            font-weight: bold;
        }
    </style>
</head>
<body class="text-center">
    <div class="login-box">
        <div class="login-logo">
            <img src="dist/img/Logo_PLN.png" width="100px" />
            <h4>
                <marquee><b>Aplikasi Management Asset PLN UP3 SEMARANG</b></marquee>
            </h4>
        </div>
        <div class="login-box-body">
            <form method="post">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <select class="form-control" name="role" required>
                        <option value="admin">Admin</option>
                        <option value="karyawan">Karyawan</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-block btn-flat" name="btnRegister" title="Register">
                        <b>Register</b>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script>
        <?php if(isset($_GET['success'])): ?>
            alert("User registered successfully.");
        <?php endif; ?>
    </script>
</body>
</html>
