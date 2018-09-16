<?php
namespace Controller;
class DB {

	/**
	 * Die Datenbankverbindungen
	 * @var array
	 */
	private static $connections = array();

	/**
	 * Datenbank Host
	 * @var string
	 */
	private static $host = 'localhost';

	/**
	 * Datenbank Benutzername
	 * @var string
	 */
	private static $username = 'root';

	/**
	 * Datenbank Passwort
	 * @var string
	 */
	private static $password = 'addedlifevalue';

	/**
	 * Erzeugt eine Datenbankverbindung
	 * @param string $database Datenbank Name
	 * @return mysqli
	 */
	public static function getConnection($database) {
		if(!isset(self::$connections[$database])){
			$mysqli = new \mysqli(self::$host, self::$username, self::$password, $database);
			if (mysqli_connect_errno()) {
				printf("Database Connection failed: %s\n", mysqli_connect_error());
				exit();
			}
			self::$connections[$database] = $mysqli;
		}
		return self::$connections[$database];
	}

}
?>