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
            width: 100%;
        }

        .sidebar {
            width: 250px;
            background-color: #222d32;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
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
    </style>
</head>

<body>
    <div class="wrapper">
        <nav class="sidebar">
            <div class="header">
                <a href="dashboard.php">Dashboard</a>
            </div>
            <a href="dashboard.php">Home</a>
            <a href="?page=users">Profile</a>
            <a href="logout.php">Logout</a>
        </nav>

        <div class="content-wrapper">
            <section class="content-header">
                <h1><a class="btn btn-danger"><b>DASHBOARD | User</b></a></h1>
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
                                <!-- Tabel asset -->
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
                                                <td><?= $asset['category'] ?? 'N/A'; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabel Categories -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Categories</h3>
                            </div>
                            <div class="box-body">
                                <table id="categoriesTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $categories = $pdo->query("SELECT * FROM categories")->fetchAll();
                                        foreach ($categories as $category) : ?>
                                            <tr>
                                                <td><?= $category['id']; ?></td>
                                                <td><?= $category['name']; ?></td>
                                                <td><?= $category['description']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabel Inventory Details -->
                <div class="row">
                    <div class="col-md-12">
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
                                        <?php foreach ($inventory_details as $detail) : ?>
                                            <tr>
                                                <td><?= $detail['asset_id']; ?></td>
                                                <td><?= $detail['ip_address']; ?></td>
                                                <td><?= $detail['mac_address']; ?></td>
                                                <td><?= $detail['location']; ?></td>
                                                <td><?= $detail['notes']; ?></td>
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

    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="plugins/select2/select2.full.min.js"></script>
    <script>
        $(function() {
            $('#assetsTable').DataTable();
            $('#categoriesTable').DataTable();
            $('#inventoryTable').DataTable();
            $('#usersTable').DataTable();
        });
    </script>

    <?php
    echo "Welcome to the Dashboard, " . htmlspecialchars($user['username']) . "! <a href='logout.php'>Logout</a><br>";

    // Display options based on user role
    if (isset($user['role'])) {
        switch ($user['role']) {
            case 'admin':
                echo "<a href='view_assets.php'>View Assets</a><br>";
                echo "<a href='add_asset.php'>Add Asset</a><br>";
                echo "<a href='manage_categories.php'>Manage Categories</a><br>";
                break;
            case 'karyawan':
                echo "<a href='view_assets.php'>View Assets</a><br>";
                break;
            case 'user':
                echo "<a href='view_assets.php'>View Available Assets</a><br>";
                break;
            default:
                echo "Your role does not have any specific options available.";
                break;
        }
    } else {
        echo "Role information is missing for your account.";
    }
    ?>
</body>

</html>
