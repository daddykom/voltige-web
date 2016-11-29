/**
 * http://usejsdoc.org/
 */
    'use strict';

	
	angular.module('VoltigeApp').controller('searchSubscriptions', ['$scope', '$http', '$location', '$rootScope', '$routeParams', function ($scope, $http, $location, $rootScope, $routeParams) {
		$scope.subscriptions = []
		$scope.backlink = $routeParams.backlink
		if( $rootScope.rootSearch.subscription ) 
			$rootScope.rootSearch.subscription['subscriptions.show_id'] = $rootScope.Show.id
		else $rootScope.rootSearch.subscription = { 'subscriptions.show_id': $rootScope.Show.id }
		
		$scope.searchSubscriptions = function(){
			$http.post('/admin/subscriptions', $rootScope.rootSearch.subscription )
			.then(function(res){
				$scope.subscriptions = res.data
			}
			,function(error){console.log(error);alert('Es ist ein Verarbeitungsfehler aufgetreten: ' + error.status )})
		}
	}])
	
	angular.module('VoltigeApp').controller('registrationOffice', ['$scope', '$http', '$rootScope', '$routeParams', '$uibModal', function ($scope, $http, $rootScope, $routeParams, $uibModal) {
		$scope.saveOrderEnable = false
		$scope.sortableOptions = {
				update: function(e,ui){
					$scope.saveOrderEnable = true
				}
		}
		
		$scope.saveReorder = function(){
			$scope.saveOrderEnable = false
			var update=[]
			for( var i = 0; i < $scope.Subscription.subscription_beings.G.length; i++){
				if( $scope.Subscription.subscription_beings.G[i].pos != i + 1 ){
					$scope.Subscription.subscription_beings.G[i].pos = i+1
					update.push($scope.Subscription.subscription_beings.G[i])
				}
			}
			$http.post('/admin/subscription_beings',update)
			.then(function(res){
				if( res.data) $scope.Subscription = res.data
			}, 
			function(error){alert('Es ist ein Fehler aufgetreten')})
		}
		
		$http.get('/admin/subscription/' + $routeParams.subscription_id)
		.then(function(res){
			if( res.data){
				$scope.Subscription = res.data
			}
		})
		
		$scope.openLonge = function(){
			var changeLongeModal = $uibModal.open({
				templateUrl: "changeLonge.html",
				scope: $scope,
				controller: function() {
					$scope.Longe = angular.copy($scope.Subscription.subscription_beings.L[0])
					$scope.replace = angular.copy($scope.Subscription.subscription_beings.LA)
					$scope.change = function(newLonge){
						var i = $scope.replace.indexOf(newLonge)
						$scope.replace.splice(i,1)					
						$scope.replace.push($scope.Longe)
						$scope.Longe = newLonge
					}
					$scope.saveLonge = function(){
						var postChange = []
						if( $scope.Longe.role != 'L' ){
							$scope.Longe.role = 'L'
							postChange.push($scope.Longe)
						}
						for( var i = 0; i < $scope.replace.length; i++ ){
							if( $scope.replace[i].role != 'LA' ){
								$scope.replace[i].role = 'LA'
								postChange.push($scope.replace[i])
							}
						}
						if( postChange.length ){
							$http.post('/admin/subscription_beings',postChange)
							.then(function(res){
								if( res.data) $scope.Subscription = res.data
							}, 
							function(error){alert('Es ist ein Fehler aufgetreten')})
						}
						changeLongeModal.close()
					}
				}
			})
		}
		$scope.onDragComplete=function(index,data,evt){
	       console.log("drag success, data:", index, data.Member.name);
	    }
	    $scope.onDropComplete=function(index,data,evt){
	    	var otherObj = $scope.Subscription.subscription_beings.G[index];
	    	var otherIndex = $scope.Subscription.subscription_beings.G.indexOf(data);
	        console.log("drop success, data:", index, data.Member.name);
	    }
	}])
	
	angular.module('VoltigeApp').controller('LongeSubAllCtrl', ['$scope', '$http', '$rootScope', function ($scope, $http, $rootScope) {
		$scope.NewMember = {}
		$http.get('/admin/longesub_all/' + $rootScope.Show.id )
		.then(function(res){
			$scope.subscriptions = res.data
		})
		$scope.getMembers = function(Subscription){
			$http.get('/admin/longsub_get_approx/' + Subscription.description )
			.then(function(req){
				$scope.approx_members = req.data
				$scope.longesubs = angular.copy(Subscription.longesubs)
				if( Subscription.longesubs ){
					for( var i = 0; i < $scope.longesubs.length; i++ ){
						for( var j = 0; j < $scope.approx_members.length; j++ ){
							if( $scope.longesubs[i].name == $scope.approx_members[j].name &&
								$scope.longesubs[i].prename == $scope.approx_members[j].prename &&
								$scope.longesubs[i].fn_no == $scope.approx_members[j].fn_no ){
								$scope.approx_members.splice(j,1)
							}
						}
					}
				}
			})
		}
		$scope.unselectMember = function(Member){
			if( !$scope.approx_members ) $scope.approx_members = []
			var i = $scope.longesubs.indexOf(Member)
			$scope.longesubs.splice(i,1)
			$scope.approx_members.push(Member)
		}
		$scope.selectMember = function(Member){
			if( !$scope.longesubs ) $scope.longesubs = []
			var i = $scope.approx_members.indexOf(Member)
			$scope.approx_members.splice(i,1)
			$scope.longesubs.push(Member)
		}
		$scope.insertMember = function(){
			$http.post('/admin/member_check', $scope.NewMember ).
			then(function(res){
				if( res.data ){
					if( res.data._errors.length == 0 ){
						if( !$scope.longesubs ) $scope.longesubs = []
						$scope.longesubs.push(res.data)
						$scope.NewMember.name = ''
						$scope.NewMember.prename = ''
					}
					else $scope.NewMember = res.data
				}
			}
			,function(error){console.log(error);alert('Es ist ein Verarbeitungsfehler aufgetreten: ' + error.status )})
		}
		$scope.saveSubscription = function(Subscription){
			Subscription.longesubs = $scope.longesubs
			$http.post('/admin/subscription_member', Subscription)
			.then(function(res){
				if( res.data ) angular.copy(res.data,Subscription)
			}
			,function(error){console.log(error);alert('Es ist ein Verarbeitungsfehler aufgetreten: ' + error.status )})
		}
	}])
   