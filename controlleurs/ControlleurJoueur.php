<?php
/**
 * Controlleur Joueur
 *
 * @see     Controlleur
 * @author  Panpan76
 * @version 1.0
 */
class ControlleurJoueur extends Controlleur{

  ################
  ### Méthodes ###
  ################

  /**
   * Voir
   *
   * @return void
   */
  public function voir($id){
    $ge = GestionnaireEntite::getInstance();
    $joueur = $ge->select('Joueur', array('id' => $id), 0b0111)->getOne();

    $this->render('joueur/voir.php', $joueur->pseudo, array(
      'joueur' => $joueur
    ));
  }



  public function login(){
    $ge = GestionnaireEntite::getInstance();

    $message = '';

    if($data = $this->aDonnees()){
      $joueur = $ge->select('Joueur', array(
        'pseudo'      => $data['pseudo'],
        'motDePasse'  => md5($data['mdp'])
      ), $ge::ENFANTS+$ge::PARENTS)->getOne();

      if(is_object($joueur)){
        $this->connexion($joueur);
      }
      else{
        $message = "Identifiants incorrect";
      }
    }

    $this->render('joueur/login.php', 'Connexion', array(
      'message' => $message
    ));
  }

  public function inscription(){
    $ge = GestionnaireEntite::getInstance();
    $races = $ge->select('Race')->getAll();

    $joueur = new Joueur();
    $message = '';

    if($data = $this->aDonnees()){
      $joueur->pseudo           = $data['pseudo'];
      $joueur->motDePasse       = md5($data['mdp']);
      $joueur->race             = $ge->select('Race', array('id' => $data['race']))->getOne();

      if($ge->persist($joueur)){
        // On récupère de-nouveau notre joueur pour avoir toutes les relations qui ont été ajouté par le SGBD
        $joueur = $ge->select('Joueur', array(
          'pseudo'      => $data['pseudo']
        ), $ge::ENFANTS+$ge::PARENTS)->getOne();

        $this->connexion($joueur);
      }
      else{
        $message = "Une erreur est survenue";
      }
    }

    $this->render('joueur/inscription.php', 'Inscription', array(
      'races'   => $races,
      'joueur'  => $joueur,
      'message' => $message
    ));
  }


  public function deconnexion(){
    unset($_SESSION['joueur']);
    Routeur::redirect('');
  }


  /**
   * Permet de gérer la connexion d'un joueur
   *
   * @param Joueur $joueur Joueur devant être connecté
   *
   * @return boolean
   */
  private function connexion($joueur){
    $_SESSION['joueur']   = $joueur->id;
    $_SESSION['planete']  = $joueur->planetes[0]->id;
    Routeur::redirect('');
  }



  /**
   * Permet de gérer la menu pour le joueur connecté
   *
   * @return boolean
   */
  public static function menuHeader(){
    $ge = GestionnaireEntite::getInstance();
    $joueur   = $ge->select('Joueur', array('id' => $_SESSION['joueur']), $ge::PARENTS)->getOne();
    $planete  = $ge->select('Planete', array('id' => $_SESSION['planete']), $ge::PARENTS+$ge::ENFANTS)->getOne();
    $controlleur = new Controlleur();

    $controlleur->render('joueur/menu_header.php', '', array(
      'joueur'  => $joueur,
      'planete' => $planete
    ));
  }


}


?>
