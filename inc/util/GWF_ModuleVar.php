<?php
class GWF_ModuleVar extends GDO
{
	public function gdoCached() { return false; }
	###########
	### GDO ###
	###########
	public function gdoTableName() { return "gwf_module_var"; }
	public function gdoColumns()
	{
		return array(
			GDO_Object::make('mv_module_id')->klass('GWF_Module')->notNull()->primary()->index(),
			GDO_Name::make('mv_name')->primary()->notNull(),
			GDO_String::make('mv_value')->notNull(),
		);
	}
	public function getVarName() { return $this->getVar('mv_name'); }
	public function getVarValue() { return $this->getVar('mv_value'); }
	
	public static function createModuleVar(GWF_Module $module, GDOType $var)
	{
		return self::table()->blank(array(
			'mv_module_id' => $module->getID(),
			'mv_name' => $var->name,
			'mv_value' => $var->getValue(),
		))->replace();
	}
	
}
