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
controller('GWFColorCtrl', function($scope) {
	
}).
controller('GWFSelectCtrl', function($scope) {
	$scope.init = function(selectedValues) {
		console.log('GWFSelectCtrl.init()', selectedValues);
		$scope.selection = selectedValues;
	};
	$scope.multiValueSelected = function(selector) {
		console.log('GWFSelectCtrl.multiValueSelected()', selector, $scope.selection);
		$(selector).attr('value', JSON.stringify($scope.selection));
	};

	$scope.valueSelected = function(selector) {
		console.log('GWFSelectCtrl.valueSelected()', selector, $scope.selection);
		$(selector).attr('value', $scope.selection);
	};
});
