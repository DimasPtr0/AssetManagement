<!DOCTYPE html>
<html>

<head>
    <title>SIMA PLN UP3 SMG</title>
    <style>
        body {
            background-image: url("dist/img/PLN_BG1.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            font-family: Arial, sans-serif;
            background-size: 100% 150%;
        }

        .logo {
            width: 200px;
            height: 200px;
            margin: 50px auto;
            display: block;
        }

        .options {
            text-align: center;
            margin-top: 50px;
        }

        .options a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background-color: #f1f1f1;
            text-decoration: none;
            color: #333;
            border-radius: 5px;
            font-weight: bold;
        }

        .marquee-container {
            height: 25px;
            width: 300px;
            margin: 20px auto;
            overflow: hidden;
            position: relative;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px 0;
            border-radius: 5px;
        }

        .marquee-text {
            display: inline-block;
            white-space: nowrap;
            font-size: 1.25em;
            color: #333;
            font-weight: bold;
            position: absolute;
            width: 100%;
            animation: marquee 10s linear infinite;
        }

        .register-button {
            text-align: center;
            margin-top: 20px;
        }

        .btn-primary {
            background-color: blue;
            border-color: blue;
            color: white;
            font-size: 1.2em; /* Increase font size */
            padding: 15px 30px; /* Increase padding */
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-primary:hover {
            background-color: darkblue;
            transform: scale(1.05); /* Add a slight scaling effect on hover */
        }

        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }
    </style>
</head>

<body>
    <img src="dist/img/Logo_PLN.png" alt="PLN Logo" class="logo">
    <div class="marquee-container">
        <div class="marquee-text">
            Selamat Datang Di SIMA PLN UP3 SEMARANG
        </div>
    </div>
    <div class="options">
        <a href="login.php">Admin</a>
        <a href="login.php">Karyawan</a>
        <a href="login.php">User</a>
    </div>
    <div class="register-button">
        <a href="register.php" class="btn-primary">Daftar</a>
    </div>
</body>

</html>
