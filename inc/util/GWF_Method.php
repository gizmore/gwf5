<?php
abstract class GWF_Method
{
	protected $module;
	
	public function __construct(GWF_Module $module)
	{
		$this->module = $module;
	}
	
	public function getName()
	{
		return GWF_String::substrFrom(get_called_class(), '_');
	}

	################
	### Override ###
	################
	public function isEnabled() { return true; }
	public function isGuestAllowed() { return true; }
	public function isCookieRequired() { return true; }
	public function isSessionRequired() { return true; }
	public function getPermissions() {}
	
	/**
	 * @return GWF_Response
	 */
	public abstract function execute();
	
	###########
	### SEO ###
	###########
	public function title()
	{
		return GWF_SITENAME;
	}
	
	##############
	### Helper ###
	##############
	public function error(string $key, array $args=null) { return $this->module->error($key, $args); }
	public function message(string $key, array $args=null) { return $this->module->message($key, $args); }
	public function href(string $app='') { return sprintf('/index.php?mo=%s&me=%s&fmt=%s%s', $this->module->getName(), $this->getName(), GWF5::getFormat(), $app); }
	public function template(string $filename, array $tVars=null) { return $this->module->template($filename, $tVars); }
	
	############
	### Exec ###
	############
	/**
	 * @return GWF_Response
	 */
	public function exec()
	{
		if (!($this->isEnabled()))
		{
			return new GWF_Error('err_method_disabled');
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
