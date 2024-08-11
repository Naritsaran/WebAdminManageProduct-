<?php
include "connection.php";
session_start();
if(!isset($_SESSION['master_login'])){
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header('location: login.php');
}

$product_id = $_GET['product_id'];


if (isset($_POST['submit'])) {

    $barcode_id = $_POST['barcode_id'];
    $product_name = $_POST['product_name'];
    $type = $_POST['type'];
    

    if (empty($barcode_id)) {
        $_SESSION['error'] = 'กรุณากรอกเลขบาร์โค้ด';
        header("location: addproduct.php");
        exit();
    } else if (empty($product_name)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อสินค้า';
        header("location: addproduct.php");
        exit();
    } else if (empty($type)) {
        $_SESSION['error'] = 'กรุณาเลือกประเภทสินค้า';
        header("location: addproduct.php");
        exit();
    } else {
        $quantity = $_POST['quantity'];
        $precautions = $_POST['precautions'];
        $ingredient = $_POST['ingredient'];
        $direction = $_POST['direction'];
        $preserve = $_POST['preserve'];
        $price = $_POST['price'];
        $productionDate = $_POST['productionDate'];
        $expirationDate = $_POST['expirationDate'];
        $synonyms = $_POST['synonyms'];
    
        if(isset($_FILES["product_img"]["tmp_name"]) && !empty($_FILES["product_img"]["tmp_name"])) {
            $targetDir = "uploads/";
            $cname = uniqid();
            $targetFile = $targetDir . $cname . '.jpg';
            move_uploaded_file($_FILES["product_img"]["tmp_name"], $targetFile);
            $imgPath = $targetFile;
    
            // ลบรูปเดิม
            $old_img = $_POST["old_img"];
            
            if(file_exists($old_img)) {
                unlink($old_img);
            }
    
            $sql = "UPDATE products SET  barcode_id='$barcode_id', product_name='$product_name', image='$imgPath', type='$type', quantity='$quantity', precautions='$precautions', ingredient='$ingredient', direction='$direction', preserve='$preserve', price='$price', productionDate='$productionDate', expirationDate='$expirationDate', synonyms='$synonyms' WHERE product_id = $product_id";
        } else {
            $sql = "UPDATE products SET  barcode_id='$barcode_id', product_name='$product_name', type='$type', quantity='$quantity', precautions='$precautions', ingredient='$ingredient', direction='$direction', preserve='$preserve', price='$price', productionDate='$productionDate', expirationDate='$expirationDate', synonyms='$synonyms' WHERE product_id = $product_id";
        }
    
        $result = mysqli_query($conn, $sql);
    
        if ($result) {
            header("location:product.php");
            exit(0);
        } else{
            echo "เกิดข้อผิดพลาดเกิดขึ้น: " . mysqli_error($conn);
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
                            <a class="nav-link" href="product.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                สินค้า
                            </a>
                            <a class="nav-link" href="admin.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>
                                ผู้ดูแลระบบ
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <?php
                    include "connection.php";
                    $id = $_GET['product_id'];
                    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ? LIMIT 1");
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $stmt->close();
                    ?>
                    <div class="container mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h2>แก้ไขข้อมูลสินค้า</h2>
                            </div>
                            <div class="card-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="barcode_id">เลขบาร์โค้ด</label>
                                        <input type="text" class="form-control" name="barcode_id" value="<?php echo $row['barcode_id'] ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_name">ชื่อสินค้า</label>
                                        <input type="text" class="form-control" name="product_name" value="<?php echo $row['product_name'] ?>" placeholder="ชื่อสินค้า" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_img">รูปภาพสินค้า</label>
                                        <input type="file" class="form-control" name="product_img" accept=".jpg, .jpeg, .png">
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
                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="type">ประเภท</label>
                                        <select class="form-control" name="type">
                                            <option value="" disabled selected>เลือกประเภท</option>
                                            <option value="เครื่องดื่ม" <?php echo ($row['type'] == 'เครื่องดื่ม') ? 'selected' : ''; ?>>เครื่องดื่ม</option>
                                            <option value="เครื่องสำอาง" <?php echo ($row['type'] == 'เครื่องสำอาง') ? 'selected' : ''; ?>>เครื่องสำอาง</option>
                                            <option value="เครื่องปรุง" <?php echo ($row['type'] == 'เครื่องปรุง') ? 'selected' : ''; ?>>เครื่องปรุง</option>
                                            <option value="อาหารสำเร็จรูป" <?php echo ($row['type'] == 'อาหารสำเร็จรูป') ? 'selected' : ''; ?>>อาหารสำเร็จรูป</option>
                                            <option value="ยา" <?php echo ($row['type'] == 'ยา') ? 'selected' : ''; ?>>ยา</option>
                                            <option value="ของใช้ทั่วไป" <?php echo ($row['type'] == 'ของใช้ทั่วไป') ? 'selected' : ''; ?>>ของใช้ทั่วไป</option>
                                            <!-- Add more options as needed -->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity">ปริมาณ</label>
                                        <input type="text" class="form-control" name="quantity" value="<?php echo $row['quantity'] ?>" placeholder="ปริมาณ">
                                    </div>
                                    <div class="form-group">
                                        <label for="precautions">ข้อควรระวัง</label>
                                        <input type="text" class="form-control" name="precautions" value="<?php echo $row['precautions'] ?>" placeholder="ข้อควรระวัง">
                                    </div>
                                    <div class="form-group">
                                        <label for="ingredient">ส่วนประกอบ</label>
                                        <textarea class="form-control" name="ingredient" placeholder="ส่วนประกอบ" rows="3"><?php echo $row['ingredient'] ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="direction">วิธีใช้</label>
                                        <input type="text" class="form-control" name="direction" value="<?php echo $row['direction'] ?>" placeholder="วิธีใช้">
                                    </div>
                                    <div class="form-group">
                                        <label for="preserve">วิธีเก็บรักษา</label>
                                        <input type="text" class="form-control" name="preserve" value="<?php echo $row['preserve'] ?>" placeholder="วิธีเก็บรักษา">
                                    </div>
                                    <div class="form-group">
                                        <label for="price">ราคา</label>
                                        <input type="number" class="form-control" name="price" value="<?php echo $row['price'] ?>" placeholder="ราคา">
                                    </div>
                                    <div class="form-group">
                                        <label for="productionDate">วันที่ผลิต</label>
                                        <input type="date" class="form-control" name="productionDate" value="<?php echo $row['productionDate'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="expirationDate">วันหมดอายุ</label>
                                        <input type="date" class="form-control" name="expirationDate" value="<?php echo $row['expirationDate'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="synonyms">คำเหมือน</label>
                                        <input type="text" class="form-control" name="synonyms" value="<?php echo $row['synonyms'] ?> " placeholder="คำเหมือน">
                                    </div>
                                    <div class="form-group text-right" style="margin-top: 20px;">
                                        <button type="submit"  class="btn btn-success" name="submit">บันทึก</button>
                                        <a href="product.php" class="btn btn-danger">ยกเลิก</a>
                                    </div>
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
