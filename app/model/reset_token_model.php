<?php

    use PHPMailer\PHPMailer\PHPMailer;
    require_once 'vendor/autoload.php';
    class reset_token_model {

        public static function send_recover_password($email, $token) {
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
                $mail->Subject = 'Khôi phục mật khẩu classroom';
                $mail->Body = "Click vào <a href='http://localhost:8888/index.php?controller=login&action=reset_password&email=$email&token=$token'>đây</a> để khôi phục mật khẩu";
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        public static function send_reset_password($email) {
            if (!user_model::is_email_exists($email)) {
                return array('code' => 1, 'error' => 'Email does not exist');
            }

            $token = md5($email . '+' . random_int(1000, 2000));
            $exp_on = time() + 3600 * 24;
            $sql = 'update reset_token set token=?, expire_on=? where email=?';

            $db = DB::getDB();
            $stm = $db->prepare($sql);
            $stm->bind_param('sis', $token, $exp_on, $email);
            if (!$stm->execute()) {
                return array('code' => 2, 'error' => 'Cannot execute command');
            }

            if ($stm->affected_rows == 0) {
                //Nếu email chưa có trong reset_token thì thêm mới
                $exp_on = time() + 3600 * 24;

                $sql = 'insert into reset_token values(?, ?, ?)';
                $stm = $db->prepare($sql);
                $stm->bind_param('ssi', $email, $token, $exp_on);

                if (!$stm->execute()) {
                    return array('code' => 2, 'error' => 'Cannot execute command');
                }
            }

            $success = reset_token_model::send_recover_password($email, $token);
            return array('code' => 0, 'success' => $success);
        }

    }
