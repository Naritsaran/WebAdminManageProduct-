<?php
include "connection.php";
session_start();
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header('location: login.php');
}

// ฟังก์ชันสำหรับการจัดการการอัปโหลดไฟล์
function uploadImage($image)
{
    $targetDir = "uploads/"; // ระบุไดเรกทอรีที่รูปภาพจะถูกเก็บ
    $cname = uniqid();
    $targetFile = $targetDir . $cname . '.jpg';
    move_uploaded_file($image["tmp_name"], $targetFile);
    return $targetFile; // เปลี่ยนเส้นทางเป็นเฉพาะชื่อไฟล์ (ไม่รวมเส้นทางแบบเต็ม)
}

if (isset($_POST['submit'])) {
    $product_id = null;
    $barcode_id = $_POST['barcode_id'];
    $product_name = $_POST['product_name'];
    $type = $_POST['type'];
    $quantity = $_POST['quantity'];
    $precautions = $_POST['precautions'];
    $ingredient = $_POST['ingredient'];
    $direction = $_POST['direction'];
    $preserve = $_POST['preserve'];
    $price = $_POST['price'];
    $productionDate = $_POST['productionDate'];
    $expirationDate = $_POST['expirationDate'];
    $synonyms = $_POST['synonyms'];

    // เพิ่มข้อมูลลงในฐานข้อมูล
    $img = $_FILES["product_img"];
    $product_image = uploadImage($img); //Part

    if (empty($barcode_id)) {
        $_SESSION['error'] = 'กรุณากรอกเลขบาร์โค้ด';
        header("location: addproductUser.php");
        exit();
    } else if (empty($product_name)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อสินค้า';
        header("location: addproductUser.php");
        exit();
    } else if (empty($type)) {
        $_SESSION['error'] = 'กรุณาเลือกประเภทสินค้า';
        header("location: addproductUser.php");
        exit();
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO `products`(`product_id`, `barcode_id`, `product_name`, `image`, `type`, `quantity`, `precautions`, `ingredient`, `direction`, `preserve`, `price`, `productionDate`, `expirationDate`, `synonyms`) VALUES (null,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssssssssss", $barcode_id, $product_name, $product_image, $type, $quantity, $precautions, $ingredient, $direction, $preserve, $price, $productionDate, $expirationDate, $synonyms);
            $stmt->execute();

            if ($stmt->affected_rows == 1) {
                header("Location: productUser.php?msg=New record created successfully");
            } else {
                echo "Failed : " . mysqli_error($conn);
            }
            $stmt->close();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
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
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Noto+Serif+Thai:wght@100..900&family=Quicksand&display=swap" rel="stylesheet">
        
        <style>
            body {
                font-family: "IBM Plex Sans Thai", sans-serif;
            }
            .form-group img {
                margin-top: 10px;
            }
        </style>
        <script>

            function showModal() {
                document.getElementById('editModel').style.display = 'block';
            }

            function hideModal() {
                document.getElementById('editModel').style.display = 'none';
            }


        </script>
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
                            <a class="nav-link" href="productUser.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                สินค้า
                            </a>
                            <a class="nav-link" href="adminUser.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>
                                โปรไฟล์
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
            <main>
                <div class="container mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h2>เพิ่มสินค้า</h2>
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data">
                            <?php if (isset($_SESSION['error'])) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?php
                                            echo $_SESSION['error'];
                                            unset($_SESSION['error']);
                                        ?>
                                    </div>
                                <?php } ?>
                                <?php if (isset($_SESSION['success'])) { ?>
                                    <div class="alert alert-success" role="alert">
                                        <?php
                                            echo $_SESSION['success'];
                                            unset($_SESSION['success']);
                                        ?>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label for="barcode_id">เลขบาร์โค้ด</label>
                                    <input type="text" class="form-control" name="barcode_id" placeholder="เลขบาร์โค้ด">
                                </div>
                                <div class="form-group">
                                    <label for="product_name">ชื่อสินค้า</label>
                                    <input type="text" class="form-control" name="product_name" placeholder="ชื่อสินค้า">
                                </div>
                                <div class="form-group">
                                    <label for="product_img">รูปภาพสินค้า</label>
                                    <input type="file" class="form-control" name="product_img" accept=".jpg, .jpeg, .png">
                                </div>
                                <div class="form-group">
                                    <label for="type">ประเภท</label>
                                    <select class="form-control" name="type">
                                        <option value="" disabled selected>เลือกประเภท</option>
                                        <option value="เครื่องดื่ม">เครื่องดื่ม</option>
                                        <option value="เครื่องสำอาง">เครื่องสำอาง</option>
                                        <option value="เครื่องปรุง">เครื่องปรุง</option>
                                        <option value="อาหารสำเร็จรูป">อาหารสำเร็จรูป</option>
                                        <option value="ยา">ยา</option>
                                        <option value="ของใช้ทั่วไป">ของใช้ทั่วไป</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="quantity">ปริมาณ</label>
                                    <input type="text" class="form-control" name="quantity" placeholder="ปริมาณ">
                                </div>
                                <div class="form-group">
                                    <label for="precautions">ข้อควรระวัง</label>
                                    <textarea type="text" class="form-control" name="precautions" placeholder="ข้อควรระวัง" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="ingredient">ส่วนประกอบ</label>
                                    <textarea class="form-control" name="ingredient" placeholder="ส่วนประกอบ" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="direction">วิธีใช้</label>
                                    <textarea type="text" class="form-control" name="direction" placeholder="วิธีใช้" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="preserve">วิธีการเก็บรักษา</label>
                                    <textarea type="text" class="form-control" name="preserve" placeholder="วิธีการเก็บรักษา" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="price">ราคา</label>
                                    <input type="number" class="form-control" name="price" placeholder="ราคา">
                                </div>
                                <div class="form-group">
                                    <label for="productionDate">วันผลิต</label>
                                    <input type="date" class="form-control" name="productionDate">
                                </div>
                                <div class="form-group">
                                    <label for="expirationDate">วันหมดอายุ</label>
                                    <input type="date" class="form-control" name="expirationDate">
                                </div>
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <label for="synonyms">คำเหมือน</label>
                                    <input type="text" class="form-control" name="synonyms" placeholder="คำเหมือน">
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary">เพิ่มสินค้า</button>
                            </form>
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





