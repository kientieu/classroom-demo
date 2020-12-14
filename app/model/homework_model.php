<?php
    require_once 'config/config.php';
    class homework_model {

        public static function delete_hwId_by_classId($classId) {
            $sql = 'delete from homework where classId = ?';
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