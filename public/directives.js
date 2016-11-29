'use strict';

angular.module('VoltigeApp')
    .filter('nl2br', function(){
    	return function (text){ 
    		return text.replace(/\n/g, '<br/>');
    	}
	})

angular.module('VoltigeApp')
	.filter('stripMore', function(){
		return function (text){ 
			var ret = text.replace(/<!--more-->.*$/g, '')
			return ret
		}
	})
angular.module('VoltigeApp')
	.directive('inputText', function($compile) {
	    return {
	    	restrict: 'EA',
	    	require: 'ngModel',
	    	scope: {
	    		model: '=ngModel',
	    		parentModel: '=',
	    	},
	    	templateUrl: '/template/directive-textinput',
	        link: function(scope, iElement, iAttrs, ngModelCtrl ) {
	        	scope.label = iAttrs.label
	        	scope.type = iAttrs.type
	        	var aModel = iAttrs.ngModel.split('.',2)
	        	scope.modelName = aModel[0]
	        	scope.fieldName = aModel[1]
	        	scope.ngModelCtrl = ngModelCtrl
	        	
	        	scope.$watch('model', function() {
	        		if( scope.type != 'number' ) ngModelCtrl.$setViewValue( scope.model )
	        		else ngModelCtrl.$setViewValue( Number(scope.model))
	            })
	        }

	    }	
	})
	
	angular.module('VoltigeApp')
	.filter('isEmpty', function() {
		return function(input, replaceText) {
			if(input) return input;
			return replaceText;
		}	
	})

angular.module('VoltigeApp')


    // Angular File Upload module does not include this directive
    // Only for example


    /**
    * The ng-thumb directive
    * @author: nerv
    * @version: 0.1.2, 2014-01-09
    */
    .directive('ngThumb', ['$window', function($window) {
        var helper = {
            support: !!($window.FileReader && $window.CanvasRenderingContext2D),
            isFile: function(item) {
                return angular.isObject(item) && item instanceof $window.File;
            },
            isImage: function(file) {
                var type =  '|' + file.type.slice(file.type.lastIndexOf('/') + 1) + '|';
                return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
            }
        };

        return {
            restrict: 'A',
            template: '<canvas/>',
            link: function(scope, element, attributes) {
                if (!helper.support) return;

                var params = scope.$eval(attributes.ngThumb);

                if (!helper.isFile(params.file)) return;
                if (!helper.isImage(params.file)) return;

                var canvas = element.find('canvas');
                var reader = new FileReader();

                reader.onload = onLoadFile;
                reader.readAsDataURL(params.file);

                function onLoadFile(event) {
                    var img = new Image();
                    img.onload = onLoadImage;
                    img.src = event.target.result;
                }

                function onLoadImage() {
                    var width = params.width || this.width / this.height * params.height;
                    var height = params.height || this.height / this.width * params.width;
                    canvas.attr({ width: width, height: height });
                    canvas[0].getContext('2d').drawImage(this, 0, 0, width, height);
                }
            }
        };
    }])
    
