"use strict";
angular.module('gwf5').
controller('GWFOpenHoursCtrl', function GWFOpenHoursCtrl($scope, $mdDialog) {
	$scope.data = { openHours: '' };
	
//	var HoursCtrl = this;
	
	$scope.initJSON = function(json) {
		console.log('GWFOpenHoursCtrl.initJSON()', json);
	};
	
	$scope.confirmHours = function() {
		console.log('GWFOpenHoursCtrl.confirmHours()');
		$mdDialog.cancel();

	};
	$scope.closeDialog = function() {
		console.log('GWFOpenHoursCtrl.closeDialog()');
		$mdDialog.cancel();
	};
	
	$scope.openHoursDialog = function($event) {
		console.log('GWFOpenHoursCtrl.openHoursDialog()')
		$mdDialog.show({
			templateUrl: 'theme/default/dialog/open-hours.html',
//			locals: {
//				initPosition: initPosition,
//				text: text,
//			},
			clickOutsideToClose: true,
			controller: GWFOpenHoursCtrl,
			parent: angular.element(window.document.body),
			targetEvent: $event,
			onComplete: function() {
			}
		});
	};
});