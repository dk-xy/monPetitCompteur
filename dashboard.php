<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'Boisson.php';
require_once 'Database.php';
require_once 'Frigo.php';
require_once 'Utilisateur.php';
require_once 'ConsommationPerso.php';
require_once 'BoissonConsommee.php';

use ch\comem\Database as Database;
use ch\comem\Boisson as Boisson;
use ch\comem\Frigo as Frigo;
use ch\comem\Utilisateur as Utilisateur;
use ch\comem\ConsommationPerso as ConsommationPerso;
use ch\comem\BoissonConsommee as BoissonConsommee;

$db = new Database;
$frigo = new Frigo($db);

//login_success.php  
session_start();
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<html>

<head>
	<title>Bienvenue sur votre dashboard</title>
	<link rel="stylesheet" href="css/Style.css">
	<link rel="stylesheet" href="css/dashboardStyle.css">
</head>

<?php if (isset($_SESSION["login"])) {
	$user = new Utilisateur($db, $_SESSION["id"]);
	$conso = new ConsommationPerso($db, $user->rendId());


?>

	<body>
		
		<div class="dashboard" style="width:500px;">
			<img class="logo" src="img/logoCompteur.png" alt="Logo Mon Petit Compteur" width="300">
			<?php echo '<h3>Bienvenu sur votre dashboard, ' . $_SESSION["login"] . ' ! </h3>';
			//DEBUT DE LA PAGE HTML DU DASHBOARD--------------------------------------------------------------
			?>
			<a href="logout.php">Logout</a>
			<!-- Panneau d'ajout de boisson--------------------------------------------------->
			<h1>Formulaire d'enregistrement de boisson</h1>
			<div class="ajoutBoisson">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
					<p>Veuillez indiquer le type de boissons ainsi que la quantité.</p>
					<div class="selecteurBoisson">
						<?php
						//affiche dynamiquement la liste des boissons
						$frigo->imprimeListeBoissons();
						?>
					</div>
					<div class="compteurDisplay">
						<button type="button" class="compteurMin">-</button>
						<input id="compteurValeur" type="number" name="comptTest" value="0" max="100">
						<button type="button" class="compteurAdd">+</button>
					</div>
					<input type="submit" value="Envoyer">
				</form>
			</div>
			<div class="infoBoisson">
				Note:
				<ul class="note">
					<li>Un verre de boisson non alcoolisée représente 33cl</li>
					<li>Un verre de bière: 25cl</li>
					<li>Un verre de vin: 12.5cl</li>
					<li>Un ver de spiritueux: 3cl</li>
				</ul>  
			</div>
			<div class="separateur"></div>

			<!-- panneau d'affichage de boisson---------------------------------------------->
			<h1>Panneau d'affichage de consommation</h1>
			<div class="affichageConso">
				<p>Veuillez indiquer la périodicité pour l'affichage ainsi que le type souhaité.</p>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
					<input type="radio" id="periodicite" name="periodicite" value="jour">
					<label for="jour">Journalière</label><br>
					<input type="radio" id="periodicite" name="periodicite" value="mois">
					<label for="mois">Mensuelle</label><br>
					<input type="radio" id="periodicite" name="periodicite" value="ans">
					<label for="ans">Annuelle</label><br>
					<select name="typeBoisson" id="typeBoisson">
						<option value=t>Toutes</option>
						<option value=e>Eau</option>
						<option value=s>Boissons sucrée</option>
						<option value=a>Boissons alcoolisées</option>
					</select>
					<input type="submit" value="Envoyer">
				</form>
			</div>




	<?php
	//FIN DE LA PAGE HTML DU DASHBOARD-------------------------------------------------------------------------

	// AJOUT DE LA BOISSON A CONSOMMATION ----------------------------------------------
	if (isset($_POST) && !empty($_POST)) {

		$timestamp = date("Y-m-d H:i:s");
		$conso->consommeBoisson($_SESSION["id"], $_POST['boissons'], $timestamp, $_COOKIE['nbBoisson']);
		//creation de la boisson avec les propriété
		$boissonCree = new BoissonConsommee($db, $_POST['boissons'], $timestamp);
		$conso->ajouteConso($boissonCree);
		echo '<div class="confirmation">votre boisson à bien été ajoutée !</div>';


		//FIN AJOUT BOISSON----------------------------------------------------------------------


		//AFFICHAGE BOISSONS CONSOMMEES----------------------------------------------------------
	}
	if (isset($_GET) && !empty($_GET)) {
		if (isset($_GET['typeBoisson']) && isset($_GET["periodicite"])) {
			$type = $_GET['typeBoisson'];
			$period = $_GET["periodicite"];
			$timestampShow = date("Y-m-d H:i:s");
			$currentUser = $_SESSION['id'];
			$for = "AND utilisateur_id =".$currentUser;
			if(isset($_GET['typeBoisson']) && $_GET['typeBoisson'] != "t") {
				$where = "WHERE boissons.type = '".$_GET['typeBoisson']."'";
			} else {
				$where = "";
			}
			//verification du type de boisson pour la requete

			//switch en fonction de la periode pour le when
			?> 

			<?php
switch ($period) {
    case "jour":
        $when = " AND day(consommation.date) = day(NOW()) AND month(consommation.date) = month(NOW()) AND year(consommation.date) = year(NOW())";
        break;

    case "mois":
        $when = "AND month(consommation.date) = month(NOW())";
        $time_request = "SELECT * FROM consommation INNER JOIN boissons ON consommation.boissons_id = boissons.id " . $where . $when . $for;
        break;

    case "ans":
        $when = "AND month(consommation.date) = month(NOW()) AND year(consommation.date) = year(NOW())";                            
        break;
}
// Actions indépendantes du 'switch'
$time_request = "SELECT * FROM consommation INNER JOIN boissons ON consommation.boissons_id = boissons.id " . $where . $when . $for;
$count_request = "SELECT count(*) FROM consommation INNER JOIN boissons ON consommation.boissons_id = boissons.id " . $where . $when . $for;

$periode_SQL = $db->executerRequete($time_request);

$count = $db->compteResultat($count_request);
$conso->affichageCompteur($count, $_GET['typeBoisson']);
$conso->calculeConsommation($periode_SQL, $_GET['typeBoisson']);
$conso->afficheTableConsommation($periode_SQL);
				}
			}
	// If not logged in
} else {
	header("location:login.php");
}
?>
		</div>
		<footer>
			<script type="text/javascript" src="js/dashboardCompteur.js"></script>
		</footer>

	</body>
</html>