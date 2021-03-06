<?php
/**
 * Classe Routeur
 *
 * Permet de gérer la routage de l'application
 *
 * @author  Panpan76
 * @version 1.0
 */
class Routeur{

  #################
  ### Variables ###
  #################

  /**
   * @var Routeur|null $instance   Instance de la classe Routeur
   */
  private static $instance = null;

  /**
   * @var array|null  $routes     Routes possibles utilisées par le Routeur
   */
  private $routes;



  ################
  ### Méthodes ###
  ################

  /**
   * Constructeur de la classe Routeur
   *
   * Visibilité privée pour utilisation du patron de conception Singleton
   *
   * @return Routeur
   */
  private function __construct(){

    // On charge le fichier de routes
    $this->setRoutes(FICHIER_ROUTES);
    return $this;
  }


  /**
   * Permet de récupérer l'instance de Routeur
   *
   * Cette méthode met en place le patron de conception Singleton
   *
   * @return Routeur
   */
  public static function getInstance(){
    // Si aucune instance n'existe
    if(is_null(self::$instance)){
      self::$instance = new self();
    }
    // On retourne l'instance de Routeur
    return self::$instance;
  }


  /**
   * Permet de charger une page
   *
   * @param string      $url    URL demandé par l'utilisateur
   * @param array|null  $data   Données liées à la requête
   *
   * @return void
   */
  public function charge($url, $data = null){
    // On récupère les informations de la page
    if(($infos = $this->getPage($url)) != null){

      // On définit notre controlleur
      $controlleur = new $infos['controlleur']();
      $controlleur->setData($data); // Pour les données POST et GET

      // On récupère la méthode est les paramètres de la route
      $methode = $infos['methode'];
      $params = $infos['params'];

      // On stock tout ça en global
      $global = Glob::getInstance();
      $global->route        = $infos['route'];
      $global->controlleur  = $infos['controlleur'];
      $global->methode      = $infos['methode'];
      $global->arguments    = $infos['params'];

      // On appelle la méthode du controlleur avec les paramètres
      call_user_func_array(array($controlleur, $methode), $params);
    }
    return null;
  }

  /**
   * Permet d'effectuer une redirection
   *
   * @param string $route Router à charger
   *
   * @return void
   * @codeCoverageIgnore
   */
  public static function redirect($route){
    $route = URL_HOST.$route;
    header("Location: $route");
  }


  /**
   * Permet de récupérer les routes depuis un fichier
   *
   * Charge les routes possibles pour le Routeur à partir d'un fichier
   *
   * @param string  $fichier  Emplacement du fichier contenant les routes
   *
   * @return void
   */
  public function setRoutes($fichier){
    // On vérifie que le fichier existe, sinon on stop
    if(!file_exists($fichier)){
      return false;
    }

    // On initialise les routes à null
    $routes = array();

    // On récupère le type de fichier
    $elements = explode('.', $fichier);
    $type     = end($elements);
    // On parse le fichier en entrée selon son type
    switch($type){
      // TODO YAML file

      case 'php':
        include $fichier;
        break;
    }

    $this->routes = $routes;
  }

  /**
   * Permet de récupéer les diverses informations nécessaires à la redirectino
   *
   * @return array|null   Liste des informations nécessaires à la redirection
   */
  private function getPage($url){
    // Pour chaque route
    foreach($this->routes as $route => $infos){
      // Si la route correspond à la requête de l'utilisateur
      if(preg_match("#^$route/?$#", $url, $matches)){
        // On pense à récupérer les paramètres de la route via $matches

        $infos['route'] = $this->cleanRoute($route);
        $infos['params'] = array();
        // On ajoute les paramètres au retour
        for($i = 1; $i < count($matches); $i++){
          $infos['params'][] = $matches[$i];
        }
        return $infos;
      }
    }
    // TODO Aucune correspondance trouvé -> page 404
    return null;
  }

  /**
   * Permet d'avoir des routes lisibles
   *
   * @param string $route
   *
   * @return string
   */
  private function cleanRoute($route){
    $route = str_replace('$', '', $route);
    $route = preg_replace('/(\(.*\))/', '{$var}', $route);
    $route = str_replace('^', '', $route);
    $route = str_replace('\/', '/', $route);
    return $route;
  }
}

?>
