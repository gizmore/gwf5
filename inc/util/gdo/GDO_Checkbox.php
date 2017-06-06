<?php
class GDO_Checkbox extends GDO_Bool
{
	public function formValue()
	{
		if ($this->value) { return $this->value; }
		$vars = Common::getRequestArray('form', []);
		return isset($vars[$this->name]) ?  '1' : '0';
	}

	public function htmlChecked()
	{
		return $this->getGDOValue() ? ' checked="checked"' : '';
	}
	
	public function render()
	{
		return GWF_Template::templateMain('form/checkbox.php', ['field'=>$this]);
	}
}
