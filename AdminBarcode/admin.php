<?php
session_start();
if (!isset($_SESSION['master_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header('location: login.php');
    exit();


}

if (isset($_SESSION['success'])) {
    echo $_SESSION['success'];
    echo "<script>alert('" . $_SESSION['success'] . "');</script>";
    unset($_SESSION['success']);  // ลบข้อความจาก session หลังจากแสดงผลแล้ว
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: "IBM Plex Sans Thai", sans-serif;
        }

        .profile-card {
            background-color: lightgrey;
        }

        .card-image {
            border-radius: 50%;
        }
    </style>
</head>

<body class="sb-nav-fixed">

<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="index.html">iScanGo</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <div class="d-flex ms-auto">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Menu</div>
                        <a class="nav-link" href="product.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            สินค้า
                        </a>
                        <a class="nav-link" href="admin.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            ผู้ดูแลระบบ
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="card w-75 my-4 mx-auto">
                        <?php
                        include "connection.php";
                        if (isset($_SESSION['master_login'])) {
                            $id = $_SESSION['master_login'];
                            $stmt = $conn->prepare("SELECT * FROM admins WHERE id = ?");
                            $stmt->bind_param('i', $id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) { ?>
                                <div class="card-body profile-card">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <img src="images/master.png" class="card-image" width="180px" height="180px" alt="Profile Picture">
                                        </div>
                                        <div class="col-md-8">
                                            <h3><?php echo htmlspecialchars($row['username']); ?></h3>
                                            <p>______________________________</p>
                                            <h6><?php echo htmlspecialchars($row['admin_type']); ?></h6>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>

                    <div class="card w-75 mx-auto mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <a href="addAdmin.php">
                                        <img src="images/adduser.png" width="130px" height="130px" alt="Add User">
                                    </a>
                                </div>
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h5>ผู้ดูแลระบบ</h5>
                                                </div>
                                                <div class="col-md-8">
                                                    <form class="d-flex" role="search" method="get">
                                                        <input class="form-control me-2" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" type="search" placeholder="Search" aria-label="Search">
                                                        <button name="submit" class="btn btn-outline-success" type="submit">Search</button>
                                                    </form>
                                                </div>
                                            </div>
                                            
                                            <?php
                                            if (isset($_GET['search'])) {
                                                
                                                $filtervalues = $_GET['search'];
                                                $stmt = $conn->prepare("SELECT * FROM admins WHERE CONCAT(username) LIKE ? AND admin_type = 'Admin'");
                                                $param = "%" . $filtervalues . "%";
                                                $stmt->bind_param('s', $param);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result->num_rows === 0) {
                                                    echo '<p class="mt-4">No results found</p>';
                                                } else {
                                                    while ($row = $result->fetch_assoc()) { ?>
                                                        <div class="card mt-2">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-2 text-center">
                                                                        <img src="images/admin1.png" width="50px" height="50px" alt="Admin Picture">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <p class="mt-2"><?php echo htmlspecialchars($row["username"]); ?></p>
                                                                    </div>
                                                                    <div class="col-md-4 text-end">
                                                                        <a href="forgetPassword.php?id=<?php echo $row['id'] ?>" class="btn btn-warning">เปลี่ยนรหัสผ่าน</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                }
                                            } else {
                                                
                                                $type = "Admin";
                                                $stmt = $conn->prepare("SELECT * FROM admins WHERE admin_type = ?");
                                                $stmt->bind_param('s', $type);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                if ($result->num_rows === 0) {
                                                    echo '<p class="mt-4">No admins found</p>';
                                                } else {
                                                    while ($row = $result->fetch_assoc()) { ?>
                                                        <div class="card mt-2">
                                                            <div class="card-body">

                                                                <div class="row">
                                                                    <div class="col-md-2 text-center">
                                                                        <img src="images/admin1.png" width="50px" height="50px" alt="Admin Picture">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <p class="mt-2"><?php echo htmlspecialchars($row["username"]); ?></p>
                                                                    </div>
                                                                    <div class="col-md-6 text-end">
                                                                        <a href="forgetPassword.php?id=<?php echo $row['id'] ?>" class="btn btn-warning">เปลี่ยนรหัสผ่าน</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>
