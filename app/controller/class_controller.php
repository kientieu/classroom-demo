<?php
require_once('base_controller.php');
require_once ('app/model/class_model.php');

class class_controller extends base_controller
{

    public static function get_all_classes()
    {
        return class_model::get_all_classes();
    }

    public static function search_class_by_class_name($class_name,$username){
        return class_model::search_class_by_class_name($class_name,$username);
    }
    
    public static function get_class_by_id($classid)
    {
        return class_model::get_class_by_id($classid);
    }

    public static function get_all_users()
    {
        return class_model::get_all_users();
    }

    public static function add_new_class($className, $subject, $room, $img)
    {
        return class_model::add_new_class($className, $subject, $room, $img);
    }
    public static function delete_class($classId) {
        return class_model::delete_class($classId);
    }

    public static function update_class($classId, $className, $subject, $room, $img) {
        return class_model::update_class($classId, $className, $subject, $room, $img);
    }


}