<?php
    require_once 'config/config.php';
    require_once 'app/controller/admin_controller.php';
    $admin_controller = new admin_controller();

    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action == 'edit' && isset($_POST['usernameEdit'])) {
            $permission = $_POST['permission'];
            $username = $_POST['usernameEdit'];
            $admin_controller->change_permission($username, $permission);
        }
        else if ($action == 'delete_user' && isset($_POST['usernameDel'])) {
            $username = $_POST['usernameDel'];
            $admin_controller->delete_user($username);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Danh sách người dùng</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="/style.css">
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
            <li><a href="index.php?controller=login&action=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
    </div>
</nav>

<h2 class="text-center">Danh sách người dùng</h2>

<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="index.php?controller=admin&action=list_user">Danh sách người dùng</a>
    <a href="index.php?controller=admin&action=list_class">Danh sách lớp</a>

</div>

<div id="responsive_table">
    <table id="table_list">

        <tr class="header">
            <td>STT</td>
            <td>Họ và tên</td>
            <td>Ngày sinh</td>
            <td>Email</td>
            <td>Điện thoại</td>
            <td>Quyền</td>
            <td>Sửa quyền</td>
        </tr>
        <?php
        $data = $admin_controller->get_all_users();
        $username = '';
        $pass = '';
        $name = '';
        $birth = '';
        $email = '';
        $phone = '';
        $permission = '';
        $total = count($data);
        foreach ($data as $row) {
            if ($row['username'] === $_SESSION['username']) {
                continue;
            }
            else {
                $username = $row['username'];
                $pass = $row['pass'];
                $name = $row['name'];
                $birth = $row['birth'];
                $email = $row['email'];
                $phone = $row['phone'];
                $permission = $row['permission'];
            }
            ?>
            <tr class="item">
                <td><?= $username; ?></td>
                <td><?= $name; ?></td>
                <td><?= $birth; ?></td>
                <td><?= $email; ?></td>
                <td><?= $phone; ?></td>
                <td><?= $permission; ?></td>
                <td><a href="#" onclick="edit_permission('<?= $username ?>')" class="edit_permission">Sửa</a></td>
            </tr>
            <?php
        }
        ?>
        <tr class="control">
            <td colspan="8">
                <p>Tổng số người dùng: <?= $total; ?></p>
            </td>
        </tr>
    </table>
</div>

<div class="modal fade" id="re_permission">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Đổi quyền</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form method="post">
                <div class="modal-body">
                    <p>Thay đổi quyền người dùng <strong id="usernameView"></strong> ?</p>

                    <label for="permission">Chọn quyền mới</label>
                    <select class="form-control" name="permission">
                        <option value="admin">Admin</option>
                        <option value="student">Sinh viên</option>
                        <option value="teacher">Giảng viên</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="usernameEdit" id="usernameEdit">
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>
