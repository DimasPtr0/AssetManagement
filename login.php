
<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: home_admin.php");
        } elseif ($user['role'] == 'karyawan') {
            header("Location: home_karyawan.php");
        } elseif ($user['role'] == 'user') {
            header("Location: home_guest.php");
        } else {
            echo "Invalid role.";
        }

        exit;
    } else {
        echo "Invalid login credentials.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="dist/img/dist/img/Logo_PLN.PNG">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/custom-login.css">
</head>
<body class="text-center">
    <div class="login-box">
        <div class="login-logo">
            <img src="dist/img/Logo_PLN.png" width=100px />
            <h4>
                <marquee><b>
                    Aplikasi Management Asset PLN UP3 SEMARANG
                </b></marquee>
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
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-block btn-flat" name="btnLogin" title="Masuk Sistem">
                        <b>M A S U K</b>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script></script>
        <?php if(isset($_SESSION['success_message'])): ?>
            alert("<?php echo $_SESSION['success_message']; ?>");
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
    </script>
</body>
</html>