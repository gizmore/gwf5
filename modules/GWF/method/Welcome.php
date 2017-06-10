<?php
class GWF_Welcome extends GWF_Method
{
	public function execute()
	{
		return $this->templatePHP('welcome.php');
	}
}
