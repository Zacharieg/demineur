<?php

class Vue {

  /**
   * Display the Header
   */
  function myHeader () {
    ?>
    <head>
      <meta charset="UTF-8">
      <title>Demineur</title>
      <link rel="stylesheet" type="text/css" href="vue/style.css" media="all"/>
    </head>
    <body>
    <div class="area" >
            <ul class="circles">
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
            </ul>
    </div >
    <?php
  }

  /**
   * Display the footer
   */
  function footer () {
    ?>
    <div class="footer"> Créé par Zacharie Guet</div>
    <?php
  }

  /**
   * Display the login page
   */
  function pageLogin () {
    $this->myHeader();
    ?>
    <h1>Connectez vous</h1>
    <div class="login">
      <form method="post" action="index.php">
        <input type="text" name="pseudo" placeholder="Pseudo" required/>
        <input type="text" name="pswd" placeholder="Mot de passe" required/>
        <input type="submit" name="soumettre" value="Connexion"/>
      </form>
    </div>
  <?php
  $this->footer();
  }

  /**
   * Display the menu page for the @param pseudo with @param gamesWon and @param gamesPlayed
   */
  function menu ($pseudo, $gagne, $jouee) {
    $this->myHeader();
    ?>
      <a href="index.php?deco=1" class="deconnexion"> Deconnexion</a>
      <h1>Bonjour <?php echo $pseudo ?></h1>
      <div class="menu">
      <div class="p">
        <div class="p-title">Score</div>
        <div class="p-corps">
          Parties Jouées : <b> <?php echo $jouee ?> </b> </br>
          Partie Gagnées : <b><?php echo $gagne ?></b>
          <div class="pourcentage"> <?php if ($jouee != 0) echo round($gagne/$jouee*100) ?>%</div>
        </div>
      </div>
      <div class="p">
      <div class="p-title">Commencer une partie</div>
      <form method="post" action="index.php" class="p-corps">
        <input type="number" name="haut" placeholder="Hauteur" required/> </br>
        <input type="number" name="larg" placeholder="Largeur" required/> </br>
        <input type="number" name="nbMine" placeholder="Nombre de Mine" required/> </br>
        <input type="submit" value="Commencer"/>
      </form>
      </div>
    </div>
  <?php
  $this->footer();
  }

  /**
   * Display the game page with the parametre of the game
   */
  function demineur ($tab, $cache, $haut, $larg, $state) {
    $this->myHeader();
    ?>
        <?php if ($state == 0)
                echo "<h1>Jouez !</h1>";
              else if ($state == 1)
                echo "<h1>Vous avez gagné !</h1>
                <a class='retour' href=''> Retourner au menu </a>";
              else
                echo "<h1>Vous avez perdu !</h1>
                <a class='retour' href=''> Retourner au menu </a>";
                ?>
        <table class="jeu">
        <?php
          for ($x=1; $x <= $larg; $x++) {
            echo "<tr>";
            for ($y=1; $y <= $haut; $y++) {
              echo $this->afficherCase($cache[$x][$y], $tab [$x][$y], $x,$y);
            }
            echo "</tr>";
          }
        ?>
        <table>
      <br/>
      <br/> <?php
      $this->footer();
  }

  /**
   * Display a cell depending the @param cache if the cell is hiding, @param numcase the value of the cell, @param x and @param y the position, 
   */
  function afficherCase ($cache, $numCase, $x, $y) {
    if ($cache == 1) {
      return "<th> <a href='index.php?x=".$x."&y=".$y."'><div class='cache'></div></a></th>";
    } else if ($numCase != -1){
      return "<th class='decouvert'>".$numCase."</th>";
    } else {
      return "<th class='bombe'></th>";
    }
  }
}
?>
