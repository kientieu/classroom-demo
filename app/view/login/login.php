<?php
require_once ('config/config.php');
require_once ('app/controller/login_controller.php');

$error = '';
$user = '';
$pass = '';

if (isset($_POST['user']) && isset($_POST['pass'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    if (empty($user)) {
        $error = 'Please enter your username';
    }
    else if (empty($pass)) {
        $error = 'Please enter your password';
    }
    else if (strlen($pass) < 6) {
        $error = 'Password must have at least 6 characters';
    }
    else {
        $result = login_controller::check_login($user,$pass);
        if($result['code'] === 0 ){
            $controller = login_controller::get_permission_by_id($user);;
            $_SESSION['username'] = $user;
            $_SESSION['permission'] = $controller;
            if ($controller === 'admin'){
                $action = "index";
            }
            else if ($controller === "teacher"){
                $action = "index";
            }else {
                $controller = 'student';
                $action = 'index';
            }
            header('Location:index.php?controller='. $controller . '&action='.$action);
        }else {
            $error = "Invalid username or password";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Đăng Nhập - TDTU Classroom</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/style.css">
</head>
<body class="main_background">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <h3 class="text-center text-secondary mt-5 mb-3">User Login</h3>
            <form method="post" action="" class="border-collapse w-100 mb-5 mx-auto px-3 pt-3 ">
                <div class="form-group">
<!--                    <label for="username">Username</label>-->
                    <input value="<?= $user ?>" name="user" id="user" type="text" class="form-control"
                           placeholder="Username"
                           oninvalid="this.setCustomValidity('Vui lòng User name')"
                           oninput="setCustomValidity('')"
                    >
                </div>
                <div class="form-group">
<!--                    <label for="password">Password</label>-->
                    <input name="pass" value="<?= $pass ?>" id="password" type="password" class="form-control"
                           placeholder="Password"
                           oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại')"
                           oninput="setCustomValidity('')"
                    >
                </div>
                <div class="form-group text-center">
                    <?php
                        if (!empty($error)) {
                            echo "<div class='alert alert-danger c'>$error</div>";
                        }
                    ?>
                    <button class="btn btn-success px-5 center">Login</button>
                </div>
                <div class="form-group">
                    <p>Don't have an account yet? <a href="/index.php?controller=login&action=go_to_register">Register now</a>.</p>
                    <p>Forgot your password? <a href="/index.php?controller=login&action=forgot">Reset your password</a>.</p>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>
