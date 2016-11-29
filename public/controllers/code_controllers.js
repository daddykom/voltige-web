/**
 * http://usejsdoc.org/
 */
    'use strict';

	
	angular.module('VoltigeApp').controller('searchCodes', ['$scope', '$http', '$location', '$rootScope', function ($scope, $http, $location, $rootScope) {
		$scope.codes = []
		
		$scope.searchCodes = function(){
			$http.post('/admin/codes', $rootScope.rootSearch.code )
			.then(function(res){
				$scope.codes = res.data
			})
		}
		
		if( !('code' in $rootScope.rootSearch) ) $rootScope.rootSearch.code = {}
		else $scope.searchCodes();
	}])
	
	angular.module('VoltigeApp').controller('editCode', ['$scope', '$http', '$routeParams', '$location', 'flash', '$rootScope', function ($scope, $http,$routeParams,$location,flash,$rootScope) {
		$scope.Code = { id: 0 }
		if( $routeParams.code_id > 0 ) 
			$http.get('/admin/code/' + $routeParams.code_id)
			.then(function(req){
				$scope.Code = req.data
			})
			
		$scope.updateCode = function(){
			$http.post('/admin/code', $scope.Code ).
				then(function(res){
					if( res.data ){
						$scope.Code = res.data
						if( res.data._errors.length == 0 ) $location.path('/admin/codes')
					}
				}
				,function(error){console.log(error);alert('Es ist ein Verarbeitungsfehler aufgetreten: ' + error.status )})
		}
		
		$scope.deleteCode = function(){
			var codename = $scope.Code.codename
			if( !confirm('Soll dieser Code wirklich gelöscht werden?')) return
			$http.delete('/admin/code/' + $scope.Code.id ).
			then(function(res){
				console.log('Die Daten wurden gelöscht!')
				flash('Der Benutzer '+codename+' wurden gelöscht!')
				$location.path('/admin/codes')
			}
			,function(error){console.log(error);alert('Es ist ein Verarbeitungsfehler aufgetreten: ' + error.status )})
		}
	}])
	
	angular.module('VoltigeApp').controller('searchCodeCmds', ['$scope', '$http', '$routeParams', '$location', '$rootScope', function ($scope, $http,$routeParams, $location, $rootScope) {
		$scope.code_cmds = []
		$http.get('/admin/code/' + $routeParams.code_id)
		.then(function(req){
			$scope.Code = req.data
			$http.post('/admin/code_cmds', { code_id: $routeParams.code_id } )
			.then(function(res){
				$scope.code_cmds = res.data
			})
		})		
	}])
	
	angular.module('VoltigeApp').controller('searchCodeTxt', ['$scope', '$http', '$routeParams', '$location', '$rootScope', 'flash', function ($scope, $http,$routeParams, $location, $rootScope, flash) {
		$scope.code_cmds = []
		$http.get('/admin/code/' + $routeParams.code_id)
		.then(function(req){
			$scope.Code = req.data
			$http.post('/admin/code_texts', { code_id: $routeParams.code_id } )
			.then(function(res){
				$scope.code_texts = res.data
			})
		})	
		$scope.changeStandard = function(element){
			console.log(element.value)
			$scope.Code.standard_code_cms_text_id = element.value
			$http.post('/admin/code', $scope.Code )
			.then(function(res){
				$scope.Code = res.data
				flash('Die Voreinstellung wurde neu gesetzt.')
			})
		}
	}])
	
	angular.module('VoltigeApp').controller('editCodeCmd', ['$scope', '$http', '$routeParams', '$location', 'flash', '$rootScope', function ($scope, $http,$routeParams,$location,flash,$rootScope) {
		var loadCode = function(code_id){
			$http.get('/admin/code/' + code_id)
			.then(function(req){
				$scope.Code = req.data
			})
			
		}
		if( $routeParams.code_id ){
			$scope.CodeCmd = { id: 0, code_id: $routeParams.code_id }
			$scope.title = 'einfügen'
			loadCode( $routeParams.code_id )
		}
		else{ 
			$scope.title = 'bearbeiten'
			$http.get('/admin/code_cmd/' + $routeParams.code_cmd_id)
			.then(function(req){
				$scope.CodeCmd = req.data
				loadCode($scope.CodeCmd.code_id)
			})
		}
			
		$scope.updateCodeCmd = function(){
			$http.post('/admin/code_cmd', $scope.CodeCmd ).
			then(function(res){
				if( res.data ){
					$scope.CodeCmd = res.data
					if( res.data._errors.length == 0 ) $location.path('/admin/code_cmds/' + $scope.Code.id)
				}
			}
			,function(error){console.log(error);alert('Es ist ein Verarbeitungsfehler aufgetreten: ' + error.status )})
		}
		
		$scope.deleteCodeCmd = function(){
			var cmd = $scope.CodeCmd.cmd
			if( !confirm('Soll dieser Command wirklich gelöscht werden?')) return
			$http.delete('/admin/code_cmd/' + $scope.CodeCmd.id ).
			then(function(res){
				flash('Der Command '+cmd+' wurden gelöscht!')
				$location.path('/admin/code_cmds/' + $scope.CodeCmd.code_id)
			}
			,function(error){console.log(error);alert('Es ist ein Verarbeitungsfehler aufgetreten: ' + error.status )})
		}
	}])
	
	angular.module('VoltigeApp').controller('editCodeTxt', ['$scope', '$http', '$routeParams', '$location', 'flash', '$rootScope', function ($scope, $http,$routeParams,$location,flash,$rootScope) {
		$scope.code_cmds = []
		$scope.CodeCmdText = { id: 0, code_cmd_id: 0 }
		$http.get('/admin/code/' + $routeParams.code_id, { cache: true } )
		.then(function(req){
			$scope.Code = req.data
		})
		$http.get('/code_cmds/' + $routeParams.code_id, { cache: true } )
		.then(function(req){
			$scope.code_cmds = req.data
		})
		
		if( parseInt($routeParams.code_cmd_text_id) ){
			$http.get('/admin/code_cmd_text/' + $routeParams.code_cmd_text_id)
			.then(function(req){
				$scope.CodeCmdText = req.data
			})
		}
		$scope.updateCodeText = function(){
			$http.post('/admin/code_cmd_text', $scope.CodeCmdText ).
			then(function(res){
				if( res.data ){
					$scope.CodeCmdText = res.data
					if( res.data._errors.length == 0 ){
						flash('Die Daten wurden gespeichert')
						$location.path('/admin/codetextsearch/' + $scope.Code.id)
					}
				}
			}
			,function(error){console.log(error);alert('Es ist ein Verarbeitungsfehler aufgetreten: ' + error.status )})
		}
		
		$scope.deleteCodeCmdText = function(){
			var text = $scope.CodeCmdText.text
			if( !confirm('Soll dieser Text wirklich gelöscht werden?')) return
			$scope.CodeCmdText.is_deleted = true
			$http.post('/admin/code_cmd_text', $scope.CodeCmdText ).
			then(function(res){
				flash('Der Text '+text+' wurden gelöscht!')
				$location.path('/admin/codetextsearch/' + $scope.CodeCmd.code_id)
			}
			,function(error){console.log(error);alert('Es ist ein Verarbeitungsfehler aufgetreten: ' + error.status )})
		}
	}])
	
