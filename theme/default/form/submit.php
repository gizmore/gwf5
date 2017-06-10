<?php $field instanceof GDO_Submit; ?>
<input
 type="submit"
 class="md-button primary md-raised"
 name="<?php echo $field->name; ?>"
 value="<?php echo $field->displayLabel(); ?>"
 <?php echo $field->htmlDisabled(); ?> /></input>
