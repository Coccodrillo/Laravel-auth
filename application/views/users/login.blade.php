@layout('master')

@section('title')
{{ __('users.title') }}
@endsection

@section('content')
<h2>{{ __('users.h2') }}</h2>
{{ Form::open('users/login') }}
	<p>
		<span class="error">&nbsp;{{ Session::get('login_not_correct'); }}</span>
	</p>
	<p>{{ Form::label('username',__('users.username')) }}<br />
	{{ Form::text('username', Input::old('username','')) }}
	{{ $errors->first('username', '<span class="error">:message</span>'); }}</p>

	<p>{{ Form::label('password',__('users.password')) }}<br />
	{{ Form::input('password', 'password', Input::old('password')) }}
	{{ $errors->first('password', '<span class="error">:message</span>'); }}</p>

	<p>{{ Form::submit(__('users.login')) }}</p>
{{ Form::close() }}
@endsection
