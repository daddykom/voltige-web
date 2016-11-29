

(function () {
    'use strict';

    angular.module('VoltigeApp').controller('UploadCtrl', ['$scope', 'Upload', '$timeout', '$rootScope', '$location', function ($scope, Upload, $timeout, $rootScope, $location) {
	    $scope.uploadFiles = function(file, errFiles) {
	        $scope.f = file;
	        $scope.errFile = errFiles && errFiles[0];
	        if (file) {
	            file.upload = Upload.upload({
	                url: 'upload',
	                data: {file: file}
	            });
	
	            file.upload.then(function (response) {
	                $rootScope.Show = response.data
	                $location.path('/admin/longesub_all')
	                console.log(response.data)
	            }, function (response) {
	                if (response.status > 0)
	                    $scope.errorMsg = response.status + ': ' + response.data;
	                
	                console.log('Success!')
	            }, function (evt) {
	                file.progress = Math.min(100, parseInt(100.0 * 
	                                         evt.loaded / evt.total));
	            });
	        }   
	    }
	}])
	
    
})();
