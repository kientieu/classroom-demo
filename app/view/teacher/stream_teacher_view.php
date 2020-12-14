<?php
require_once ('config/config.php');
require_once ('app/controller/class_user_controller.php');
require_once ('app/controller/login_controller.php');
require_once ('app/controller/class_controller.php');
require_once ('app/controller/notification_controller.php');
$error = '';
$username = '';
$data = '';
$class = '';
if (!isset($_SESSION['username']) and !isset($_GET['data'])){
    header('Location:/index.php?controller=login&action=logout');
}

$username = $_SESSION['username'];
$teacher_name = login_controller::get_name_email_by_id($username)[0];
$classId = $_GET['data'];
$class = class_controller::get_class_by_id($classId);

$text = '';
$file = '';
if (isset($_POST['subject'])) {
    $text = $_POST['subject'];


    if (empty($text)) {
        $error = 'Hãy nhập nội dung';
    }
    else {
        if (isset($_FILES['file'])){
            $file = $_FILES['file'];
            if ($file['error'] != UPLOAD_ERR_OK) {
                $error = 'Vui lòng upload file';
            }
            $target_dir = $_SERVER["DOCUMENT_ROOT"]."/class_material/".$classId."/";
            $target_file = $target_dir . basename($file["name"]);
            if (file_exists($target_file)) {
                $error = "Sorry, file already exists.";
            }
            else if ($file['size'] < 1048576 *20){
                move_uploaded_file($file['tmp_name'],$target_file);
            }
            $file = $file['name'];
        }
        notification_controller::new_notification($text,$file,$classId,$username);
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $class['className'] ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/style.css">
    <script src="/main.js"></script>

    <link rel="stylesheet" href="teacher_view.css">

</head>
<body>

<!-- Top navigation -->
<nav id="navbar" class="navbar navbar-inverse" >
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <li><a id="classroom" href="/index.php?controller=teacher&action=index"><?= $class['className'] ?></a></li>
            <li><a id="page" href="/index.php?controller=teacher&action=stream&data=<?=$classId?>" >Luồng</a></li>
            <li><a id="page2" href="/index.php?controller=teacher&action=people&data=<?=$classId?>">Danh sách sinh viên</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="#"><span class="glyphicon glyphicon-user"></span> <?= $teacher_name ?></a></li>
            <li><a href="/index.php?controller=login&action=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
    </div>
</nav>

<img src="/images/class_background/<?= $class['img']?>" alt="Avatar" class="responsive_image">

<div class="row" >
    <div class="card1">
        <div class="container_cmt1">
            <form action="#" method="POST" enctype="multipart/form-data">

                <div class="row">
                    <div>
                        <label for="subject">Thêm thông báo mới</label>
                    </div>

                    <div>
                        <textarea id="subject" name="subject" placeholder="Chia sẻ đôi điều với lớp học ...."></textarea>
                    </div>

                    <div class="form-group">
                        <div class="custom-file">
                            <label for="file">Chọn file đính kèm</label>
                            <input name="file" type="file" class="custom-file-input" id="customFile" accept="file_extension|audio/*|video/*|image/*|media_type">
                        </div>
                    </div>

                </div>
                <div>
                </div>
                <div class="row">
                    <input id="submit_post" type="submit" value="Post">
                </div>

            </form>

        </div>
    </div>
</div>

<?php
     
?>
<div class="row">

    <?php
    $data = notification_controller::get_all_noti($classId);
    $content = '';

    foreach ($data as $row) {
        $content = $row['content'];
        ?>
        <div class="row">

            <div id="row" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card1">
                    <table id="table_thong_bao"  onclick="window.location.href='/index.php?controller=teacher&action=comment&data=<?=$classId?>&data2=<?=$row['notiId']?>'">
                        <tr>
                            <td id="st_nguoi_thong_bao"> Người đăng: </td>
                            <td id="st_nguoi_thong_bao"> <?= $teacher_name; ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" id="st_nguoi_thong_bao"> <?= $content; ?> </td>
                        </tr>
                    </table>

                    <div class="container2"> <a href="/index.php?controller=teacher&action=comment_view&data=<?=$classId?>&data2=<?=$row['notiId']?>'">Bình luận</a> </div>
                </div>

            </div>

        </div>
        <?php
    }
    ?>
</div>

</body>
</html>