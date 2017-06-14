"use strict";
angular.module('gwf5').
controller('GWFAppCtrl', function($scope, $mdSidenav) {
	$scope.data = {}
	$scope.init = function() {
		$scope.data.topMenu = { title: 'GWFv5' };
		$scope.data.leftMenu = { enabled: true };
		$scope.data.rightMenu = { enabled: true };
	}
	
	$scope.openLeft = function() { $mdSidenav('left').open(); };
	$scope.closeLeft = function() { $mdSidenav('left').close(); };
	$scope.openRight = function() { $mdSidenav('right').open(); };
	$scope.closeRight = function() { $mdSidenav('right').close(); };
	
	$scope.init();
});