<?php
class GDO_Checkbox extends GDO_Bool
{
    public function initial($initial)
    {
        $this->initial = (string)$initial;
        return $this;
    }
    
    public function formValue()
	{
		$vars = Common::getRequestArray('form', []);
		return isset($vars[$this->name]) ?  '1' : '0';
	}
	
	public function displayFormValue()
	{
		return $this->value;
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
	
	public function renderFilter()
	{
		return GWF_Template::mainPHP('filter/checkbox.php', ['field'=>$this]);
	}
	
	public function validate($value)
	{
		if ( (!$this->null) && ($value == 0) )
		{
			return $this->error('err_checkbox_required');
		}
		return true; #parent::validate($value);
	}
}
