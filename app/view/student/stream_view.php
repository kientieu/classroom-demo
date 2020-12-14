<?php
    require_once 'config/config.php';
    require_once 'app/controller/student_controller.php';

    $student_controller = new student_controller();

    $error = '';
    $username = '';
    $data = '';
    $result = '';
    if (isset($_SESSION['username'])){
        $username = $_SESSION['username'];
        $result = $student_controller->get_all_classes_with_id($username);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
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
        <div class="navbar-header">
            <span id= "slider_menu" onclick="openNav()" >&#9776</span>
        </div>
        <ul class="nav navbar-nav">
            <li><a id="classroom" href="#"><?= $_GET['className']; ?></a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li><a href="#"><span class="glyphicon glyphicon-user"> Welcome, <b><?= $_SESSION['username'] ?></b></span></a></li>
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

<img src="/images/class_background/<?= $_GET['img']; ?>" alt="Avatar" class="responsive_image">


<div class="row">

    <?php
        $classId = $_GET['classId'];
        $className = $_GET['className'];
        $teacherName = $_GET['teacherName'];
        $img = $_GET['img'];

        $data = $student_controller->get_all_noti($classId);
        $notiId = '';
        $content = '';

        foreach ($data as $row) {
            $notiId = $row['notiId'];
            $content = $row['content'];
            $file = $row['file'];
            $filePath = "/class_material/" . $className . "/" . $file;
        ?>
            <div class="row">

                <div id="row" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card1">
                        <table id="table_thong_bao"  onclick="window.location.href='/index.php?controller=student&action=comment_view&classId=<?= $classId; ?>&className=<?= $className; ?>&teacherName=<?= $teacherName; ?>&img=<?= $img; ?>&notiId=<?= $notiId; ?>&teacherName=<?= $teacherName; ?>&content=<?= $content; ?>&file=<?= $file; ?>'">
                            <tr>
                                <td id="st_nguoi_thong_bao"> Người đăng: </td>
                                <td id="st_nguoi_thong_bao"> <?= $teacherName; ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" id="st_nguoi_thong_bao"> <?= $content; ?> </td>
                            </tr>
                            <tr>
                                <td colspan="3" id="st_nguoi_thong_bao">File đính kèm: <a href="<?= $filePath; ?>"  download><?= $file ?></a> </td>
                            </tr>
                        </table>

                        <div class="container2"> <a href="/index.php?controller=student&action=comment_view&classId=<?= $classId; ?>&className=<?= $className; ?>&teacherName=<?= $teacherName; ?>&img=<?= $img; ?>&notiId=<?= $notiId; ?>&teacherName=<?= $teacherName; ?>&content=<?= $content; ?>&file=<?= $file; ?>">Bình luận</a> </div>
                    </div>

                </div>

            </div>
        <?php
    }
    ?>
</div>

</body>
</html>