<?php
/**
 * Get enum values for all tables
 * @author gizmore
 */
final class GWF_GetEnums extends GWF_Method
{
	public function isAjax() { return true; }
	
	public function execute()
	{
		$tables = [];
		# Non abstract core tables
		$tables = GWF_ModuleInstall::includeCoreTables();
		foreach ($tables as $i => $table)
		{
			if ($table->gdoAbstract())
			{
				unset($tables[$i]);
			}
		}
		
		# Add non abstract module tables
		foreach (GWF5::instance()->getActiveModules() as $module)
		{
			if ($classes = $module->getClasses())
			{
				foreach ($classes as $class)
				{
					if (is_subclass_of($class, 'GDO'))
					{
						if ($table = GDO::tableFor($class))
						{
							if (!$table->gdoAbstract())
							{
								$tables[] = $table;
							}
						}
					}
				}
			}
		}
		
		# Add Enum values
		$columns = [];
		foreach ($tables as $table)
		{
			foreach ($table->gdoColumnsCache() as $name => $gdoType)
			{
				if ($gdoType instanceof GDO_Enum)
				{
					$columns[$table->gdoClassName().'.'.$name] = $gdoType->enumValues;
				}
			}
		}
		
		die(json_encode($columns));
	}
}
