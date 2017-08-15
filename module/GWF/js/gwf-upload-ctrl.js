'use strict';
angular.module('gwf5').
controller('GWFUploadCtrl', function($scope, $http) {
	$scope.transfer = {
		fileNum: 0,
		filesCount: 0,
		bytesTotal: 0,
		bytesTransferred: 0,
		speed: '0',
		fileName: '',
		inProgress: false,
	};
	
	$scope.initGWFConfig = function(config, selector) {
		console.log('UploadCtrl.initGWFConfig()', config, selector);
		$scope.config = config;
		$scope.config.selector = selector;
//		$scope.initFiles(config);
		$scope.updateHiddenFiles();
	};
	
	$scope.initFiles = function(config) {
		console.log('UploadCtrl.initFiles()', config.selectedFiles);
		var files = [];
		var lfFiles = [];
		var flowFiles = [];
		for (var i in config.selectedFiles) {
			var gwfFile = config.selectedFiles[i];
			var file = {
				id: gwfFile['file_id'],
				name: gwfFile['file_name'],
				type: gwfFile['file_type'],
				size: gwfFile['file_size'],
			};
			files.push(file);
			var flowFile = new Flow.FlowFile($scope.$flow, file);
			flowFiles.push(flowFile);
			var lfFile = $scope.genLfFileObj(file);
			lfFiles.push(lfFile);
		}
		setTimeout(function(){
			$scope.$flow.files = flowFiles;
			$scope.lfFiles = lfFiles;
			$scope.updateHiddenFiles();
			$scope.$apply();
		}, 1);
//		$scope.$flow.upload();
//		console.log($scope.$flow);
	};
	
	$scope.displayFileName = function() {
		var display = [];
		for (var i in $scope.lfFiles) {
			var lfFile = $scope.lfFiles[i];
			var file = lfFile.lfFile;
			display.push(file.name + " (" + file.size + ")");
		}
		
		return display.length ? display.join(', ') : $scope.config.label;
	};
	
    $scope.genLfFileObj = function(file) {
        var lfFileObj = {
            "key":file.id,
            "lfFile":file,
            "lfFileName":file.name,
            "lfFileType":file.type,
            "lfTagType":null,
            "lfDataUrl":null, //window.URL.createObjectURL(file),
            "isRemote":false,
			"isInitial":true
        };
        return lfFileObj;
    };

	$scope.lfFilesChanged = function($event) {
		console.log('UploadCtrl.lfFilesChanged()', $event, $scope, $scope.$flow);
		var files = [];
		var keep = [];
		for (var i in $scope.lfFiles) {
			var lfFile = $scope.lfFiles[i];
			console.log(lfFile);
			if ($scope.isValidFile(lfFile.lfFile)) {
				if (!lfFile.isInitial) {
					files.push(lfFile.lfFile);
				}
				keep.push(lfFile);
			}
		}
		$scope.lfFiles = keep;
		if (files.length > 0) {
			setTimeout(function(){
				$scope.$flow.addFiles(files, $event);
			}, 1);
		}
		
		$scope.updateHiddenFiles();
	};
	
	$scope.removeInitialFile = function(fileId) {
		console.log('UploadCtrl.removeInitialFile()', fileId);
		var files = [];
		for (var i in $scope.config.selectedFiles) {
			var initialFile = $scope.config.selectedFiles[i];
			if (initialFile.file_id != fileId) {
				files.push(initialFile);
			}	
		}
		$scope.config.selectedFiles = files;
		$scope.updateHiddenFiles();
	};
	
	$scope.updateHiddenFiles = function() {
		console.log('UploadCtrl.updateHiddenFiles()', $scope.lfFiles);
		var value = [];
		for (var i in $scope.lfFiles) {
			var lfFile = $scope.lfFiles[i];
//			if (lfFile.isInitial) {
//				value.push({initial:true, id:lfFile.key});
//			}
//			else {
				value.push({initial:false, id:0, name: lfFile.lfFile.name});
//			}
		}
		for (var i in $scope.config.selectedFiles) {
			var file = $scope.config.selectedFiles[i];
			value.push({initial:true, id:file.file_id});
		}

		$($scope.config.selector).val(JSON.stringify(value));
	};
	
	$scope.onFlowSubmitted = function($flow) {
		console.log('UploadCtrl.onFlowSubmitted()', $flow);
		var acceptedFiles = [];
		for (var i in $flow.files) {
			if ($scope.isValidFile($flow.files[i].file)) {
				acceptedFiles.push($flow.files[i]);
			}
		}
		$flow.files = acceptedFiles;
		console.log('Accepted files', acceptedFiles);
		$flow.upload();
	};
	
	$scope.isValidFile = function($file) {
		console.log('UploadCtrl.isValidFile()', $file, $scope.config);
		var maxSize = $scope.config.maxsize;
		var mimeTypes = $scope.config.mimes;
		if ($file.size > maxSize) {
			$scope.denyFile($file, 'Max size exceeded.');
		}
		else if ((mimeTypes.indexOf($file.type) < 0) && (mimeTypes.length > 0)) {
			$scope.denyFile($file, 'Invalid mime type: '+$file.type);
		}
		else {
			return true;
		}
	};
	
	$scope.denyFile = function($file, error) {
		console.log('UploadCtrl.denyFile()', $file, error);
		alert(error);
//		ErrorSrvc.showError(error, 'Upload');
	};
	
	$scope.onRemoveFile = function($file, $flow) {
		console.log('UploadCtrl.onRemoveFile()', $file, $flow);
		alert('Removing files during upload is not yet supported.');
		// TODO: Send http request to indicate to delete a temp upload?
		//       Alternatively, set hidden fields for deleted files?
		// THEN: $flow.removeFile($file);
	};

	
	$scope.onFlowError = function($file, $flow, $message) {
		console.log('UploadCtrl.onFlowError()', $file, $flow, $message);
		$scope.denyFile($file, $message);
	};
	
	$scope.onFlowProgress = function($file, $flow, $msg) {
//		console.log('UploadCtrl.onFlowProgress()', $file, $flow, $msg);
		var transfer = $scope.transfer;
		var j = 0, index = 0;
		transfer.bytesTotal = 0;
		transfer.bytesTransferred = 0;
		for (var i in $flow.files) {
			// Detect file num
			var file = $flow.files[i];
			if (file === $file) {
				index = j;
			}
			j++;
			// Sum bytes
			transfer.bytesTotal += $file.size;
			transfer.bytesTransferred = $file._prevUploadedSize;
		}
		transfer.fileNum = index + 1;
		transfer.filesCount = $flow.files.length;
		transfer.speed = $file.currentSpeed;
		transfer.fileName = $file.name;
		transfer.inProgress = true;
	};

	$scope.onFlowSuccess = function($file, $flow, $msg) {
		console.log('UploadCtrl.onFlowSuccess()', $file, $flow, $msg);
		$scope.transfer.speed = $scope.transfer.bytesTotal;
		$scope.transfer.bytesTransferred = $scope.transfer.bytesTotal;
		$scope.transfer.inProgress = false;
	};
	
	$scope.progressIndicatorDisabled = function() {
		return !$scope.transfer.inProgress;
	};
	
	$scope.progressIndicatorValue = function() {
		var t = $scope.transfer;
		var value = t.bytesTransferred / t.bytesTotal;
		return value * 100;
	};

});
