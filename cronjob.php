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
GWF_Cronjob::run();
