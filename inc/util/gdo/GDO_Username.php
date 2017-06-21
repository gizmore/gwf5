<?php
/**
 * Username field with optional ajax completion.
 * @author gizmore
 * @since 5.0
 */
class GDO_Username extends GDO_String
{
	const LENGTH = 32;
	
	public $min = 2;
	public $max = 32;
	public $pattern = "/^[a-z][-_0-9a-z]{1,31}$/i";

	public function defaultLabel() { return $this->label('username'); }
	
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
	### Exists ###
	##############
	public $exists;
	public function exists()
	{
		$this->exists= true;
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
	
	################
	### Validate ###
	################
	public function validate($value)
	{
		if ( ($value === null) && ($this->null) )
		{
			return true;
		}
		
		# Check existance
		if ($this->exists)
		{
			if ($user = GWF_User::getByLogin($value))
			{
				$this->gdo = $user;
				return true;
			}
			else
			{
				return $this->error('err_user');
			}
		}
		# Check name pattern validity
		return parent::validate($value);
	}
}
