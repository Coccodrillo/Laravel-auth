<?php

class User extends Eloquent {

	public static $timestamps = true;

	public function set_password($password)
	{
		$this->set_attribute('password', Hash::make($password));
	}

}

?>
