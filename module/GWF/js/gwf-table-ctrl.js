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
			var data = {a:a.attr('gdo-id'),b:b.attr('gdo-id')};
			GWFRequestSrvc.send(url, data).then(function(result) {
				// Successfully swapped positions.
				// Do alike in JS
				var a1 = a.prev(); var a2 = a.next(); // Prev and 
				var b1 = b.prev(); var b2 = b.next(); // Next row
				a.remove(); b.remove(); // Remove swappers
				// Put in new places
				if (a1.length) { b.after(a1); } else { b.before(a2); }
				if (b1.length) { a.after(b1); } else { a.before(b2); }
			});
		}
	};
	
	setTimeout(function(){
		$('.gwf-table tr').draggable({
			helper: 'clone',
		});
		$('.gwf-table tr').droppable({
			drop: function(event, ui) {
				var a = $(ui.draggable);
				var b = $(event.target);
				$scope.onDrop(a, b);
			}
		});
	})
});
