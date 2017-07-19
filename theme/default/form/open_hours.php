<?php $field instanceof GDO_OpenHours; ?>
<md-input-container class="md-block md-float md-icon-left<?php echo $field->classError(); ?>" flex>

  <label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
  <?php echo $field->htmlIcon(); ?>

<div>
<table>
<tr><th>Tag</th><th>Von</th><th>BIS</th></tr>
<tr><td>Thhdhag</td><td>Von</td><td>BIS</td></tr>
</table>
</div>

  <input
   type="hidden"
   name="form[<?php echo $field->name; ?>]"
   value="<?php echo $field->displayFormValue(); ?>"
   <?php echo $field->htmlRequired(); ?>
   <?php echo $field->htmlPattern(); ?>
   <?php echo $field->htmlDisabled(); ?>/>
  <div class="gwf-form-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
