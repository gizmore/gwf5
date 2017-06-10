<?php $field instanceof GDO_File; ?>
<md-input-container
 ng-controller="GWFUploadCtrl"
 flex
 class="md-block md-float md-icon-left"
 flow-init="{target: '<?php echo $field->action; ?>', singleFile: <?php echo $field->multiple?'false':'true'; ?>, fileParameterName: '<?php echo $field->name; ?>', testChunks: false}"
 flow-file-progress="onFlowProgress($file, $flow, $message);"
 flow-file-success="onFlowSuccess($file, $flow, $message);"
 flow-file-error="onFlowError($file, $flow, $message);"
 flow-files-submitted="onFlowSubmitted($flow);"
 ng-init='initGWFConfig(<?php echo $field->flowJSONConfig(); ?>);'>
  <label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
  <?php echo $field->htmlIcon(); ?>
  <lf-ng-md-file-input
   lf-files="data.lfFiles"
   lf-caption="<?php echo $field->displayLabel(); ?>"
   ng-change="lfFilesChanged($event)"
   <?php if ($field->preview) : ?>preview<?php endif; ?>
   <?php echo $field->htmlDisabled(); ?>
   <?php echo $field->htmlRequired(); ?>></lf-ng-md-file-input>
  <div class="gwf-form-error"><?php echo $field->displayError(); ?></div>
</md-input-container>
