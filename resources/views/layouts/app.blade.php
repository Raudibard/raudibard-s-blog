<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Raudibard\'s Blog') }}</title>

	<link href="{{ URL::asset('/favicon.ico') }}" rel="shortcut icon" type="image/x-icon">

	<!-- Styles -->
	<link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('/css/colorbox.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('/css/app.css') }}" rel="stylesheet">
</head>
<body ng-app="RaudibardApp">

	<div class="container-fluid"><div class="container-fluid-inner">

		@yield('content')
		
	</div></div>

	<!-- Scripts -->
	<script src="{{ URL::asset('/js/jquery-3.1.1.min.js') }}"></script>
	<script src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>
	<script src="{{ URL::asset('/js/colorbox.min.js') }}"></script>
	<script src="{{ URL::asset('/js/angular.min.js') }}"></script>
	<script src="{{ URL::asset('/js/angular-messages.min.js') }}"></script>
	<script src="{{ URL::asset('/js/angular-sanitize.min.js') }}"></script>
	<script src="{{ URL::asset('/js/angular-file-upload.min.js') }}"></script>
	<script>
		baseUrl = '{{ URL::asset('/') }}';
		raudibardApp = angular.module('RaudibardApp', ['ngMessages', 'ngSanitize', 'angularFileUpload']);
		raudibardApp.constant('CSRF_TOKEN', '{!! csrf_token() !!}');
	</script>
	<script src="{{ URL::asset('/js/app.js') }}"></script>
	<script src="{{ URL::asset('/js/angular.filters.js') }}"></script>
	<script src="{{ URL::asset('/js/angular.directives.js') }}"></script>
	<script src="{{ URL::asset('/js/controllers/AuthCtrl.js') }}"></script>
	<script src="{{ URL::asset('/js/controllers/ArticlesCtrl.js') }}"></script>
	<script src="{{ URL::asset('/js/controllers/CommentsCtrl.js') }}"></script>
</body>
</html>
