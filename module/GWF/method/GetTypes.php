<?php
/**
 * Get all types used in all tables.
 * @author gizmore
 */
final class GWF_GetTypes extends GWF_Method
{
	public function isAjax() { return true; }
	
	public function execute()
	{
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
		$types = [];
		foreach ($tables as $table)
		{
			$types[$table->gdoClassName()] = [];
			foreach ($table->gdoColumnsCache() as $name => $gdoType)
			{
				$types[$table->gdoClassName()][$gdoType->name] = $gdoType->gdoClassName();
			}
		}
		
		die(json_encode($types));
	}
}
