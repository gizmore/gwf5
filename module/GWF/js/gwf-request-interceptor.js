'use strict';
angular.module('gwf5').
factory('RequestInterceptor', function($q, $injector) {
	var ErrorSrvc;
	return {
		'request': function(config) {
			  return config;
		},
		'requestError': function(rejection) {
	        if (!ErrorSrvc) { ErrorSrvc = $injector.get('GWFErrorSrvc'); }
			ErrorSrvc.showNetworkError(rejection);
			return $q.reject(rejection);
		},
		'response': function(response) {
			return response;
		},
		'responseError': function(rejection) {
	        if (!ErrorSrvc) { ErrorSrvc = $injector.get('GWFErrorSrvc'); }
			var code = rejection.status;
			if ((code == 403) || (code == 405)) {
			}
			else if (code == 404) {
				ErrorSrvc.show404Error(rejection);
			}
			else {
				ErrorSrvc.showServerError(rejection);
			}
			return $q.reject(rejection);
		}
	};
}).
config(function($httpProvider) {  
	$httpProvider.interceptors.push('RequestInterceptor');
});
