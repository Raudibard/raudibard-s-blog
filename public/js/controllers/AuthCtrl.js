
var raudibardApp = angular.module('RaudibardApp');
	raudibardApp.controller('AuthCtrl', function($scope, $http, $window, CSRF_TOKEN){

		$scope.signIn = function(signInForm) {
			if (signInForm.$valid) {
				$scope.data._token = CSRF_TOKEN;
				$http.post(baseUrl + 'login', $scope.data).then(function(response){
					$window.location.href = baseUrl;
				}, function(response){
					var errors = angular.fromJson(response.data);
					$scope.showResponse('Something went wrong:', true, false, errors);
				});
			}
		}

		$scope.signUp = function(signUpForm) {
			if (signUpForm.$valid) {
				if ($scope.data.password !== $scope.data.password_confirmation) {
					signUpForm.passwordConfirm.$setValidity('required', false);
				} else {
					$scope.data._token = CSRF_TOKEN;
					$http.post(baseUrl + 'register', $scope.data).then(function(){
						$scope.showResponse('Greetings, <strong>' + $scope.data.name + '</strong>! You can Sign In.');
					}, function(response){
						var errors = angular.fromJson(response.data);
						$scope.showResponse('Something went wrong:', true, false, errors);
					});
				}
			}
		}

	})