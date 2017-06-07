<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $method->title(); ?></title>
    <link href="/theme/default/css/gwf5.css" rel="stylesheet" />
    <link href="/theme/default/css/gwf5-darkness.css" rel="stylesheet" />
  </head>
  <body>
    <?php echo GWF_TopMenu::instance()->render(); ?>

    <?php echo $response->__toString(); ?>

  </body>
</html>
