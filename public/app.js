//inject angular file upload directives and services.
//var app = angular.module('VoltigeApp', ['ngFileUpload']);

(function () {
    'use strict';
     
     var app = angular.module('VoltigeApp', [
                     'ui.bootstrap', 
                     'ngAnimate', 
                     'ngRoute', 
                     'ui.sortable', 
                     'ngMessages', 
                     'ngSanitize', 
                     'angular-jwt',
                     'ngStorage',
                     'ngFileUpload',
                     'flash',
                     ])
     
     .config(['$routeProvider', '$httpProvider', 'jwtInterceptorProvider',
              function($routeProvider, $httpProvider, jwtInterceptorProvider) {
    	 
            	jwtInterceptorProvider.tokenGetter = ['$localStorage', function($localStorage) {
                	return $localStorage.userToken
              	}]	

            	$httpProvider.interceptors.push('jwtInterceptor')
            	$httpProvider.interceptors.push('authInterceptor')
            	$httpProvider.interceptors.push('errorInterceptor')
		    	$httpProvider.interceptors.push('controlSpinnerInterceptor');

            	
                $routeProvider.
                when('/', {
              	  title: 'Home',
              	  templateUrl: 'template/main',
              	  controller: 'showHtml'
                }).
                when('/admin/upload', {
                	title: 'Upload',
                	templateUrl: 'template/upload',
                	controller: 'UploadCtrl'
                }).
                when('/admin/longesub_all', {
                	title: 'Ersatzlongenführer aus Kommentar übertragen',
                	templateUrl: 'template/longesub_all',
                	controller: 'LongeSubAllCtrl'
                }).
                  when('/login', {
                	  title: 'Einloggen',
                	  templateUrl: '/template/user_login',
                	  controller: 'loginUser'
                  }).
                  when('/admin/users', {
                	  title: 'Benutzerverwaltung',
                	  templateUrl: 'template/user_search',
                	  controller: 'searchUsers'
                  }).
                  when('/admin/user/:user_id', {
                	  title: 'Benutzer bearbeiten',
                	  templateUrl: 'template/user_edit',
                	  controller: 'editUser'
                  }).
                  when('/admin/subscriptions/:backlink', {
                	  title: 'Teilnehmer suchen',
                	  templateUrl: 'template/subscription_search',
                	  controller: 'searchSubscriptions'
                  }).
                  when('/admin/registration_office/:subscription_id', {
                	  title: 'Meldestelle',
                	  templateUrl: 'template/registration_office',
                	  controller: 'registrationOffice'
                  }).
                  when('/admin/shows', {
                	  title: 'Turner verwalten',
                	  templateUrl: 'template/show_search',
                	  controller: 'searchShows'
                  }).
                  when('/admin/show_select', {
                	  title: 'Turner auswählen',
                	  templateUrl: 'template/show_search',
                	  controller: 'searchShows'
                  }).
                  when('/admin/show/:show_id', {
                	  title: 'Turnier bearbeiten',
                	  templateUrl: 'template/show_edit',
                	  controller: 'editShow'
                  }).
                  when('/admin/codes', {
                	  title: 'Codes verwalten',
                	  templateUrl: 'template/code_search',
                	  controller: 'searchCodes'
                  }).
                  when('/admin/code/:code_id', {
                	  title: 'Code bearbeiten',
                	  templateUrl: 'template/code_edit',
                	  controller: 'editCode'
                  }).
                  when('/admin/code_cmds/:code_id', {
                	  title: 'Commands verwalten',
                	  templateUrl: 'template/code_cmd_search',
                	  controller: 'searchCodeCmds'
                  }).
                  when('/admin/code_cmd/:code_cmd_id', {
                	  title: 'Command bearbeiten',
                	  templateUrl: 'template/code_cmd_edit',
                	  controller: 'editCodeCmd'
                  }).
                  when('/admin/code_cmd/0/:code_id', {
                	  title: 'Command einfügen',
                	  templateUrl: 'template/code_cmd_edit',
                	  controller: 'editCodeCmd'
                  }).
                  when('/admin/codetexts', {
                	  title: 'Codes verwalten',
                	  templateUrl: 'template/codetext_search',
                	  controller: 'searchCodes'
                  }).
                  when('/admin/codetexts/:code_cmd_text_id/:code_id', {
                	  title: 'Codetext bearbeiten',
                	  templateUrl: 'template/codetext_edit',
                	  controller: 'editCodeTxt'
                  }).
                  when('/admin/codetexts/0/:code_id', {
                	  title: 'Codetext einfügen',
                	  templateUrl: 'template/codetext_edit',
                	  controller: 'editCodeTxt'
                  }).
                  when('/admin/codetextsearch/:code_id', {
                	  title: 'Codetext bearbeiten',
                	  templateUrl: 'template/codetext_text_search',
                	  controller: 'searchCodeTxt'
                  }).
                  when('/admin/category_bases', {
                	  title: 'Basis-Kategorien suchen',
                	  templateUrl: 'template/category_base_search',
                	  controller: 'searchCategoryBases'
                  }).
                  when('/admin/category_base/:category_base_id', {
                	  title: 'Basis-Kategorien verwalten',
                	  templateUrl: 'template/category_base_edit',
                	  controller: 'editCategoryBase'
                  }).
                  otherwise({
                	title: 'other',
                    redirectTo: '/'
                  });
                
                
              }])
     
     .run(['$location', '$rootScope', '$http', '$localStorage', function($location, $rootScope, $http, $localStorage) {
    	 $rootScope.page_title = ''
    	 $rootScope.Show = {}
    	 $rootScope.rootSearch = {}
    	 $http.get('/actual_show')
    	 .then( function(res){
    		 $rootScope.Show = res.data
    	 })
    	 $rootScope.isAuthorized = function(group){
    		 if( !$localStorage.userAuth ) return false
    		 if( group && group != $localStorage.userAuth.user.group ) return false
    		 return true
    	 }
    	 $rootScope.logout = function(){
    		 delete $localStorage.userAuth
    		 $location.path('/')
    	 }
    	 $rootScope.$on('$routeChangeSuccess', function (event, current, previous) {

 	        if (current.hasOwnProperty('$$route')) {
	            $rootScope.page_title = current.$$route.title;
	        }

 	        if( $localStorage.userAuth ){ 
 	        	$http.get('/admin/token' ).
 	        	then(function(res){
 	        		if( res.data.token ) $localStorage.userAuth = res.data
 	        		else delete $localStorage.userAuth
 	        	})
 	        }
    	 })
        
    	}])
     
})();