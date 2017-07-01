"use strict";
angular.module('gwf5').
controller('GWFTableCtrl', function($scope, GWFRequestSrvc) {
	
	$scope.init = function(config) {
		console.log('GWFTableCtrl.init()', config);
		$scope.config = config;
		$scope.setupDragDrop($('.gwf-table tr'));
	};
	
	$scope.onDrop = function(a, b) {
		console.log('GWFTableCtrl.onDrop()', a, b);
		if ($scope.config.sortable) {
			var url = $scope.config.sortableURL;
			var data = {a:a.attr('gdo-id'),b:b.attr('gdo-id')};
			GWFRequestSrvc.send(url, data).then(function(result) {
				// Successfully swapped positions.
				// Do alike in JS
				var a1 = a.prev(); var a2 = a.next(); // Prev and 
				var b1 = b.prev(); var b2 = b.next(); // Next row
				if (a1[0] == b[0]) { a1 = a1.prev(); }
				if (a2[0] == b[0]) { a2 = a2.next(); }
				if (b1[0] == a[0]) { b1 = b1.prev(); }
				if (b2[0] == a[0]) { b2 = b2.next(); }
				// Remove swappers
				a.remove(); b.remove();
				// Put in new places
				if (b2.length) {b2.before(a); } else {  b1.after(a); }
				if (a1.length) { a1.after(b); } else { a2.before(b); }
				// Restore clone
				$scope.setupDragDrop(a);
				$scope.setupDragDrop(b);
			});
		}
	};
	
	$scope.setupDragDrop = function($elements) {
		setTimeout(function(){
			$elements.draggable({
				helper: 'clone',
			});
			$elements.droppable({
				drop: function(event, ui) {
					var a = $(ui.draggable);
					var b = $(event.target);
					$scope.onDrop(a, b);
				}
			});
		})
	};
	
});
