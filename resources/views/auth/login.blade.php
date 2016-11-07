@extends('layouts.app')

@section('content')

<div class="row">

	<div class="col-md-4"><!--//--></div>

	<div class="col-md-4 col-xs-12">

		<div class="welcome" ng-controller="AuthCtrl">

			<div class="panel panel-primary">

				<div class="panel-heading text-center">
					<h1 class="panel-title">Welcome to <strong>Raudibard's Blog</strong>!</h1>
				</div>

				<div class="panel-body">

					<div class="form-wrapper">

						@include('errors.list')

						<form name="signInForm" ng-submit="signIn(signInForm)" novalidate>

							<div class="form-group">
								{!! Form::text('email', old('email'), [
									'class'		 => 'form-control',
									'ng-model'	  => 'data.email',
									'ng-pattern'	=> '/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/',
									'placeholder'   => 'Email',
									'required'
								]) !!}
								<div class="error" ng-messages="signInForm.email.$error" ng-if="signInForm.$submitted" ng-cloak>
									<div ng-message="required">Please enter your email!</div>
									<div ng-message="pattern">Please enter a valid email address!</div>
								</div>
							</div>

							<div class="form-group">
								{!! Form::password('password', [
									'class'		 => 'form-control',
									'ng-model'	  => 'data.password',
									'ng-minlength'  => '3',
									'placeholder'   => 'Password',
									'required'
								]) !!}
								<div class="error" ng-messages="signInForm.password.$error" ng-if="signInForm.$submitted" ng-cloak>
									<div ng-message="required">Please enter your password!</div>
									<div ng-message="minlength">Your password is too short!</div>
								</div>
							</div>

							<div class="form-group text-center">
								{!! Form::submit('Sign In', ['class' => 'btn btn-success form-control']) !!}
							</div>

						{!! Form::close() !!}

					</div>

					<hr />

					<h4 class="text-center">Don't have an account yet?</h4>

					<div class="text-center">
						<a href="{{ url('/register') }}" class="btn btn-link">Create New Account</a>
					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="col-md-4"><!--//--></div>

</div>

@endsection