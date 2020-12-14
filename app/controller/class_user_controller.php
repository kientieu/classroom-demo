<?php
require_once ('config/config.php');
require_once ('app/model/class_user_model.php');
require_once ('app/model/class_model.php');
require_once ('app/model/user_model.php');
class class_user_controller extends base_controller
{

    public static function get_teacher_username_by_classId($classId) {
        $sql = "SELECT * FROM class_user
                    INNER JOIN user ON user.username = class_user.username
                    WHERE user.permission = 'teacher' and class_user.classId = ?";
        $db = DB::getDB();

        $stm = $db->prepare($sql);
        $stm->bind_param('i', $classId);

        $status = $stm->execute();
        if ($status) {
            $result = $stm->get_result();
            $data = array();
            while ($row = $result->fetch_assoc()) {
                array_push($data, $row);
            }
            return $data;
        }
        $stm->close();
        return null;
    }

    public function get_all_classes_with_id($userid){
        $data = array();
        $result = class_user_model::get_all_classes_id_with_id($userid);
        if ($result!=null){
            foreach ($result as $i){
                $class = class_model::get_class_by_id($i);
                if ($class!=null){
                    array_push($data, $class);
                }
            }
        }
        return $data;
    }
    public static function get_all_user_name_with_classid($classid){
        $data = array();
        $result = class_user_model::get_all_userID_with_classid($classid);
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
    public static function get_all_userID_with_classid($classid){
        $result = class_user_model::get_all_userID_with_classid($classid);
        $data = array();
        foreach($result as $userid){
            if(user_model::get_permission_by_id($userid)==='teacher'){
                array_unshift($data , $userid);
            }else {
                array_push($data, $userid);
            }
        }
        return $data;
    }

    public static function get_all_classes_id_with_id($userid){
        return class_user_model::get_all_classes_id_with_id($userid);
    }

    public static function add_new_class($classname, $subject, $room, $image, $username)
    {
        $token = class_user_controller::generateRandomString(6);
        $classId = class_model::add_new_class($classname, $subject, $room, $image, $token);
        class_user_controller::add_user_to_new_class($username, $classId);
        return array('token' => $token, 'result' => $classId);
    }

    public static function  add_user_to_new_class($username, $classId, $accept = 0) {
        return class_user_model::add_user_to_new_class($username,$classId, $accept);
    }

    public static function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public static function get_accept($classId, $username) {
        return class_user_model::get_accept($classId,$username);
    }
    public static function delete_row_by_classId($classId, $username) {
        return class_user_model::delete_row_by_classId($classId,$username);
    }

    public static function check_accept_attendance_of_student($username, $classId) {
        return class_user_model::check_accept_attendance_of_student($username, $classId);
    }
}