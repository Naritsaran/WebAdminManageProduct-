<?php
session_start();
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header('location: login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
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
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700&family=Noto+Serif+Thai:wght@100..900&family=Quicksand&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "IBM Plex Sans Thai", sans-serif;
        }
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
            width: 300px;
            padding: 20px;
            background: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .modal-header, .modal-body, .modal-footer {
            margin-bottom: 20px;
        }
        .modal-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .cancel-button {
            background: #6c757d;
            color: white;
        }
        .delete-button {
            background: #dc3545;
            color: white;
        }
    </style>
    <script>
        var deleteUrl = '';

        function showModal(url) {
            deleteUrl = url;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function hideModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        function deletePro() {
            window.location.href = deleteUrl;
        }
    </script>
</head>
<body class="sb-nav-fixed">
    <!-- Modal -->
    <div id="deleteModal" class="modal" style="width: 400px; height: 300px;">
        <div class="modal-header">
            <h5>ยืนยันการลบสินค้า</h5>
        </div>
        <div class="modal-body">
            คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?
        </div>
        <div class="modal-footer">
            <button onclick="hideModal()" class="modal-button cancel-button">ยกเลิก</button>
            <button onclick="deletePro()" class="modal-button delete-button">ลบสินค้า</button>
        </div>
    </div>

    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">iScanGo</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input name="search" value="<?php if (isset($_GET['search'])) { echo $_GET['search']; } ?>" class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button name="submit" class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Menu</div>
                        <a class="nav-link" href="productUser.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            สินค้า
                        </a>
                        <a class="nav-link" href="adminUser.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            โปรไฟล์
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h1 class="mt-4">สินค้า</h1>
                        <a href="addproductUser.php">
                            <button class="btn btn-primary" id="myBtn">เพิ่มสินค้า</button>
                        </a>
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>เลขบาร์โค้ด</th>
                                        <th>ชื่อสินค้า</th>
                                        <th>รูปภาพ</th>
                                        <th>ประเภท</th>
                                        <th>การจัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    include "connection.php";
                                    if (isset($_GET['search'])) {
                                        $filtervalues = $_GET['search'] ?? '';

                                        // เตรียมคำสั่ง SQL โดยใช้ MySQLi
                                        $stmt = $conn->prepare("SELECT * FROM products WHERE CONCAT(barcode_id, product_name, type) LIKE ?");
                                        $param = "%" . $filtervalues . "%";
                                        $stmt->bind_param('s', $param);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result->num_rows === 0) {
                                            echo '<p class="mt-4">No results found</p>';
                                        } else {
                                            while ($row = $result->fetch_assoc()) { ?>
                                                                                            <tr >
                                                <td onclick="location.href='showProductUser.php?product_id=<?php echo htmlspecialchars($row['product_id']) ?>'"><?php echo $row['barcode_id'] ?></td>
                                                <td onclick="location.href='showProductUser.php?product_id=<?php echo htmlspecialchars($row['product_id']) ?>'"><?php echo $row['product_name'] ?></td>
                                                <td onclick="location.href='showProductUser.php?product_id=<?php echo htmlspecialchars($row['product_id']) ?>'">
                                                <?php
                                                $imagePath = isset($row['image']) && !empty($row['image']) ? $row['image'] : 'images/image.png';
                                                ?>

                                                <img id="main-image" src="<?php echo $imagePath; ?>" class="img-fluid" style="width: 70px; height: 70px; border: 1px solid lightblue; border-radius: 10px;" onerror="this.onerror=null; this.src='images/image.png'; this.style = 'width: 70px; height: 70px;';">

                                                <script>
                                                    document.getElementById('main-image').addEventListener('error', function() {
                                                        this.src = 'images/image.png';
                                                        this.style = 'width: 70px; height: 70px;';
                                                    });
                                                </script>
                                                </td>
                                                <td onclick="location.href='showProductUser.php?product_id=<?php echo htmlspecialchars($row['product_id']) ?>'"><?php echo $row['type'] ?></td>
                                                <td>
                                                    <a href="editproductUser.php?product_id=<?php echo htmlspecialchars($row['product_id']) ?>" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> แก้ไข
                                                    </a>
                                                    <a href="#" onclick="showModal('deleteproduct.php?product_id=<?php echo $row['product_id'] ?>&imgPart=<?php echo urlencode($row['image']); ?>')" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> ลบ
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php }
                                        }
                                    } else { 
                                        include "connection.php";
                                        $sql = "SELECT * FROM products";
                                        $result = mysqli_query($conn, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) { ?>
                                            <tr style="text-align: center; vertical-align: middle;">
                                                <td onclick="location.href='showProductUser.php?product_id=<?php echo htmlspecialchars($row['product_id']) ?>'"><?php echo $row['barcode_id'] ?></td>
                                                <td onclick="location.href='showProductUser.php?product_id=<?php echo htmlspecialchars($row['product_id']) ?>'"><?php echo $row['product_name'] ?></td>
                                                <td onclick="location.href='showProductUser.php?product_id=<?php echo htmlspecialchars($row['product_id']) ?>'" >
                                                <?php
                                                $imagePath = isset($row['image']) && !empty($row['image']) ? $row['image'] : 'images/image.png';
                                                ?>

                                                <img id="main-image" src="<?php echo $imagePath; ?>" class="img-fluid" style="width: 70px; height: 70px; border: 1px solid lightblue; border-radius: 10px;" onerror="this.onerror=null; this.src='images/image.png'; this.style = 'width: 70px; height: 70px;';">

                                                <script>
                                                    document.getElementById('main-image').addEventListener('error', function() {
                                                        this.src = 'images/image.png';
                                                        this.style = 'width: 70px; height: 70px;';
                                                    });
                                                </script>
                                                </td>
                                                <td onclick="location.href='showProductUser.php?product_id=<?php echo htmlspecialchars($row['product_id']) ?>'"><?php echo $row['type'] ?></td>
                                                <td>
                                                    <a href="editproductUser.php?product_id=<?php echo htmlspecialchars($row['product_id']) ?>" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> แก้ไข
                                                    </a>
                                                    <a href="#" onclick="showModal('deleteproductUser.php?product_id=<?php echo $row['product_id'] ?>&imgPart=<?php echo urlencode($row['image']); ?>')" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> ลบ
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php } }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
