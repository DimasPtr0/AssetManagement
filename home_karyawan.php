<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user details
$stmt = $pdo->prepare("SELECT username, role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit;
}

// Set default limit and page
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $limit;

// Get search keyword
$search_keyword = isset($_GET['search']) ? $_GET['search'] : '';

// Get statistics data
$categoryCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$assetCount = $pdo->query("SELECT COUNT(*) FROM assets")->fetchColumn();
$activeAssets = $pdo->query("SELECT COUNT(*) FROM assets WHERE status = 'active'")->fetchColumn();
$maintenanceAssets = $pdo->query("SELECT COUNT(*) FROM assets WHERE status = 'maintenance'")->fetchColumn();
$decommissionedAssets = $pdo->query("SELECT COUNT(*) FROM assets WHERE status = 'decommissioned'")->fetchColumn();

// Get data from tables with limit and search keyword
$sql = "SELECT * FROM assets WHERE category_id LIKE :keyword OR name LIKE :keyword LIMIT $start_from, $limit";
$stmt = $pdo->prepare($sql);
$stmt->execute(['keyword' => "%$search_keyword%"]);
$assets = $stmt->fetchAll();

// Calculate total pages
$total_pages = ceil($assetCount / $limit);
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
            padding-top: 20px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            border-bottom: 1px solid #1e282c;
        }

        .sidebar ul li a {
            display: block;
            padding: 15px;
            color: #b8c7ce;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar ul li a:hover,
        .sidebar ul li.active a {
            background-color: #1e282c;
            color: #ffffff;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .box {
            margin-bottom: 20px;
        }

        .box-header {
            background-color: #f7f7f7;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .box-title {
            margin: 0;
        }

        .box-content {
            background-color: #ffffff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .box-content .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .box-content .table th,
        .box-content .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .box-content .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .box-content .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .box-content .pagination li {
            display: inline;
            margin: 0 5px;
        }

        .box-content .pagination li a {
            text-decoration: none;
            padding: 10px 15px;
            color: #337ab7;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #ffffff;
        }

        .box-content .pagination li.active a {
            background-color: #337ab7;
            color: #ffffff;
            border-color: #337ab7;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="dashboard.php">Dashboard</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="nav nav-pills nav-stacked">
            <br>
            <br>
            <li class="active">
                <a href="home_karyawan.php">
                    <i class="fa fa-home"></i> Home
                </a>
            </li>
            <li>
                <a href="karyawan/categories/data_categories.php">
                    <i class="fa fa-list"></i> Categories
                </a>
            </li>
            <li>
                <a href="karyawan/asset/data_asset.php">
                    <i class="fa fa-archive"></i> Assets
                </a>
            </li>
            <li>
                <a href="karyawan/inventory/data_inventory.php">
                    <i class="fa fa-database"></i> Inventory Details
                </a>
            </li>
        </ul>
    </div>


    <!-- Main Content -->
    <div class="content">
        <br><br>
        <section class="content-header">
            <h1>Welcome | <?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></h1>
        </section>

        <!-- Start Karyawan Dashboard -->
        <h1>Karyawan Dashboard</h1>
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h2><b><?= $assetCount; ?></b></h2>
                        <p>Assets</p>
                    </div>
                    <div class="icon">
                        <i class="ion-ios-box"></i>
                    </div>
                    <a href="admin/asset/data_asset.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h2><b><?= $categoryCount; ?></b></h2>
                        <p>Categories</p>
                    </div>
                    <div class="icon">
                        <i class="ion-ios-folder"></i>
                    </div>
                    <a href="admin/categories/data_categories.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h2><b><?= $activeAssets; ?></b></h2>
                        <p>Active Assets</p>
                    </div>
                    <div class="icon">
                        <i class="ion-checkmark"></i>
                    </div>
                    <a href="admin/asset/data_asset.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h2><b><?= $maintenanceAssets; ?></b></h2>
                        <p>Assets in Maintenance</p>
                    </div>
                    <div class="icon">
                        <i class="ion-wrench"></i>
                    </div>
                    <a href="admin/asset/data_asset.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <!-- End Karyawan Dashboard -->

        <section class="box-content">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Asset Data</h3>
                </div>
                <div class="box-content">
                    <form method="get" action="dashboard.php">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control pull-right" placeholder="Search" value="<?= htmlspecialchars($search_keyword, ENT_QUOTES, 'UTF-8'); ?>">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Asset Name</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assets as $index => $asset) : ?>
                                <tr>
                                    <td><?= $start_from + $index + 1; ?></td>
                                    <td><?= htmlspecialchars($asset['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($asset['category_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($asset['status'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= htmlspecialchars($asset['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li class="<?= ($page == $i) ? 'active' : ''; ?>"><a href="dashboard.php?page=<?= $i; ?>"><?= $i; ?></a></li>
                        <?php endfor; ?>
                    </ul>
                </div>
            </div>
        </section>
    </div>
    <!-- End Main Content -->

    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="plugins/select2/select2.full.min.js"></script>
    <script src="dist/js/app.min.js"></script>
</body>

</html>