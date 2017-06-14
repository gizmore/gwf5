<?php $field instanceof GDO_MD5;
$value = unpack('H*', $field->formValue());
echo array_pop($value);
