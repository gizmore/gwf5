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
	
	
}
