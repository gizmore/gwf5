<section layout="row" layout-fill flex layout-align="center">
<?php echo GDO_Link::make()->label('btn_phpinfo')->href(href('GWF', 'PHPInfo'))->render(); ?>
<?php echo GDO_Link::make()->label('btn_modules')->href(Module_Admin::instance()->getMethodHREF('Modules'))->render(); ?>
<?php echo GDO_Link::make()->label('btn_users')->href(Module_Admin::instance()->getMethodHREF('Users'))->render(); ?>
<?php echo GDO_Link::make()->label('btn_permissions')->href(Module_Admin::instance()->getMethodHREF('Permissions'))->render(); ?>
<?php echo GDO_Link::make()->label('btn_cronjob')->href(href('Admin', 'Cronjob'))->render(); ?>
<?php echo GDO_Link::make()->label('btn_login_as')->href(href('Admin', 'LoginAs'))->render(); ?>
</section>
