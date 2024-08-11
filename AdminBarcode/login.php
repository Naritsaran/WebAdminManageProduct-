<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - SB Admin</title>
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
    <body class="bg-dark">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                        <form action="signin.php" method="post">
                                            <?php if(isset($_SESSION['error'])) { ?>
                                                <div class="alert alert-danger" role="alert" >
                                                    <?php
                                                        echo $_SESSION['error'];
                                                        unset($_SESSION['error']);
                                                    ?>
                                                </div>
                                            <?php } ?>
                                            <?php if(isset($_SESSION['success'])) { ?>
                                                <div class="alert alert-success" role="alert" >
                                                    <?php
                                                        echo $_SESSION['success'];
                                                        unset($_SESSION['success']);
                                                    ?>
                                                </div>
                                            <?php } ?>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="username" id="username" type="username" placeholder="ชื่อผู้ใช้" />
                                              <label for="inputUsername">ชื่อผู้ใช้</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="password" id="password" type="password" placeholder="รหัสผ่าน" style="margin-bottom: 10px;" />
                                                <label for="inputPassword">รหัสผ่าน</label>
                                                <input type="checkbox" id="showPassword" onclick="togglePassword()" style="margin-left: 5px;"> แสดงรหัสผ่าน
                                                <script>
                                                    function togglePassword() {
                                                        var passwordField = document.getElementById("password");
                                                        if (passwordField.type === "password") {
                                                            passwordField.type = "text";
                                                        } else {
                                                            passwordField.type = "password";
                                                        }
                                                    }
                                                </script>
                                            </div>
                                            <div class="form-check mb-3">
                                                <!-- <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" /> -->
                                                <!-- <label class="form-check-label" for="inputRememberPassword">Remember Password</label> -->
                                            </div>
                                            <div style="margin-left: 350px; margin-top: -30px; ">
                                                <!-- <a class="small" href="password.html">Forgot Password?</a> -->
                                                <!-- <a class="btn btn-primary" href="" onclick="gotologin()">Login</a> -->
                                                <button class="btn btn-dark" type="submit" name="signin">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small">
                                            <!-- <a href="register.html">Need an account? Sign up!</a> -->
                                             <p>iScanGO</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <!-- <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div> -->
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
