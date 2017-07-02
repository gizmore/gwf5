<?php
############
### Init ###
############
while (ob_get_level()>0) { ob_end_clean(); }
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'protected/config.php';
include'inc/GWF5.php';

# Init
$gwf5 = new GWF5();
$perf = new GWF_DebugInfo();
GWF_Log::init(null, GWF_ERROR_LEVEL, 'protected/logs');
GWF_Debug::init();
GWF_Debug::enableErrorHandler();
GWF_Debug::enableExceptionHandler();
GWF_Debug::setDieOnError(GWF_ERROR_DIE);
GWF_Debug::setMailOnError(GWF_ERROR_MAIL);
$db = new GDODB(GWF_DB_HOST, GWF_DB_USER, GWF_DB_PASS, GWF_DB_NAME, (GWF_DB_DEBUG && !isset($_GET['ajax'])));
GDOCache::init();
if (!GWF_MEMCACHE) GDOCache::flush();
# Exec
try
{
	# Turn off Output buffering
	ob_start(); # Level 1
	
	GWF_Session::init(GWF_SESS_NAME, GWF_SESS_DOMAIN, GWF_SESS_TIME, !GWF_SESS_JS, GWF_SESS_HTTPS);
	$modules = $gwf5->loadModulesCache();
	GWF_Log::init(GWF_User::current()->getUserName(), GWF_ERROR_LEVEL, 'protected/logs');
	
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
	
	define('GWF_CORE_STABLE', microtime(true));
	if (!($response = $method->exec()))
	{
		$response = new GWF_Error('err_blank_response');
	}

	$unwanted = ob_get_clean();
	while (ob_get_level() > 0) { ob_end_clean(); }
	
	$response = GWF_Response::make($unwanted)->add($response);
	echo $gwf5->render($response);
}
catch (Exception $e)
{
	$content = ob_get_clean();
	while (ob_get_level() > 0) { ob_end_clean(); }
	GWF_Log::logException($e);
	$message = GWF_Debug::backtraceException($e, $gwf5->isHTML(), ' (maintrace)');
	echo defined('GWF_CORE_STABLE') ?
		$gwf5->render(GWF_Error::make($message)) :
		($message.PHP_EOL);
}
finally
{
	while (ob_get_level() > 0) { ob_end_flush(); }
}
