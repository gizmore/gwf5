<?php
############
### Init ###
############
include 'protected/config.php';
include'inc/GWF5.php';

# Init
$gwf5 = new GWF5();
$perf = new GWF_DebugInfo();
GWF_Log::init();
$db = new GDODB(GWF_DB_HOST, GWF_DB_USER, GWF_DB_PASS, GWF_DB_NAME);
GWF_Session::init(GWF_SESS_NAME, GWF_SESS_DOMAIN, GWF_SESS_TIME, !GWF_SESS_JS, GWF_SESS_HTTPS);
GWF_Debug::init();
GWF_Debug::enableErrorHandler();
GWF_Debug::enableExceptionHandler();
GWF_Debug::setDieOnError(false);
GWF_Debug::setMailOnError(true);

# Load modules
$modules = $gwf5->loadModules();

# Include JS
if ($gwf5->isFullPageRequest())
{
	foreach ($modules as $module)
	{
		$module->onIncludeScripts();
	}
}


# Get module and method
$module = $method = null;
if ($module = $gwf5->getModule(Common::getGetString('mo', GWF_MODULE)))
{
	$method = $module->getMethod(Common::getGetString('me', GWF_METHOD));
}
if (!$method)
{
	$method = $gwf5->defaultMethod();
}


# Exec
ob_start();
if (!($response = $method->exec()))
{
	$response = new GWF_Error('err_blank_response');
}
$response = GWF_Response::make(ob_get_clean())->add($response);


# Render
echo $gwf5->render($method, $response);

$gwf5->finish();

# Debug
if ($gwf5->isFullPageRequest())
{
	echo "<!-- " . $perf->display() . ' -->';
}
