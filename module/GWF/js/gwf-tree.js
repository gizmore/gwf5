"use strict";
angular.module('gwf5').
controller('GWFTreeCtrl', function($scope) {
	$scope.init = function(options, tree) {
		console.log('GWFTreeCtrl.init()', options, tree);
		$scope.hiddenId = options.id;
		$scope.multiple = !!options.multiple;
		$scope.tree = tree;
		$scope.all = {};
		for (var i in tree) {
			$scope.initNode(tree[i]);
		}
	};
	$scope.initNode = function(node) {
		$scope.all[node.id] = node;
		node.expanded = false;
		node.sel = node.selected = !!node.selected;
		for (var i in node.children) {
			$scope.initNode(node.children[i]);
		}
	};
	
	$scope.onToggled = function($event, id) {
		var root = $scope.all[id];
		root.sel = !root.sel;
		console.log('GWFTreeCtrl.onToggled()', root.label, root.sel);
		$scope.toggleChilds(root, root.sel);
		$scope.onBubble($scope.all[root.parent]);
//		$event.preventDefault();
//		$event.stopPropagation();
		for (var i in $scope.all) {
			var node = $scope.all[i];
			node.selected = node.sel; 
		}
		setTimeout(function(){
			root.selected = root.sel;
			$scope.$apply();
		},1);
		$($scope.hiddenId).val($scope.selection());
		return true;
	}
	$scope.selection = function() {
		var sel = []
		for (var i in $scope.all) {
			var cbx = $scope.all[i];
			if (cbx.sel) {
				sel.push(cbx.id);
			}
		}
		return JSON.stringify(sel);
	};
	
	$scope.onBubble = function(node) {
		if (node) {
			var selected = $scope.getSumToggle(node.id);
			console.log('Parent bubbled', node.label, selected);
			node.sel = node.selected = selected;
			var parent = $scope.all[node.parent];
			if (parent) {
				$scope.onBubble(parent);
			}
		}
	};
	
	$scope.toggleChilds = function(node, selected) {
//		console.log('GWFTreeCtrl.toggleChilds()', node, selected);
		for (var i in node.children) {
			var child = node.children[i];
			child.sel = selected;
			console.log('Child drowned', child.label, selected);
			$scope.toggleChilds(child, selected);
//			setTimeout($scope.toggleChilds.bind($scope, child, selected), 1);
		}
//		$scope.$apply();
	};
	
	$scope.getSumToggle = function(id) {
		var node = $scope.all[id];
		var sum = undefined;
		for (var i in node.children) {
			var child = node.children[i];
			if (sum === undefined) {
				sum = child.sel;
			}
			else if (sum !== child.sel) {
				sum = null;
				break;
			}
		}
		
		if (sum === undefined) {
			sum = node.sel;
		}
		
		console.log('GWFTreeCtrl.getSumToggle()', node.label, sum);
		return sum;
	};
	$scope.isShown = function(id) {
		var node = $scope.all[id];
		var parent = $scope.all[node.parent];
		return parent ? parent.expanded : true;
	};
	$scope.isCollapsed = function(id) {
		var node = $scope.all[id];
		return !node.expanded;
	};
	$scope.shrink = function(id, expand) {
		var node = $scope.all[id];
		node.expanded = !!expand;
		for (var i in node.children) {
			var child = node.children[i];
			$scope.shrink(child.id, expand);
		}
	};
	$scope.expand = function(id) {
		return $scope.shrink(id, true);
	};

	$scope.isIndeterminate = function(id) {
		var i = $scope.getSumToggle(id) === null;
//		console.log('GWF_TreeCtrl.isIndeterminate()', $scope.all[id].label, i);
		return i;
	};

});
