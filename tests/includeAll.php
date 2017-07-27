<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'entites/EntiteMere.php';

$sources = array('classes', 'controlleurs', 'entites');

foreach($sources as $source){
  $srcs = glob("$source/*.php");
  foreach($srcs as $src){
    require_once $src;
  }
}

?>
