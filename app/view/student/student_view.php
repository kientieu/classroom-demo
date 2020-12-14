<?php
    require_once 'config/config.php';
    require_once 'app/controller/student_controller.php';

    $student_controller = new student_controller();

    $error = '';
    $username = '';
    $data = array();
    $result = '';
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $result = $student_controller->get_all_classes_with_id($username);
    }
    else {
        header('Location: /index.php?controller=login&action=logout');
    }

    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action == 'postClassToken' && isset($_POST['classToken'])) {
            $classToken = $_POST['classToken'];
            $student_controller->check_accept_attendance($username, $classToken);
            echo "<script>alert('Đã gửi yêu cầu tham gia đến giảng viên!')</script>";
        }
    }

    if (isset($_GET['search_value'])) {
        $result = student_controller::search_class_by_class_name($_GET['search_value'], $username);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lớp học</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="jquery-1.6.1.js"></script>

    <link rel="stylesheet" type="text/css" href="/style.css">
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
            <li><a href="#"><span class="glyphicon glyphicon-user"> Welcome, <b><?= $_SESSION['username'] ?></b></span></a></li>
            <li><a href="#" class="add_student"><span class="glyphicon glyphicon-plus"></span></a></li>
            <li><a href="/index.php?controller=login&action=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
    </div>
</nav>

<!--slide navigation-->
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="/index.php?controller=student&action=student_view">Danh sách lớp học</a>
    <?php
        $data = $result;
        $classId = '';
        $className = '';
        $subject = '';
        $room = '';
        $img = '';
        $teacher_name = '';


            foreach ($data as $row) {
                $classId = $row['classId'];
                $className = $row['className'];
                $subject = $row['subject'];
                $room = $row['room'];
                $img = $row['img'];
                $teacher_name = $student_controller->get_all_user_name_with_classId($classId)[0];
                ?>
                    <a href="/index.php?controller=student&action=stream_view&classId=<?= $classId; ?>&className=<?= $className; ?>&teacherName=<?= $teacher_name; ?>&img=<?= $img; ?>"><?= $className ?></a>
                <?php
            }

    ?>
</div>


<!--thanh tìm kiếm-->

<div class="container">
    <div class="row">
        <div>
            <h3 class="text-center mb-3" >Tìm kiếm lớp học</h3>
            <form action="#" method="get">
                <input type="text" name="search_value" class="form-control" placeholder="Nhập tên lớp học">
                <input type="submit" class="form-control" value="Tìm">
            </form>

        </div>
    </div>
</div>

<div class="row">
    <?php

    $data = $result;

    foreach ($data as $row) {
        $classId = $row['classId'];
        $className = $row['className'];
        $subject = $row['subject'];
        $room = $row['room'];
        $img = $row['img'];
        $teacher_name = $student_controller->get_all_user_name_with_classId($classId)[0];
        ?>
        <div id="row" class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
            <div class="card_st">
                <table id="table_mon_hoc" background="/images/class_background/<?= $img; ?>">
                <tr>
                    <td id="ten_lop">
                        <span><a href="/index.php?controller=student&action=stream_view&classId=<?= $classId; ?>&className=<?= $className; ?>&teacherName=<?= $teacher_name; ?>&img=<?= $img; ?>"><?= $className; ?></a></span>
                    </td>

                </tr>
                <tr>
                    <td id="ten_giang_vien">
                        <p >Giảng viên phụ trách: </p>
                        <p ><?= $teacher_name; ?></p>
                    </td>
                </tr>
                </table>

                <div class="container1"></div>

                <div class="topnav">
                    <a href="#">&#10060</a>
                    <a href="#">&#128194</a>
                </div>

            </div>

        </div>
        <?php
    }
    ?>
</div>

<!--Attend class model-->
<div class="modal fade" id="add_student">
    <div class="modal-dialog">
        <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tham gia lớp học mới</h4>
                </div>
            <form method="post">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Mã vào lớp học </label>
                        <input name="classToken" type="text" placeholder="Nhập mã lớp ..." class="form-control"/>
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="hidden" name="action" value="postClassToken">
                    <button type="submit" class="btn btn-success" id="save-newfolder">Tham gia</button>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>