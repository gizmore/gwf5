<?php
class GWF_TestError extends GWF_Method
{
	public function execute()
	{
		return $this->error('err_test', ['test']);
	}
}