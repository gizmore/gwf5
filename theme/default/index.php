<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $method->title; ?></title>
    <link href="/theme/default/css/gwf5.css" rel="stylesheet" />
    <link href="/theme/default/css/gwf5-darkness.css" rel="stylesheet" />
    <?php echo GWF_Website::displayLink(); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="index, follow" />
  </head>
  <body ng-app="gwf5" layout="column" layout-fill flex ng-controller="GWFAppCtrl" ng-cloak>
  
    <!-- Begin Toolbar -->
    <md-toolbar class="md-hue-2">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="<?php l('btn_left_menu'); ?>" ng-disabled="data.leftMenu.disabled" ng-click="openLeft()">
          <?php echo GDO_Button::matIconS('menu'); ?>
        </md-button>
        <h2 flex md-truncate>{{data.topMenu.title}}</h2>
        <md-button class="md-icon-button" aria-label="<?php l('btn_right_menu'); ?>" ng-disabled="data.rightMenu.disabled" ng-click="openRight()">
          <?php echo GDO_Button::matIconS('menu'); ?>
        </md-button>
      </div>
    </md-toolbar>
	<!-- End Toolbar -->
 
 	<?php echo GWF_Javascript::displayJavascripts(Module_GWF::instance()->cfgMinifyJS() === 'concat'); ?>
 
    <section layout="row" flex>
  
      <!-- LEFT SIDENAV -->
      <md-sidenav class="md-sidenav-left" md-component-id="left" md-is-locked-open="$mdMedia('gt-md')" md-whiteframe="4">
        <md-toolbar class="md-theme-indigo">
          <h1 class="md-toolbar-tools"><?php l('sidenav_left_title'); ?></h1>
        </md-toolbar>
        <?php echo GWF_Navbar::left()->render(); ?>
      </md-sidenav>
      <!-- END LEFT SIDENAV -->

      <!-- CONTENT -->
      <md-content flex layout-padding>
        <div layout="column" layout-align="top center">
          <?php echo $response->__toString(); ?>
        </div>
        <div flex></div>
      </md-content>
      <!-- END CONTENT -->

      <!-- RIGHT SIDENAV -->
      <md-sidenav class="md-sidenav-right md-whiteframe-4dp" md-component-id="right">
        <md-toolbar class="md-theme-light">
          <h1 class="md-toolbar-tools"><?php l('sidenav_right_title'); ?></h1>
        </md-toolbar>
        <?php echo GWF_Navbar::right()->render(); ?>
      </md-sidenav>
      <!-- END RIGHT SIDENAV -->

    </section>
  </body>
</html>
