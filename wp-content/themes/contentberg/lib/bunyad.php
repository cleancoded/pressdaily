<?php
/**
 * Bunyad Framework - factory and pseudo-registry for objects
 * 
 * Basic namespacing utility is provided by this factory for easy changes
 * for a a theme that has different needs than the original one. 
 * 
 * @package Bunyad
 */

class Bunyad_Base {
	
	protected static $_cache = array();
	protected static $_registered = array();

	public static $fallback_path;
	
	/**
	 * Build the required object instance
	 * 
	 * @param string  $object
	 * @param boolean $fresh 	Whether to get a fresh copy; will not be cached and won't override 
	 * 							current copy in cache.
	 */
	public static function factory($object = 'core', $fresh = false)
	{
		if (isset(self::$_cache[$object]) && !$fresh) {
			return self::$_cache[$object];
		}
		
		// Pre-defined class relation?
		if (!empty(self::$_registered[$object]['class'])) {
			$class = self::$_registered[$object]['class'];
		}
		else {

			// Convert short-codes to Bunyad_ShortCodes; core to Bunyad_Core etc.
			$class = str_replace('/', '_', $object);
			$class = apply_filters('bunyad_factory_class', 'Bunyad_' . self::file_to_class_name($class));
		}
		
		// Try auto-loading the class
		if (!class_exists($class)) {
			
			// Locate file in child theme or parent theme lib
			$file = locate_template('lib/' . $object . '.php');

			// Check fallback path if not found in theme
			if (!$file && self::$fallback_path) {
				$file = self::$fallback_path . $object . '.php';

				if (!file_exists($file)) {
					return false;
				}
			}

			if ($file) {
				require_once $file;
			}			
		}
		
		// Class not found
		if (!class_exists($class)) {
			return false;
		}
		
		// Don't cache fresh objects
		if ($fresh) {
			return new $class; 
		}
		
		self::$_cache[$object] = new $class;
		return self::$_cache[$object];
	}
	
	public static function file_to_class_name($file_name)
	{
		return implode('', array_map('ucfirst', explode('-', $file_name)));
	}
	
	/**
	 * Core class
	 * 
	 * @return Bunyad_Core
	 */
	public static function core($fresh = false) 
	{
		return self::factory('core', $fresh);
	}
	
	/**
	 * Global Registry class
	 * 
	 * @return Bunyad_Registry
	 */
	public static function registry($fresh = false) 
	{
		return self::factory('registry', $fresh);
	}
	
	/**
	 * Shortcodes handler
	 *
	 * @param boolean $fresh
	 * @return Bunyad_ShortCodes
	 */
	public static function codes($fresh = false)
	{
		if (is_object(self::options()->shortcodes)) {
			return self::options()->shortcodes;
		}
	}
	
	/**
	 * Options class
	 * 
	 * @return Bunyad_Options
	 */
	public static function options($fresh = false)
	{
		return self::factory('options', $fresh);
	}
	
	/**
	 * Posts related functionality
	 * 
	 * @return Bunyad_Posts
	 */
	public static function posts($fresh = false)
	{
		return self::factory('posts', $fresh);
	}
	
	/**
	 * WordPress Menus related functionality
	 * 
	 * @return Bunyad_Menus
	 */
	public static function menus($fresh = false)
	{
		return self::factory('menus', $fresh);
	}
	
	/**
	 * Reviews related functionality
	 * 
	 * @return Bunyad_Reviews
	 */
	public static function reviews($fresh = false)
	{
		return self::factory('reviews', $fresh);
	}
	
	/**
	 * Markup generator for HTML
	 * 
	 * @return Bunyad_Markup
	 */
	public static function markup($fresh = false)
	{
		return self::factory('markup', $fresh);
	}
	
	/**
	 * Register an object for namespacing or for factory loading
	 * 
	 * @param string $name
	 * @param array  $options  set class to map and init to 
	 */
	public static function register($name, $options = array())
	{	
		// Pre-initialized object?
		if (isset($options['object'])) {
			self::$_cache[$name] = $options['object'];
			
			return $options['object'];
		}
		
		// Need a class at this point
		if (!isset($options['class'])) {
			return;
		}
		
		self::$_registered[$name] = $options;
		
		// Init it ?
		if (!empty($options['init'])) {
			return self::factory($name);
		}
	}
	
	/**
	 * Call registered loaders or registry objects - alias for factory at the moment.
	 * 
	 * Using this instead of a magic method until PHP 5.2 goes away at shared hosts -  
	 * __callStatic() is available in >= 5.3
	 * 
	 * @param string $name
	 * @param array  $fresh
	 */
	public static function get($name, $fresh = false)
	{
		// auto-load it via factory
		return self::factory($name, $fresh);
	}
}