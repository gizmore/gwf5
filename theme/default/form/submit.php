<?php $field instanceof GDO_Submit; ?>
<input type="submit" name="<?php echo $field->name; ?>" value="<?php echo $field->displayLabel(); ?>"<?php echo $field->htmlDisabled(); ?> />
