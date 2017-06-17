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
	public $name = 'xsrf';
	
	public function csrfToken()
	{
		$csrf = '';
		if (GWF_Session::instance())
		{
			if (!($csrf = GWF_Session::get('xsrf')))
			{
				$csrf = GWF_Random::randomKey(8);
				GWF_Session::set('xsrf', $csrf);
			}
		}
		return $csrf;
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
		$csrf = GWF_Session::get('xsrf');
		if ($csrf !== $value)
		{
			return $this->error('err_csrf');
		}
		
		$csrf = GWF_Random::randomKey(8);
		GWF_Session::set('xsrf', $csrf);
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
