"use strict";
angular.module('gwf5').
controller('GWFFormCtrl', function($scope) {
}).
controller('GWFCbxCtrl', function($scope) {
	$scope.cbxChanged = function(cbxName) {
//		console.log('GWFCbxCtrl.cbxChanged()', cbxName);
		if ($scope.cbx) {
			jQuery(cbxName).attr('checked', 'checked'); 
		}
		else {
			jQuery(cbxName).removeAttr('checked'); 
		}
	};
}).
controller('GWFCKEditorCtrl', function($scope) {
}).
controller('GWFSelectCtrl', function($scope) {
	$scope.init = function(selectedValues, multiple) {
//		console.log('GWFSelectCtrl.init()', selectedValues);
		$scope.multiple = multiple;
		$scope.selection = selectedValues;
	};
	$scope.multiValueSelected = function(selector) {
//		console.log('GWFSelectCtrl.multiValueSelected()', selector, $scope.selection);
		var value = $scope.multiple ? JSON.stringify($scope.selection) : $scope.selection;
		$(selector).attr('value', value);
	};

	$scope.valueSelected = function(selector) {
//		console.log('GWFSelectCtrl.valueSelected()', selector, $scope.selection);
		$(selector).attr('value', $scope.selection);
	};
}).controller('GWFDatepickerCtrl', function($scope){
	$scope.datePicked = function(selector) {
//		console.log('GWFSelectCtrl.datePicked()', selector, $scope.pickDate);
		var value = $scope.pickDate.toISOString().substr(0, 19).replace('T', ' ');
		$(selector).val(value);
	};
});
