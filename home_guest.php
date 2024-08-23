<?php
session_start();
include 'db.php'; // Ensure you include the db.php file for database queries

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get username and role from database based on user_id stored in session
$stmt = $pdo->prepare("SELECT username, role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit;
}

// Get statistics data from categories and assets tables
$categoryCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$assetCount = $pdo->query("SELECT COUNT(*) FROM assets")->fetchColumn();
$activeAssets = $pdo->query("SELECT COUNT(*) FROM assets WHERE status = 'active'")->fetchColumn();
$maintenanceAssets = $pdo->query("SELECT COUNT(*) FROM assets WHERE status = 'maintenance'")->fetchColumn();
$decommissionedAssets = $pdo->query("SELECT COUNT(*) FROM assets WHERE status = 'decommissioned'")->fetchColumn();

// Get data from assets table
$assets = $pdo->query("SELECT * FROM assets")->fetchAll();

// Get data from inventory details table
$inventory_details = $pdo->query("SELECT * FROM inventory_details")->fetchAll();

// Get data from users table
$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard Management Asset</title>
    <link rel="icon" href="dist/img/asset_management.png">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="plugins/select2/select2.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="dist/css/sidebar.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <style>
        .wrapper {
            display: flex;
            flex-direction: row;
            width: 100%;
        }

        .sidebar {
            width: 250px;
            background-color: #222d32;
            height: 100vh;
            position: fixed;
            top: 0;
            overflow-y: auto;
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
        }

        .sidebar a {
            padding: 10px;
            text-decoration: none;
            font-size: 18px;
            color: #b8c7ce;
            display: block;
        }

        .sidebar a:hover {
            background-color: #1e282c;
            color: white;
        }

        .sidebar .header {
            color: white;
            background: #1a2226;
            text-align: center;
            padding: 10px 0;
            font-size: 20px;
        }

        .sidebar .header a {
            color: white;
            text-decoration: none;
        }

        .sidebar .header a:hover {
            color: #b8c7ce;
        }

        .small-box {
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            color: white;
        }

        .small-box .icon {
            font-size: 70px;
            top: 10px;
            right: 10px;
            opacity: 0.4;
        }

        .box {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }

        .box-header {
            margin-bottom: 20px;
        }

        .box-title {
            font-size: 24px;
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        table thead th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <nav class="sidebar">
            <div class="header">
                <a href="dashboard.php">Dashboard</a>
            </div>
            <ul class="nav">
                <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
        </nav>

        <div class="content-wrapper">
            <section class="content-header">
                <h1><a class="btn btn-danger"><b>DASHBOARD | <?= $user['username']; ?></b></a></h1>
            </section>
            <hr>
            <section class="content">
                <div class="row">
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h2><b><?= $categoryCount; ?></b></h2>
                                <p>Categories</p>
                            </div>
                            <div class="icon">
                                <i class="ion-ios-folder"></i>
                            </div>
                            <a href="?page=data-categories" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h2><b><?= $assetCount; ?></b></h2>
                                <p>Assets</p>
                            </div>
                            <div class="icon">
                                <i class="ion-ios-box"></i>
                            </div>
                            <a href="?page=data-assets" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h2><b><?= $activeAssets; ?></b></h2>
                                <p>Active Assets</p>
                            </div>
                            <div class="icon">
                                <i class="ion-ios-checkmark"></i>
                            </div>
                            <a href="?page=active-assets" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h2><b><?= $maintenanceAssets; ?></b></h2>
                                <p>Assets in Maintenance</p>
                            </div>
                            <div class="icon">
                                <i class="ion-ios-gear"></i>
                            </div>
                            <a href="?page=maintenance-assets" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Assets</h3>
                            </div>
                            <div class="box-body">
                                <table id="assetsTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Category</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($assets as $asset) : ?>
                                            <tr>
                                                <td><?= $asset['id']; ?></td>
                                                <td><?= $asset['name']; ?></td>
                                                <td><?= $asset['status']; ?></td>
                                                <td><?= $asset['category_id']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Inventory Details</h3>
                            </div>
                            <div class="box-body">
                                <table id="inventoryTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Asset ID</th>
                                            <th>IP Address</th>
                                            <th>MAC Address</th>
                                            <th>Location</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($inventory_details as $inventory) : ?>
                                            <tr>
                                                <td><?= $inventory['asset_id']; ?></td>
                                                <td><?= $inventory['ip_address']; ?></td>
                                                <td><?= $inventory['mac_address']; ?></td>
                                                <td><?= $inventory['location']; ?></td>
                                                <td><?= $inventory['notes']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">User Management</h3>
                            </div>
                            <div class="box-body">
                                <table id="usersTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Role</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user) : ?>
                                            <tr>
                                                <td><?= $user['id']; ?></td>
                                                <td><?= $user['username']; ?></td>
                                                <td><?= $user['role']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="plugins/select2/select2.full.min.js"></script>
    <script>
        $(function () {
            $("#assetsTable").DataTable();
            $("#inventoryTable").DataTable();
            $("#usersTable").DataTable();
        });
    </script>
</body>
</html>
