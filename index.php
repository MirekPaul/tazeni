<?php
	//aktivování proměnných session
		session_start();

	
	//obsluha tlačítek odkazů a načítání stránek
	$planetlist = null;
	$pravidla = null;
	$flotillist = null;	
	$aktivniPlaneta = null;
	$eventlist = null;
	if (array_key_exists("player",$_SESSION) == false) $_SESSION = ["player" => "nepřihlášen"];//pro případ prvního spuštění stránky, kdy se do session ještě nic nezapsalo
	
	//kliknutím na tlačítko odkazu dorazí v proměnné $_GET požadavek na stránku, která se má zobrazit
	if (array_key_exists("planetlist",$_GET)) $planetlist=true;
	if (array_key_exists("flotillist",$_GET)) $flotillist=true;
	if (array_key_exists("pravidla",$_GET)) $pravidla=true;
	if (array_key_exists("eventlist",$_GET)) $eventlist=true;
	if (array_key_exists("planeta",$_GET)) $aktivniPlaneta = $_GET["planeta"];
	//přihlášení uživatele prozatím probíhá automaticky bez mezi kroku pro zadání hesla
	if (array_key_exists("s",$_GET)) $_SESSION = ["player" => "S"];
	if (array_key_exists("g",$_GET)) $_SESSION = ["player" => "G"];
	

	//načtení databáze
	$db = new PDO('sqlite:./content/tazeni.db');

		//načtení planetlistu do struktury objektu
		$planetlistDB = $db -> query("SELECT * FROM planetlist");
		$planetlistDB ->execute();
		$planetlistArray = $planetlistDB -> fetchAll();

		//objekt planeta bude uchovavat instalnce všech planet pro rychlý přístup bez nutnosti dotazování do databáze
		class Planeta {
			public $jmeno;
			public $body;
			public $frakce;
			public $vlastnik;
	
			function __construct($getJmeno,$getBody,$getFrakce,$getVlastnik)	{
				$this -> jmeno = $getJmeno;
				$this -> body = $getBody;
				$this -> frakce = $getFrakce;
				$this -> vlastnik = $getVlastnik;
			}
		}
	
		//pole instancí objektu jednotlivých planet
		$planety = [
			$tyran = new Planeta($planetlistArray[0][1],$planetlistArray[0][0],$planetlistArray[0][2],$planetlistArray[0][3]),
			$arcadia = new Planeta($planetlistArray[1][1],$planetlistArray[1][0],$planetlistArray[1][2],$planetlistArray[1][3]),
			$gorkamorka = new Planeta($planetlistArray[2][1],$planetlistArray[2][0],$planetlistArray[2][2],$planetlistArray[2][3]),			
		];
/*
		//načtení flotillistu
		$flotillistDB = $db -> query("SELECT * FROM flotillist");
		$flotillistDB ->execute();
		$flotillistArray = $flotillistDB -> fetchAll();
		//var_dump($flotillistArray);
*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="./content/scanmi.png" type="image/x-icon">
	<title>.3 tažení</title>
	<link rel="stylesheet" href="./content/lokalniCSS.css">
</head>
<body>
	<header>
		<img src="./content/scan.png" width="100" align="left">
		<div>TENTO FAN ART PROJEKT SLOUŽÍ VÝHRADNĚ SOUKROMÝM ÚČELŮM A NENÍ URČEN PRO ŠIRŠÍ VEŘEJNOST</div>
		<div></div>
		<br>
		<!--PRVNÍ ŘÁDEK HLAVIČKY-->
		<form class="nabidkaPrvni" method="get">
			<a href="index.php?pravidla=">PRAVIDLA</a>
			<a href="index.php?planetlist=">PLANETLIST</a>
			<a href="index.php?flotillist=">FLOTILLIST</a>
			<a href="index.php?eventlist=">EVENTLIST</a>
			<a href="index.php?s=">HRÁČ S</a>
			<a href="index.php?g=">HRÁČ G</a>			
		</form>

		<!--DRUHÝ ŘÁDEK HLAVIČKY-->
		<form class="nabidkaDruha" method="get">
			<?php				
				echo "HRÁČ " . $_SESSION["player"] . " ";

				//vypsání seznamu planet přihlášeného hráče (vypsání se provede přímým dotazem do databáze, nikoliv přes objekt)
				$planetyHraceDB = $db -> query("SELECT nazev FROM planetlist WHERE vlastnik = ?");
				$planetyHraceDB ->execute([$_SESSION["player"]]);
				$planetyHrace = $planetyHraceDB -> fetchAll();				
				foreach ($planetyHrace as $planetaHrace){//samotné vypsání					
					echo " <a href='index.php?planeta=" . $planetaHrace["nazev"] . "'>" . $planetaHrace["nazev"] . "</a>";
				}			
			?>
		</form>
	</header>

<br>
<!-- HLAVNÍ OBSAH-->
 <div class="kontejner">
	<div class="obsah">
		<?php //blok pro zobrazení hlavního obsahu
			if ($planetlist==true) require_once "./content/planet/planetlist.php";
			if ($pravidla==true) require_once "./content/pravidla.php";
			if ($flotillist==true) require_once "./content/flotil/flotillist.php";
			if ($aktivniPlaneta != null) require_once "./content/planet/planetadisp.php";
			if ($eventlist==true) require_once "./content/event/eventlist.php";
		?>
	</div>
 </div>
</body>
</html>