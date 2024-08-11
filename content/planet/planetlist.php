
<?php

	function vpis($hrac,$list) {//vypíše planety patřící konkrétnímu hráči (buť S nebo G), na této stránce je volána dvakrát
		foreach ($list as $planeta){//iterování pole $planety (přichází jako parametr $list) obsahující instance všech planet načtených z databáze
			if ($planeta->vlastnik == $hrac){//v prvním volání vypisujeme planety hráče S a ve druhém volání hráče G - označení hráče přichází jako parametr $hrac
				echo "<tr><td><a href='index.php?planeta=" . $planeta->jmeno . "'>" . $planeta->jmeno . "</a></td><td>" . $planeta->body . "</td><td>" . $planeta->frakce . "</td></tr>";				
			}

		}
	}

		echo "<div class='vypis'>";//<div> obalující celý obsah stránky planetlist

		echo "<table border=1><tr><th colspan=3>Hráč_S</th><tr>";//vypsání tabulky i s hlavičkou pro hráče S
			vpis("S",$planety);//volání funkce vypisující planety hráče S
		echo "</table>";//ukončovací tag tabulky

		echo "<table border=1><tr><th colspan=3>Hráč_G</th><tr>";
			vpis("G",$planety);
		echo "</table>";
		echo "</div>";
	