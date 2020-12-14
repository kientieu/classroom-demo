<?php
    require_once 'config/config.php';
    class comment_model {

        public static function delete_cmt_by_notiId($notiId) {
            $sql = 'delete from comment where notiId = ?';
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

        // NEW
        public static function add_new_cmt($content, $notiId, $username) { // lấy id sinh viên từ đầu trang; notiId when onclick bình luận
            $sql = 'insert into comment (content, notiId, username) values(?, ?, ?)';
            $db = DB::getDB();

            $stm = $db->prepare($sql);
            $stm->bind_param('sis', $content,$notiId,$username);
            $status = $stm->execute();

            if ($status) {
                return 0;
            }
            $stm->close();
            return null;
        }

        // DELETE COMMENT

        public static function delete_cmt($cmtId) {
            $sql = 'delete from comment where cmtId = ?';
            $db = DB::getDB();

            $stm = $db->prepare($sql);
            $stm->bind_param('i', $cmtId);
            $status = $stm->execute();

            if ($status) {
                return 0;
            }
            $stm->close();
            return null;
        }

        public static function get_cmt_by_notiId($notiId){
            $sql = 'select * from comment where notiId = ?';
            $db = DB::getDB();

            $stm = $db->prepare($sql);
            $stm->bind_param('i', $notiId);
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

        public static function delete_cmtId_by_notiId($notiId) {
            $sql = 'delete from comment where notiId = ?';
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



    }
