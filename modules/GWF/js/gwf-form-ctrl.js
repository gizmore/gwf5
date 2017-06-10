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
});
