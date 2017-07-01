<?php
$bar = GDO_Bar::make();
$bar->addFields(array(
	GDO_Link::make('link_add_perm')->href(href('Admin', 'PermissionAdd'))->icon('add'),
	GDO_Link::make('link_grant_perm')->href(href('Admin', 'PermissionGrant'))->icon('add'),
));
echo $bar->render();
