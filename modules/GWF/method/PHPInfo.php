<?php
final class GWF_PHPInfo extends GWF_Method
{
	public function execute()
	{
		phpinfo();
		die();
	}
}
