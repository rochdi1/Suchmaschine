<?php
namespace Model;

/**
 * Blacklist
 * @author Abdlatif rochdi<a.rochdi@addedlifevalue.com>
 * @version 0.1
 * @date 18.09.2014
 */
class blacklist implements \iterator {
	/**
	 * Wörter oder link welche nicht gezählt werden sollen
	 * @var array
	 */
	private $list = array();

	/**
	 * Liefert das vorherige items von der List
	 * @return string
	 */

	public function rewind() {
		reset($this -> list);
	}

	/**
	 * Liefert das derzeitige items von der List
	 * @return string
	 */
	public function current() {
		return current($this -> list);
	}

	/**
	 * Liefert die  key_items von der List
	 * @return string
	 */
	public function key() {
		return key($this -> list);
	}

	/**
	 * Liefert das  nächste items von der List
	 * @return string
	 */
	public function next() {
		return next($this -> list);
	}

	/**
	 * Liefert die valiedierung von items
	 * @return string
	 */
	public function valid() {
		return $this -> current() !== false;
	}

	/**
	 * Liefert die valiedierung von items
	 * @return string
	 */
	public function __construct($list = NULL) {
		if (is_array($list)) {
			$this -> list = $list;
		}
	}

	/**
	 * fügt die neu list ein
	 * @return string
	 */
	public function add($string) {
		array_push($this -> list, $string);
	}

	/**
	 * erstellt die list ohne  Blacklist
	 * @return string
	 */

	public function is_in($string) {
		return in_array($string, $this -> list);
	}

	/**
	 * Lädt daten aus der angegeben JSON-Datei und erzeugt daraus ein blacklist-objekt.
	 * @param string $filename Dateiname der zu ladenden JSON-Datei
	 * @return string
	 */

	public static function loadJSONFile($filename) {
		$file = file_get_contents($filename);
		$array = json_decode($file);

		$blacklist = new self($array);
		
		return $blacklist;
	}

}
?>