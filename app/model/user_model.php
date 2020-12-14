<?php
require_once('config/config.php');

use PHPMailer\PHPMailer\PHPMailer;
require_once ('vendor/autoload.php');

class user_model
{
    public static function set_recover_password($email, $pass) {
        $hashPass = password_hash($pass, PASSWORD_DEFAULT);

        $sql = 'update user set pass=? where email=?';

        $db = DB::getDB();
        $stm = $db->prepare($sql);
        $stm->bind_param('ss', $hashPass, $email);

        if (!$stm->execute()) {
            return array('code' => 1, 'error' => 'Cannot execute command');
        }
        return array('code' => 0, 'error' => 'Change password successfully');
    }
    
    public static function get_email_by_teacher_username($username) {
        $sql = 'select * from user where username = ?';
        $db = DB::getDB();

        $stm = $db->prepare($sql);
        $stm->bind_param('s', $username);
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

    public static function check_to_login($username,$pass){
        $sql = "SELECT * FROM USER WHERE username = ?";
        $db = DB::getDB();
        $stm = $db->prepare($sql);
        if ($stm === False){
            return array('code' => 3, 'message' => 'sql code wrong');
        }
        $stm->bind_param('s',$username);
        $status = $stm->execute();

        if ($status) {
            $result = $stm->get_result();
            if ($result->num_rows == 0) {
                return array('code' => 2, 'error' => 'User does not exist'); //không có user nào tồn tại
            }else {
                $data = mysqli_fetch_assoc($result);
                $hashed_pass = $data['pass'];
                if (!password_verify($pass, $hashed_pass)) {
                    return array('code' => 3, 'error' => 'Invalid password');
                }
                else {
                    $permission = $data['permission'];
                    return array('code' => 0, 'message' => '', 'data' => $permission);
                }
            }
        }
        return array('code' => 1, 'message' => 'something wrong');
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

    public static function is_email_exists($email) {
        $sql = "SELECT username FROM USER WHERE email = ?";
        $db = DB::getDB();
        $stm = $db->prepare($sql);
        $stm->bind_param('s', $email);
        $status = $stm->execute();

        if($status) {
            $result = $stm->get_result();
            if ($result->num_rows > 0) {
                return true;
            }
            else {
                return false;
            }
        }else {
            die("Query error: " . $stm->error);
        }
    }

    public static function check_permission($user) {
        $sql = "SELECT username FROM USER WHERE username = ? AND permission = ?";
        $db = DB::getDB();
        $stm = $db->prepare($sql);
        $permisstion = "admin";
        $stm->bind_param('ss', $user,$permisstion);
        $status = $stm->execute();

        if($status) {
            $result = $stm->get_result();
            if ($result->num_rows > 0) {
                return true;
            }
            else {
                return false;
            }
        }else {
            die("Query error: " . $stm->error);
        }
    }

    public static function get_teacher_username() {
        $sql = 'select * from user where permission = ?';
        $db = DB::getDB();

        $permission = 'teacher';
        $stm = $db->prepare($sql);
        $stm->bind_param('s', $permission);
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

    public static function is_username_exists($user) {
        $sql = "SELECT username FROM USER WHERE username = ?";
        $db = DB::getDB();
        $stm = $db->prepare($sql);
        $stm->bind_param('s', $user);
        $status = $stm->execute();

        if($status) {
            $result = $stm->get_result();
            if ($result->num_rows > 0) {
                return true;
            }
            else {
                return false;
            }
        }else {
            die("Query error: " . $stm->error);
        }
    }

    public static function get_permission_by_id($user){
        $sql = "SELECT permission FROM USER WHERE username = ?";
        $db = DB::getDB();
        $stm = $db->prepare($sql);
        $stm->bind_param('s', $user);
        $status = $stm->execute();

        if($status) {
            $result = $stm->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc()['permission'];
            }
            else {
                return null;
            }
        }else {
            die("Query error: " . $stm->error);
        }
    }

    public static function get_username_by_email($email){
        $sql = "SELECT username FROM user WHERE email = ?";
        $db = DB::getDB();
        $stm = $db->prepare($sql);
        $stm->bind_param('s', $email);
        $status = $stm->execute();

        $data = array();
        if($status) {
            $result = $stm->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc()['username'];
            }
            else {
                return null;
            }
        }else {
            die("Query error: " . $stm->error);
        }
    }

    public static function get_user_by_id($user){
        $sql = "SELECT * FROM user WHERE username = ?";
        $db = DB::getDB();
        $stm = $db->prepare($sql);
        $stm->bind_param('s', $user);
        $status = $stm->execute();

        $data = array();
        if($status) {
            $result = $stm->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }
            else {
                return null;
            }
        }else {
            die("Query error: " . $stm->error);
        }
    }

    public static function register($name, $email, $user, $pass, $birth, $phone){
        $register_code = 'INSERT INTO user(username, pass, name, birth, email, phone, permission) VALUES(?,?,?,?,?,?,?)';
        $db = DB::getDB();
        $stm = $db->prepare($register_code);
        if ($stm === False){
            return array('code' => 4, 'error' => 'something wrong');
        }
        $permission = "student";
        $stm->bind_param('sssssss',$user, $pass, $name, $birth, $email, $phone, $permission);
        $status = $stm->execute();

        if($status) {
            return array('code' => 0, 'sucess' => '');
        }else {
            return array('code' => 3, 'error' => 'something wrong');
        }
    }

    public static function send_noti_email($email, $content,$class_name) {
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
            $mail->Subject = $class_name;
            $mail->Body = $content;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function change_permission($username, $permission) {
        $sql = 'update user set permission = ? where username = ?';
        $db = DB::getDB();

        $stm = $db->prepare($sql);
        $stm->bind_param('ss', $permission, $username);
        $status = $stm->execute();

        if($status) {
            return 0;
        }
        $stm->close();
        return null;
    }



}

