<?php
############
### Init ###
############
chdir('../');

require 'protected/config.php';
require 'inc/GWF5.php';

# Load
$db = new GDODB(GWF_DB_HOST, GWF_DB_USER, GWF_DB_PASS, GWF_DB_NAME);
$gwf5 = new GWF5();

$perf = new GWF_DebugInfo();

GWF_Debug::init();
GWF_Debug::enableErrorHandler();
GWF_Debug::enableExceptionHandler();
GWF_Debug::setDieOnError(false);
GWF_Debug::setMailOnError(true);


$db->queryWrite("SET foreign_key_checks = 0");


global $tables;
$tables = array();
function wizardLoadUtil($entry, $path, $tables)
{
	global $tables;
	$class = GWF_String::substrTo($entry, '.');
	if (class_exists($class))
	{
		if (is_subclass_of($class, 'GDO'))
		{
			if ($table = GDO::tableFor($class))
			{
				$tables[$class] = $table;
			}
		}
	}
}
GWF_Filewalker::traverse(GWF_PATH . 'inc/util', 'wizardLoadUtil', false, false, $tables);

while (count($tables))
{
	$changed = false;
	
	foreach ($tables as $classname => $table)
	{
		if ($deps = $table->gdoDependencies())
		{
			foreach ($deps as $dep)
			{
				if (isset($tables[$dep]))
				{
					continue;
				}
			}
			
		}

		echo "Installing {$table->gdoTableIdentifier()}<br/>\n";
		$table->dropTable();
		$table->createTable();
		$changed = true;
		unset($tables[$classname]);
		break;
	}

	if (!$changed)
	{
		throw new GWF_Exception("err_gdo_dependency not met", [implode(', ', array_keys($tables))]);
	}
}

$db->queryWrite("SET foreign_key_checks = 1");

	
$modules = $gwf5->loadModules(false);
GWF_ModuleInstall::installModules($modules);

echo $gwf5->renderBlank();

echo $perf->display();
