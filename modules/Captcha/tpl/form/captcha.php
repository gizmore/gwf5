<?php $field instanceof GDO_Captcha; ?>
<md-input-container class="md-block md-float md-icon-left" layout-fill flex>
  <label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
    <img
     src="<?php echo $field->hrefCaptcha(); ?>"
     onclick="this.src='<?php echo $field->hrefNewCaptcha(); ?>'+(new Date().getTime())" />
  <?php echo $field->htmlIcon(); ?>
  <input
   type="text"
   style="width:120px; clear: both;"
   name="form[<?php echo $field->name; ?>]"
   value="<?php echo $field->displayFormValue(); ?>"/>
  <div class="gwf-form-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
