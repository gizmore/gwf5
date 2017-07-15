"use strict";
angular.module('gwf5').
controller('GWFListCtrl', function($scope, $controller, GWFRequestSrvc) {
	
	$controller('GWFAppCtrl', { $scope: $scope });
	
	$scope.data = $scope.data || {};
	$scope.data.items = [];
	
	$scope.init = function(config) {
		console.log('GWFListCtrl.init()', config);
		$scope.config = config;
	};
	
	$scope.infiniteItems = {
		numLoaded_: 0,
		toLoad_: 0,
		items: [],

		// Required.
		getItemAtIndex: function (index) {
			if (index > this.numLoaded_) {
				this.fetchMoreItems_(index);
				return null;
			}
			return this.items[index];
		},

		// Required.
		getLength: function () {
			return this.numLoaded_ + 5;
		},

		fetchMoreItems_: function (index) {
			if (this.toLoad_ < index) {
				this.toLoad_ += 5;
				$http.get('items.json').then(angular.bind(this, function (obj) {
					this.items = this.items.concat(obj.data);
					this.numLoaded_ = this.toLoad_;
				}));
			}
		}
	};

	
});
