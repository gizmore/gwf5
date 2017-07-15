<?php
/**
 * Server a file from partially db(meta) and fs.
 * @author gizmore
 */
final class GWF_GetFile extends GWF_Method
{
	public function getPermission() { return 'admin'; }
	
	public function execute()
	{
		return $this->executeWithId(Common::getRequestString('file'));
	}
	
	public function executeWithId(string $id)
	{
		if (!($file = GWF_File::getById($id)))
		{
			return $this->error('err_unknown_file', null, 404);
		}
		if (!GWF_File::isFile($file->getPath()))
		{
			return $this->error('err_file_not_found', [htmlspecialchars($file->getPath())]);
		}
		GWF_Stream::serve($file);
	}
}
