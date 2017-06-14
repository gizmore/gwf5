<?php
class GDO_File extends GDO_Object
{
	public $klass = 'GWF_File';
	
	public $mimes = [];
	public function mime(string $mime)
	{
		$this->mimes[] = $mime;
		return $this;
	}
	
	public function imageFile()
	{
		$this->mime('image/jpeg');
		$this->mime('image/gif');
		$this->mime('image/png');
		return $this->preview();
	}
	
	public $minsize = 1024;
	public function minsize(int $minsize)
	{
		$this->minsize = $minsize;
		return $this;
	}
	public $maxsize = 4096 * 1024;
	public function maxsize(int $maxsize)
	{
		$this->maxsize = $maxsize;
		return $this;
	}
	
	public $preview = false;
	public function preview(bool $preview=true)
	{
		$this->preview = $preview;
		return $this;
	}
	
	public $multiple = false;
	public $minfiles = 0;
	public $maxfiles = 1;
	public function minfiles(int $minfiles)
	{
		$this->minfiles = $minfiles;
		return $minfiles ? $this->notNull() : $this;
	}
	public function maxfiles(int $maxfiles)
	{
		$this->maxfiles = $maxfiles;
		$this->multiple = $maxfiles > 1;
		return $this;
	}
	
	public $action;
	public function action(string $action)
	{
		$this->action = GWF_HTML::escape($action.'&ajax=1&fmt=json&flowField='.$this->name);
		return $this;
	}
	
	public function flowJSONConfig()
	{
		return json_encode(array(
			'minsize' => $this->minsize,
			'maxsize' => $this->maxsize,
			'minfiles' => $this->minfiles,
			'maxfiles' => $this->maxfiles,
			'preview' => $this->preview,
			'mimes' => $this->mimes,
		));
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('form/file.php', ['field'=>$this]);
	}
	
	################
	### Validate ###
	################
	public function formValue()
	{
		return $this->getFiles($this->name);
	}
	
	protected function filteredFormValue()
	{
		return $this->formValue();
	}
	
	public function validate($value)
	{
		$valid = true;
		if (count($value) < $this->minfiles)
		{
			$valid = $this->error('err_upload_min_files', [count($value), $this->minfiles]);
		}
		elseif (count($value) > $this->maxfiles)
		{
			$valid = $this->error('err_upload_max_files', [count($value), $this->maxfiles]);
		}
		
		if (!$valid)
		{
			$this->cleanup();
		}
		
		return $valid;
	}
	
	###################
	### Flow upload ###
	###################
	private function getTempDir($key='')
	{
		return GWF_PATH.'temp/flow/'.GWF_Session::instance()->getID().'/'.$key;
	}
	
	private function getChunkDir($key)
	{
		$chunkFilename = preg_replace('#[\\/]#', '', $_REQUEST['flowFilename']);
		return $this->getTempDir($key).'/'.$chunkFilename;
	}
	
	private function denyFlowFile($key, $file, $reason)
	{
		return @file_put_contents($this->getChunkDir($key).'/denied', $reason);
	}
	
	private function deniedFlowFile($key, $file)
	{
		$file = $this->getChunkDir($key).'/denied';
		return GWF_File::isFile($file) ? file_get_contents($file) : false;
	}
	
	private function getFile(string $key)
	{
		if ($files = $this->getFiles($key))
		{
			return $files[0];
		}
	}
	
	private function getFiles(string $key)
	{
		$files = array();
		$path = $this->getTempDir($key);
		if ($dir = @dir($path))
		{
			while ($entry = $dir->read())
			{
				if (($entry !== '.') && ($entry !== '..'))
				{
					if ($flowFile = $this->getFileFromDir($path.'/'.$entry))
					{
						$files[] = $flowFile;
					}
				}
			}
		}
		return $files;
	}
	
	private function getFileFromDir($dir)
	{
		return GWF_File::isFile($dir.'/0') ?
		array(
			'name' => @file_get_contents($dir.'/name'),
			'mime' => @file_get_contents($dir.'/mime'),
			'size' => filesize($dir.'/0'),
			'dir' => $dir,
			'path' => $dir.'/0',
		) : false;
	}
	
	public function cleanup()
	{
		GWF_File::removeDir($this->getTempDir());
	}
	
	public function flowUpload()
	{
		foreach ($_FILES as $key => $file)
		{
			$this->onFlowUploadFile($key, $file);
		}
		die();
	}
	
	private function onFlowError($error)
	{
		header("HTTP/1.0 413 $error");
		GWF_Log::logError("FLOW: $error");
		echo $error;
		return false;
	}
	
	private function onFlowUploadFile($key, $file)
	{
		$chunkDir = $this->getChunkDir($key);
		if (!GWF_File::createDir($chunkDir))
		{
			return $this->onFlowError('Create temp dir');
		}
		
		if (false !== ($error = $this->deniedFlowFile($key, $file)))
		{
			return $this->onFlowError("Denied: $error");
		}
		
		if (!$this->onFlowCopyChunk($key, $file))
		{
			return $this->onFlowError("Copy chunk failed.");
		}
		
		if ($_REQUEST['flowChunkNumber'] === $_REQUEST['flowTotalChunks'])
		{
			if ($error = $this->onFlowFinishFile($key, $file))
			{
				return $this->onFlowError($error);
			}
		}
		
		# Announce result
		$result = json_encode(array(
			'success' => true,
		));
		echo $result;
		return true;
	}
	
	private function onFlowCopyChunk($key, $file)
	{
		if (!$this->onFlowCheckSizeBeforeCopy($key, $file))
		{
			return false;
		}
		$chunkDir = $this->getChunkDir($key);
		$chunkNumber = (int) $_REQUEST['flowChunkNumber'];
		$chunkFile = $chunkDir.'/'.$chunkNumber;
		return @copy($file['tmp_name'], $chunkFile);
	}
	
	private function onFlowCheckSizeBeforeCopy($key, $file)
	{
		$chunkDir = $this->getChunkDir($key);
		$already = GWF_File::dirsize($chunkDir);
		$additive = filesize($file['tmp_name']);
		$sumSize = $already + $additive;
		if ($sumSize > $this->maxsize)
		{
			$this->denyFlowFile($key, $file, "exceed size of {$this->maxsize}");
			return false;
		}
		return true;
	}
	
	private function onFlowFinishFile($key, $file)
	{
		$chunkDir = $this->getChunkDir($key);
		
		# Merge chunks to single temp file
		$finalFile = $chunkDir.'/temp';
		GWF_Filewalker::traverse($chunkDir, array($this, 'onMergeFile'), false, true, array($finalFile));
		
		# Write user chosen name to a file for later
		$nameFile = $chunkDir.'/name';
		@file_put_contents($nameFile, $file['name']);
		
		# Write mime type for later use
		$mimeFile = $chunkDir.'/mime';
		@file_put_contents($mimeFile, mime_content_type($chunkDir.'/temp'));
		
		# Run finishing tests to deny.
		if (false !== ($error = $this->onFlowFinishTests($key, $file)))
		{
			$this->denyFlowFile($key, $file, $error);
			return $error;
		}
		
		# Move single temp to chunk 0
		if (!@rename($finalFile, $chunkDir.'/0'))
		{
			return "Cannot move temp file.";
		}
		
		return false;
	}
	
	public function onMergeFile($entry, $fullpath, $args)
	{
		list($finalFile) = $args;
		@file_put_contents($finalFile, file_get_contents($fullpath), FILE_APPEND);
	}
	
	private function onFlowFinishTests($key, $file)
	{
		if (false !== ($error = $this->onFlowTestChecksum($key, $file)))
		{
			return $error;
		}
		if (false !== ($error = $this->onFlowTestMime($key, $file)))
		{
			return $error;
		}
		if (false !== ($error = $this->onFlowTestImageDimension($key, $file)))
		{
			return $error;
		}
		return false;
	}
	
	private function onFlowTestChecksum($key, $file)
	{
		return false;
	}
	
	private function onFlowTestMime($key, $file)
	{
		if (!($mime = @file_get_contents($this->getChunkDir($key).'/mime'))) {
			return "$key: No mime found for file";
		}
		if ((!in_array($mime, $this->mimes, true)) && (count($this->mimes)>0)) {
			return "$key: Unsupported MIME TYPE: $mime";
		}
		return false;
	}
	
	private function onFlowTestImageDimension($key, $file)
	{
		return false;
	}
	
}
