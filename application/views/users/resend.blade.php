@layout('master')

@section('title')
{{ __('users.resend') }}
@endsection

@section('content')
<h2>{{ __('users.resend_h2') }}</h2>
{{ Form::open('users/resend') }}
	<p>{{ Form::label('username',__('users.username')) }}<br />
	{{ Form::text('username', Input::old('username','')) }}
	{{ $errors->first('username', '<span class="error">:message</span>'); }}</p>

	<p>{{ Form::submit(__('users.resend')) }}</p>
{{ Form::close() }}
@endsection
