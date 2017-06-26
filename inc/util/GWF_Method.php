<?php
/**
 * Abstract baseclass for all methods.
 * There are some derived method classes already for forms, tables and cronjobs.
 * 
 * @see GWF_MethodForm
 * @see GWF_MethodTable
 * @see GWF_MethodCronjob
 * @see GWF_Module
 * 
 * @author gizmore
 * @version 5.0
 * @since 1.0
 */
abstract class GWF_Method
{
	/**
	 * @var GWF_Module
	 */
	protected $module;
	
	private $name;
	
	public function __construct(GWF_Module $module)
	{
		$this->module = $module;
		$this->name = GWF_String::substrFrom(get_called_class(), '_');
	}
	
// 	###############
// 	### Factory ###
// 	###############
// 	public static function instance()
// 	{
// 		$klass = get_called_class();
// 		$module = GWF5::instance()->getModule(GWF_String::substrTo($klass, '_'));
// 		return new $klass($module);
// 	}
	
	##############
	### Helper ###
	##############
	public function getName() { return $this->name; }
	public function getSiteName() { return GWF5::instance()->getSiteName(); }
	
	/**
	 * Get selected checkboxes of a table via GDO_NumRow as CSV.
	 * @example 1,2,3,5
	 * @return string
	 */
	public function getRBX() { $rbx = implode(',', array_map('intval', array_keys(Common::getRequestArray('rbx')))); return empty($rbx) ? null : $rbx; }
	
	################
	### Override ###
	################
	public function isEnabled() { return true; }
	public function isUserRequired() { return false; }
	public function isGuestAllowed() { return true; }
	public function isCookieRequired() { return false; }
	public function isSessionRequired() { return false; }
	public function isTransactional() { return false; }
	public function getPermission() {}
	public function getUserType() {}
	public function init() {}
	
	
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
	 * Test permissions and execute method.
	 * @return GWF_Response
	 */
	public function exec()
	{
		$user = GWF_User::current();
		
		if (!($this->isEnabled()))
		{
			return new GWF_Error('err_method_disabled');
		}
		
		if ( ($this->isUserRequired()) && (!$user->isAuthenticated()) )
		{
			return new GWF_Error('err_user_required');
		}
		
		if ( (!$this->isGuestAllowed()) && (!$user->isMember()) )
		{
			return new GWF_Error('err_members_only');
		}
		
		if ( ($this->getUserType()) && ($this->getUserType() !== $user->getType()) )
		{
			return new GWF_Error('err_user_type', [$this->getUserType()]);
		}
		
		if ( ($permission = $this->getPermission()) && (!$user->hasPermission($permission)) )
		{
			return new GWF_Error('err_permission_required', [t('perm_'.$permission)]);
		}
			
		return $this->execWrap();
	}
	
	/**
	 * Execute a method by name. Convinience
	 * @param string $methodName
	 * @return GWF_Response
	 */
	public function execMethod(string $methodName)
	{
		return $this->module->getMethod($methodName)->execWrap();
	}
	
	public function execWrap()
	{
		$this->module->initModule();
		$this->init();
		return $this->isTransactional() && (count($_POST) > 0) ? $this->execTransactional() : $this->execute();
	}

	#####################
	### Transactional ###
	#####################
	public function execTransactional()
	{
		$db = GDODB::instance();
		try
		{
			$db->transactionBegin();
			$result = $this->execute();
			$db->transactionEnd();
			return $result;
		}
		catch (Exception $e)
		{
			$db->transactionRollback();
			throw $e;
		}
	}
	
	
}
