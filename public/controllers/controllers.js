/**
 * http://usejsdoc.org/
 */
    'use strict';

	angular.module('VoltigeApp').controller('showHtml', ['$scope', '$http', function ($scope, $http) {
		
	}])
	
	angular.module('VoltigeApp').controller('adminSubscribers', ['$scope', '$http', '$routeParams', '$route', function ($scope, $http, $routeParams, $route) {
		$scope.subscribers = [{name:'hans'}]
		$http.get('/admin/check') // check admin rights
		
		$http.get('/admin/admin_subscribers/' + $routeParams.course_id).
		then(function(res){
			$scope.subscribers = res.data.subscribers
			$scope.Course = res.data.Course
		})	
		$scope.deleteSubscriber = function(id){
			if( !confirm('Soll dieser Teilnehmer wirklich gelöscht werden?') ) return;
			
			$http['delete']('/admin/subscriber/' + id )
			.then(function(res){
				$route.reload();
			})
		}
		
	}])
	
	
	angular.module('VoltigeApp').controller('showCourse', ['$scope', '$http', '$routeParams','GeoCoder', '$location', function ($scope, $http, $routeParams, GeoCoder, $location) {
		$scope.mapLocation = '[47.5199434,7.552736399999958]';
		$scope.myInterval = 5000;
		$scope.noWrapSlides = false;
		$scope.active = 0;
		var slides = $scope.slides = [];
		var currIndex = 0;
		
		$scope.Subscriber = { country: 'CH'}
		
		$scope.saveSubscriber = function(){
			$scope.Subscriber.course_id = $scope.Course.id
			$http.post('subscriber',$scope.Subscriber ).
			then(function(res){
				$scope.Subscriber = res.data.Subscriber
				$location.path('/registered')
			})
		}
		$http.get('/course/' + $routeParams.course_id).
		then(function(res){
			$scope.Course = res.data
			if( $scope.Course.position_lat != '') setPosition($scope.Course.position_lat, $scope.Course.position_lng)
			var url = '/fotos/' + $scope.Course.id;
			$http.get(url)
				.then(function(res){
					res.data.forEach(function(rec){ 
						rec.src = 'thumbs/' + rec.fname
						rec.postload = new Image()
						rec.postload.onload = function(){rec.src = rec.fname}
						rec.postload.src = '/gallery/' + rec.fname
						$scope.slides.push(rec) })
				})
		})
		var setPosition = function( lat, lng  ){
			$scope.mapLocation = '[' + lat + ',' + lng + ']'
			GeoCoder.geocode({location: {lat:lat, lng:lng}}).then(function(result,status) {
				if( status ) $scope.mapAddress = $scope.mapLocation
				else{
					$scope.mapAddress = result[0].formatted_address
				}
			  })
		}
	}])
	angular.module('VoltigeApp').controller('adminCourse', ['$scope', '$http', '$routeParams', 'FileUploader','GeoCoder','$location', '$timeout', 'flash', 'uibDateParser', function ($scope, $http, $routeParams, FileUploader,GeoCoder,$location, $timeout, flash, uibDateParser) {
		$scope.mapLocation = '[47.5199434,7.552736399999958]';
		$scope.mapSearch = ''
		$scope.courseFotos = []
		
		$scope.menuGallery = [
		                      ['Einfügen', function ($itemScope) {
		                    	  $scope.courseFotos.push({fname:$itemScope.item})
		                      }],
		                      null, // Dividier
		                      ['Löschen', function ($itemScope) {
		                    	  deleteGalleryItem( $itemScope.item)
		                      }]
		                  ]
		$scope.menuCourseFotos = [
		                      ['Löschen', function ($itemScope) {
		                    	  deleteCourseFoto( $itemScope.item)
		                      }]
		                      ]
		
		$http.get('/admin/check') // check admin rights

		function deleteCourseFoto(item){
			angular.element('img[src="/thumb/gallery/'+item.fname+'"]').addClass('highlight')
			var i = $scope.courseFotos.indexOf(item)
			$scope.courseFotos.splice(i,1)
			angular.element('img[src="/thumb/gallery/'+item.fname+'"]').removeClass('highlight')
		}
		$scope.droppedFoto = function(data,event){
				$scope.courseFotos.push({fname: data})
				var i = $scope.imgGallery.indexOf(data)
				$scope.imgGallery.splice(i,1)
			}
		
		function deleteGalleryItem(fname){
			angular.element('img[src="/thumb/gallery/'+fname+'"]').addClass('highlight')
			if( confirm('Soll dieses Bild aus der Gallerie gelöscht werden?') ){
				$http.get('/del_gallery/' + fname)
				.then( function(res){
					loadGallery()
				},
				 function(){
					$timeout( function(){alert('Dieses Foto wird noch verwendet')})
				})
			}
			angular.element('img[src="/thumb/gallery/'+fname+'"]').removeClass('highlight')
		}
		function loadGallery(){
			$http.get('/gallery').
				then(function(res){
					$scope.imgGallery = res.data
				}, function(){
					$scope.imgGallery = []
				})
		}
			
		loadGallery()
		if( $routeParams.course_id > 0 ){
			$http.get('/course/' + $routeParams.course_id).
				then(function(res){
					$scope.Course = res.data
					$scope.Course.from_dt = new Date(res.data.from_dt)
					if( $scope.Course.position_lat != '') setPosition($scope.Course.position_lat, $scope.Course.position_lng, true)
					loadImages($scope.Course.id)
					if( $routeParams.copy ) $scope.Course.id = 0
				})
		}
		
			
		var uploader = $scope.uploader = new FileUploader({
            url: '/upload',
            autoUpload: true
        });
		uploader.onSuccessItem = function(e){ loadGallery() }
		uploader.onWhenAddingFileFailed = function(e){alert('Es sind nur Bilder erlaubt!')}
		uploader.filters.push({
            name: 'imageFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
                return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
            }
        });
		
		$scope.updateCourse = function(){
			var that = $scope
			$http.post('/admin/admin_course',$scope.Course ).
				then(function(res){
					that.Course = res.data.Course
					$http.post('/admin/admin_course_foto/', {course_id:$scope.Course.id,fotos:$scope.courseFotos}).
						then(function(res){
							$scope.courseFotos = res.data
							flash.set('success','Die Daten wurden gespeichert')
							$location.path('/admin')
						})
				})
		}
		$scope.searchLocation = function(){
			if( !$scope.Course ) return
			GeoCoder.geocode({address: $scope.mapSearch}).then(function(result,status) {
				if( status ) return
				var latlng = result[0].geometry.location
				setPosition(latlng.lat(), latlng.lng())
			    $scope.mapAddress = result[0].formatted_address
			  })
			  
		}
		var setPosition = function( lat, lng, adr  ){
			$scope.mapLocation = '[' + lat + ',' + lng + ']'
			$scope.Course.position_lat = lat
			$scope.Course.position_lng = lng
			GeoCoder.geocode({location: {lat:lat, lng:lng}}).then(function(result,status) {
				if( status ) $scope.mapAddress = $scope.mapLocation
				else{
					$scope.mapAddress = result[0].formatted_address
					if( adr ) $scope.mapSearch = result[0].formatted_address
				}
			  })
		}
		$scope.endPosition = function(e){
			var latlng = this.getPosition()
			setPosition(latlng.lat(),latlng.lng(), true)
		}
		var loadImages = function(course_id){
			var url = '/fotos/' + course_id;
			$http.get(url)
				.then(function(res){
					$scope.courseFotos = res.data
					if( $routeParams.copy ){
						$scope.courseFotos.forEach( function(Foto){
							Foto.id = 0
							Foto.course_id = 0
						})
					}
				})
			
		}
	}])
