<?php
require_once PATH_VUE."/vue.php";
require_once PATH_MODELE."/DB.php";

class Login {

  private $vue;
  private $modele;

  function __construct ($m) {
    $this->vue = new Vue();
    $this->modele = $m;
  }

  /**
   * Load the view
  */
  function pageLogin () {
    $this->vue->pageLogin();
  }

  /**
   * Verify if the pseudo and password match in the database
   * @param pseudo The pseudo to verify
   * @param pswd The password to verify
   * @return Exist If the couple exist in the Database
   */
  function verify($pseudo, $pswd) {
    return $this->modele->login($pseudo, $pswd);
  }

  /**
   * Verify if the login's URL variable exist and return if the vraiables are correct
   * @return Exist If the variable exist and are correct
   */
  function verifyConnect($pseudo, $pswd) {
    if ($this->verify($pseudo, $pswd)) {
      $_SESSION[SESSION_LOGIN_NAME] = ($pseudo." ".$pswd);
      return true;
    } else {
      if (isset($_SESSION[SESSION_LOGIN_NAME]))
        unset($_SESSION[SESSION_LOGIN_NAME]);
    }
    return false;
  }

  /**
   * Verify if the login's session variable exist and return if the vraiables are correct
   * @return Exist If the variable exist and are correct
   */
  function verifySession () {
    if (isset($_SESSION[SESSION_LOGIN_NAME])) {
        $param = explode(" ", $_SESSION[SESSION_LOGIN_NAME]);
        if (count($param) == 2)
          return $this->verify($param[0], $param[1]);
        return false;
    }
    return false;
  }

  /**
   * Use the method verifySession and verifyConnect for etablish a connexion
   * @return Connect If the connexion is correct
   */
  function login() {
    if (isset($_POST["pseudo"]) && isset($_POST["pswd"])) {
      return $this->verifyConnect($_POST["pseudo"],$_POST["pswd"]);
    };
    return $this->verifySession();
  }

  /**
   * Get the Pseudo from the actual connexion
   * @return String The pseudo
   */
  function getPseudo () {
    if (isset($_POST["pseudo"]))
      return $_POST["pseudo"];
    if (isset($_SESSION[SESSION_LOGIN_NAME])) {
      $param = explode(" ", $_SESSION[SESSION_LOGIN_NAME]);
      return $param[0];
    }
    return false ;
  }

  /**
   * Destroy the connexion if the GET variable deco is set
   */
  function verifyDeco() {
    if (isset($_GET["deco"])) {
      unset($_SESSION[SESSION_LOGIN_NAME]);
      return true;
    }
    return false;
  }

}
?>
