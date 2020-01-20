<?php

include_once PATH_CONTROLEUR."/login.php";
include_once PATH_CONTROLEUR."/demineur.php";
include_once PATH_MODELE."/DB.php";

class Routeur {

  private $ctrlLogin;
  private $ctrlDemineur;
  private $modele;

  function __construct () {
    $this->modele = new DBJoueurs();
    $this->ctrlLogin = new Login($this->modele);
    $this->ctrlDemineur = new Demineur($this->modele);
  }

  /**
   * Route the controlers
   */
  function routerRequete () {
    if (!$this->ctrlLogin->login() || $this->ctrlLogin->verifyDeco())
      $this->ctrlLogin->pageLogin();
    else
      $this->ctrlDemineur->pageDemineur($this->ctrlLogin->getPseudo());
  }
}

?>
