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
    $joueur = $ge->select('Joueur', array('id' => $id))[0];

    $this->render('joueur/voir.php', $joueur->pseudo, array(
      'joueur' => $joueur
    ));
  }



  public function login(){

    $this->render('joueur/login.php', 'Connexion');
  }

  public function inscription(){
    $ge = GestionnaireEntite::getInstance();
    $races = $ge->select('Race');

    $joueur = new Joueur();
    $message = '';

    if($data = $this->aDonnees()){
      $joueur->pseudo           = $data['pseudo'];
      $joueur->motDePasse       = md5($data['mdp']);
      $joueur->dateInscription  = date("Y-m-d H:i:s");
      $joueur->race             = $data['race'];

      if($ge->persist($joueur)){
        // TODO On se connecte
        var_dump($joueur);
        // TODO On redirige
      }
      else{
        $message = "Une erreur est survenue";
      }
      var_dump($data);
    }

    $this->render('joueur/inscription.php', 'Inscription', array(
      'races'   => $races,
      'joueur'  => $joueur,
      'message' => $message
    ));
  }

}


?>
