@layout('master')

@section('title')
{{ __('users.change') }}
@endsection

@section('content')
<h2>{{ __('users.change_h2') }}</h2>
{{ Form::open('users/change') }}
	<span class="error">&nbsp;{{ Session::get('message'); }}</span>
	<p>{{ Form::label('password',__('users.old_password')) }}<br />
	{{ Form::input('password', 'password', Input::old('password')) }}
	{{ $errors->first('password', '<span class="error">:message</span>'); }}</p>

	<p>{{ Form::label('password2',__('users.new_password')) }}<br />
	{{ Form::input('password', 'password2', Input::old('password2')) }}
	{{ $errors->first('password2', '<span class="error">:message</span>'); }}</p>

	<p>{{ Form::label('password3',__('users.repeat_new_password')) }}<br />
	{{ Form::input('password', 'password3', Input::old('password3')) }}
	{{ $errors->first('password3', '<span class="error">:message</span>'); }}</p>

	<p>{{ Form::submit(__('users.change')) }}</p>
{{ Form::close() }}
@endsection
