<?php
    require_once 'vendor/autoload.php';
    class student_controller extends base_controller
    {
        function __construct(){
            $this->name = 'student';
        }

        public function student_view() {
            $this->process('student_view', array());
        }
        public function index() {
            $this->process('student_view', array());
        }

        public function stream_view() {
            $this->process('stream_view', array());
        }

        public function comment_view() {
            $this->process('comment_view', array());
        }

        public function get_all_classes_with_id($username){
            $data = array();
            $result = class_user_model::get_all_classes_id_with_id($username);
            if ($result!=null){
                foreach ($result as $i) {
                    $class = class_model::get_class_by_id($i);
                    array_push($data, $class);
                }
            }
            return $data;
        }

        public function get_all_user_name_with_classId($classId){
            $data = array();
            $result = class_user_model::get_all_username_with_classId($classId);
            if ($result!=null){
                foreach ($result as $id){
                    $user = user_model::get_user_by_id($id);
                    if ($user!=null){
                        if ($user['permission'] ==="teacher"){
                            array_unshift($data , $user['name']);
                        }else {
                            array_push($data, $user['name']);
                        }
                    }
                }
            }
            return $data;
        }

        public function get_user_by_id($username) {
            return user_model::get_user_by_id($username);
        }

        public function get_all_noti($classId) {
            return notification_model::get_all_noti($classId);
        }

        public function get_cmt_by_notiId($notiId) {
            return comment_model::get_cmt_by_notiId($notiId);
        }

        public function add_new_cmt($content, $notiId, $username) {
            return comment_model::add_new_cmt($content, $notiId, $username);
        }

        public function check_accept_attendance($username, $token) {
            return class_user_model::check_accept_attendance($username, $token);
        }

        public static function search_class_by_class_name($class_name,$username){
            return class_model::search_class_by_class_name($class_name,$username);
        }
    }