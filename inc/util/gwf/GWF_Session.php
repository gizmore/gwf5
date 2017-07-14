<?php
class GWF_Session extends GDO
{
	const DUMMY_COOKIE_CONTENT = 'GWF_like_16_byte';
	
	private static $INSTANCE;
	public static $STARTED = false;
	
	private static $COOKIE_NAME = 'GWF5';
	private static $COOKIE_DOMAIN = 'localhost';
	private static $COOKIE_JS = true;
	private static $COOKIE_HTTPS = true;
	private static $COOKIE_SECONDS = 72600;
	
	###########
	### GDO ###
	###########
	public static $ENGINE = self::MYISAM; # self::MYISAM
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('sess_id'),
			GDO_Token::make('sess_token')->notNull(),
			GDO_Object::make('sess_user')->klass('GWF_User'),
			GDO_IP::make('sess_ip'),
			GDO_UpdatedAt::make('sess_time'),
			GDO_Serialize::make('sess_data'),
		);
	}
	public function getID() { return $this->getVar('sess_id'); }
	public function getToken() { return $this->getVar('sess_token'); }
	public function getUser() { return $this->getValue('sess_user'); }
	public function getIP() { return $this->getValue('sess_ip'); }
	public function getTime() { return $this->getValue('sess_time'); }
	public function getData() { return $this->getValue('sess_data'); }
	
	/**
	 * Get current user or ghost.
	 * @return GWF_User
	 */
	public static function user()
	{
		if ( (!($session = self::instance())) ||
		     (!($user = $session->getUser())) )
		{
			return GWF_User::ghost();
		}
		return $user;
	}
	
	/**
	 * @return GWF_Session
	 */
	public static function instance()
	{
		if ( (!self::$INSTANCE) && (!self::$STARTED) )
		{
			self::$INSTANCE = self::start();
			self::$STARTED = true; # only one try
		}
		return self::$INSTANCE;
	}
	
	public static function reset()
	{
		self::$INSTANCE = null;
		self::$STARTED = false;
	}
	
	public static function init(string $cookieName='GWF5', string $domain='localhost', int $seconds=-1, bool $httpOnly=true, bool $https = false)
	{
		self::$COOKIE_NAME = $cookieName;
		self::$COOKIE_DOMAIN = $domain;
		self::$COOKIE_SECONDS = GWF_Math::clamp($seconds, -1, 1234567);
		self::$COOKIE_JS = !$httpOnly;
		self::$COOKIE_HTTPS = $https;
	}
	
	######################
	### Get/Set/Remove ###
	######################
	public static function get(string $key, $initial=null)
	{
		$session = self::instance();
		$data = $session ? $session->getData() : [];
		return isset($data[$key]) ? $data[$key] : $initial;
	}
	
	public static function set(string $key, $value)
	{
		if ($session = self::instance())
		{
			$data = $session->getData();
			$data[$key] = $value;
			$session->setValue('sess_data', $data);
		}
	}
	
	public static function remove(string $key)
	{
		$session = self::instance();
		$data = $session->getData();
		unset($data[$key]);
		$session->setValue('sess_data', $data);
	}
	
	public static function commit()
	{
		if (self::$INSTANCE)
		{
			self::$INSTANCE->save();
		}
	}
	
	/**
	 * Start and get user session
	 * @param string $cookieval
	 * @param string $cookieip
	 * @return GWF_Session
	 */
	private static function start($cookieValue=true, $cookieIP=true)
	{
		# Parse cookie value
		if ($cookieValue === true)
		{
			if (!isset($_COOKIE[self::$COOKIE_NAME]))
			{
				self::setDummyCookie();
				return false;
			}
			$cookieValue = (string)$_COOKIE[self::$COOKIE_NAME];
		}

		# Special first cookie
		if ($cookieValue === self::DUMMY_COOKIE_CONTENT)
		{
			$session = self::createSession($cookieIP);
		}
		# Try to reload
		elseif ($session = self::reload($cookieValue, $cookieIP))
		{
		}
		# Set special first dummy cookie
		else
		{
			self::setDummyCookie();
			return false;
		}

		return $session;
	}
	
	public static function reload(string $cookieValue)
	{
		list($sessId, $sessToken) = @explode('-', $cookieValue, 2);
		# Fetch from possibly from cache via find :)
		if (!($session = self::table()->find($sessId, false)))
		{
			return false;
		}
		
		if ($session->getToken() !== $sessToken)
		{
			return false;
		}
		#
// 		$query = self::table()->select('*')->where(sprintf('sess_id=%s AND sess_token=%s', GDO::quoteS($sessId), GDO::quoteS($sessToken)));
// 		if (!($session = $query->exec()->fetchObject()))
// 		{
// 			return false;
// 		}
		
		# IP Check?
		if ( ($ip = $session->getIP()) && ($ip !== GDO_IP::current()) )
		{
			return false;
		}
		
		self::$INSTANCE = $session;
		
		return $session;
	}
	
	public function ipCheck($cookieIP=true)
	{
		return true;
	}
	
	private function setCookie()
	{
		if (!GWF5::instance()->isCLI())
		{
			setcookie(self::$COOKIE_NAME, $this->cookieContent(), time() + self::$COOKIE_SECONDS, '/', self::$COOKIE_DOMAIN, self::cookieSecure(), !self::$COOKIE_JS);
		}
	}
	
	public function cookieContent()
	{
		return "{$this->getID()}-{$this->getToken()}";
	}
	
	private static function cookieSecure()
	{
		return false; # TODO: Evaluate protocoll and OR with setting.
	}
	
	private static function setDummyCookie()
	{
		if (!GWF5::instance()->isCLI())
		{
			setcookie(self::$COOKIE_NAME, self::DUMMY_COOKIE_CONTENT, time()+300, '/', self::$COOKIE_DOMAIN, self::cookieSecure(), !self::$COOKIE_JS);
		}
	}
	
	private static function createSession($cookieIP=true)
	{
		$session = self::table()->blank();
		if ($cookieIP)
		{
			$cookieIP = $cookieIP === true ? GDO_IP::current() : $cookieIP;
			$session->setVar('sess_ip', $cookieIP);
		}
		$session->insert();
		$session->setCookie();
		return $session;
	}
}