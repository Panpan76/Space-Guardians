<?php
/**
 * Controlleur Alliance
 *
 * @see     Controlleur
 * @author  Panpan76
 * @version 1.0
 */
class ControlleurAlliance extends Controlleur{

  ################
  ### Méthodes ###
  ################

  public function index(){
    $ge = GestionnaireEntite::getInstance();

    $alliances = $ge->select('Alliance', array(), $ge::PARENTS+$ge::ENFANTS)->getAll();

    $this->render('alliance/index.php', 'Alliances', array(
      'alliances' => $alliances
    ));
  }

  /**
   * Voir
   *
   * @return void
   */
  public static function voir($id){
    $ge = GestionnaireEntite::getInstance();

    $alliance = $ge->select('Alliance', array('id' => $id), $ge::PARENTS+$ge::ENFANTS)->getOne();

    $this->render('alliance/voir.php', 'Alliances', array(
      'alliance' => $alliance
    ));
  }



  public function nouvelle(){
    $ge = GestionnaireEntite::getInstance();

    $alliance = new Alliance();
    $message = '';

    if($data = $this->aDonnees()){
      $alliance->nom          = $data['nom'];
      $alliance->tag          = $data['tag'];
      $alliance->description  = $data['description'];

      if($ge->persist($alliance)){
        // On récupère de-nouveau notre alliance pour avoir toutes les relations qui ont été ajouté par le SGBD
        $alliance = $ge->select('Alliance', array(
          'nom' => $data['nom']
        ))->getOne();

        $rang = new RangAlliance();
        $rang->nom = 'Maître';
        $rang->alliance = $alliance;

        if($ge->persist($rang)){
          $rang = $ge->select('RangAlliance', array(
            'alliance'  => $alliance->id,
            'nom'       => $rang->nom
          ))->getOne();

          $joueur = $ge->select('Joueur', array('id' => $_SESSION['joueur']), $ge::PARENTS)->getOne();
          $joueur->alliance = $alliance;
          $joueur->rang = $rang;

          if($ge->persist($joueur)){
            Routeur::redirect("alliance/$alliance->id");
          }
        }

      }
      else{
        $message = "Une erreur est survenue";
      }
    }

    $this->render('alliance/nouvelle.php', 'Créer une alliance', array(
      'message'  => $message,
      'alliance' => $alliance
    ));
  }



}


?>
