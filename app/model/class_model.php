<?php
    require_once ('config/config.php');
    require_once ('notification_model.php');
    require_once ('comment_model.php');
    require_once ('class_user_model.php');
    require_once ('homework_model.php');
    require_once ('title_model.php');

    class class_model {
        public $classId;
        public $className;
        public $subject;
        public $room;
        public $img;

        public function __construct($classId, $className, $subject, $room, $img) {
            $this->classId = $classId;
            $this->className = $className;
            $this->subject = $subject;
            $this->room = $room;
            $this->img = $img;
        }

        public static function search_class_by_class_name($class_name,$username){
            $sql = "select DISTINCT class.* from class,class_user where className like concat('%', ?, '%') 
                and class_user.username= ? and class.classId = class_user.classId";
            $db = DB::getDB();
            $stm = $db->prepare($sql);
            $stm->bind_param('ss', $class_name,$username);
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

        public static function admin_search_class_by_class_name($class_name){
            $sql = "select * from class where className like concat('%', ?, '%')";
            $db = DB::getDB();
            $stm = $db->prepare($sql);
            $stm->bind_param('s', $class_name);
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

        public static function get_all_classes() {
            $sql = 'select * from class';
            $db = DB::getDB();

            $stm = $db->prepare($sql);
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

        public static function get_class_by_id($classid) {
            $sql = 'SELECT * FROM class WHERE classId= ?';
            $db = DB::getDB();
            $stm = $db->prepare($sql);
            $stm->bind_param('i', $classid);
            $status = $stm->execute();
            if ($status) {
                $result = $stm->get_result();
                if ($result->num_rows!=0){
                    return $result->fetch_assoc();
                }
            }

            $stm->close();
            return null;
        }

        public static function get_classId_by_token($token){
            $sql = 'select classId from class where token = ?';
            $db = DB::getDB();

            $stm = $db->prepare($sql);
            $stm->bind_param("s",$token);
            $status = $stm->execute();
            if ($status) {
                $result = $stm->get_result();
                return $result->fetch_assoc();
            }
            $stm->close();
            return null;
        }

        public static function get_all_users() {
            $sql = 'select * from user';
            $db = DB::getDB();

            $stm = $db->prepare($sql);
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

        public static function add_new_class($className, $subject, $room, $img, $token) {
            $sql = 'insert into class(className, subject, room, img, token) values(?, ?, ?, ?, ?)';
            $db = DB::getDB();

            $stm = $db->prepare($sql);
            $stm->bind_param('sssss', $className, $subject, $room, $img, $token);
            $status = $stm->execute();

            if ($status) {
                return $db->insert_id;
            }
            $stm->close();
            return null;
        }
        public static function update_class($classId, $className, $subject, $room, $img) {
            $sql = 'update class set className = ?, subject = ?, room = ?, img = ? where classId = ?';
            $db = DB::getDB();

            $stm = $db->prepare($sql);
            $stm->bind_param('ssssi', $className, $subject, $room, $img, $classId);
            $status = $stm->execute();

            if ($status) {
                return 0;
            }
            $stm->close();
            return null;
        }

        public static function delete_class($classId) {

            $data = notification_model::get_notiId_by_classId($classId);
            $notiId = $data['notiId'];
            comment_model::delete_cmtId_by_notiId($notiId);
            notification_model::delete_notiId_by_classId($classId);
            class_user_model::delete_id_by_classId($classId);
            homework_model::delete_hwId_by_classId($classId);
            title_model::delete_titleId_by_classId($classId);

            $sql = 'delete from class where classId = ?';
            $db = DB::getDB();

            $stm = $db->prepare($sql);
            $stm->bind_param('i', $classId);
            $status = $stm->execute();

            if ($status) {
                return 0;
            }
            $stm->close();
            return null;
        }

    }
