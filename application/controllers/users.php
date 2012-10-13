<?php

class Users_Controller extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->filter('before', 'auth')->only(array('change'));
	}

	public $restful = true;

	public function get_index()
	{
		if ( Session::has('pre_login_url') )
		{
			$url = Session::get('pre_login_url');
			Session::forget('pre_login_url');
			return Redirect::to($url);
		}
		return Redirect::to('tracker');
	}

	public function get_login()
	{
		return View::make('users.login');
	}

	public function post_login()
	{
		$credentials = Input::get();
		$rules = array(
			'username'  => 'required|email|between:6,30|exists:users,email',
			'password'  => 'required|max:50'
		);
		$validation = Validator::make($credentials, $rules);
		if ($validation->fails())
		{
			return Redirect::to('users/login')->with_errors($validation->errors)->with_input();
		}
		$user = User::where_email($credentials["username"])->first();
		if ($user->hash)
		{
			return Custom::message('Account not confirmed', 'You haven\'t confirmed your email address yet', 'Click on the confirmation link in the email we sent you. If you haven\'t received an email, click '.HTML::link('users/resend', 'here').'.');
		}
		if (Auth::attempt($credentials))
		{
			 return Redirect::to('users/index');
		}
		return Redirect::to('users/login')->with('login_not_correct', __('users.login_not_correct'))->with_input();
	}



	public function get_register()
	{
		return View::make('users.register');
	}

	public function post_register()
	{
		$register = Input::get();
		$rules = array(
			'username'  => 'required|email|between:6,30|unique:users,email',
			'password'  => 'required|between:6,30',
			'password2'  => 'required|same:password'
		);
		$messages = array(
			'same' => __('users.error_password2'),
		);
		$validation = Validator::make($register, $rules, $messages);
		if ($validation->fails())
		{
			return Redirect::to('users/register')->with_errors($validation->errors)->with_input();
		}
		$created = User::create(array('email' => $register["username"], 'password' => $register["password"], 'group' => 2, 'hash' => Str::random(32)));
		if ($created)
		{
			$user = User::where_email($register["username"])->first();
			$content = array('subject' => 'Confirm your email address', 'body' => 'Click on the link to confirm registration.<br /><br />'.HTML::link('users/hash/?email='.$user->email."&hash=".$user->hash, 'Confirm registration'));
			if (Custom::email($user, $content))
			{
				Auth::login($user->id);
				return Custom::message('Registered', 'You have successfuly completed registration', 'message', __('users.registered'));
			}
			return Custom::message('Error', 'Error while sending email', 'Please contact the administrator at the bottom link');
		}
		return Custom::message('Error', 'Database error', 'Please contact the administrator at the bottom link');
	}

	public function get_resend()
	{
		return View::make('users.resend');
	}

	public function post_resend()
	{
		$credentials = Input::get();
		$rules = array(
			'username'  => 'required|email|max:50|exists:users,email',
		);
		$messages = array(
			'exists' => 'Provided email hasn\'t been registered yet.'
		);
		$validation = Validator::make($credentials, $rules, $messages);
		if ($validation->fails())
		{
			return Redirect::to('users/resend')->with_errors($validation->errors)->with_input();
		}
		$user = User::where_email($credentials["username"])->first();
		if (!$user->hash)
		{
			return Custom::message('Account already confirmed', 'Confirmation has already been completed', '');
		}
		$content = array('subject' => 'Confirm your email address', 'body' => 'Click on the link to confirm registration.<br /><br />'.HTML::link('users/hash/?email='.$user->email."&hash=".$user->hash, 'Confirm registration'));
		if (Custom::email($user, $content))
		{
			return Custom::message('Email resent', 'Confirmation email has been successfully resent', 'Click on the link to complete the registration process');
		}
		return Custom::message('Error', 'Error while sending email', 'Please contact the administrator at the bottom link');
	}


	public function get_hash()
	{
		$user = User::where_email(Input::get('email'))->first();
		if ($user->hash==Input::get('hash'))
		{
			$user->hash = null;
			$user->save();
			Auth::login($user->id);
			return Custom::message('Registration confirmed', 'You have successfully confirmed your email account', 'You can now continue using Tasker without further interruptions.');
		}
	}

	public function get_change()
	{
		return View::make('users.change');
	}

	public function post_change()
	{
		$change = Input::get();
		$rules = array(
			'password'  => 'required|hash_check',
			'password2'  => 'required|between:6,30',
			'password3'  => 'required|same:password2'
		);
		Validator::register('hash_check', function($attribute, $value, $parameters)
		{
			return $value == Hash::check($value, Auth::user()->password);
		});
		$messages = array(
			'same' => __('users.error_password2'),
			'hash_check' => 'Existing password is not correct.',
		);
		$validation = Validator::make($change, $rules, $messages);
		if ($validation->fails())
		{
			return Redirect::to('users/change')->with_errors($validation->errors)->with_input();
		}
		$user = User::find(Auth::user()->id);
		$user->password = $change["password2"];
		if ($user->save())
		{
			return Custom::message('Password changed', __('users.pass_changed'), '');
		}
		return Custom::message('Error', 'Database error', 'Please contact the administrator at the bottom link');
	}

	public function get_forgot()
	{
		return View::make('users.forgot');
	}

	public function post_forgot()
	{
		$credentials = Input::get();
		$rules = array(
			'username'  => 'required|email|max:50|exists:users,email|forgot',
		);
		Validator::register('forgot', function($attribute, $value, $parameters)
		{
			$user = User::where_email($value)->first();
			return (isset($user)) ? (bool) !$user->forgot : true;
		});
		$messages = array(
			'exists' => 'Provided email hasn\'t been registered yet.',
			'forgot' => 'The recovery procedure is already in progress.'
		);
		$validation = Validator::make($credentials, $rules, $messages);
		if ($validation->fails())
		{
			return Redirect::to('users/forgot')->with_errors($validation->errors)->with_input();
		}

		$user = User::where_email($credentials["username"])->first();
		$user->forgot = Str::random(32);

		if ($user->save())
		{
			$content = array('subject' => 'Change password you forgot', 'body' => 'Click on the following link to change your password.<br /><br />'.HTML::link('users/recover/?email='.$user->email."&forgot=".$user->forgot, 'Change your password'));

			if (Custom::email($user, $content))
			{
				return Custom::message('Email sent', 'Email with the reset link has been successfully sent', 'Click on the link to change your password');
			}
			return Custom::message('Email resent', 'Confirmation email has been successfully resent', 'Click on the link to complete the registration process');
		}
		return Custom::message('Error', 'Database error', 'Please contact the administrator at the bottom link');
	}

	public function get_recover()
	{
		$user = User::where_email(Input::get('email'))->first();
		if (isset($user)) {
			if ($user->forgot==Input::get('forgot'))
			{
				return View::make('users.recover');
			}
		}
		return Redirect::to('users/login');
	}

	public function post_recover()
	{
		$user = User::where_forgot(Input::get('forgot'))->first();
		if ($user)
		{
			$register = array(
				'password' => Input::get('password'),
				'password2' => Input::get('password2')
				);
			$rules = array(
				'password'  => 'required|between:6,30',
				'password2'  => 'required|same:password'
			);
			$messages = array(
				'same' => __('users.error_password2'),
			);
			$validation = Validator::make($register, $rules, $messages);
			if ($validation->fails())
			{
				return Redirect::to('users/recover')->with_errors($validation->errors)->with_input();
			}
			$user->forgot = null;
			$user->password = Input::get('password');
			if ($user->save())
			{
				Auth::login($user->id);
				return Custom::message('Password successfully changed', 'You have changed your password', 'You\'ve also been logged in. Continue using Tasker without further interruptions.');
			}
			return Custom::message('Error', 'Database error', 'Please contact the administrator at the bottom link');
		}
	}

	public function get_logout()
	{
		Auth::logout();
		return Redirect::to('users');
	}

}
