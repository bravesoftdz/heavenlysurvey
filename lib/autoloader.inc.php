<?php
/**
 * This allows us to autoload everything so we don't have to type
 * out long requires/includes
 */

spl_autoload_register(function ($className) {
	$paths = array();
	$paths[] = __DIR__;

	$className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
	foreach ($paths as $path) {
		$filename = $path . DIRECTORY_SEPARATOR . $className;
		if (is_readable($filename . '.class.php')) {
			require_once ($filename . '.class.php');
			break;
		}
	}
});
