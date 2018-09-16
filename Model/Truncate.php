<?php

function truncate($x) {
    if (!$x) {
       throw new Exception('Problemme bei der verbindung.');
    }
    else return $Crawler = new \Crawler($url);
                  
}

try {
$fp = fopen("http://testserver2/arochdi/search/", "a");
$Crawler = new \Crawler($url);
 

   
} catch (Exception $e) {

if (flock($fp, LOCK_EX)) { // exklusive Sperre

	 fputs ($fp, "Problemme bei der verbindung\n");

  
    flock($fp, LOCK_UN); // Gib Sperre frei
} else {
    echo "Konnte Sperre nicht erhalten!";
}

    echo 'Exception abgefangen: ',  $e->getMessage(), "\n";


}