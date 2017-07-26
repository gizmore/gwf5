<?php
############
### Init ###
############
if (php_sapi_name() !== 'cli')
{
    echo "This is a CLI application.";
    die(-1);
}

require 'protected/config.php';
require 'inc/GWF5.php';

# Load
$gwf5 = new GWF5();
$perf = new GWF_DebugInfo();
GDOCache::init();
GWF_Log::init();
$db = new GDODB(GWF_DB_HOST, GWF_DB_USER, GWF_DB_PASS, GWF_DB_NAME);
GWF_Cronjob::run();
