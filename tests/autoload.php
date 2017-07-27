<?php

/**
 * Fonction magique appelée lors d'un appèle à une classe pour charger dynamiquement le fichier la contenant
 *
 * @return boolean
 */
function __autoload($classe){
  $fichiers = array(
    '../classes/'.$classe.'.php',
    '../controlleurs/'.$classe.'.php',
    '../entites/'.$classe.'.php'
  );
  foreach($fichiers as $fichier){
    if(file_exists($fichier)){
      require_once $fichier;
      return true;
    }
  }
  $sources = glob('../phpunit/*');
  foreach($sources as $source){
    if(preg_match("/$classe/", $source)){
      if(file_exists($source)){
        require_once $source;
        return true;
      }
    }
  }
  return false;
}

?>
