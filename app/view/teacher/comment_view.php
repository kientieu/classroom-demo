<?php
require_once ('config/config.php');
require_once ('app/controller/class_user_controller.php');
require_once ('app/controller/login_controller.php');
require_once ('app/controller/class_controller.php');
require_once ('app/controller/notification_controller.php');
require_once ('app/controller/comment_controller.php');
$error = '';
$username = '';
$data = '';
$class = '';
if (!isset($_SESSION['username']) and !isset($_GET['data']) and !isset($_GET['data2'])){
    header('Location:/index.php?controller=login&action=logout');
}
$username = $_SESSION['username'];
$user = login_controller::get_name_email_by_id($username);
$classId = $_GET['data'];
$class = class_controller::get_class_by_id($classId);


if (isset($_POST['delete_cmt'])){
    comment_controller::del_cmt_by_id($_POST['delete_cmt']);
}

$notiId = $_GET['data2'];
if (isset($_POST['delete'])){
    notification_controller::delete_noti($notiId);
    header('Location:/index.php?controller=teacher&action=stream&data='.$classId);
}

//if (isset($_POST['subject'])) {
//    $text = $_POST['subject'];
//    $file = $_FILES['file'];
//    if (empty($text)) {
//        $error = 'Hãy nhập nội dung';
//    }
//    else {
//        $target_dir = $_SERVER["DOCUMENT_ROOT"]."/class_material/".$classId."/";
//        $target_file = $target_dir . basename($file["name"]);
//        if (file_exists($target_file)) {
//            $error = "Sorry, file already exists.";
//        }else if ($file['size'] < 1048576 *20){
//            move_uploaded_file($file['tmp_name'],$target_file);
//            notification_controller::update_notification($notiId,$text,$file["name"]);
//        }
//    }
//}

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
        notification_controller::update_notification($notiId,$text,$file);
    }
}
$noti = notification_controller::get_noti_by_notiId($notiId);
if ($noti===null) {
    header('Location:/index.php?controller=teacher&action=stream&data='.$classId);
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'postCmt' && isset($_POST['cmtContent'])) {
        $cmtContent = $_POST['cmtContent'];
        teacher_controller::add_new_cmt($cmtContent, $notiId, $username);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bình Luận</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/style.css">
    <script src="/main.js"></script>

</head>
<body>

<!-- Top navigation -->
<nav id="navbar" class="navbar navbar-inverse" >
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <li><a href="/index.php?controller=teacher&action=stream&data=<?=$classId?>">Back</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="#"><span class="glyphicon glyphicon-user"></span><?= $user[0] ?></a></li>
            <li><a href="/index.php?controller=login&action=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
    </div>
</nav>


<div class="row" >
        <div class="card1">
            <div class="container_cmt1">
                <form method="POST" action="#" enctype="multipart/form-data">

                    <div class="row">
                        <div>
                            <label for="subject">Chỉnh sửa thông báo</label>
                        </div>

                        <div>
                            <textarea id="subject" name="subject" placeholder="Chia sẻ đôi điều với lớp học ...." value="<?= $noti['content'] ?>"><?= $noti['content'] ?></textarea>
                        </div>

                        <div class="form-group">
                            <div class="custom-file">
                                <label for="file">Chọn file đính kèm</label>
                                <a href="<?="/class_material/".$classId."/".$noti['file']?>" download><?= $noti['file'] ?></a>
                                <input name="file" type="file" class="custom-file-input" id="customFile" accept="file_extension|audio/*|video/*|image/*|media_type">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <input id="submit_post" type="submit" value="Cập nhật thông báo">
                    </div>

                    <div class="row">
                        <input name="delete" id="submit_post" type="submit" value="Xoá thông báo">
                    </div>

                </form>

            </div>
        </div>
</div>


<div class="text-center">BÌNH LUẬN</div>


<div class="row">
        <?php
        $data = comment_controller::get_cmt_by_notiId($notiId);
        $cmtId = '';
        $content = '';
        $username = '';

        foreach ($data as $row) {
            $content = $row['content'];
            $username = $row['username'];
            $name = login_controller::get_name_email_by_id($row['username']);
            ?>

            <div class="card2">
                <table id="table_thong_bao" >
                    <tr>
                        <td id="st_nguoi_thong_bao"> Người đăng:<?= $name[0]; ?></td>
                        <td id="st_nguoi_thong_bao"> <?= $content; ?></td>
                        <td>
                            <span>
                                <div class="dropdown">
                                    <span class="dropdown-toggle" data-toggle="dropdown">&#8285</span>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <li><a href="#" data-toggle="modal" data-target="#delete_cmt">Xoá</a></li>
                                        </li>
                                    </ul>
                                </div>
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <?php
        }
        ?>


    <div id="delete_cmt" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form method="post" action="">
                    <div class="modal-header">
                        <hp class="modal-title">Xóa Comment</hp>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body h15">
                        <select class="select col-xs-12 col-sm-12 col-md-12 col-lg-12" name="delete_cmt" id="students">
                            <?php
                            foreach ($data as $row) {
                                $content = $row['content'];
                                $name = login_controller::get_name_email_by_id($row['username']);
                                ?>
                                <option value="<?= $row['cmtId'] ?>"><?php echo ($name[0].":".$content) ?></option>
                                <?php
                            }
                            ?>
                        </select>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<div class="container_cmt1">
    <form method="post">
        <div class="row">
            <div>
                <input type="text" id="cmt_section" name="cmtContent" placeholder="Thêm bình luận ...">
            </div
        </div>

        <div class="row">
            <input type="hidden" name="action" value="postCmt">
            <button id="submit_post" type="submit">Đăng</button>
        </div>
    </form>

</div>

</body>
</html>