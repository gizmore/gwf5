<?php
abstract class GWF_Method
{
	protected $module;
	
	public function __construct(GWF_Module $module)
	{
		$this->module = $module;
	}
	
	##############
	### Helper ###
	##############
	public function getSiteName() { return GWF5::instance()->getSiteName(); }
	public function getName() { return GWF_String::substrFrom(get_called_class(), '_'); }

	################
	### Override ###
	################
	public function isEnabled() { return true; }
	public function isGuestAllowed() { return true; }
	public function isCookieRequired() { return false; }
	public function isSessionRequired() { return false; }
	public function getPermission() {}
	public function getUserType() {}
	
	/**
	 * @return GWF_Response
	 */
	public abstract function execute();
	
	###########
	### SEO ###
	###########
	public $title;
	public function title(string $key, array $args=null)
	{
		$this->title = t($key, $args);
		return $this;
	}
	
	##############
	### Helper ###
	##############
	public function error(string $key, array $args=null) { return $this->module->error($key, $args); }
	public function message(string $key, array $args=null) { return $this->module->message($key, $args); }
	public function href(string $app='') { return sprintf('/index.php?mo=%s&me=%s%s', $this->module->getName(), $this->getName(), $app); }
	public function templatePHP(string $filename, array $tVars=null) { return $this->module->templatePHP($filename, $tVars); }
	public function getFormat() { return GWF5::instance()->getFormat(); }
	############
	### Exec ###
	############
	/**
	 * @return GWF_Response
	 */
	public function exec()
	{
		$user = GWF_User::current();
		
		if (!($this->isEnabled()))
		{
			return new GWF_Error('err_method_disabled');
		}
		
		if ( (!$this->isGuestAllowed()) && (!GWF_User::current()->isMember()) )
		{
			return new GWF_Error('err_members_only');
		}
		
		if ( ($this->getUserType()) && ($this->getUserType() !== $user->getType()) )
		{
			return new GWF_Error('err_already_authenticated');
		}
			
		return $this->execute();
	}
	
	/**
	 * Execute a method by name
	 * @param string $methodName
	 * @return GWF_Response
	 */
	public function execMethod(string $methodName)
	{
		return $this->module->getMethod($methodName)->execute();
	}
	
}
