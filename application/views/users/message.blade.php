@layout('master')

@section('title')
{{ $title }}
@endsection

@section('content')
<h2>{{ $h2 }}</h2>
<p>{{ $message }}</p>
<p>{{HTML::link('users', 'Continue')}}</p>
@endsection
