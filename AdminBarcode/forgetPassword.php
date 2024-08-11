<?php
session_start();

if (!isset($_SESSION['master_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header('location: login.php');
}

include "connection.php";
$id = $_GET['id'];
if (isset($_POST['submit'])) {
    
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];
    echo $password;
    if (strlen($password) > 20 || strlen($password) < 5) {
        $_SESSION['error'] = 'รหัสผ่านต้องมีความยาวระหว่าง 5 ถึง 20 ตัวอักษร';
        header("location: forgetPassword.php");
        exit();
    } else if (empty($c_password)) {
        $_SESSION['error'] = 'กรุณายืนยันรหัสผ่าน';
        header("location: forgetPassword.php");
        exit();
    } else if ($password != $c_password) {
        $_SESSION['error'] = 'รหัสผ่านไม่ตรงกัน';
        header("location: forgetPassword.php");
        exit();
    } else {
        try {            
            
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE admins SET password='$passwordHash' WHERE id = $id ";
            
            $result = mysqli_query($conn, $sql);
            if ($result) {
                
                $_SESSION['success'] = 'รหัสผ่านถูกเปลี่ยนเรียบร้อยแล้ว ' . $password;
                header("location: admin.php");
                exit(0);
            }else{
                echo "เกิดข้อผิดพลาดเกิดขึ้น: " . mysqli_error($conn);
            }
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
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
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@100;200;300;400;500;600;700&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Noto+Serif+Thai:wght@100..900&family=Quicksand&display=swap" rel="stylesheet">
    
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
                <div class="container  justify-content-center align-items-center mt-4" style="width: 500px; ">
                    <div class="card" >
                        <div class="card-header text-center">
                            <h4>เปลี่ยนรหัสผ่าน</h4>
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data" onsubmit="return validateForm();">
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
                                <div class="form-floating mb-3">
                                    <input id="password" class="form-control" type="password" name="password" placeholder="รหัสผ่าน" required />
                                    <label for="password">รหัสผ่านใหม่</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input id="c_password" class="form-control" type="password" name="c_password" placeholder="ยืนยันรหัสผ่าน" required />
                                    <label for="c_password">ยืนยันรหัสผ่านใหม่</label>
                                </div>

                                <input type="checkbox" id="showPassword" onclick="togglePassword()" style="margin-left: 5px;"> แสดงรหัสผ่าน

                                <script>
                                    function togglePassword() {
                                        var passwordField = document.getElementById("password");
                                        var confirmPasswordField = document.getElementById("c_password");
                                        
                                        if (passwordField.type === "password") {
                                            passwordField.type = "text";
                                            confirmPasswordField.type = "text";
                                        } else {
                                            passwordField.type = "password";
                                            confirmPasswordField.type = "password";
                                        }
                                    }
                                </script>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-success me-2" name="submit">Save</button>
                                    <a href="admin.php" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>
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
