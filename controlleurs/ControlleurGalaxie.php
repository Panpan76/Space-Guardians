<?php
/**
 * Controlleur Galaxie
 *
 * @see     Controlleur
 * @author  Panpan76
 * @version 1.0
 */
class ControlleurGalaxie extends Controlleur{

  ################
  ### Méthodes ###
  ################

  public function index($id){
    $ge = GestionnaireEntite::getInstance();

    $galaxie = $ge->select('Galaxie', array('id' => $id))->getOne();
    $planete = $ge->select('Planete', array('id' => $_SESSION['planete']), $ge::PARENTS+$ge::ENFANTS)->getOne();
    $joueur = $ge->select('Joueur', array('id' => $_SESSION['joueur']), $ge::ENFANTS+$ge::PARENTS)->getOne();

    $this->render('galaxie/index.php', $galaxie->nom, array(
      'galaxie' => $galaxie,
      'joueur'  => $joueur,
      'planete' => $planete
    ));
  }

  /**
   * Voir
   *
   * @return void
   */
  public static function voir($id){
    $ge = GestionnaireEntite::getInstance();

    $galaxie = $ge->select('Galaxie', array('id' => $id))->getOne();

    $degrades = array(
      1 => '#FFFFFF',
      2 => '#FFF4F4',
      3 => '#FFE9E9',
      4 => '#FFDEDE',
      5 => '#FFD3D3',
      6 => '#FFC8C8',
      7 => '#FFBDBD',
      8 => '#FFB2B2',
      9 => '#FFA7A7',
      10 => '#FF9C9C',
      11 => '#FF9191',
      12 => '#FF8686',
      13 => '#FF7B7B',
      14 => '#FF7070',
      15 => '#FF6565',
      16 => '#FF5A5A',
      17 => '#FF4F4F',
      18 => '#FF4444',
      19 => '#FF3939',
      20 => '#FF2E2E',
      21 => '#FF2323',
      22 => '#FF1818',
      23 => '#FF0D0D',
      24 => '#FF0202',
      25 => '#FF0000',
      26 => '#FF0000',
    );

    $joueur = $ge->select('Joueur', array('id' => $_SESSION['joueur']), $ge::ENFANTS+$ge::PARENTS)->getOne();

    $systemes = $ge->select('SystemeSolaire', array(), $ge::ENFANTS+$ge::PARENTS)->getAll();

    $controlleur = new Controlleur();

    $controlleur->render('galaxie/voir.php', $galaxie->nom, array(
      'degrades'  => $degrades,
      'systemes'  => $systemes,
      'joueur'    => $joueur
    ));
  }



  public function nouvelle(){
    $ge = GestionnaireEntite::getInstance();

    set_time_limit(0);

    $galaxie = new Galaxie();
    $galaxie->nom = 'Super galaxie';
    $galaxie->largeur = 2000;
    $galaxie->hauteur = 2000;

    $ge->persist($galaxie);

    // Routeur::redirect("galaxie/$galaxie->id");
        $this->render('galaxie/voir.php', $galaxie->nom, array(
          'galaxie' => $galaxie
        ));
  }



}


?>
