"use strict";
angular.module('gwf5').
controller('GWFFormCtrl', function($scope) {
}).controller('GWFCbxCtrl', function($scope) {
	$scope.cbxChanged = function(cbxName, $event) {
		console.log('GWFCbxCtrl.cbxChanged()', cbxName, $event);
		if ($scope.cbx) {
			jQuery(cbxName).attr('checked', 'checked'); 
		}
		else {
			jQuery(cbxName).removeAttr('checked'); 
		}
	};
	$scope.cbxChangedDyn = function(cbxName) {
		if ($scope[cbxName]) {
			jQuery('#'+cbxName).attr('checked', 'checked'); 
		}
		else {
			jQuery('#'+cbxName).removeAttr('checked'); 
		}
	};
}).controller('GWFTableToggleCtrl', function($scope){
	$scope.cbxToggleAll = function($event) {
		console.log('GWFTableToggleCtrl.cbxToggleAll()', $event);
		
	};
}).controller('GWFCKEditorCtrl', function($scope) {
}).controller('GWFSelectCtrl', function($scope) {
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
}).controller('GWFAutoCompleteCtrl', function($scope, $q, GWFRequestSrvc) {
	$scope.init = function(gwfConfig, formId) {
		console.log('GWFAutoCompleteCtrl.init()', gwfConfig, formId);
		$scope.config = gwfConfig;
		$scope.formid = formId;
		$scope.data.selectedItem = gwfConfig.value;
	};
	$scope.objectSelected = function(item) {
		console.log('GWFAutoCompleteCtrl.objectSelected()', item);
		$scope.data.searchText = item.text;
		$($scope.formid).val(item.id);
	};
	$scope.query = function(searchText) {
		console.log('GWFAutoCompleteCtrl.query()', searchText);
		var defer = $q.defer();
		GWFRequestSrvc.send($scope.config.url, {query:searchText}, true).
			then($scope.querySuccess.bind($scope, defer), $scope.queryFailure.bind($scope, defer));
		return defer.promise;
	};
	$scope.querySuccess = function(defer, result) {
		console.log('GWFAutoCompleteCtrl.querySuccess()', result);
		defer.resolve(result.data);
	};
	$scope.queryFailure = function(defer, result) {
		console.log('GWFAutoCompleteCtrl.queryFailure()', result);
		defer.reject(result);
	};
});
