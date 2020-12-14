<?php
    require_once 'config/config.php';
    require_once 'app/controller/admin_controller.php';
    $admin_controller = new admin_controller();

    if (isset($_SESSION['username'])) {
        $result = $admin_controller->check_permission($_SESSION['username']);
        if (!$result) {
            header('Location: /index.php');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Thông tin lớp học</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="/style.css">
    <script src="jquery-1.6.1.js"></script>
    <script src="/main.js"></script>
</head>
<body class="main_background">
<?php

    $error = '';
    $classname = '';
    $subject = '';
    $room = '';
    $image = '';
    $token = '';

    if (isset($_POST['classname']) && isset($_POST['subject']) && isset($_POST['room']) && isset($_POST['class_images']))
    {
        $classname = $_POST['classname'];
        $subject = $_POST['subject'];
        $room = $_POST['room'];
        $image = $_POST['class_images'];
        $username = $_POST['username'];

        if (empty($classname)) {
            $error = 'Hãy nhập tên lớp học';
        }
        else if (empty($subject)) {
            $error = 'Hãy nhập tên môn học';
        }
        else if (empty($room)) {
            $error = 'Hãy nhập phòng học';
        }
        else {
            if (isset($_GET['classId'])) {
                $classId = $_GET['classId'];

                if (isset($_POST['class_images'])) {
                    $image = $_POST['class_images'];
                }
                else {
                    $image = $_GET['class_images'];
                }

                $result = $admin_controller->update_class($classId, $classname, $subject, $room, $image);

                if (isset($_POST['username'])) {
                    $username = $_POST['username'];
                }
                else {
                    $username = $_GET['username'];
                }
                $result1 = $admin_controller->update_teacher_username_by_classId($username, $classId);

                if ($result === 0 && $result1 === 0) {
                    echo "<script type='text/javascript'>
                    alert('Cập nhật thành công');
                    window.location.replace('/index.php?controller=admin&action=list_class');
                    </script>";
                }
                else {
                    $error = 'Cập nhật thất bại! Vui lòng thử lại sau';
                }
            }
            else {
                $result = $admin_controller->add_new_class($classname, $subject, $room, $image, $username);
                $filePath = "C://xampp/htdocs/class_material/" . $result['classId'];
                mkdir($filePath);

                if ($result['classId'] != null) {
                    $error = 'Thêm lớp học thành công';
                    $token = $result['token'];
                    $classname = '';
                    $subject = '';
                    $room = '';
                }
                else {
                    $error = 'Thêm thất bại! Vui lòng thử lại sau';
                }
            }

        }
    }
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-8 border rounded my-5 p-4  mx-3 sua_lop_hoc">
            <p class="mb-5"><a href="/index.php?controller=admin&action=list_class">Quay lại</a></p>
            <h3 class="text-center text-secondary mt-2 mb-3 mb-3"><?php if (isset($_GET['classId'])) { echo 'Sửa lớp học'; } else { echo 'Thêm lớp học mới'; } ?></h3>
            <form method="post" action="" novalidate enctype="multipart/form-data">

                <div class="form-group">
                    <label for="classname">Tên lớp học</label>
                    <input value="<?php if (isset($_GET['className'])) { echo $_GET['className']; } else { echo $classname; } ?>" name="classname" required class="form-control" type="text" placeholder="Tên lớp học" id="classname">
                </div>

                <div class="form-group">
                    <label for="subject">Tên môn học</label>
                    <input value="<?php if (isset($_GET['subject'])) { echo $_GET['subject']; } else { echo $subject; } ?>" name="subject" required class="form-control" type="text" placeholder="Tên môn học">
                </div>

                <div class="form-group">
                    <label for="room">Phòng học</label>
                    <input value="<?php if (isset($_GET['room'])) { echo $_GET['room']; } else { echo $room; } ?>" name="room" required class="form-control" type="text" placeholder="Phòng học" id="room">
                </div>

                <div class="form-group">
                    <label for="customFile">Ảnh đại diện</label>
                    <div class="select_option">
                        <select class="select_option select col-xs-12 col-sm-12 col-md-12 col-lg-12" name="class_images" id="class_image" onclick="update_image()">
                            <option value="image1.jpg">image1</option>
                            <option value="image2.jpg">image2</option>
                            <option value="image3.jpg">image3</option>
                            <option value="image4.jpg">image4</option>
                            <option value="image5.jpg">image5</option>
                        </select>

                        <input name="image" type="hidden">
                        <input name="username" type="hidden" value="<?= $_SESSION['username']?>"
                    </div>
                </div>

                <div class="form-group">
                    <label for="room">Giảng viên phụ trách <?php if (isset($_GET['username'])) { echo '(Hiện tại: ' . $_GET['username'] . ')'; }?></label>
                    <select name="username" class="form-control">
                        <?php
                            $data = $admin_controller->get_teacher_username();
                            $username = '';
                            foreach($data as $row) {
                                $username = $row['username'];
                                ?>
                                <option value="<?= $username; ?>"><?= $username; ?></option>
                                <?php
                            }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <?php
                    if (!empty($error)) {
                        echo "<div class='alert alert-danger'>$error</div>";
                        if (!empty($token)) {
                            echo "<div class='alert alert-success'>Mã vào lớp học <strong>$token</strong></div>";
                        }
                    }
                    ?>
                    <button type="submit" class="btn btn-primary px-5 mr-2"><?php if (isset($_GET['classId'])) { echo 'Sửa'; } else { echo 'Thêm'; } ?></button>
                </div>
            </form>

        </div>
    </div>

</div>

</body>
</html>

