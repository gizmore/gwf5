<?php
/**
 * Debug backtrace and error handler.
 * Can send email on PHP errors.
 * Also has a method to get debug timings.
 * @example GWF_Debug::enableErrorHandler(); fatal_ooops();
 * @see GWF_DEBUG_EMAIL in protected/config.php
 * @todo it displays and sends two errors for each error
 * @author gizmore
 * @version 3.02
 */
final class GWF_Debug
{
	private static $die = true;
	private static $enabled = false;
	private static $exception = false;
	private static $MAIL_ON_ERROR = true;

	public static function init()
	{
		
	}
	
	public static function initNoGWF()
	{
		# Autoconfigure constants in case not defined
		$path = getcwd();
		$wwwpath = (PHP_SAPI === 'cli') ? $path : $_SERVER['REQUEST_URI']; # FIXME: vuln
		Common::defineConst('GWF_PATH', $path);
		Common::defineConst('GWF_WWW_PATH', $wwwpath);

		# Check if Mail class exists
//		if( false === class_exists('GWF_Mail') )
//		{
//			self::setMailOnError(false);
//		}

		# TODO: GWF_IP6, GWF_Log
	}

	################
	### Settings ###
	################
	public static function setDieOnError($bool=true)
	{
		self::$die = $bool;
	}

	public static function setMailOnError($bool=true)
	{
		self::$MAIL_ON_ERROR = $bool;
	}

	public static function disableErrorHandler()
	{
		if (self::$enabled === true)
		{
			restore_error_handler();
			self::$enabled = false;
		}
	}

	public static function enableErrorHandler()
	{
		if (self::$enabled === false)
		{
			set_error_handler(array('GWF_Debug', 'error_handler'));
			register_shutdown_function(array('GWF_Debug', 'shutdown_function'));
			self::$enabled = true;
		}
	}

	public static function enableStubErrorHandler()
	{
		self::disableErrorHandler();
		set_error_handler(array('GWF_Debug', 'error_handler_stub'));
		self::$enabled = true;
	}

	/**
	 * Call the garbage collector if exists.
	 */
	public static function collectGarbage()
	{
		if (function_exists('gc_collect_cycles'))
		{
			gc_collect_cycles();
		}
	}

	######################
	### Error Handlers ###
	######################
	public static function error_handler_stub($errno, $errstr, $errfile, $errline, $errcontext)
	{
		return false;
	}

	/**
	 * This one get's called on a fatal. No stacktrace available and some vars are messed up.
	 */
	public static function shutdown_function()
	{
		if (self::$enabled)
		{
			$error = error_get_last();

			if ($error['type'] != 0)
			{
				chdir(GWF_PATH);
				if (!class_exists('GWF_Log')) include 'inc/util/GWF_Log.php';
				if (!class_exists('GWF_IP')) include 'inc/util/GWF_IP.php';
				if (!class_exists('GWF_Mail')) include 'inc/util/GWF_Mail.php';
				self::error_handler(1, $error['message'], self::shortpath($error['file']), $error['line'], NULL);
			}
		}
	}

	/**
	 * Throw a custom error.
	 * @param string $message
	 * @param string $file
	 * @param string $line
	 */
// 	public static function throwError($message, $file='unknown', $line='???')
// 	{
// 		self::error_handler(-1, $message, $file, $line, NULL);
// 	}

	/**
	 * Error handler creates some html backtrace and can die on _every_ warning etc.
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param int $errline
	 * @param $errcontext
	 * @return false
	 */
	public static function error_handler($errno, $errstr, $errfile, $errline, $errcontext)
	{
		if (error_reporting() === 0)
		{
			return;
		}

		# Log as critical!
		if (class_exists('GWF_Log', false))
		{
			GWF_Log::logCritical(sprintf('%s in %s line %s', $errstr, $errfile, $errline));
			GWF_Log::flush();
		}

		switch($errno)
		{
			case -1: $errnostr = 'GWF Error'; break;

			case E_ERROR: case E_CORE_ERROR: $errnostr = 'PHP Fatal Error'; break;
			case E_WARNING: case E_USER_WARNING: case E_CORE_WARNING: $errnostr = 'PHP Warning'; break;
			case E_USER_NOTICE: case E_NOTICE: $errnostr = 'PHP Notice'; break;
			case E_USER_ERROR: $errnostr = 'PHP Error'; break;
			case E_STRICT: $errnostr = 'PHP Strict Error'; break;
			# if(PHP5.3) case E_DEPRECATED: case E_USER_DEPRECATED: $errnostr = 'PHP Deprecated'; break;
			# if(PHP5.2) case E_RECOVERABLE_ERROR: $errnostr = 'PHP Recoverable Error'; break;
			case E_COMPILE_WARNING: case E_COMPILE_ERROR: $errnostr = 'PHP Compiling Error'; break;
			case E_PARSE: $errnostr = 'PHP Parsing Error'; break;

			default: $errnostr = 'PHP Unknown Error'; break;
		}

		$is_html = PHP_SAPI !== 'cli';
		
		if ($is_html)
		{
			$message = sprintf('<p>%s(%s):&nbsp;%s&nbsp;in&nbsp;<b style=\"font-size:16px;\">%s</b>&nbsp;line&nbsp;<b style=\"font-size:16px;\">%s</b></p>', $errnostr, $errno, $errstr, $errfile, $errline).PHP_EOL;
		}
		else
		{
			$message = sprintf('%s(%s) %s in %s line %s.', $errnostr, $errno, $errstr, $errfile, $errline);
		}
		
		# Output error
		if (PHP_SAPI === 'cli')
		{
			file_put_contents('php://stderr', self::backtrace($message, false).PHP_EOL);
		}
		elseif (GWF_USER_STACKTRACE)
		{
			echo self::backtrace($message, $is_html).PHP_EOL;
		}
		elseif ($is_html)
		{
			printf('<div class="gwf-exception">%s</div>', $message);
		}
		else
		{
			echo $message.PHP_EOL;
		}
		
		# Send error to admin
		if (self::$MAIL_ON_ERROR)
		{
			self::sendDebugMail(self::backtrace($message, false));
		}

		if (true === self::$die)
		{
			die(1); # oops :)
		}

		return true; 
	}

	public static function exception_handler($e)
	{
		try
		{
			$is_html = PHP_SAPI !== 'cli';
			$firstLine = sprintf("%s in %s Line %s\n", $e->getMessage(), $e->getFile(), $e->getLine());
			
			$mail = self::$MAIL_ON_ERROR;
			$log = true;

			if ($e instanceof GWF_Exception)
			{
				$mail = true; //$mail && (GWF_Exception::MAIL !== $e->getCode());
				$log = true; //$log && (GWF_Exception::LOG !== $e->getCode());
			}

			# TODO: formatting for log, email, html

			# Send error to admin?
			if ($mail)
			{
				self::sendDebugMail($firstLine . $e->getTraceAsString());
			}

			# Log it?
			if ($log)
			{
				GWF_Log::logCritical($firstLine);
			}
			
			echo self::backtraceException($e, $is_html);
			
		}
		catch (Exception $null) { }
	}

	public static function disableExceptionHandler()
	{
		if (self::$exception === true)
		{
			restore_exception_handler();
			self::$exception = false;
		}
	}

	public static function enableExceptionHandler()
	{
		if (self::$exception === false)
		{
			set_exception_handler(array('GWF_Debug', 'exception_handler'));
			self::$exception = true;
		}
	}

	/**
	 * Send error report mail.
	 * @param string $message
	 */
	public static function sendDebugMail($message)
	{
		return GWF_Mail::sendDebugMail(': PHP Error', $message);
	}

	/**
	 * Get some additional information
	 * @todo move?
	 */
	public static function getDebugText($message)
	{
		$user = "???USER???";
// 		try { $user = GWF_User::current()->displayName(); } catch (Exception $e) { $user = 'ERROR'; }
		$args = array(
			isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'NULL',
			isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : self::getMoMe(),
			isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'NULL',
			isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'NULL',
			isset($_SERVER['USER_AGENT']) ? $_SERVER['USER_AGENT'] : 'NULL',
			$user,
			$message,
			print_r($_GET, true),
			print_r($_POST, true),
			print_r($_COOKIE, true),
		);
		$args = array_map('htmlspecialchars', $args);
		$pattern = "RequestMethod: %s\nRequestURI: %s\nReferer: %s\nIP: %s\nUserAgent: %s\nUser: %s\n\nMessage: %s\n\n_GET: %s\n\n_POST: %s\n\n_COOKIE: %s\n\n";
		return vsprintf($pattern, $args);
	}

	private static function getMoMe()
	{
		return Common::getGetString('mo').'/'.Common::getGetString('me');
	}

	/**
	 * Return a backtrace in either HTML or plaintext. You should use monospace font for html.
	 * HTML means (x)html(5) and <pre> style.
	 * Plaintext means nice for logfiles.
	 * @param string $message
	 * @param boolean $html
	 * @return string
	 */
	public static function backtrace($message='', $html=true)
	{
		return self::backtraceMessage($message, $html, debug_backtrace());
	}
	
	public static function backtraceException(Throwable $e, $html=true)
	{
		$message = sprintf('PHP Exception: %s in %s line %s', $e->getMessage(), self::shortpath($e->getFile()), $e->getLine());
		return self::backtraceMessage($message, $html, $e->getTrace()); # explode("<br>\n", $e->getTraceAsString()));
	}
	
	private static function backtraceMessage($message, $html=true, array $stack)
	{
		$badformat = false;
		
		# Fix full path disclosure
		$message = self::shortpath($message);
		
		if (!GWF_USER_STACKTRACE)
		{
			return $html ? sprintf('<div class="gwf-exception">%s</div>', $message) : $message;
		}
		

		# Append PRE header.
		$back = $html ? ('<div class="gwf-stacktrace">'.PHP_EOL) : '';

		# Append general title message.
		if ($message !== '')
		{
			$back .= $html ? '<em>'.$message.'</em>' : $message;
		}

		$implode = [];
		$preline = 'Unknown';
		$prefile = 'Unknown';
		$longest = 0;
		$i = 0;
		
		foreach ($stack as $row)
		{
			if (is_array($row))
			{
				if ($i++ > 0)
				{
					$function = sprintf('%s%s()', isset($row['class']) ? $row['class'].$row['type'] : '', $row['function']);
					$implode[] = array(
						$function,
						$prefile,
						$preline,
					);
					$len = strlen($function);
					$longest = max(array($len, $longest));
				}
				$preline = isset($row['line']) ? $row['line'] : '?';
				$prefile = isset($row['file']) ? $row['file'] : '[unknown file]';
			}
			else
			{
				$badformat = true;
// 				$longest = max(array(strlen($row), $longest));
// 				preg_match('/[^\w/', $subject)
				$copy[] = trim(str_replace('<br>', '', $row));
			}
		}
		
		if ($badformat)
		{
			
		}

		if (!$badformat)
		{
			$copy = [];
// 			var_dump($implode);
			foreach ($implode as $imp)
			{
				list($func, $file, $line) = $imp;
				$len = strlen($func);
				$func .= str_repeat('.', $longest-$len);
				$copy[] = sprintf('%s %s line %s.', $func, self::shortpath($file), $line);
			}
		}

		$back .= $html ? '<hr/>' : PHP_EOL;
		$back .= sprintf('Backtrace starts in %s line %s.<br/>', self::shortpath($prefile), $preline).PHP_EOL;
		$back .= implode("<br/>\n", array_reverse($copy));
		$back .= $html ? '</div>' : '';
		return $back;
	}

	/**
	 * Strip full pathes so we don't have a full path disclosure.
	 * @param string $path
	 * @return string
	 */
	public static function shortpath($path)
	{
		$path = str_replace(GWF_PATH, '', $path);
		return trim($path, ' /');
	}
}
