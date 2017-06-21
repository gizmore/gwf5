<?php
/**
 * Often used stuff.
 * @author gizmore
 * @version 5.00
 * @since 1.00
 */
final class Common
{
	##################
	### Get / Post ###
	##################
	public static function getGet(string $var, $default=null) { return isset($_GET[$var]) ? $_GET[$var] : $default; }
	public static function getGetInt(string $var, $default=0) { return isset($_GET[$var]) && is_string($_GET[$var]) ? ((int)$_GET[$var]) : $default; }
	public static function getGetFloat(string $var, $default=0) { return isset($_GET[$var]) && is_string($_GET[$var]) ? ((float)$_GET[$var]) : $default; }
	public static function getGetString(string $var, $default=null) { return isset($_GET[$var]) && is_string($_GET[$var]) ? $_GET[$var] : $default; }
	public static function getGetArray(string $var, $default=[]) { return (isset($_GET[$var]) && is_array($_GET[$var])) ? $_GET[$var] : $default; }

	public static function getPost(string $var, $default=null) { return isset($_POST[$var]) ? ($_POST[$var]) : $default; }
	public static function getPostInt(string $var, $default=0) { return isset($_POST[$var]) ? ((int)$_POST[$var]) : $default; }
	public static function getPostFloat(string $var, $default=0) { return isset($_POST[$var]) ? ((float)$_POST[$var]) : $default; }
	public static function getPostString(string $var, $default=null) { return isset($_POST[$var]) ? (string)$_POST[$var] : $default; }
	public static function getPostArray(string $var, $default=[]) { return (isset($_POST[$var]) && is_array($_POST[$var])) ? $_POST[$var] : $default; }

	public static function getRequest(string $var, $default=null) { return isset($_REQUEST[$var]) ? ($_REQUEST[$var]) : $default; }
	public static function getRequestInt(string $var, $default=0) { return isset($_REQUEST[$var]) ? ((int)$_REQUEST[$var]) : $default; }
	public static function getRequestFloat(string $var, $default=0.0) { return isset($_REQUEST[$var]) ? ((float)$_REQUEST[$var]) : $default; }
	public static function getRequestString(string $var, $default=null) { return isset($_REQUEST[$var]) ? (string)$_REQUEST[$var] : $default; }
	public static function getRequestArray(string $var, $default=[]) { return (isset($_REQUEST[$var]) && is_array($_REQUEST[$var])) ? $_REQUEST[$var] : $default; }

	public static function getForm(string $var, $default=null) { $vars = self::getRequestArray('form'); return isset($vars[$var]) ? $vars[$var] : $default; }
	public static function getFormString(string $var, $default=null) { return (string)self::getForm($var, $default); }
}
