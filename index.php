<?php
    require_once("vendor/autoload.php");
    if (!isset($_SESSION)) {
        session_start();
    }

    $data = null;
    if (isset($_SESSION['username']) and isset($_SESSION['permission'])) {
        if (isset($_GET['controller']) and isset($_GET['action'])) {
            $controller = $_GET['controller'];
            $action = $_GET['action'];
        if (isset($_GET['data'])){
            $data = array($_GET['data']);

        }
        if (isset($_GET['data2'])){
            $data = array_push($data,$_GET['data2']);
        }
        }else {
            $controller = $_SESSION['permission'];
            $action = 'index';
        }
    }
    else {
        if (isset($_GET['controller']) and isset($_GET['action'])) {
            $controller = $_GET['controller'];
            $action = $_GET['action'];
        }
        else {
            $controller = 'login';
            $action = 'go_to_login';
        }
    }
    include_once('app/controller/' . $controller . '_controller.php');
    $className = $controller."_controller";

    if (class_exists($className)) {
        $obj = new $className();
        if ($data!=null){
            $obj->$action($data);
        }else{
            $obj->$action();
        }
    }
?>
