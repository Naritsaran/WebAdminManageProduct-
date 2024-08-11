<?php
    session_start();
    require 'connection.php'; // รวมการเชื่อมต่อกับฐานข้อมูล

    if(isset($_POST['signin'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        if(empty($username)){
            $_SESSION['error'] = 'กรุณากรอกชื่อผู้ใช้';
            header("location: login.php");
            exit(); // หยุดการทำงานของโค้ดหลังจาก header redirect
        } else if(empty($password)){
            $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
            header("location: login.php");
            exit();
        }

        $check_data = $conn->prepare("SELECT * FROM admins WHERE username = ?");
        $check_data->bind_param("s", $username); 
        $check_data->execute();
        $result = $check_data->get_result();
        $row = $result->fetch_assoc();

        if($result->num_rows > 0){
            if($username == $row['username']){
                if(password_verify($password, $row['password'])){
                    if($row['admin_type'] == 'Master'){
                        $_SESSION['master_login'] = $row['id'];
                        header("location: product.php");
                    } else {
                        $_SESSION['admin_login'] = $row['id'];
                        echo $_SESSION['admin_login'];
                        header("location: productUser.php");
                    }
                    exit();
                } else {
                    $_SESSION['error'] = 'รหัสผ่านไม่ถูกต้อง';
                    header("location: login.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = 'ชื่อผู้ใช้ไม่ถูกต้อง';
                header("location: login.php");
                exit();
            }
            
        } else {
            $_SESSION['error'] = 'ไม่มีข้อมูลในระบบ';
            header("location: login.php");
            exit();
        }
    }
?>

