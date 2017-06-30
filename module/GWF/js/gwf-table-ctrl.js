"use strict";
angular.module('gwf5').
controller('GWFTableCtrl', function($scope, GWFRequestSrvc) {
	
	$scope.init = function(config) {
		console.log('GWFTableCtrl.init()', config);
		$scope.config = config;
	};
	
	$scope.onDrop = function(a, b) {
		console.log('GWFTableCtrl.onDrop()', a, b);
		if ($scope.config.sortable) {
			var url = $scope.config.sortableURL;
			var data = {a:a,b:b};
			GWFRequestSrvc.send(url, data);
		}
		
	};
	
	setTimeout(function(){
		$('.gwf-table tr').draggable({
			helper: 'clone',
		});
		$('.gwf-table tr').droppable({
			drop: function(event, ui) {
//				console.log(event, ui);
				$scope.onDrop($(ui.draggable).attr('gdo-id'), $(event.target).attr('gdo-id'));
			}
		});
	})
});
