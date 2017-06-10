<?php
final class GDO_User extends GDO_Object
{
	public function __construct()
	{
		$this->klass = 'GWF_User';
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
	### Exists ###
	##############
	public $exists;
	public function exists()
	{
		$this->exists= true;
		return $this;
	}
	
	
}
