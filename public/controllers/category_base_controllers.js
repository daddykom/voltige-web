/**
 * http://usejsdoc.org/
 */
    'use strict';

	
	angular.module('VoltigeApp').controller('searchCategoryBases', ['$scope', '$http', '$location', '$rootScope', function ($scope, $http, $location, $rootScope) {
		$scope.category_bases = []
		if( !('category_base' in $rootScope.rootSearch) ) $rootScope.rootSearch.category_base = {}
		$scope.admin_category_base = ($location.path() == '/admin/category_bases')
		
		$http.get('/admin/category_bases')
		.then(function(res){
			$scope.category_bases = res.data
		})
		$scope.searchCategoryBases= function(){
			$http.post('/admin/category_bases', $rootScope.rootSearch.category_base )
			.then(function(res){
				$scope.category_bases = res.data
			})
		}
	}])
	
	angular.module('VoltigeApp').controller('editCategoryBase', ['$scope', '$http', '$routeParams', '$location', 'flash', function ($scope, $http,$routeParams,$location,flash) {
		$scope.CategoryBase = { id: $routeParams.category_base_id }
		
		if( $routeParams.category_base_id > 0 ) 
			$http.get('/admin/category_base/' + $routeParams.category_base_id)
			.then(function(req){
				$scope.CategoryBase = req.data
			})
			
		$scope.updateCategoryBase = function(){
			$http.post('/admin/category_base', $scope.CategoryBase ).
				then(function(res){
					if( res.data ){
						$scope.CategoryBase = res.data
						if( res.data._errors.length == 0 ) $location.path('/admin/category_bases')
					}
				}
				,function(error){console.log(error);alert('Es ist ein Verarbeitungsfehler aufgetreten: ' + error.status )})
		}
		$scope.deleteCategoryBase = function(){
			var short_description = $scope.CategoryBase.short_description
			if( !confirm('Soll diese Basiskategorie wirklich gelöscht werden?')) return
			$http.delete('/admin/category_base/' + $scope.CategoryBase.id ).
			then(function(res){
				flash('Die Basiskategorie '+short_description+' wurden gelöscht!')
				$location.path('/admin/category_bases')
			}
			,function(error){console.log(error);alert('Es ist ein Verarbeitungsfehler aufgetreten: ' + error.status )})
		}
	}])
	
