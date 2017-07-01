<?php
/**
 * Permission select
 * @author gizmore
 */
final class GDO_Permission extends GDO_ObjectSelect
{
	public function defaultLabel() { return $this->label('permission'); }
	
	public function __construct()
	{
		$this->table(GWF_Permission::table());
	}
	
// 	private $onlyOwn = false;
// 	public function onlyOwn(bool $onlyOwn=true)
// 	{
// 		$this->onlyOwn = $onlyOwn;
// 		return $this;
// 	}
	
}
