<?php
class GDO_Message extends GDO_Text
{
	public function render()
	{
		return GWF_Template::mainPHP('form/message.php', ['field'=>$this]);
	}
}
