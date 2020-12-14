<?php


class notification_model
{
    public static function get_notiId_by_classId($classId)
    {
        $sql = 'select notiId from notification where classId = ?';
        $db = DB::getDB();
        $stm = $db->prepare($sql);
        $stm->bind_param('i', $classId);
        $status = $stm->execute();

        if ($status) {
            $result = $stm->get_result();
            return $result->fetch_assoc();
        }
        $stm->close();
        return null;
    }

    public static function new_notification($text,$file,$classId,$username)
    {
        $sql = 'INSERT INTO notification(content, classId, username, file) VALUES(?,?,?,?)';
        $db = DB::getDB();
        $stm = $db->prepare($sql);
        $stm->bind_param('siss', $text, $classId, $username, $file);
        $status = $stm->execute();
        if ($status) {
            return 0;
        }
        $stm->close();
        return null;
    }

    public static function delete_notiId_by_classId($classId)
    {
        $sql = 'delete from notification where classId = ?';
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

    public static function get_noti_by_notiId($notiId)
    {
        $sql = 'select * from notification where notiId = ?';
        $db = DB::getDB();

        $stm = $db->prepare($sql);
        $stm->bind_param('i', $notiId);
        $status = $stm->execute();

        if ($status) {
            $result = $stm->get_result();
            return $result->fetch_assoc();
        }
        $stm->close();
        return null;
    }

    public static function del_noti($notiId)
    {
        $sql = 'delete from notification where notiId = ?';
        $db = DB::getDB();
        $stm = $db->prepare($sql);
        $stm->bind_param('i', $notiId);
        $status = $stm->execute();

        if ($status) {
            return 0;
        }
        $stm->close();
        return null;
    }

    public static function get_all_noti($classId)
    {
        $sql = 'select * from notification where classId = ?';
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

    public static function update_noti($notiId,$content,$file)
    {
        $sql = 'update notification set content = ? , file= ? where notiId = ?';
        $db = DB::getDB();
        $stm = $db->prepare($sql);
        $stm->bind_param('ssi', $content,$file, $notiId);
        $status = $stm->execute();
        if ($status) {
            return 0;
        }
        $stm->close();
        return null;
    }
}

