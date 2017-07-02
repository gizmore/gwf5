"use strict";
angular.module('gwf5').
controller('GWFListCtrl', function($scope, GWFRequestSrvc) {
	
	$scope.init = function(config) {
		console.log('GWFListCtrl.init()', config);
		$scope.config = config;
	};
	
});
