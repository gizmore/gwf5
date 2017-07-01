<?php
############
### Init ###
############
chdir('../');
define('GWF_INSTALL', true);

require 'protected/config.php';
require 'inc/GWF5.php';

# Load
$db = new GDODB(GWF_DB_HOST, GWF_DB_USER, GWF_DB_PASS, GWF_DB_NAME);
GDOCache::init();
$gwf5 = new GWF5();
GWF_Log::init();
$perf = new GWF_DebugInfo();

GWF_Debug::init();
GWF_Debug::enableErrorHandler();
GWF_Debug::enableExceptionHandler();
GWF_Debug::setDieOnError(false);
GWF_Debug::setMailOnError(true);
############
### Core ###
############
// $db->queryWrite("SET foreign_key_checks = 0");
GWF_ModuleInstall::installCoreTables();
// $db->queryWrite("SET foreign_key_checks = 1");


####################
### Lang/Country ###
####################
require 'GWF_InstallData.php';
GWF_InstallData::install();

###############
### Modules ###
###############
$modules = $gwf5->loadModules(false);
GWF_ModuleInstall::installModules($modules);


##############
### Admins ###
##############
$user = GWF_User::blank(array(
'user_name'=>'system',
'user_email' => GWF_BOT_EMAIL,
'user_type' => 'bot',
'user_password' => GWF_Password::create('system')->__toString(),
))->insert();
$user = GWF_User::blank(array(
'user_name'=>'gizmore',
'user_email' => 'gizmore@gizmore.org',
'user_type' => 'member',
'user_password' => GWF_Password::create('11111111')->__toString(),
))->insert();
GWF_UserPermission::grant($user, 'admin');
GWF_UserPermission::grant($user, 'staff');

GDOCache::flush();

echo $gwf5->renderBlank();

echo $perf->display();
