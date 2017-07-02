<?php
$bar = GDO_Bar::make('admintabs');
$bar->addFields(array(
	GDO_Link::make('btn_phpinfo')->href(href('GWF', 'PHPInfo')),
	GDO_Link::make('btn_clearcache')->href(href('Admin', 'ClearCache')),
	GDO_Link::make('btn_modules')->href(href('Admin', 'Modules')),
	GDO_Link::make('btn_users')->href(href('Admin', 'Users')),
	GDO_Link::make('btn_permissions')->href(href('Admin', 'Permissions')),
	GDO_Link::make('btn_cronjob')->href(href('Admin', 'Cronjob')),
	GDO_Link::make('btn_login_as')->href(href('Admin', 'LoginAs')),
));
echo $bar->renderCell();
