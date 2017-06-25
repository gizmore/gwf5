<?php
############
### Init ###
############
include 'protected/config.php';
include'inc/GWF5.php';

# Init
$gwf5 = new GWF5();
$perf = new GWF_DebugInfo();
GWF_Debug::init();
GWF_Debug::enableErrorHandler();
GWF_Debug::enableExceptionHandler();
GWF_Debug::setDieOnError(GWF_ERROR_DIE);
GWF_Debug::setMailOnError(GWF_ERROR_MAIL);
$db = new GDODB(GWF_DB_HOST, GWF_DB_USER, GWF_DB_PASS, GWF_DB_NAME, (GWF_DB_DEBUG && !isset($_REQUEST['ajax'])));
GDOCache::init();
if ( (GWF_SALT === @$_GET['xcache']) || (!GWF_MEMCACHE) ) GDOCache::flush();
$modules = $gwf5->loadModulesCache();
GWF_Session::init(GWF_SESS_NAME, GWF_SESS_DOMAIN, GWF_SESS_TIME, !GWF_SESS_JS, GWF_SESS_HTTPS);
GWF_Log::init(GWF_User::current()->getUserName(), GWF_ERROR_LEVEL, 'protected/logs');

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
try
{
	ob_start();
	if (!($response = $method->exec()))
	{
		$response = new GWF_Error('err_blank_response');
	}
	$response = GWF_Response::make(ob_get_clean())->add($response);
}
catch (Exception $e)
{
	GWF_Log::logException($e);
	$response = GWF_Response::make(ob_get_clean())->add(GWF_Error::make(GWF_Debug::backtraceException($e)));
}

# Render
echo $gwf5->render($method, $response);
