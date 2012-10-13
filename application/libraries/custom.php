<?php
class Custom {

	public static $msgss = array();

	public static function message($title, $h2, $message){
		return View::make('users.message')
					->with('title', $title)
					->with('h2', $h2)
					->with('message', $message);
	}

	public static function email($user, $content)
	{
		$mail = new SMTP();
		$mail->to($user->email);
		$mail->from('rok@loremipsum.si', 'Tasker');
		$mail->subject($content['subject']);
		$mail->body($content['body']);
		return $mail->send();
	}
}
