
<?php

	function vpis($hrac) {//vypíše flotily patřící konkrétnímu hráči (buť S nebo G), na této stránce je volána dvakrát

		//dotaz do databáze na flotily konkrétního hráče
		global $db;
		$listFlotilDB = $db -> prepare("SELECT * FROM flotillist WHERE majitel = ?");
		$listFlotilDB -> execute([$hrac]);
		$listFlotil = $listFlotilDB -> fetchAll();
		//var_dump($listFlotil);
		foreach ($listFlotil as $flotila){//iterování pole výstupu z databáze
			echo "<tr><td>" . $flotila["rasa"] . "</td><td>" . $flotila["body"] . "</td><td>" . $flotila["poloha"] . "</td><td>" . $flotila["cil"] . "</td><td>" . date("d-m-Y;h:i:s", $flotila["rozkaz"]) . "</td></tr>";

		}
	}

	function vypisHlavickuTabulky() {
		echo "<tr><th>Frakce</th><th>Body</th><th>Poloha</th><th>Cíl</th><th>Rozkaz</th></tr>";
	}
	
		echo "<div class='vypis'>";//<div> obalující celý obsah stránky flotillist

		echo "<table border=1><tr><th colspan=6>Hráč_S</th><tr>";//vypsání tabulky i s hlavičkou pro hráče S
		vypisHlavickuTabulky();

			vpis("S");//volání funkce vypisující flotily hráče S
		echo "</table>";//ukončovací tag tabulky

		echo "<table border=1><tr><th colspan=6>Hráč_G</th><tr>";
		vypisHlavickuTabulky();
			vpis("G");
		echo "</table>";
		echo "</div>";
	