<?php
abstract class GWF_MethodQueryCard extends GWF_Method
{
	/**
	 * @return GDO
	 */
	public abstract function gdoTable();
	
	/**
	 * @return GDOType[]
	 */
	public function gdoParameters()
	{
		return $this->gdoTable()->gdoPrimaryKeyColumns();
	}
	
	public function gdoQueryCard()
	{
		$params = $this->gdoParameters();
		return $this->gdoTable()->find($params[0]->parameterValue());
	}
	
	public function execute()
	{
		return $this->renderCard();
	}
	
	public function renderCard()
	{
		$object = $this->gdoQueryCard();
		return $object->renderCard();
	}
}
