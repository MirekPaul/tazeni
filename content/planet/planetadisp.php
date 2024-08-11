<?php

	//dotaz do databáze na údaje o planetě
	$planetaAktDB = $db -> prepare("SELECT * FROM planetlist WHERE nazev = ?");
	$planetaAktDB ->execute([$_GET["planeta"]]);
	$planetaAkt = $planetaAktDB -> fetchAll();				

	//údaje o planetě
	echo "<h3>" . $_GET["planeta"] . " ";//název planety
	echo "<span class='hrac'>" . $planetaAkt[0]["vlastnik"] . "</span> ";
	echo $planetaAkt[0]["frakce"] . "</h3>";
	
	//kontrola, zda je přihlášen vlastník této planety
	if ($planetaAkt[0]["vlastnik"]==$_SESSION["player"]){//pokud ano, načteme formulář pro vyslání nové flotily z planety
		require_once "./content/planet/formularVyslaniFlotily.php";
	}
	else {//konstrukce v souboru formularVyslaniFlitily.php si tento řádek musí zobrazovat sama, aby se po odeslání formuláře automaticky aktualizoval
			//pokud se tento soubor, při nespnění podmínky, nenačte je potřeba to udělat zde
		echo "<div class='planetaBody'>Zbývající stav bodů: <span class='bodovaHodnotaPlanety'>" . $planetaAkt[0]["body"] . "</span></div>";
	}

	//flotily u planety
	$flotiOrbitDB = $db -> prepare("SELECT * FROM flotillist WHERE poloha = ?");
	$flotiOrbitDB ->execute([$_GET["planeta"]]);
	$flotiOrbit = $flotiOrbitDB -> fetchAll();

	echo "<center>Flotily na orbitě:</center>";

	echo "<div class='vypis'><table border=1>";
	echo "<tr><th>Frakce</th><th>Cíl</th><th>Vlastník</th><th>Body</th><th>Rozkaz</th></tr>";
	foreach ($flotiOrbit as $flotila){//iterování pole $flotiOrbit obsahující všech flotily na orbitě planety načtených z databáze
		
			echo "<tr><td>" . $flotila["rasa"] . "</td><td>";
			if ($flotila["cil"] == $_GET["planeta"]) {echo "-";} //pokud je flotila na orbitě a nehodlá odletat, pak cíl pro přehlednost nevypíšeme
			else {echo $flotila["cil"];} 
			echo "</td><td>" . $flotila["majitel"] . "</td><td>" . $flotila["body"] . "</td><td>" . date("d-m-Y;h:i:s", $flotila["rozkaz"]) . "</td></tr>" ;		

	}

	echo "</table></div>";