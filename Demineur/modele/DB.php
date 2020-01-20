<?php

// Classe qui gère les accès à la base de données

class DBJoueurs{
	private $connexion;
// Constructeur de la classe

	public function __construct(){
		try{
			$chaine="mysql:host=".HOST.";dbname=".BD;
			$this->connexion = new PDO($chaine,LOGIN,PASSWORD);
			$this->connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e){
			$exception=new ConnexionException("problème de connexion à la base");
			throw $exception;
		}
	}

	public function deconnexion(){
		$this->connexion=null;
	}

  public function login($pseudo, $pswd){
		try{
			$statement = $this->connexion->prepare("select motDePasse from joueurs where pseudo=?;");
      $pseudoParam=$pseudo;
      $statement->bindParam(1, $pseudoParam);

			$statement->execute();
			$result=$statement->fetch(PDO::FETCH_ASSOC);

			if ($result['motDePasse']!=NUll){
				if (password_verify($pswd, $result['motDePasse']))
					return true;
				return false;
			}
			else{
				return false;
			}
		} catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table joueurs");
		}
	}

	/**
	 * Get the games won
	 * @return gamesWon
	 */
	public function getPartieGagnees($pseudo) {
		try{
			$statement = $this->connexion->prepare("select * from parties where pseudo=?;");
			$statement->bindParam(1, $pseudo);

			$statement->execute();
			$result=$statement->fetch(PDO::FETCH_ASSOC);

		if (isset($result['nbPartiesGagnees'])) {
				return $result['nbPartiesGagnees'];
			} return 0;
		} catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table parties");
		}
	}

	/**
	 * Get the game played
	 * @return gamePlayed
	 */
	public function getPartieJouees($pseudo) {
		try{
			$statement = $this->connexion->prepare("select * from parties where pseudo=?;");
			$statement->bindParam(1, $pseudo);

			$statement->execute();
			$result=$statement->fetch(PDO::FETCH_ASSOC);

			if (isset($result['nbPartiesJouees'])) {
				return $result['nbPartiesJouees'];
			} return 0;
		} catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table parties");
		}
	}

	/**
	 * Add 1 to the game Won and 1 to the game loose for the @param pseudo
	 */
	public function addPartieGagnee($pseudo) {
		try{
			$statement = $this->connexion->prepare("select * from parties where pseudo=?;");
			$statement->bindParam(1, $pseudo);

			$statement->execute();
			$result=$statement->fetch(PDO::FETCH_ASSOC);

			if (!isset($result['nbPartiesJouees'])) {
				$statement = $this->connexion->prepare("INSERT INTO parties values(?,1,1)");
				$statement->bindParam(1, $pseudo);
				$statement->execute();
			} else {
				$statement = $this->connexion->prepare("UPDATE parties SET nbPartiesGagnees=:gagne, nbPartiesJouees=:jouee WHERE pseudo=:pseudo");
				$gagne = $result['nbPartiesGagnees'] + 1;
				$jouee = $result['nbPartiesJouees'] + 1;
				$statement->bindParam(':gagne', $gagne);
				$statement->bindParam(':jouee', $jouee);
				$statement->bindParam(':pseudo', $pseudo);
				$statement->execute();
			}

		} catch(PDOException $e){
			$this->deconnexion();
			echo $e;
		}
	}

	/**
	 * Add 1 to the game loose for the @param pseudo
	 */
	public function addPartiePerdue($pseudo) {
		try{
			$statement = $this->connexion->prepare("select * from parties where pseudo=?;");
			$statement->bindParam(1, $pseudo);

			$statement->execute();
			$result=$statement->fetch(PDO::FETCH_ASSOC);

			if (!isset($result['nbPartiesJouees'])) {
				$statement = $this->connexion->prepare("INSERT INTO parties values(?,1,0)");
				$statement->bindParam(1, $pseudo);
				$statement->execute();
			} else {
				$statement = $this->connexion->prepare("UPDATE parties SET nbPartiesJouees=:partie WHERE pseudo=:pseudo");
				$jouee = $result['nbPartiesJouees'] + 1;
				$statement->bindParam(':partie', $jouee);
				$statement->bindParam(':pseudo', $pseudo);
				$statement->execute();
			}

		} catch(PDOException $e){
			$this->deconnexion();
			throw new TableAccesException("problème avec la table parties");
		}
	}

}

?>
