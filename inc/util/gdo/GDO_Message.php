<?php
/**
 * A message is GDO_Text with an editor.
 * Currently, no nice md editor is to be found on the webs?
 * 
 * @author gizmore
 * @since 3.0
 * @version 5.0
 */
class GDO_Message extends GDO_Text
{
	public function render()
	{
		return GWF_Template::mainPHP('form/message.php', ['field'=>$this]);
	}
	
	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/message.php', ['field'=>$this])->getHTML();
	}
}
