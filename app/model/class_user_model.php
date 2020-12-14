<?php
require_once('config/config.php');

use PHPMailer\PHPMailer\PHPMailer;
require_once ('vendor/autoload.php');

    class class_user_model
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

        public static function get_accept($classId, $username) {
            $sql = 'select accept from class_user where classId = ? and username= ?';
            $db = DB::getDB();

            $stm = $db->prepare($sql);
            $stm->bind_param('si', $username, $classId);
            $status = $stm->execute();

            if ($status) {
                return $stm->get_result()->fetch_assoc()['accept'];
            }

            $stm->close();
            return null;
        }

        public static function set_accept($classId, $username) {

        }

        public static function delete_row_by_classId($classId, $username) {
            $sql = 'delete from class_user where classId = ? and username = ?';
            $db = DB::getDB();

            $stm = $db->prepare($sql);
            $stm->bind_param('is', $classId,$username);
            $status = $stm->execute();

            if ($status) {
                return 0;
            }
            $stm->close();
            return null;
        }

        public static function delete_id_by_classId($classId) {
            $sql = 'delete from class_user where classId = ?';
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

        public static function add_user_to_new_class($username, $classId, $accept) {
            $sql = 'insert into class_user(username, classId, accept) values (?, ?, ?)';
            $db = DB::getDB();

            $stm = $db->prepare($sql);
            $stm->bind_param('sii', $username, $classId, $accept);
            $status = $stm->execute();

            if ($status) {
                return 0;
            }

            $stm->close();
            return null;
        }

        public static function teacher_send_noti_attend_class($username, $email, $className, $classId) {
            // Instantiation and passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
                $mail->isSMTP();                                            // Send using SMTP
                $mail->CharSet = 'UTF-8';
                $mail->Host = 'smtp.gmail.com';                    // Set the SMTP server to send through
                $mail->SMTPAuth = true;                                   // Enable SMTP authentication
                $mail->Username = 'naiss11250@gmail.com';                     // SMTP username
                $mail->Password = 'dxgfohpfcndbswzh';                               // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                //Recipients
                $mail->setFrom('naiss11250@gmail.com', 'Admin classroom');
                $mail->addAddress($email, 'Admin classroom');     // Add a recipient
                /*$mail->addAddress('ellen@example.com');               // Name is optional
                $mail->addReplyTo('info@example.com', 'Information');
                $mail->addCC('cc@example.com');
                $mail->addBCC('bcc@example.com');*/

                // Attachments
                /*$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); */   // Optional name

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Xác nhận tham gia lớp học từ giảng viên';
                $mail->Body = "Giảng viên phụ trách lớp $className muốn thêm bạn vào lớp học. <br>
                                Click vào <a href='http://localhost:8888/index.php?controller=login&action=accept_attendance_student&username=$username&classId=$classId&className=$className'>đây</a> để xác nhận tham gia lớp học của giảng viên";
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        public static function check_accept_attendance_of_student($username, $classId) {
            $data = user_model::get_user_by_id($username);
            $email = $data['email'];

            $data1 = class_model::get_class_by_id($classId);
            $className = $data1['className'];

            $success = class_user_model::teacher_send_noti_attend_class($username, $email, $className, $classId);
            return array('code' => 0, 'success' => $success);

        }

        public static function get_all_classes_id_with_id($userid) {
            $sql = 'SELECT * FROM class_user WHERE username = ? ';
            $db = DB::getDB();
            $stm = $db->prepare($sql);
            $stm->bind_param('s', $userid);

            $status = $stm->execute();
            if ($status) {
                $result = $stm->get_result();
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    array_push($data, $row['classId']);
                }
                return $data;
            }
            $stm->close();
            return null;
        }

        public static function get_all_userID_with_classid($classid){
            $sql = 'SELECT * FROM class_user WHERE classId = ? ';
            $db = DB::getDB();
            $stm = $db->prepare($sql);
            $stm->bind_param('i', $classid);

            $status = $stm->execute();
            if ($status) {
                $result = $stm->get_result();
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    array_push($data, $row['username']);
                }
                return $data;
            }
            $stm->close();
            return null;
        }

        public static function add_teacher_to_new_class($username, $classId) {
            $sql = 'insert into class_user(username, classId) values (?, ?)';
            $db = DB::getDB();

            $stm = $db->prepare($sql);
            $stm->bind_param('si', $username, $classId);
            $status = $stm->execute();

            if ($status) {
                return 0;
            }

            $stm->close();
            return null;
        }

        public static function add_student($username, $token) {
            $data = class_model::get_classId_by_token($token);
            $classId = $data['classId'];
            $sql = 'insert into class_user(username, classId) values(?, ?)';
            $db = DB::getDB();

            $stm = $db->prepare($sql);
            $stm->bind_param('si', $username,$classId);
            $status = $stm->execute();

            if ($status) {
                return 0;
            }
            $stm->close();
            return null;
        }

        public static function update_teacher_username_by_classId($username, $classId) {
            $sql = 'update class_user set username = ? where classId = ?';
            $db = DB::getDB();

            $stm = $db->prepare($sql);
            $stm->bind_param('si', $username,$classId);
            $status = $stm->execute();

            if ($status) {
                return 0;
            }
            $stm->close();
            return null;
        }



        public static function get_all_username_with_classId($classId){
            $sql = 'SELECT * FROM class_user WHERE classId = ? ';
            $db = DB::getDB();
            $stm = $db->prepare($sql);
            $stm->bind_param('i', $classId);

            $status = $stm->execute();
            if ($status) {
                $result = $stm->get_result();
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    array_push($data, $row['username']);
                }
                return $data;
            }
            $stm->close();
            return null;
        }


        public static function send_accept_attend_class($username, $email, $token, $className) {
            // Instantiation and passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
                $mail->isSMTP();                                            // Send using SMTP
                $mail->CharSet = 'UTF-8';
                $mail->Host = 'smtp.gmail.com';                    // Set the SMTP server to send through
                $mail->SMTPAuth = true;                                   // Enable SMTP authentication
                $mail->Username = 'naiss11250@gmail.com';                     // SMTP username
                $mail->Password = 'dxgfohpfcndbswzh';                               // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                //Recipients
                $mail->setFrom('naiss11250@gmail.com', 'Admin classroom');
                $mail->addAddress($email, 'Admin classroom');     // Add a recipient
                /*$mail->addAddress('ellen@example.com');               // Name is optional
                $mail->addReplyTo('info@example.com', 'Information');
                $mail->addCC('cc@example.com');
                $mail->addBCC('bcc@example.com');*/

                // Attachments
                /*$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); */   // Optional name

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Xác nhận yêu cầu tham gia lớp học';
                $mail->Body = "Click vào <a href='http://localhost:8888/index.php?controller=login&action=accept_attendance&username=$username&token=$token&className=$className'>đây</a> để xác nhận yêu cầu tham gia lớp học của học viên";
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        public static function check_accept_attendance($username, $token) {
            $data = class_model::get_classId_by_token($token);
            $classId = $data['classId'];

            $data1 = class_model::get_class_by_id($classId);
            $className = $data1['className'];

            $data2 = class_user_model::get_teacher_username_by_classId($classId);
            $teacherUsername = '';
            foreach ($data2 as $row2) {
                $teacherUsername = $row2['username'];
            }

            $data3 = user_model::get_email_by_teacher_username($teacherUsername);
            $email = '';
            foreach ($data3 as $row3) {
                $email = $row3['email'];
            }

            $success = class_user_model::send_accept_attend_class($username, $email, $token, $className);
            return array('code' => 0, 'success' => $success);

        }
    }