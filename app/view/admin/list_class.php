<?php
    require_once 'config/config.php';
    require_once 'app/controller/admin_controller.php';
    $admin_controller = new admin_controller();

    if (isset($_SESSION['username'])) {
        $result = $admin_controller->check_permission($_SESSION['username']);
        if (!$result) {
            header('Location:/index.php');
        }
    }

    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action == 'delete_class' && isset($_POST['classIdPost'])) {
            $classId = $_POST['classIdPost'];
            $admin_controller->delete_class($classId);
        }
    }

    $data = $admin_controller->get_all_classes();

    if (isset($_GET['search_value'])) {
        $result = $admin_controller->admin_search_class_by_class_name($_GET['search_value']);
        $data = $result;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Danh sách lớp</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <link rel="stylesheet" href="/style.css">
    <script src="jquery-1.6.1.js"></script>
    <script src="/main.js"></script>
</head>

<body>

<nav id="navbar" class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <span id= "slider_menu" onclick="openNav()" >&#9776</span>
        </div>
        <ul class="nav navbar-nav">
            <li><a id="classroom" href="#">Classroom</a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li><a href="#"><span class="glyphicon glyphicon-user"></span> Welcome, <b><?= $_SESSION['username'] ?></b></a></li>
            <li><a href="/index.php?controller=login&action=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
    </div>
</nav>

<h2 class="text-center">Danh sách lớp học</h2>

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

<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="/index.php?controller=admin&action=list_user">Danh sách người dùng</a>
    <a href="/index.php?controller=admin&action=list_class">Danh sách lớp</a>
</div>

<div id="responsive_table">
    <table id="table_list">

        <tr class="control1">
            <td colspan="4">
                <a class="btn btn-success btn-lg" href="/index.php?controller=admin&action=add_class" >Thêm lớp học</a>
            </td>
        </tr>
        <tr class="header">
            <td>Mã lớp</td>
            <td>Tên lớp</td>
            <td>Môn học</td>
            <td>Phòng học</td>
            <td>Hình đại diện</td>
            <td>Mã vào lớp</td>
            <td>Sửa</td>
            <td>Xoá</td>
        </tr>
        <?php
        $classId = '';
        $className = '';
        $subject = '';
        $room = '';
        $img = '';
        $token = '';

        $username = '';

        $total = count($data);
        foreach ($data as $row) {
            $classId = $row['classId'];
            $className = $row['className'];
            $subject = $row['subject'];
            $room = $row['room'];
            $img = $row['img'];
            $token = $row['token'];
            $data1 = $admin_controller->get_teacher_username_by_classId($classId);
            foreach ($data1 as $row1) {
                $username = $row1['username'];
            }
            ?>
            <tr class="item">
                <td><?= $classId; ?></td>
                <td><?= $className; ?></td>
                <td><?= $subject; ?></td>
                <td><?= $room; ?></td>
                <td><img id="image" src="/images/class_background/<?= $img; ?>"></td>
                <td><?= $token; ?></td>
                <td><a href="/index.php?controller=admin&action=add_class&classId=<?= $classId ?>&className=<?= $className ?>&subject=<?= $subject ?>&room=<?= $room ?>&image=<?= $img ?>&username=<?= $username; ?>" class="edit">Sửa</a></td>
                <td> <a href="#" class="delete" onclick="del_class(<?= $classId; ?>, '<?= $className; ?>')">Xoá</a></td>
            </tr>
            <?php
        }
        ?>
        <tr class="control">
            <td colspan="8">
                <p>Tổng số lớp: <?= $total; ?></p>
            </td>
        </tr>
    </table>
</div>


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
</body>
</html>
