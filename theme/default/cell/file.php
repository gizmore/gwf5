<?php $gdo instanceof GWF_File; ?>
<div class="gwf-file">
  <span class="gwf-file-name"><?php html($gdo->getName()); ?></span>
  <span class="gwf-file-size"><?php echo $gdo->displaySize(); ?></span>
  <span class="gwf-file-type"><?php echo $gdo->getType(); ?></span>
</div>