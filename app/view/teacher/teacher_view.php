<?php
require_once ('app/controller/class_user_controller.php');
require_once ('app/controller/class_controller.php');
$error = '';
$username = '';
$data = '';
$result = '';
if (!isset($_SESSION['username'])){
    header('Location:/index.php?controller=login&action=logout');

}
if (isset($_POST['classIdPost'])) {
    class_controller::delete_class($_POST['classIdPost']);
}

$username = $_SESSION['username'];
$teacher_name = login_controller::get_name_email_by_id($username)[0];
$result = class_user_controller::get_all_classes_with_id($username);

if (isset($_GET['search_value'])) {
    $result = class_controller::search_class_by_class_name($_GET['search_value'],$username);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Teacher</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/style.css">
    <script src="/main.js"></script>

</head>
<body>


<nav id="navbar" class="navbar navbar-inverse" >
    <div class="container-fluid">
        <div class="navbar-header">
            <span id= "slider_menu" onclick="openNav()" >&#9776</span>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="#" id="classroom">Classroom</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li><a href="#"><span class="glyphicon glyphicon-user"></span> Welcome, <?= $teacher_name?> </a></li>
            <li><a href="/index.php?controller=login&action=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
    </div>
</nav>



<!--slide navigation-->

<div id="mySidenav" class="sidenav">
<!--    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>-->
    <button type="button" class="closebtn" onclick="closeNav()">x</button>
    <?php
    $data = $result;
    $className = '';
    $classId = '';
    foreach ($data as $row) {
        $className = $row['className'];
        $classId = $row['classId'];
        ?>
        <a href="/index.php?controller=teacher&action=stream&data=<?=$classId?>"><?= $className ?></a>
        <?php
    }
    ?>
</div>


<!--thanh tìm kiếm-->

<div class="container">
    <div class="row">
        <div >
            <h3 class="text-center mb-3" >Tìm kiếm lớp học</h3>
            <form action="#" method="get">
                <input type="text" name="search_value" class="form-control" placeholder="Nhập tên lớp học">
                <input type="submit" class="form-control" value="Tìm">
            </form>

        </div>
    </div>
</div>

<div class="text-center"> <a class="btn btn-success btn-lg" href="/index.php?controller=teacher&action=add_class&data=-1">Thêm lớp học mới</a> </div>






<div class="row">
    <?php
    $data = $result;
    $classId = '';
    $className = '';
    $subject = '';
    $room = '';
    $img = '';
    foreach ($data as $row) {
        $classId = $row['classId'];
        $className = $row['className'];
        $subject = $row['subject'];
        $room = $row['room'];
        $img = $row['img'];
        $teacher_name = class_user_controller::get_all_user_name_with_classid($classId)[0];
        ?>
        <div id="row" class="col-xs-6 col-sm-4 col-md-4 col-lg-3">


            <div class="card_st">
                <table id="table_mon_hoc" background="/images/class_background/<?= $img ?>">
                    <tr>
                        <div>
                            <td id="ten_lop" onclick="window.location.href='/index.php?controller=teacher&action=stream&data=<?=$classId?>'">
                                <span><?= $className ?></span>
                            </td>
                        </div>
                        <td id="ten_lop">
                            <span>
                                <div class="dropdown">
                                    <span class="dropdown-toggle" data-toggle="dropdown">&#8285</span>
                                    <ul class="dropdown-menu">
                                        <li><a href="#" onclick="del_class(<?= $classId ?>,'<?= $className ?>')" data-toggle="modal" data-target="#myModal">Xóa lớp học</a></li>
                                        <li><a href="/index.php?controller=teacher&action=add_class&data=<?=$classId?>">Sửa lớp học</a></li>
                                    </ul>
                                </div>
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td id="ten_giang_vien">
                            <p >Mã lớp: <?= $row['token']?></p>
                        </td>
                    </tr>
                </table>

                <div class="container1" ></div>
            </div>

        </div>
        <?php
    }
    ?>
    <!-- Delete Confirm Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                    <h3 class="modal-title">Xóa lớp học</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form method="post">
                    <div class="modal-body">
                        <p>Bạn có chắc rằng muốn xóa <strong id="classNameView"></strong> ?</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" value="delete_class">
                        <input type="hidden" name="classIdPost" id="classIdPost">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
</body>
</html>