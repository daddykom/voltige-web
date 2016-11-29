/**
 * http://usejsdoc.org/
 */
    'use strict';

	
	angular.module('VoltigeApp').controller('searchUsers', ['$scope', '$http', '$location', '$rootScope', function ($scope, $http, $location, $rootScope) {
		$http.get('/codes/user-group',{cache: true})
			.then(function(req){
				$scope.groups = req.data
			})
		$scope.searchUsers = function(){
			$http.post('/admin/users', $rootScope.rootSearch.user )
			.then(function(res){
				$scope.users = res.data
			})
		}
		
		$scope.users = []
		if( !('user' in $rootScope.rootSearch) ) $rootScope.rootSearch.user = {}
		else $scope.searchUsers()
		
	}])
	
	angular.module('VoltigeApp').controller('editUser', ['$scope', '$http', '$routeParams', '$location', 'flash', '$rootScope', function ($scope, $http,$routeParams,$location,flash,$rootScope) {
		$scope.User = { id: parseInt( $routeParams.user_id ) }
		$http.get('/codes/user-group',{cache: true})
		.then(function(req){
			$scope.groups = req.data
		})
		console.log($scope.User)
		
		if( $routeParams.user_id > 0 ) 
			$http.get('/admin/user/' + $routeParams.user_id)
			.then(function(req){
				$scope.User = req.data
			})
			
		$scope.updateUser = function(){
			$http.post('/admin/user', $scope.User ).
				then(function(res){
					if( res.data ){
						$scope.User = res.data
						if( res.data._errors.length == 0 ) $location.path('/admin/users')
					}
				}
				,function(error){console.log(error);alert('Es ist ein Verarbeitungsfehler aufgetreten: ' + error.status )})
		}
		
		$scope.deleteUser = function(){
			var userid = $scope.User.userid
			if( !confirm('Soll dieser Benutzer wirklich gelöscht werden?')) return
			$http.delete('/admin/user/' + $scope.User.id ).
			then(function(res){
				console.log('Die Daten wurden gelöscht!')
				flash('Der Benutzer '+userid+' wurden gelöscht!')
				$location.path('/admin/users')
			}
			,function(error){console.log(error);alert('Es ist ein Verarbeitungsfehler aufgetreten: ' + error.status )})
		}
	}])
	
	
	angular.module('VoltigeApp').controller('loginUser', ['$scope', '$http', '$localStorage', '$routeParams', '$location', function ($scope, $http, $localStorage, $routeParams, $location) {
		$scope.User = {}
		
		$scope.login = function(){
			$http.post('/user_check', $scope.User ).
				then(function(res){
					if( res.data.token ){
						$localStorage.userAuth = res.data
						$location.path($routeParams.oldPath)
					}
					else alert('Die Email oder das Passwort waren falsch.')
				})
		}
	}])
