<?php

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
    while($sec > 0){
      $res[$n] = $sec % 60;
      $sec = floor($sec / 60);
      $n++;
    }
    $str = '';
    for($i = $n-1; $i >= 0; $i--){
      $str .= $res[$i].$unites[$i].' ';
    }
    $str = substr($str, 0, -1);
    return $str;
  }
  else{
    return round(($sec * 1000), 2).'ms';
  }
}


?>
