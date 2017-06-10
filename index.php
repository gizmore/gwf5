<?php
############
### Init ###
############
require 'protected/config.php';
require 'inc/GWF5.php';

# Load
$gwf5 = new GWF5();
$perf = new GWF_DebugInfo();
GWF_Log::init();
$db = new GDODB(GWF_DB_HOST, GWF_DB_USER, GWF_DB_PASS, GWF_DB_NAME);
GWF_Session::init(GWF_SESS_NAME, GWF_SESS_DOMAIN, GWF_SESS_TIME, !GWF_SESS_JS, GWF_SESS_HTTPS);
$modules = $gwf5->loadModules();

if ($gwf5->isFullPageRequest())
{
	foreach ($modules as $module)
	{
		$module->onIncludeScripts();
	}
}


# Get module and method
$module = $method = null;
if ($module = $gwf5->getModule())
{
	if (!($method = $module->getMethod(Common::getGetString('me', GWF_METHOD))))
	{
		$method = $module->defaultMethod();
	}
}
else
{
	$module = $gwf5->defaultModule();
	$method = $module->defaultMethod();
}

# Exec
if (!($response = $method->exec()))
{
	$response = new GWF_Error('err_blank_response');
}

# Render
echo $gwf5->render($method, $response);

# ----
if ($gwf5->isFullPageRequest())
{
	echo "<!-- " . $perf->display() . ' -->';
}
