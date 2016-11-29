/**
 * http://usejsdoc.org/
 */
    'use strict';

	
	angular.module('VoltigeApp').controller('searchShows', ['$scope', '$http', '$location', '$rootScope', function ($scope, $http, $location, $rootScope) {
		$scope.shows = []
		if( !('show' in $rootScope.rootSearch) ) $rootScope.rootSearch.show = {}
		$scope.admin_show = ($location.path() == '/admin/shows')
		
		$http.get('/admin/shows')
		.then(function(res){
			$scope.shows = res.data
		})
		$scope.searchShows= function(){
			$http.post('/admin/shows', $rootScope.rootSearch.show )
			.then(function(res){
				$scope.shows = res.data
			})
		}
		$scope.selectShow = function(id){
			$http.get('/admin/show/' + id )
			.then(function(req){
				$rootScope.Show = req.data
			})
		}
	}])
	
	angular.module('VoltigeApp').controller('editShow', ['$scope', '$http', '$routeParams', '$location', 'flash', function ($scope, $http,$routeParams,$location,flash) {
		$scope.Show = { group: 'admin', id: $routeParams.show_id }
		
		if( $routeParams.show_id > 0 ) 
			$http.get('/admin/show/' + $routeParams.show_id)
			.then(function(req){
				$scope.Show = req.data
			})
			
		$scope.updateShow = function(){
			$http.post('/admin/show', $scope.Show ).
				then(function(res){
					if( res.data ){
						$scope.Show = res.data
						if( res.data._errors.length == 0 ) $location.path('/admin/shows')
					}
				}
				,function(error){console.log(error);alert('Es ist ein Verarbeitungsfehler aufgetreten: ' + error.status )})
		}
		$scope.deleteShow = function(){
			var showno = $scope.Show.showno
			if( !confirm('Soll dieser Turnier wirklich gelöscht werden? Alle damit verbundenen Daten gehen verloren!')) return
			$http.delete('/admin/show/' + $scope.Show.id ).
			then(function(res){
				flash('Der Turnier '+showno+' wurden gelöscht!')
				$location.path('/admin/shows')
			}
			,function(error){console.log(error);alert('Es ist ein Verarbeitungsfehler aufgetreten: ' + error.status )})
		}
	}])
	
