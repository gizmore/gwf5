<section layout="row" layout-fill flex>
<?php echo GDO_Button::make()->label('btn_modules')->href(Module_Admin::instance()->getMethodHREF('Modules'))->render(); ?>
<?php echo GDO_Button::make()->label('btn_users')->href(Module_Admin::instance()->getMethodHREF('Users'))->render(); ?>
<?php echo GDO_Button::make()->label('btn_permissions')->href(Module_Admin::instance()->getMethodHREF('Permissions'))->render(); ?>
</section>
