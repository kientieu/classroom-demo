<?php
require_once ('base_controller.php');
require_once ('app/model/notification_model.php');
require_once ('app/model/comment_model.php');
require_once ('app/model/user_model.php');
class notification_controller
{
    public function get_all_noti($classId) {
        return notification_model::get_all_noti($classId);
    }

    public static function new_notification($text,$file,$classId,$username)
    {

        notification_controller::send_noti_email($text,$classId);
        return notification_model::new_notification($text,$file,$classId,$username);
    }

    public static function get_noti_by_notiId($notiId) {
        return notification_model::get_noti_by_notiId($notiId);
    }

    public static function  update_notification($notiId,$text,$file){
        $noti = notification_controller::get_noti_by_notiId($notiId);
        notification_controller::send_noti_email($text,$noti);
        return notification_model::update_noti($notiId,$text,$file);
    }

    public static function delete_noti($notiId){
        comment_model::delete_cmt_by_notiId($notiId);
       return notification_model::del_noti($notiId);
    }
    public static function send_noti_email($text,$classId){
        $usersID = class_user_model::get_all_userID_with_classid($classId);
        array_shift($usersID);
        $class = class_model::get_class_by_id($classId);
        foreach ($usersID as $userID){
            $email = login_controller::get_name_email_by_id($userID)[1];
            user_model::send_noti_email($email,$text,$class['className']);
        }
    }
}