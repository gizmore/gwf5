"use strict";
angular.module('gwf5').
controller('GWFFormCtrl', function($scope) {
}).
controller('GWFCbxCtrl', function($scope) {
	$scope.cbxChanged = function(cbxName) {
		console.log('GWFCbxCtrl.cbxChanged()', cbxName);
		if ($scope.data.cbx) {
			jQuery(cbxName).attr('checked', 'checked'); 
		}
		else {
			jQuery(cbxName).removeAttr('checked'); 
		}
	};
}).
controller('GWFSelectCtrl', function($scope) {
	$scope.init = function(selector, selection) {
		$scope.data.selector = selector;
		$scope.data.selection = selection;
		$scope.valueSelected();
	}
	$scope.valueSelected = function() {
		console.log('GWFSelectCtrl.valueSelected()', $scope.data.selection);
		$($scope.data.selector).val($scope.data.selection);
	};
});
