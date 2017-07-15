<?php
/**
 * Column type that references another table/GDO
 * 
 * @author gizmore
 * @since 5.0
 * @see GDO_Primary
 */
class GDO_Object extends GDOType
{
	use GDO_ObjectTrait;
	
	public function toJSON()
	{
		if ($gdo = $this->getGDOValue())
		{
			return array($this->name => $gdo->toJSON());
		}
		return array($this->name => null);
	}
	
	public function render()
	{
		if ($this->completionURL)
		{
			return GWF_Template::mainPHP('form/object_completion.php', ['field'=>$this]);
		}
		else
		{
			return GWF_Template::mainPHP('form/object.php', ['field'=>$this]);
		}
	}
	
	public function renderChoice()
	{
		if ($obj = $this->getGDOValue())
		{
			return $obj->renderChoice();
		}
		return $this->getValue();
	}
	
	public function validate($value)
	{
		if (parent::validate($value))
		{
			if ( ($value !== null) && (!$this->getGDOValue()) )
			{
				return $this->error('err_gdo_not_found', [$this->table->gdoHumanName(), htmle($value)]);
			}
			return true;
		}
	}
	
	
}
