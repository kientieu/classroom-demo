<?php
require_once('base_controller.php');
require_once ('app/model/user_model.php');

class login_controller extends base_controller
{
    function __construct() {
        $this->name = 'login';
    }
    function go_to_login()
    {
        $this->process('login', array());
    }

    function go_to_register() {
        $this->process('register',array());
    }
    function logout(){
        $this->process('logout',array());
    }

    function accept_attendance() {
        $this->process('accept_attendance', array());
    }

    function accept_attendance_student() {
        $this->process('accept_attendance_student', array());
    }

    static function check_login($user,$pass){
        return user_model::check_to_login($user,$pass);
    }

    static function register_check($name, $email, $user, $pass, $birth, $phone){

        if (user_model::is_username_exists($user)){
            $error = 'Username already exist';
        }else if (user_model::is_email_exists($email)){
            $error = 'This email already exist';
        }else {
            $result = user_model::register($name, $email, $user, $pass, $birth, $phone);
            if ($result['code'] == 0) {
                return array('code'=> 0, 'message' =>"Create account successful");
            }else {
                $error = 'An error occured. Please try again later';
            }
        }
        return array('code'=> 1, 'message' =>$error);
    }

    private static function get_user_by_id($userid){
        return user_model::get_user_by_id($userid);
    }

    public static function get_name_email_by_id($userid){
        $data = array();
        $result = user_model::get_user_by_id($userid);
        array_push($data,$result['name'],$result['email']);

        return $data;
    }

    public static function get_permission_by_id($user){
        return user_model::get_permission_by_id($user);
    }

    public static function get_teacher_username()
    {
        return user_model::get_teacher_username();
    }

    public static function get_username_by_email($email){
        return user_model::get_username_by_email($email);
    }


    function login() {
        $this->process('login', array());
    }

    function forgot() {
        $this->process('forgot', array());
    }

    function reset_password() {
        $this->process('reset_password', array());
    }


    public static function get_username_login($username) {
        return user_model::get_username_login($username);
    }

    public static function send_reset_password($email) {
        return reset_token_model::send_reset_password($email);
    }

    public static function set_recover_password($email, $pass) {
        return user_model::set_recover_password($email, $pass);
    }

    public static function add_student($username, $token) {
        return class_user_model::add_student($username, $token);
    }
}
