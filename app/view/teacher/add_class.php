<?php
require_once ('config/config.php');
require_once ('app/controller/class_controller.php');
require_once ('app/controller/login_controller.php');
require_once ('app/controller/class_user_controller.php');

if (!isset($_SESSION['username'])){
    header('Location:/index.php?controller=login&action=logout');
}
$result = login_controller::get_permission_by_id($_SESSION['username']);
if ($result!='teacher') {
    header('Location:/index.php?controller=login&action=logout');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Thêm lớp học</title>
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

$class = '';
$teacher_name = '';
$ten = 'Thêm lớp học mới';
$btn = "Thêm";
$flag = 0;
if (isset($_GET['data'])){
    $classId = $_GET['data'];

    if ($classId!=-1){
        $flag = 1;
        $class = class_controller::get_class_by_id($classId);
        if ($class!=null){
            $classname = $class['className'];
            $subject = $class['subject'];
            $room = $class['room'];
            $teacher_name = class_user_controller::get_all_user_name_with_classid($classId)[0];
        }
        $ten = 'Sửa lớp học ';
        $btn = "Sửa";
    }
}

if (isset($_POST['classname']) && isset($_POST['subject']) && isset($_POST['room']) && isset($_POST['class_images']))
{
    $classname = $_POST['classname'];
    $subject = $_POST['subject'];
    $room = $_POST['room'];
    $username = $_POST['username'];
    $image = $_POST['class_images'];
    if (empty($classname)) {
        $error = 'Hãy nhập tên lớp học';
    }
    else if (empty($subject)) {
        $error = 'Hãy nhập tên môn học';
    }
    else if (empty($room)) {
        $error = 'Hãy nhập phòng học';
    }
    else if (empty($image)) {
        $error = 'Vui lòng upload ảnh lớp học';
    }
    else {
        if ($flag===1) {
            $classId = $_GET['data'];
            $result = class_controller::update_class($classId, $classname, $subject, $room, $image);
            if ($result === 0) {
                echo "<script type='text/javascript'>alert('Cập nhật thành công')</script>";
                header('Location:/index.php');
            }
            else {
                $error = 'Cập nhật thất bại! Vui lòng thử lại sau';
            }
        }
        else {
            $result = class_user_controller::add_new_class($classname, $subject, $room, $image, $username);
            if ($result['result'] != null) {
                $target_dir = $_SERVER["DOCUMENT_ROOT"]."/class_material/" . $result['result'];
                if (!file_exists(($target_dir))) {
                    mkdir($target_dir);
                }
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
            <p class="mb-5"><a href="/index.php">Quay lại</a></p>
            <h3 class="text-center text-secondary mt-2 mb-3 mb-3"><?= $ten ?></h3>
            <form method="POST" action="" novalidate enctype="multipart/form-data">

                <div class="form-group">
                    <label for="classname">Tên lớp học</label>
                    <input value="<?= $classname ?>" name="classname" required class="form-control" type="text" placeholder="Tên lớp học" id="classname">
                </div>

                <div class="form-group">
                    <label for="subject">Tên môn học</label>
                    <input value="<?= $subject ?>" name="subject" required class="form-control" type="text" placeholder="Tên môn học">
                </div>

                <div class="form-group">
                    <label for="room">Phòng học</label>
                    <input value="<?= $room ?>" name="room" required class="form-control" type="text" placeholder="Phòng học" id="room">
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
                    <?php
                    if (!empty($error)) {
                        echo "<div class='alert alert-danger'>$error</div>";
                        if (!empty($token)) {
                            echo "<div class='alert alert-success'>Mã vào lớp học <strong>$token</strong></div>";
                        }
                    }
                    ?>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary px-5 mr-2"><?= $btn ?></button>
                </div>
            </form>

        </div>
    </div>

</div>

</body>
</html>

