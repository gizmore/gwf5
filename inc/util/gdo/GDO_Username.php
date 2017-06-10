<?php
/**
 * Username field with optional ajax completion.
 * @author gizmore
 * @since 5.0
 */
class GDO_Username extends GDO_String
{
	public function __construct()
	{
		$this->min = 2;
		$this->max = 32;
		$this->pattern = "/^[a-z][-_0-9a-z]{1,31}$/i";
	}
	
	##################
	### Completion ###
	##################
	public $completion;
	public function completion()
	{
		$this->completion = true;
		return $this;
	}
	
	##############
	### Render ###
	##############
	public function render()
	{
		$tVars = array(
			'field' => $this,
		);
		return GWF_Template::mainPHP('form/username.php', $tVars);
	}
}
