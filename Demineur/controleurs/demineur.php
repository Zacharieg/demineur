<?php

require_once PATH_VUE."/vue.php";
require_once PATH_MODELE."/DB.php";
require_once PATH_METIER."/partie.php";

class Demineur {

  private $partie;
  private $vue;
  private $modele;

  function __construct ($m) {
    $this->vue = new Vue();
    $this->modele = $m;
    $this->partie = null;
  }

  /**
   * Return if a game is initialized
   * 
   * @return  boolean
   */
  function existePartie() {
    return ($this->partie != null);
  }

  /**
   * Create a game if a game if no game is saved or load the game
   */
  function creerPartie() {
    if(isset($_POST["nbMine"])) {
      $this->partie = new Partie($_POST["haut"],$_POST["larg"],$_POST["nbMine"], 0, null, null);
      $this->partie->constructTab();
      $this->sauvegarderPartie($this->partie);
    } else if (isset($_SESSION["state"]))
      $this->actualiserPartie();
  }

  /**
   * Load the game
   */
  function actualiserPartie() {
    $this->partie = new Partie($_SESSION["hauteur"],$_SESSION["largeur"],$_SESSION["nbMine"],$_SESSION["state"],$_SESSION["tab"],$_SESSION["cache"]);
  }

  /**
   * Save the game in Session variables
   */
  function sauvegarderPartie($partie) {
    $_SESSION["nbMine"] = $partie->getNbMine();
    $_SESSION["hauteur"] = $partie->getHaut();
    $_SESSION["largeur"] = $partie->getLarg();
    $_SESSION["state"] = $partie->getState();
    $_SESSION["tab"] = $partie->getTab();
    $_SESSION["cache"] = $partie->getCache();
  }

  /**
   * Reveal a cell if the GET variable is set
   */
  function reveler() {
    if(isset($_GET["x"]) && isset($_GET["y"]))
      $this->partie->reveler($_GET["x"],$_GET["y"]);
      $this->sauvegarderPartie($this->partie);
  }

  /**
   * Principal function
   */
  function pageDemineur($pseudo) {
    $this->creerPartie();

    if ($this->existePartie()) {
      $this->reveler();
      if ($this->partie->getState() == 0) {
        $this->vue->demineur($this->partie->getTab(), $this->partie->getCache(), $this->partie->getHaut(), $this->partie->getLarg(), 0);
      } else {
        $this->vue->demineur($this->partie->getTab(), $this->partie->getCache(), $this->partie->getHaut(), $this->partie->getLarg(), $this->partie->getState());
        if ($_SESSION["state"] == 1)
          $this->modele->addPartieGagnee($pseudo);
        else
          $this->modele->addPartiePerdue($pseudo);

        $_SESSION["state"] = null;
      }
    } else
      $this->vue->menu($pseudo, $this->modele->getPartieGagnees($pseudo), $this->modele->getPartieJouees($pseudo));
  }
}

?>
