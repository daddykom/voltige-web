/**
 * http://usejsdoc.org/
 */

angular.module('VoltigeApp')
.factory('controlSpinnerInterceptor', ['$q', function($q) {
    return {
      // optional method
      'request': function(config) {
    	  angular.element('#loadergif').show();
        return config;
      },

      // optional method
     'requestError': function(rejection) {
   	  	angular.element('#loadergif').hide();
        return $q.reject(rejection);
      },

      'response': function(response) {
     	  	angular.element('#loadergif').hide();
        return response;
      },

      // optional method
     'responseError': function(rejection) {
    	  	angular.element('#loadergif').hide();
        return $q.reject(rejection);
      }
    };
  }])


angular.module('VoltigeApp')
.factory('authInterceptor', ['jwtHelper', '$q', '$localStorage', '$location',
	function (jwtHelper, $q, $localStorage, $location) {
	
	return {
		// Add authorization token to headers
		request: function (config) {
			if( !/^\/*admin/i.test(config.url) ) return config
			config.headers = config.headers || {}
			if ($localStorage.userAuth && $localStorage.userAuth.token ) {
				config.headers.Authorization = 'Bearer ' + $localStorage.userAuth.token
			}
			return config
		},
		// Intercept 401s and redirect you to login
		responseError: function(response) {
			var loginPath = '/login'
			if(response.status === 401) {
				// remove any stale tokens
				console.log('error 401')
				delete $localStorage.userAuth
				var oldPath = $location.path();
				if( $location.path() != loginPath ) $location.path(loginPath).search({oldPath:oldPath})
			}
			return $q.reject(response)
		}
	}
}])
.factory('errorInterceptor', ['$q', '$localStorage', '$location',
                             function ($q, $localStorage, $location) {
	
	return {
		// Remove error Array
		request: function (config) {
			if( config.method != "POST" || !('_errors' in config.data) ) return config
			delete config.data['_errors']
			return config
		}
	}
}])

