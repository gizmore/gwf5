<?php
final class GWF_Permission extends GDO
{
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('perm_id'),
			GDO_Name::make('perm_name')->unique(),	
		);
	}
	
	public static function getByName(string $name) { return self::getBy('perm_name', $name); }
	
	public static function create(string $name)
	{
		if (!($perm = self::getByName($name)))
		{
			$perm = self::blank(['perm_name'=>$name])->insert();
		}
		return $perm;
	}
	
	##############
	### Getter ###
	##############
	public function getName() { return $this->getVar('perm_name'); }
	
	###############
	### Display ###
	###############
	public function displayName() { return l('perm_'.$this->getName()); }
	public function display_perm_edit() { return GDO_EditButton::make()->href($this->hrefEdit()); }
	public function display_user_count() { return $this->getVar('user_count'); }
	
	############
	### HREF ###
	############
	public function href_edit() { return href('Admin', 'Permission', '&permission='.$this->getID()); }
	
}
