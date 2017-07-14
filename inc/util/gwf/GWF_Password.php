<?php
/**
 * Password hash
 * @author gizmore
 */
final class GWF_Password
{
	###############
	### Factory ###
	###############
	public static $OPTIONS = [
		'cost' => 11,
	];
	
	public static function create(string $plaintext)
	{
		return new self(password_hash($plaintext, PASSWORD_BCRYPT, self::$OPTIONS));
	}
	
	###############
	### Members ###
	###############
	private $hash;
	
	public function __construct(string $hash)
	{
		$this->hash = $hash;
	}
	
	public function __toString()
	{
		return $this->hash;
	}
	
	public function validate(string $password)
	{
		return password_verify($password, $this->hash);
	}
	
}
