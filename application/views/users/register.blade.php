@layout('master')

@section('title')
{{ __('users.registration') }}
@endsection

@section('content')
<h2>{{ __('users.register_h2') }}</h2>
{{ Form::open('users/register') }}
	<p>{{ Form::label('username',__('users.username')) }}<br />
	{{ Form::text('username', Input::old('username','')) }}
	{{ $errors->first('username', '<span class="error">:message</span>'); }}</p>

	<p>{{ Form::label('password',__('users.password')) }}<br />
	{{ Form::input('password', 'password', Input::old('password')) }}
	{{ $errors->first('password', '<span class="error">:message</span>'); }}</p>

	<p>{{ Form::label('password2',__('users.password2')) }}<br />
	{{ Form::input('password', 'password2', Input::old('password2')) }}
	{{ $errors->first('password2', '<span class="error">:message</span>'); }}</p>

	<p>{{ Form::submit(__('users.register')) }}</p>
{{ Form::close() }}
@endsection
