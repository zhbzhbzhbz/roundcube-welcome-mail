<?php
/*
* Let a newly created user receive a welcome mail after first-logon.
* @author Zhouhongbo
* Roundcube Plugin API: https://github.com/roundcube/roundcubemail/wiki/Plugin-API
* Roundcube Plugin Hook: https://github.com/roundcube/roundcubemail/wiki/Plugin-Hooks#task-login
* User-create Event sample: https://github.com/roundcube/roundcubemail/blob/master/plugins/squirrelmail_usercopy/squirrelmail_usercopy.php 
*/
require 'class.phpmailer.php';
class welcome_mail extends rcube_plugin
{
    public $task = 'login';
	private $from = 'admin@163.org.cn'; //发件人地址
	private $password = "";
	private $subject = '欢迎使用企业邮箱！'; //主题
	private $body = '</p>'; //内容
	function init()
    {
        $this->add_hook('user_create', array($this, 'send_mail_after_first_logon'));
    }
	
	function send_mail_after_first_logon($args){
		$user_email = $args['user'];//切记，这里不能用user_email和user_name，是空的
		$mail = new PHPMailer;
		// Set PHPMailer to use the sendmail transport
		$mail->isSendmail();
		
		//如果用本地的smtp服务器发给本地的邮箱，这部分不需要
		//$mail->SMTPAuth = true; // enable SMTP authentication
		//$mail->SMTPSecure = "ssl"; // sets the prefix to the servier
		//$mail->Host = smtp.mailserver.com";
		//$mail->Port = "587";
		//$mail->Username = $this->from;
		//$mail->Password = $this->password;
		
		
		$mail->CharSet='UTF-8'; //否则中文乱码
		$mail->setFrom($this->from, 'Admin');
		$mail->addReplyTo($this->from, 'Admin');
		$mail->addAddress($user_email, "Dear ***er");
		$mail->Subject = $this->subject;
		$mail->IsHTML(true);  // send as HTML
		$mail->Body = $this->body;
		$mail->AddAttachment("/opt/www/roundcubemail/plugins/welcome_mail/README_EN.pdf"); // 需要绝对路径
		$mail->AddAttachment("/opt/www/roundcubemail/plugins/welcome_mail/README_CN.pdf"); // 需要绝对路径
		
		if (!$mail->send()) {
			error_log("Mailer Error: " . $mail->ErrorInfo);
		}
		return $args;
	}
}
?>