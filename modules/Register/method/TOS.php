<?php
final class Register_TOS extends GWF_Method
{
	public function execute()
	{
		return $this->templatePHP('tos.php');
	}
}