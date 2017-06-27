"use strict";
angular.module('gwf5').
config(function(ivhTreeviewOptionsProvider) {
	ivhTreeviewOptionsProvider.set({
		defaultSelectedState: false,
		validate: true,
		expandToDepth: -1,
		twistieCollapsedTpl: '<i class="material-icons">chevron_right</i>',
		twistieExpandedTpl: '<i class="material-icons">expand_more</i>',
		twistieLeafTpl: '<span style="cursor: default;">&#8192;&#8192;</span>'
	});
}).
directive('mdBox', function(ivhTreeviewMgr) {
	return {
		restrict: 'AE',
		template: [
			'<span class="ascii-box">',
			'<span ng-show="node.selected" class="x"><md-checkbox style="min-height: 100%; line-height: 0" aria-label="checked" ng-checked="true"></md-checkbox></span>',
			'<span ng-show="node.__ivhTreeviewIndeterminate" class="y"><md-checkbox style="min-height: 100%; line-height: 0" aria-label="checked" ng-checked="false"></md-checkbox></span>',
			'<span ng-hide="node.selected || node.__ivhTreeviewIndeterminate"><md-checkbox style="min-height: 100%; line-height: 0" aria-label="checked" ng-checked="false"></md-checkbox></span>',
			'</span>',  
			].join(''),
		link: function(scope, element, attrs) {
			element.on('click', function() {
				var parent = scope, tree;
				while(parent = parent.$parent) {
					if (tree = parent.tree) {
						break;
					}
				}
				ivhTreeviewMgr.select(tree, scope.node, scope.node.selected);
				scope.$apply();
			});
		}
	};
}).
controller('GWFTreeCtrl', function($scope) {
	$scope.init = function(tree) {
		$scope.tree = tree;
	};
});
