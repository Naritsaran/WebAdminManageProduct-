<?php
include "connection.php";
session_start();

if (!isset($_SESSION['master_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header('location: login.php');
    exit();
}


$product_id = $_GET['product_id'];

$stmt = $conn->prepare("SELECT * FROM `products` WHERE `product_id` = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Product not found.";
    exit();
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>iScanGo - Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700&family=Noto+Serif+Thai:wght@100..900&family=Quicksand&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: "IBM Plex Sans Thai", sans-serif;
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
                    <h1 class="mt-4">รายละเอียดสินค้า</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-box-open"></i>
                            รายละเอียดสินค้า
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                <?php
                                    $imagePath = isset($row['image']) && !empty($row['image']) ? $row['image'] : 'images/image.png';
                                ?>

                                    <img src="<?php echo $product['image']; ?>" class="img-fluid" alt="Product Image" onerror="this.onerror=null; this.src='images/image.png';">

                                <script>
                                    document.getElementById('main-image').addEventListener('error', function() {
                                        this.src = 'images/image.png';
                                    });
                                </script>
                                </div>
                                <div class="col-md-8">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th>เลขบาร์โค้ด</th>
                                                <td><?php echo $product['barcode_id']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>ชื่อสินค้า</th>
                                                <td><?php echo $product['product_name']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>ประเภท</th>
                                                <td><?php echo $product['type']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>ปริมาณ</th>
                                                <td><?php echo $product['quantity']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>ข้อควรระวัง</th>
                                                <td><?php echo $product['precautions']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>ส่วนประกอบ</th>
                                                <td><?php echo $product['ingredient']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>วิธีใช้</th>
                                                <td><?php echo $product['direction']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>วิธีเก็บรักษา</th>
                                                <td><?php echo $product['preserve']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>ราคา</th>
                                                <td><?php echo $product['price']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>วันที่ผลิต</th>
                                                <td><?php echo $product['productionDate']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>วันหมดอายุ</th>
                                                <td><?php echo $product['expirationDate']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>คำเหมือน</th>
                                                <td><?php echo $product['synonyms']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <a href="product.php" class="btn btn-primary">กลับไปยังหน้าสินค้า</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
            </footer>
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
