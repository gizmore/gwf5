<?php
/**
 * GWF_Form CSRF protection
 * @author gizmore
 * @version 5.0
 * @since 1.0
 */
class GDO_AntiCSRF extends GDOType
{
	############
	### Stub ###
	############
	public function blankData() {}
	public function addFormValue(GWF_Form $form, $value) {}
	public function hasChanged() { return false; }
	
	##############
	### Expire ###
	##############
	public $csrfExpire = 7200; # 2 hours is a sensible default.
	public function csrfExpire(int $csrfExpire)
	{
		$this->csrfExpire = $csrfExpire;
		return $this;
	}
	
	###############
	### Cleanup ###
	###############
	public $csrfMaxTokens = 30; # Last 30 forms should be fine
	public function csrfMaxTokens(int $csrfMaxTokens)
	{
		$this->csrfMaxTokens = $csrfMaxTokens;
		return $this;
	}
	
	#################
	### Construct ###
	#################
	public function __construct()
	{
		$this->name = 'csrf';
	}
	
	public function csrfToken()
	{
		$token = '';
		if (GWF_Session::instance())
		{
			$token = GWF_Random::randomKey(8);
			# Append csrf token to session
			$csrf = GWF_Session::get('csrf', []);
			$expire = time() + $this->csrfExpire;
			$csrf[$token] = $expire;
			# Cleanup max
			while (count($csrf) > $this->csrfMaxTokens)
			{
				array_shift($csrf);
			}
			GWF_Session::set('csrf', $csrf);
		}
		return $token;
	}
	
	################
	### Validate ###
	################
	public function validate($value)
	{
		if (!GWF_Session::instance())
		{
			return $this->error('err_session_required');
		}

		# Check session for token
		$csrf = GWF_Session::get('csrf', []);
		if (!isset($csrf[$value]))
		{
			return $this->error('err_csrf');
		}
		
		# Cleanup expire
		$now = time();
		foreach ($csrf as $token => $expire)
		{
			if ($expire < $now)
			{
				unset($csrf[$token]);
			}
		}
		
		# Save
		unset($csrf[$value]);
		GWF_Session::set('csrf', $csrf);
		
		return true;
	}

	##############
	### Render ###
	##############
	public function render()
	{
		return GWF_Template::mainPHP('form/csrf.php', ['field'=>$this]);
	}
	
	public function jsonFormValue()
	{
		return $this->csrfToken();
	}
}
