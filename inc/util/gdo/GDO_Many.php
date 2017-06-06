<?php
/**
 * HasMany relationship via gdoValue exposure.
 * @author gizmore
 */
class GDO_Many extends GDO_Object
{
	############
	### Stub ###
	############
	public function blankData() { return null; }
	public function gdoColumnDefine() { return ''; }
	
// 	#############
// 	### Cache ###
// 	#############
// 	/**
// 	 * @var GDO[]
// 	 */
// 	public $manyCache;
// 	public function refresh()
// 	{
// 		$this->manyCache = null;
// 		return $this;
// 	}
	
	############
	### Join ###
	############
	public $manyOn;
	public function manyOn(string $manyOn)
	{
		$this->manyOn = $manyOn;
		return $this;
	}
	
	#################
	### GDO Value ###
	#################
// 	public function gdo(GDO $gdo)
// 	{
// 		$this->
// 	}
	
	public function getGDOValue()
	{
// 		if (!$this->manyCache)
// 		{
			return $this->foreignQuery()->join($this->manyOn)->select('*')->where($this->gdo->getPKWhere())->exec()->fetchAllObjects();
// 		}
// 		return $this->manyCache;
	}
	
	
	/**
	 * Get GDO converted value. It's a one to many relation.
	 * {@inheritDoc}
	 * @see GDOType::gdoValue()
	 * @return GDO[]
	 */
	public function gdoValue(GDO $gdo, $value)
	{
		return $this->value($value)->getGDOValue();
	}
}
