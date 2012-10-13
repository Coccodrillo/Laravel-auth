<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Tasker @yield('title')</title>
	<meta name="viewport" content="width=device-width">
	{{ HTML::style('laravel/css/style.css') }}
</head>
<body>
	<div class="wrapper">
		<header>
			<h1>@yield('title')</h1>
		</header>
		<div role="main" class="main">
			<div class="home">
				@yield('content')
			</div>
			@if (Auth::check())
				<p>Logged into Tasker with {{Auth::user()->email}}</p>
			@endif
		</div>
		<ul class="out-links">
			@if (!Auth::check() && URI::current()!='users/login')
				<li>{{ HTML::link('users/login', __('users.login')) }}</li>
			@endif
			@if (!Auth::check())
				<li>{{ HTML::link('users/register', __('users.register')) }}</li>
			@endif
			@if (!Auth::check() && URI::current()!='users/forgot')
				<li>{{HTML::link("users/forgot", __('users.forgot'))}}</li>
			@endif
			@if (Auth::check() && URI::current()!='users/change')
				<li>{{HTML::link("users/change", __('users.change'))}}</li>
			@endif

			@if (Auth::check() && URI::current()!='users/login')
				<li>{{HTML::link("users/logout", __('users.logout'))}}</li>
			@endif
		</ul>
	</div>
</body>
</html>
