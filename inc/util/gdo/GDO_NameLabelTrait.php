<?php
trait GDO_NameLabelTrait
{
	public function name(string $name)
	{
		$this->name = $name;
		return $this->label($name);
	}
}