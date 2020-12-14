<?php
    require_once 'vendor/autoload.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == 'accept') {
        login_controller::add_student($_GET['username'], $_GET['token']);
        header('Location:/index.php?controller=login&action=logout');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
        rel="stylesheet"
        href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    />
    <link
        rel="stylesheet"
        href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
        integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
        crossorigin="anonymous"
    />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="/style.css">
</head>
<body class="main_background">
<?php
    $error = '';
    $message = '';
    $className = '';
    if (isset($_GET['username']) && isset($_GET['token']) && isset($_GET['className'])) {
        $username = $_GET['username'];
        $token = $_GET['token'];
        $className = $_GET['className'];

        if (strlen($token) != 6) {
            $error = 'Invalid token format';
        }
    }
    else {
        $error = 'Invalid url';
    }
?>

<?php
    if (!empty($error)) {
    ?>
        <div class="row">
            <div class="col-md-6 mt-5 mx-auto p-3 border rounded">
                <h4>Confirm request attending class <?= $className; ?></h4>
                <p class="text-danger"><?= $error; ?></p>
            </div>
        </div>
    <?php
    }
    else {
    ?>
        <div class="row">
            <div class="col-md-6 mt-5 mx-auto p-3 border rounded">
                <h4>Confirm request attending class <?= $className; ?></h4>
                <p class="text-success">To add student into class: Click <b>Accept</b> or Not: Click Decline</p>
                <form method="post">
                    <input type="hidden" name="action" value="accept">
                    <div class="text-center">
                        <button type="submit" class="btn btn-success px-5">Accept</button>
                        <button type="button" class="btn btn-danger px-5">Decline</button>
                    </div>
                </form>
            </div>
        </div>
    <?php
    }
?>
</body>
</html>
