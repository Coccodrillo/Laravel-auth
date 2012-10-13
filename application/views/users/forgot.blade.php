@layout('master')

@section('title')
{{ __('users.forgot') }}
@endsection

@section('content')
<h2>{{ __('users.forgot_h2') }}</h2>
{{ Form::open('users/forgot') }}
	<span class="error">&nbsp;{{ Session::get('message'); }}</span>
	<p>{{ Form::label('username',__('users.username')) }}<br />
	{{ Form::text('username', Input::old('username','')) }}
	{{ $errors->first('username', '<span class="error">:message</span>'); }}</p>

	<p>{{ Form::submit(__('users.recover')) }}</p>
{{ Form::close() }}
@endsection
