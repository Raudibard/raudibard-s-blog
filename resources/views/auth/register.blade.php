@extends('layouts.app')

@section('content')

<div class="row">
	
	<div class="col-md-4"><!--//--></div>

	<div class="col-md-4 col-xs-12">

		<div class="welcome" ng-controller="AuthCtrl">

			<div class="panel panel-primary">

				<div class="panel-heading text-center">
					<h1 class="panel-title"><strong>Raudibard's Blog</strong>: Create New Account</h1>
				</div>

				<div class="panel-body">

					<div class="form-wrapper">

						@include('errors.list')

						<form name="signUpForm" ng-submit="signUp(signUpForm)" novalidate>

							<div class="form-group">
								{!! Form::text('name', null, [
									'class'		 => 'form-control',
									'ng-model'	  => 'data.name',
									'placeholder'   => 'Your Name',
									'required'
								]) !!}
								<div class="error" ng-messages="signUpForm.name.$error" ng-if="signUpForm.$submitted" ng-cloak>
									<div ng-message="required">Please enter your name!</div>
								</div>
							</div>

							<div class="form-group">
								{!! Form::text('email', null, [
									'class'		 => 'form-control',
									'ng-model'	  => 'data.email',
									'ng-pattern'	=> '/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/',
									'placeholder'   => 'Email',
									'required'
								]) !!}
								<div class="error" ng-messages="signUpForm.email.$error" ng-if="signUpForm.$submitted" ng-cloak>
									<div ng-message="required">Please enter your email!</div>
									<div ng-message="pattern">Please enter a valid email address!</div>
								</div>
							</div>

							<div class="form-group">
								{!! Form::password('password', [
									'class'		 => 'form-control',
									'ng-model'	  => 'data.password',
									'ng-minlength'  => '6',
									'placeholder'   => 'Password',
									'required'
								]) !!}
								<div class="error" ng-messages="signUpForm.password.$error" ng-if="signUpForm.$submitted" ng-cloak>
									<div ng-message="required">Please enter your password!</div>
									<div ng-message="minlength">Your password is too short!</div>
								</div>
							</div>

							<div class="form-group">
								{!! Form::password('passwordConfirm', [
									'class'		 => 'form-control',
									'ng-model'	  => 'data.password_confirmation',
									'placeholder'   => 'Password Confirm',
									'required'
								]) !!}
								<div class="error" ng-messages="signUpForm.passwordConfirm.$error" ng-if="signUpForm.$submitted" ng-cloak>
									<div ng-message="required">Password and Password Confirm must match!</div>
								</div>
							</div>

							<div class="form-group text-center">
								{!! Form::submit('Create New Account', [
									'class' => 'btn btn-success form-control'
								]) !!}
							</div>

						{!! Form::close() !!}

					</div>

					<hr />

					<h4 class="text-center">Already registered?</h4>

					<div class="text-center">
						<a href="{{ url('/login') }}" class="btn btn-link">Sign In</a>
					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="col-md-4"><!--//--></div>

</div>

@endsection