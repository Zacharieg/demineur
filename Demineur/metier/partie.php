<?php

class Partie {
  private $tab;
  private $mines;
  private $hauteur;
  private $largeur;
  private $cache;
  private $state; //0 : in game, 1:win, -1:loose

  function __construct($haut,$larg, $nbmines, $state, $tab, $cache){

    if ($nbmines > $haut*$larg)
      throw new Exception("Il y a trop de mine !");

    $this->hauteur=$haut;
    $this->largeur=$larg;
    $this->mines=$nbmines;
    $this->state = $state;
    $this->tab = $tab;
    $this->cache = $cache;
  }

  /**
   * Construct a random Minesweeper with :
   *  - array tab : contain -1 if mine, number of mine around if not
   *  - array cache : contain 1 if the cell is hide, 0 if not
   */
  function constructTab() {

    $this->state = 0;

    for ($l=1; $l <= $this->largeur; $l++)
      for ($h=1; $h <= $this->hauteur; $h++)
        $this->tab[$l][$h] = 0;

    for ($l=1; $l <= $this->largeur; $l++)
      for ($h=1; $h <= $this->hauteur; $h++)
        $this->cache[$l][$h] = 1;

    for ($i=0; $i < $this->mines; $i++) {
      do {
        $x = rand(1,$this->largeur);
        $y = rand(1,$this->hauteur);
      } while ($this->tab[$x][$y] == -1);
      $this->tab[$x][$y] = -1;
    }

    for ($l=1; $l <= $this->largeur; $l++)
      for ($h=1; $h <= $this->hauteur; $h++)
        if ($this->tab[$l][$h] != -1) {
          $nbmine = 0;
          $beforex = ($l == 1)? 0:-1;
          $afterx = ($l == $this->largeur)? 0:1;
          $beforey = ($h == 1)? 0:-1;
          $aftery = ($h == $this->hauteur)? 0:1;
          for ($i= $beforex; $i <= $afterx; $i++)
             for ($j= $beforey; $j <= $aftery; $j++) {
                if ($this->tab[$l + $i][$h + $j] == -1) {
                  $nbmine = $nbmine + 1;
                }
              }
          $this->tab[$l][$h] = $nbmine;
        }
  }

  /**
   * Reveal a cell (and other around if 0), regenerate the Minesweeper if the first cell reavealed is not a 0
   */
  function reveler($x, $y) {

    if ($this->firstReveal())
      while ($this->tab[$x][$y] != 0)
        $this->constructTab();


    if ($this->cache[$x][$y] == 1)
      if ($this->tab[$x][$y] == -1)
        $this->state = -1;
      else {
        $this->cache[$x][$y] = 0;

        $beforex = ($x == 1)? 0:-1;
        $afterx = ($x == $this->largeur)? 0:1;
        $beforey = ($y == 1)? 0:-1;
        $aftery = ($y == $this->hauteur)? 0:1;
        for ($i= $beforex; $i <= $afterx; $i++)
            for ($j= $beforey; $j <= $aftery; $j++)
              if ($this->tab[$x][$y] == 0)
                $this->reveler($x + $i,$y + $j);

        if ($this->aGagne())
          $this->state = 1;

        if ($this->state != 0)
          for ($l=1; $l <= $this->largeur; $l++)
            for ($h=1; $h <= $this->hauteur; $h++)
              $this->cache[$l][$h] = 0;
      }
  }

  /**
   * Check if the player has won (if the hide cells are only mines)
   * @return hasWon Return True if the player has won, Fasle if not
   */
  function aGagne() {
    $ret = true;

    for ($x=1; $x <= $this->largeur; $x++)
      for ($y=1; $y <= $this->hauteur; $y++)
        if ($this->cache[$x][$y] == 1 && $this->tab[$x][$y] != -1)
          $ret = false;

    return $ret;
  }

  /**
   * Check if the cells are all hide (for detect the first reveal)
   * @return firstReveal Return if all cell are hide
   */
  function firstReveal() {
    $ret = true;

    for ($x=1; $x <= $this->largeur; $x++)
      for ($y=1; $y <= $this->hauteur; $y++)
        if ($this->cache[$x][$y] == 0)
          $ret = false;

    return $ret;
  }

  /**
   * Get the tab of number
   */
  function getTab() {
    return $this->tab;
  }

  /**
   * Get the tab of hide cell
   */
  function getCache() {
    return $this->cache;
  }

  /**
   * Get the State of the game
   */
  function getState() {
    if ($this->aGagne())
          $this->state = 1;
    return $this->state;
  }

  /**
   * Get the height of Minesweeper
   */
  function getHaut() {
    return $this->hauteur;
  }

  /**
   * Get the width of the MineSweeper
   */
  function getLarg() {
    return $this->largeur;
  }

  /**
   * Get the number of mines
   */
  function getNbMine() {
    return $this->mines;
  }
}




?>
