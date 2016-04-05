<?php

class db {
	public static $pdo = null;
	public static function pdo() {
		if (empty(self::$pdo)) {
			self::$pdo = new PDO('sqlite:' . __DIR__ . '/../db/survey.sqlite3');
			self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$pdo;
	}
}
