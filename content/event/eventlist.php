<?php

//echo "eventlits";
echo "<table border=1 class='eventlist'>";
echo "<tr><th>Event</th><th>Poloha</th><th>Body</th><th>Frakce</th><th>Vlastník</th><th>Rozkaz</th></tr>";

//PvF
$dotazDB = $db -> prepare("SELECT * FROM planetlist ORDER BY poradi");
$dotazDB -> execute();
$dotaz = $dotazDB -> fetchAll();
echo "<tr><td rowspan=" . count($dotaz)+1 . "'>PvF</td></tr>";
foreach ($dotaz as $polozka){
	echo "<tr><td>" . $polozka["nazev"] . "</td><td>" . $polozka["body"] . "</td><td>" . $polozka["frakce"] . "</td><td class='nastred'>" . $polozka["vlastnik"] . "</td><td class='nastred'>-</td></tr>";
}

//FvF
$dotazDB = $db -> prepare("SELECT * FROM flotillist WHERE poloha=cil ORDER BY rozkaz");
$dotazDB -> execute();
$dotaz = $dotazDB -> fetchAll();

echo "<tr><td rowspan=" . count($dotaz)+1 . "'>FvF</td></tr>";
foreach ($dotaz as $polozka){
	echo "<tr><td>" . $polozka["poloha"] . "</td><td>" . $polozka["body"] . "</td><td>" . $polozka["rasa"] . "</td><td class='nastred'>" . $polozka["majitel"] . "</td><td class='nastred'>" . date("d-m-Y;h:i:s", $polozka["rozkaz"]) . "</td></tr>";
}

//FvP
echo "<tr><td rowspan=" . count($dotaz)+1 . "'>FvP</td></tr>";
foreach ($dotaz as $polozka){
	echo "<tr><td>" . $polozka["poloha"] . "</td><td>" . $polozka["body"] . "</td><td>" . $polozka["rasa"] .  "</td><td class='nastred'>" . $polozka["majitel"] . "</td><td class='nastred'>" . date("d-m-Y;h:i:s", $polozka["rozkaz"]) . "</td></tr>";
}

//transit

$dotazDB = $db -> prepare("SELECT * FROM flotillist WHERE NOT poloha = cil ORDER BY rozkaz");
$dotazDB -> execute();
$dotaz = $dotazDB -> fetchAll();

echo "<tr><td rowspan=" . count($dotaz)+1 . "'>transit</td></tr>";
foreach ($dotaz as $polozka){
	echo "<tr><td>" . $polozka["poloha"] . " -> " . $polozka["cil"] . "</td><td>" . $polozka["body"] . "</td><td>" . $polozka["rasa"] . "</td><td class='nastred'>" . $polozka["majitel"] . "</td><td class='nastred'>" . date("d-m-Y;h:i:s", $polozka["rozkaz"]) . "</td></tr>";
}

echo "</table>";
//var_dump(count($dotaz));