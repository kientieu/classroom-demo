<?php
    require_once 'vendor/autoload.php';
    class admin_controller extends base_controller {
        function __construct() {
            $this->name = 'admin';
        }

        function list_class() {
            $this->process('list_class', array());
        }

        function add_class() {
            $this->process('add_class', array());
        }

        function list_user() {
            $this->process('list_user', array());
        }

        function index() {
            $this->process('list_class', array());
        }
        function check_permission($user) {
            return user_model::check_permission($user);
        }

        function get_all_classes() {
            return class_model::get_all_classes();
        }

        function get_all_users() {
            return user_model::get_all_users();
        }

        function get_teacher_username() {
            return user_model::get_teacher_username("teacher");
        }

        function get_teacher_username_by_classId($classId) {
            return class_user_model::get_teacher_username_by_classId($classId);
        }

        function admin_search_class_by_class_name($classId) {
            return class_model::admin_search_class_by_class_name($classId);
        }

        function add_new_class($className, $subject, $room, $img, $username) {
            $token = $this->generateRandomString(6);
            $classId = class_model::add_new_class($className, $subject, $room, $img, $token);
            $this->add_teacher_to_new_class($username, $classId);
            return array('token' => $token, 'classId' => $classId);
        }

        function add_teacher_to_new_class($username, $classId) {
            return class_user_model::add_teacher_to_new_class($username, $classId);
        }

        function generateRandomString($length) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        function update_class($classId, $className, $subject, $room, $img) {
            return class_model::update_class($classId, $className, $subject, $room, $img);
        }

        function update_teacher_username_by_classId($username, $classId) {
            return class_user_model::update_teacher_username_by_classId($username, $classId);
        }

        function delete_class($classId) {
            return class_model::delete_class($classId);
        }

        function change_permission($username, $permission) {
            return  user_model::change_permission($username, $permission);
            if ($result['code'] == 0) {
                return array('code' => 0, 'message' => "Create account successful");
            } else {
                $error = 'An error occured. Please try again later';
            }
            return array('code' => 1, 'message' => $error);
        }

        function delete_user($username) {
            return user_model::delete_user($username);
        }
    }

