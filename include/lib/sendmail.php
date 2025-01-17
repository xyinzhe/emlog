<?php

class SendMail {

	public $smtp_host;
	public $smtp_port;
	public $smtp_username;
	public $smtp_password;

	public function __construct() {
		$this->smtp_host = Option::get('smtp_server');
		$this->smtp_port = Option::get('smtp_port');
		$this->smtp_username = Option::get('smtp_mail');
		$this->smtp_password = Option::get('smtp_pw');
	}

	function send($to, $title, $content) {
		$mail = new PHPMailer(true);
		$mail->IsSMTP();
		$mail->CharSet = 'UTF-8';
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = $this->smtp_port == '587' ? 'STARTTLS' : 'ssl';
		$mail->Port = $this->smtp_port;
		$mail->Host = $this->smtp_host;
		$mail->Username = $this->smtp_username;
		$mail->Password = $this->smtp_password;
		$mail->From = $this->smtp_username;
		if (is_array($to)) {
			foreach ($to as $value) {
				$mail->AddAddress($value);
			}
		} else {
			$mail->AddAddress($to); #抄送
		}

		$mail->Subject = $title;
		$mail->Body = $content;
		$mail->WordWrap = 80; // Set the length of each string line

		$mail->IsHTML(true);

		try {
			return $mail->Send();
		} catch (Exception $exc) {
			return false;
		}
	}

}
