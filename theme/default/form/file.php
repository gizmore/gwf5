<?php $field instanceof GDO_File; ?>
<md-input-container  ng-controller="GWFUploadCtrl"
 flex
 class="md-block md-float md-icon-left<?php echo $field->classError(); ?>"
 flow-init="{target: '<?php echo $field->getAction(); ?>', singleFile: <?= $field->multiple?'false':'true'; ?>, fileParameterName: '<?= $field->name; ?>', testChunks: false}"
 flow-file-progress="onFlowProgress($file, $flow, $message);"
 flow-file-success="onFlowSuccess($file, $flow, $message);"
 flow-file-removed="onRemoveFile($file, $flow);"
 flow-file-error="onFlowError($file, $flow, $message);"
 flow-files-submitted="onFlowSubmitted($flow);"
 ng-init='initGWFConfig(<?php echo json_encode($field->initJSON()); ?>, "#gwffile_<?php echo $field->name; ?>");'>
  <label for="form[<?php echo $field->name; ?>]"><?php echo $field->displayLabel(); ?></label>
  <?php echo $field->htmlIcon(); ?>
  <lf-ng-md-file-input
   lf-files="lfFiles"
   lf-caption="{{displayFileName()}}"
   lf-placeholder="{{displayFileName()}}"
   ng-change="lfFilesChanged($event)"
   <?php if ($field->preview) : ?>preview<?php endif; ?>
   <?php echo $field->htmlDisabled(); ?>
   <?php echo $field->htmlRequired(); ?>></lf-ng-md-file-input>
  <br/>
  <div class="gwf-form-error"><?php echo $field->displayError(); ?></div>
  <input
   type="hidden"
   id="gwffile_<?php echo $field->name; ?>"
   name="form[<?php echo $field->name; ?>]" />
</md-input-container>
