<div class="gwf-pagemenu"><?php $pagemenu instanceof GWF_PageMenu; ?>
  <ul>
    <?php foreach ($pages as $page) : $page instanceof GWF_PageMenuItem; ?>
      <li<?php echo $page->htmlClass(); ?>><a href="<?php echo $page->href; ?>">[-<?php echo $page->page; ?>-]</a></li>
    <?php endforeach; ?>
  </ul>
</div>
