<?php $field instanceof GDO_Checkbox; ?>
<?php if ($field->isChecked()) : ?>
<i class="material-icons">done</i>
<?php else : ?>
<i class="material-icons">remove</i>
<?php endif;?>