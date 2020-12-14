<?php
require_once ('config/config.php');
require_once ('app/controller/class_user_controller.php');
require_once ('app/controller/login_controller.php');
require_once ('app/controller/class_controller.php');
$error = '';
$username = '';
$data = '';
$result = '';
if (isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    $teacher_name = login_controller::get_name_email_by_id($username)[0];
}else {
    header('Location:/index.php?controller=login&action=logout');
}

if (isset($_POST['classid_post']) and isset($_POST['user_email_post'])){
    $temp_username = login_controller::get_username_by_email($_POST['user_email_post']);
    class_user_model::check_accept_attendance_of_student($temp_username, $_POST['classid_post']);
    echo "<script>alert('Đã gửi lời mời tham gia đến mail sinh viên!')</script>";
}

if (isset($_POST['username_delete']) and isset($_POST['classid_post'])){
    class_user_controller::delete_row_by_classId($_POST['classid_post'],$_POST['username_delete']);
}

if (isset($_GET['data'])){
    $classID = $_GET['data'];
    $result = class_user_controller::get_all_userID_with_classid($classID);
    array_shift($result);
}else if (isset($_POST['classid_post'])){
    $classID = $_GET['data'];
    $result = class_user_controller::get_all_userID_with_classid($classID);
    array_shift($result);
}else {
    header('Location:/index.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Danh sách sinh viên</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="/style.css">
    <script src="/main.js"></script>
</head>


<body>

<!-- Top navigation -->
<nav id="navbar" class="navbar navbar-inverse" >
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <li><a id="classroom"  href="/index.php?controller=teacher&action=index" ><?= class_controller::get_class_by_id($classID)['className'] ?></a></li>
            <li><a id="page2" href="/index.php?controller=teacher&action=stream&data=<?=$classID?>" >Luồng</a>   </li>
            <li><a id="page" href="">Danh sách sinh viên</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="#"><span class="glyphicon glyphicon-user"></span> <?= $teacher_name ?></a></li>
            <li><a href="/index.php?controller=login&action=logout"><span class="glyphicon glyphicon-log-out"></span> Log-out</a></li>
        </ul>
    </div>
</nav>

<div id="responsive_table">
    <table id="table_list"">
        <tr class="control">
            <td colspan="2">
                <button> <a href="#" data-toggle="modal" data-target="#teach_add_student">Thêm sinh viên</a></button>
                <button> <a href="#" data-toggle="modal" data-target="#delete_student">Xóa sinh viên</a></button>
            </td>
        </tr>

        <tr class="header">
            <td>Họ và tên</td>
            <td>Email</td>
        </tr>
    <?php
    $name = '';
    $email = '';
    $total = 0;
    foreach($result as $userid){
        $temp_result = login_controller::get_name_email_by_id($userid);
        $name = $temp_result[0];
        $email = $temp_result[1];
        if ($userid === $username){
            continue;
        }
        $total = $total+1;
    ?>
        <tr class="item">
            <td><?= $name ?></td>
            <td><?= $email ?></td>
        </tr>

    <?php
    }
    ?>
        <tr class="control">
            <td colspan="3"> <p>Tổng số sinh viên: <?= $total ?></p></td>
        </tr>

    </table>
</div>

<div id="delete_student" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" action="">
                <div class="modal-header">
                    <hp class="modal-title">Xóa sinh viên</hp>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body h50">
                    <select class="select col-xs-12 col-sm-12 col-md-12 col-lg-12" name="username_delete" id="students">
                        <?php
                        $name = '';
                            foreach($result as $userid){
                            $temp_result = login_controller::get_name_email_by_id($userid);
                            $name = $temp_result[0];
                            if ($userid === $username){
                                continue;
                            }
                        ?>
                        <option value="<?= $userid ?>"><?= $name ?></option>
                        <?php
                            }
                        ?>
                    </select>

                    <input name="classid_post" type="hidden" value="<?= $classID ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </form>
        </div>

    </div>
</div>


<div class="modal fade" id="teach_add_student">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="">
                <div class="modal-header">
                    <h4 class="modal-title">Thêm sinh viên mới</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Email sinh viên muốn thêm vào lớp </label>
                        <input name="user_email_post" type="text" placeholder="Email sinh viên" class="form-control" id="add_name"/>
                        <input name="classid_post" type="hidden" value="<?= $classID ?>">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>


</body>
</html>