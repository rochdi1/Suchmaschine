<?php
namespace Model;

/**
 * Blacklist
 * @author Abdlatif rochdi<a.rochdi@addedlifevalue.com>
 * @version 0.1
 * @date 06.10.2014
 */
class Ergebnis {
	
	
	
    /**
	 * Title der  Seite
	 * @var string
	 */
	protected $title = '';
	/**
	 * Beschreibung der  Seite
	 * @var string
	 */
	protected $description = '';
	/**
	 * Link der  Seite
	 * @var string
	 */
	protected $url = '';

	public function __construct($title, $description, $url) {

		$this -> setUrl($url);
		$this -> setTitle($title);
		$this -> setDescription($description);

	}

	/**
	 * Setzt die Url von der Text
	 * @param string $url Die Ziel Adresse von der Text
	 * @return void
	 */
	public function setUrl($url) {
		$this -> url = (string)$url;
	}

	/**
	 * Setzt der Title von text
	 * @param string $title
	 * @return void
	 */

	public function setTitle($title) {
		$this -> title = (string)$title;
	}

	/**
	 * Setzt das Beschreibung von der text
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this -> description = (string)$description;
	}

	/**
	 * Erhält das Inhalt von URL
	 * @param String $url Der Link
	 * @return void
	 */

	public function getUrl() {
		return $this -> url;
	}

	/**
	 * Erhält das Inhalt von Title
	 * @param String $Title Der Text
	 * @return void
	 */
	public function getTitle() {
		return $this -> title;
	}

	/**
	 * Erhält das Inhalt von Description
	 * @param String $description
	 * @return void
	 */
	public function getDescription() {
		return $this -> description;
	}

}
?>

