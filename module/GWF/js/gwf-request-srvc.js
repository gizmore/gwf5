'use strict';
angular.module('gwf5').
service('GWFRequestSrvc', function($http, GWFLoadingSrvc) {
	
	var RequestSrvc = this;

	RequestSrvc.send = function(url, data, noBusy) {
		console.log('RequestSrvc.send()', url, data, noBusy);
		if (!noBusy) {
			GWFLoadingSrvc.addTask('http');
		}
		return $http({
			method: 'POST',
			url: url,
			data: data,
			withCredentials: true,
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'	
			},
			transformRequest: RequestSrvc.transformPostData
		})['finally'](function() {
			GWFLoadingSrvc.removeTask('http');
		});
	};
	
	RequestSrvc.transformPostData = function(obj) {
		var str = [];
		for(var p in obj) {
			str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
		}
		return str.join("&");
	};
	
});
