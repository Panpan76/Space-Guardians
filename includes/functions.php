<?php

/**
 * Fonction magique appelée lors d'un appèle à une classe pour charger dynamiquement le fichier la contenant
 *
 * @return boolean
 */
function __autoload($classe){
  $fichiers = array(
    'classes/'.$classe.'.php',
    'controlleurs/'.$classe.'.php',
    'entites/'.$classe.'.php',
  );
  foreach($fichiers as $fichier){
    if(file_exists($fichier)){
      require_once $fichier;
      return true;
    }
  }
  return false;
}

/**
 * Permet de recréer des objets passé en session
 *
 * @param Objet   $obj        Objet en session
 * @param string  $to_class   Nom de la classe de l'objet
 *
 * @return objet|false
 */
function cast($obj, $to_class){
  if(class_exists($to_class)){
    $obj_in = serialize($obj);
    $obj_out = 'O:'.strlen($to_class).':"'.$to_class.'":'.substr($obj_in, $obj_in[2] + 7);
    return unserialize($obj_out);
  }
  return false;
}

/**
 * Permet de savoir si l'utilisateur est connecté ou non
 *
 * @return boolean
 */
function estCo(){
  return isset($_SESSION['joueur']) && !empty($_SESSION['joueur']);
}

/**
 * Permet de créer un lien absolu à partir de la route
 *
 * @param string $route Route
 *
 * @return string
 */
function lien($route){
  return URL_HOST.$route;
}

function convertirSecondes($sec){
  if($sec > 1){
    $unites = array('s', 'm', 'h');
    $res = array();
    $n = 0;
    while($sec / 60 >= 1){
      $res[$n] = $sec % 60;
      $sec = floor($sec / 60);
      $n++;
    }
    $str = '';
    for($i = $n; $i > 0; $i--){
      $str .= $res[$i].$unites[$i].' ';
    }
    return $str;
  }
  else{
    return round(($sec * 1000), 2).'ms';
  }
}


?>
