<?php
class GDO_Checkbox extends GDO_Bool
{
	public function formValue()
	{
		if ($this->value) { return $this->value; }
		$vars = Common::getRequestArray('form', []);
		return isset($vars[$this->name]) ?  '1' : '0';
	}
	
	public function isChecked()
	{
		return $this->getGDOVar() > 0;
	}

	public function htmlChecked()
	{
		return $this->getGDOValue() ? ' checked="checked"' : '';
	}
	
	public function render()
	{
		return GWF_Template::mainPHP('form/checkbox.php', ['field'=>$this]);
	}
	
	public function renderCell()
	{
		return GWF_Template::mainPHP('cell/checkbox.php', ['field'=>$this]);
	}
	
	public function validate($value)
	{
		if ( (!$this->null) && ($value == 0) )
		{
			return $this->error('err_checkbox_required');
		}
		return parent::validate($value);
	}
}
