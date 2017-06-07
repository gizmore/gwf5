<?php $field instanceof GDO_Gender; ?>
<select
 name="form[<?php echo $field->name; ?>]"
 class="gwf-gender-select"
 <?php echo $field->htmlRequired(); ?>
 <?php echo $field->htmlDisabled(); ?>>
  <option value="m"><?php echo $field->displayMaleLabel(); ?></option> 
  <option value="f"><?php echo $field->displayFemaleLabel(); ?></option> 
  <option value=" "><?php echo $field->displayNoGenderLabel(); ?></option> 
</select>
<div class="form-error"><?php echo $field->displayError(); ?></div>
