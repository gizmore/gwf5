<?php $field instanceof GDO_Color;
$fg = GWF_Color::fromHex($field->getValue())->complementary()->asHex();
printf('<span style="background: %1$s; color: %2$s">%1$s</span>', $field->getValue(), $fg);
