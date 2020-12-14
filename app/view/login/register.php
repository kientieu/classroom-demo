<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng kí tài khoản sinh viên</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <script src="main.js"></script>
</head>
<body class="main_background">
<?php
    require_once ("app/controller/login_controller.php");
    $error = '';
    $name = '';
    $email = '';
    $user = '';
    $pass = '';
    $pass_confirm = '';
    $birth = '';
    $phone = '';
    $success = "";
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['user'])
    && isset($_POST['pass']) && isset($_POST['pass_confirm']) && isset($_POST['birth']) && isset($_POST['phone']))
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $user = $_POST['user'];
        $pass = $_POST['pass'];
        $pass_confirm = $_POST['pass_confirm'];
        $birth = $_POST['birth'];
        $phone = $_POST['phone'];

        if (empty($name)) {
            $error = 'Please enter your name';
        }
        else if (empty($email)) {
            $error = 'Please enter your email';
        }
        else if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            $error = 'This is not a valid email address';
        }
        else if (empty($birth)) {
            $error = 'Please enter your birthday';
        }
        else if (empty($phone)) {
            $error = 'Please enter your phone number';
        }
        else if (empty($pass)) {
            $error = 'Please enter your password';
        }
        else if (strlen($pass) < 6) {
            $error = 'Password must have at least 6 characters';
        }
        else if ($pass != $pass_confirm) {
            $error = 'Password does not match';
        }
        else {
            $pass = password_hash($pass, PASSWORD_DEFAULT);
            $result = login_controller::register_check($name, $email, $user, $pass, $pass_confirm, $birth, $phone);
            if ($result['code'] == 0){
                $success = $result['message'];
            }else {
                $error = $result['message'];
            }
        }
    }
?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 border my-5 p-4 rounded mx-3">
                <h3 class="text-center text-secondary mt-2 mb-3 mb-3">Create a new account</h3>
                <form method="post" action="" >
                    <div class="form-group ">
                        <label for="name">Họ và Tên</label>
                        <input value="<?= $name?>" name="name" required class="form-control" type="text"
                               placeholder="Nhập họ tên" id="name"
                               oninvalid="this.setCustomValidity('Vui lòng nhập họ tên')"
                               oninput="setCustomValidity('')">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input value="<?= $email?>" name="email" required class="form-control" type="email"
                               placeholder="Email" id="email" onkeyup="logup_on_change(this.value);"
                               oninvalid="this.setCustomValidity('Vui lòng nhập email')"
                               oninput="setCustomValidity('')"
                        >
                    </div>
                    <div class="form-group">
                        <label for="user">Username</label>
                        <input value="<?= $user?>" name="user" required class="form-control" type="text"
                               placeholder="Username" id="user" READONLY
                               oninvalid="this.setCustomValidity('Vui lòng nhập username')"
                               oninput="setCustomValidity('')"
                        >
                    </div>
                    <div class="form-group">
                        <label for="pass">Password</label>
                        <input  value="" name="pass" required class="form-control" type="password"
                                placeholder="Password" id="pass"
                                oninvalid="this.setCustomValidity('Vui lòng nhập password')"
                                oninput="setCustomValidity('')">
                        <div class="invalid-feedback">Password is not valid.</div>
                    </div>

                    <div class="form-group">
                        <label for="pass2">Confirm Password</label>
                        <input value="" name="pass_confirm" required class="form-control"
                               type="password" placeholder="Confirm Password" id="pass2"
                               oninvalid="this.setCustomValidity('Vui lòng nhập password lại')"
                               oninput="setCustomValidity('')"
                        >
                    </div>

                    <div class="form-group">
                        <label for="user">Birth</label>
                        <input value="<?= $birth?>" name="birth" required class="form-control" type="datetime-local"
                               placeholder="Birth" id="birth"
                               oninvalid="this.setCustomValidity('Vui lòng nhập ngày sinh')"
                               oninput="setCustomValidity('')"
                        >
                    </div>
                    <div class="form-group">
                        <label for="user">Phone</label>
                        <input value="<?= $phone?>" name="phone" required class="form-control" type="tel"
                               placeholder="Phone" id="phone" pattern="[0-9]{10}"
                               oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại')"
                               oninput="setCustomValidity('')">
                    </div>
                    <div class="form-group">
                        <?php
                            if (!empty($error)) {
                                echo "<div class='alert alert-danger'>$error</div>";
                            }
                            else if (!empty($success)) {
                                echo "<div class='alert alert-success'>$success</div>";
                            }
                        ?>
                        <button type="submit" class="btn btn-success  mt-3 mr-2">Register</button>
                        <button type="reset" class="btn btn-outline-success  mt-3">Reset</button>
                    </div>
                    <div class="form-group">
                        <p>Already have an account? <a href="/index.php">Login</a> now.</p>
                    </div>
                </form>

            </div>
        </div>

    </div>
</body>
</html>

