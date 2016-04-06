<?php

/** 
 * This helper class will automaticaly get the PDO object for survey.sqlite3
 */
class db {
	public static $pdo = null;

	/** 
	 * Grab the pdo object if it exists, otherwise, create a new one
	 * @return object pdo object
	 */
	public static function pdo() {
		if (empty(self::$pdo)) {
			self::$pdo = new PDO('sqlite:' . __DIR__ . '/../db/survey.sqlite3');
			self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$pdo;
	}
}
