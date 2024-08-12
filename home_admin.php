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

// Get data from tables with limit
$sql = "SELECT * FROM assets WHERE category_id LIKE :keyword OR name LIKE :keyword LIMIT $start_from, $limit";
$stmt = $pdo->prepare($sql);
$stmt->execute(['keyword' => "%$search_keyword%"]);
$assets = $stmt->fetchAll();

$inventory_details = $pdo->query("SELECT * FROM inventory_details LIMIT $start_from, $limit")->fetchAll();
$users = $pdo->query("SELECT * FROM users")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

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

        .box-content .box-header {
            background-color: #f7f7f7;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }

        .box-content .box-title {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .box-content .box-body {
            padding: 20px;
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
    <div class="sidebar">
        <ul class="nav nav-pills nav-stacked">
            <br>
            <br>
            <li class="active"><a href="home_admin.php">Home</a></li>
            <li><a href="admin/categories/data_categories.php">Categories</a></li>
            <li><a href="admin/asset/data_asset.php">Assets</a></li>
            <li><a href="admin/inventory/data_inventory.php">Inventory Details</a></li>
            <li><a href="admin/users/data_user.php">Users</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <br>
        <br>
        <section class="content-header">
            <h1>Welcome | <?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></h1>
        </section>

        <!-- start admin dashboard -->
        <h1>Admin Dashboard</h1>
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
                        <i class="ion-ios-checkmark"></i>
                    </div>
                    <a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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
                    <a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h2><b><?= $decommissionedAssets; ?></b></h2>
                        <p>Decommissioned Assets</p>
                    </div>
                    <div class="icon">
                        <i class="ion-ios-trash"></i>
                    </div>
                    <a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div> -->
        <!-- end admin dashboard -->

        <br>
        <br>

        <section class="content-header">
            <h1>Inventory Details</h1>
        </section>

        <form method="GET" action="">
            <div class="form-group">
                <label for="search">Search by Category or Asset Name:</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Search..." value="<?= htmlspecialchars($search_keyword); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <br>

        <section class="box-content">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Assets</h3>
                </div>
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Asset ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Status</th>
                                <!-- <th>Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($assets as $asset) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($asset['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($asset['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($asset['category_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($asset['status'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <!-- <td>
                                        <a href="edit_asset.php?id=<?php echo $asset['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="delete_asset.php?id=<?php echo $asset['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this asset?');">Delete</a>
                                    </td> -->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li class="<?php if ($page == $i) echo 'active'; ?>"><a href="?page=<?= $i; ?>&limit=<?= $limit; ?>&search=<?= htmlspecialchars($search_keyword); ?>"><?= $i; ?></a></li>
                        <?php endfor; ?>
                    </ul>
                </div>
            </div>
        </section>
    </div>

    <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/select2/select2.full.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="plugins/fastclick/fastclick.js"></script>
    <script src="dist/js/app.min.js"></script>
    <script>
        $(function() {
            $("#example1").DataTable();
        });
    </script>
</body>

</html>