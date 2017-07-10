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
    $joueur = $ge->select('Joueur', array('id' => $id))->getOne();

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
      ))->getOne();

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
      $joueur->dateInscription  = date("Y-m-d H:i:s");
      $joueur->race             = $data['race'];

      if($ge->persist($joueur)){
        // On récupère de-nouveau notre joueur pour avoir toutes les relations qui ont été ajouté par le SGBD
        $joueur = $ge->select('Joueur', array(
          'pseudo'      => $data['pseudo']
        ))->getOne();

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
    $_SESSION['joueur'] = $joueur;
    Routeur::redirect('');
  }

}


?>
