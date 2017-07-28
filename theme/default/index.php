<!DOCTYPE html>
<html>
  <head>
    <title><?php #echo $method->title; ?></title>
    <link href="theme/default/css/gwf5.css" rel="stylesheet" />
    <?php echo GWF_Website::displayLink(); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="index, follow" />
  </head>
  <body ng-app="gwf5" layout="column" layout-fill flex ng-controller="GWFAppCtrl" ng-cloak>
  
    <!-- Begin Toolbar -->
    <header>
    <md-toolbar class="md-hue-2">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="<?php l('btn_left_menu'); ?>" ng-disabled="data.leftMenu.disabled" ng-click="openLeft()">
          <?php echo GDO_Button::matIconS('menu'); ?>
        </md-button>
        <?= GWF_Navbar::top()->render(); ?>
        <md-button class="md-icon-button" aria-label="<?php l('btn_right_menu'); ?>" ng-disabled="data.rightMenu.disabled" ng-click="openRight()">
          <?php echo GDO_Button::matIconS('menu'); ?>
        </md-button>
      </div>
    </md-toolbar>
    </header>
	<!-- End Toolbar -->
 
    <section layout="row" flex>
  
      <!-- LEFT SIDENAV -->
      <md-sidenav class="md-sidenav-left" md-component-id="left" md-is-locked-open="$mdMedia('gt-md')" md-whiteframe="4">
        <?php echo GWF_Navbar::left()->render(); ?>
      </md-sidenav>
      <!-- END LEFT SIDENAV -->

      <!-- CONTENT -->
      <md-content flex class="gwf-main">
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
    
	<!-- JS -->
<?php
$minify = false;
if ($module = Module_GWF::instance())
{
	$minify = $module->cfgMinifyJS() === 'concat';
}
echo GWF_Javascript::displayJavascripts($minify);
?>

    <!-- BEGIN FOOTER -->
    <footer class="md-whiteframe-4dp" layout="row" layout-align="center center">
      <?php echo GWF_Navbar::bottom()->render(); ?>
    </footer>
    <!-- END FOOTER -->

  </body>
</html>
