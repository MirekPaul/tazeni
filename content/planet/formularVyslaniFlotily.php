
<?php

//obsluha tlačítka pro vyslání flotily
//validaci řeší javascript, příchozí data budou validní

if (array_key_exists("vyslat", $_POST)) {	
	$novaBodovaHodnota = $planetaAkt[0]["body"] - $_POST["bodHod"];
	
	//aktualizování bodové hodnoty planety
	$zapisDB = $db -> prepare("UPDATE planetlist SET body = ? WHERE nazev = ?");
	$zapisDB ->execute([$novaBodovaHodnota, $_GET["planeta"]]);
	//aktualizování bodové hodnoty planety v objektu
	$planetaAkt[0]["body"] = $novaBodovaHodnota;

	//zapis vyslané flotily do databáze
	$zapisDBd = $db -> prepare("INSERT INTO flotillist (body, rasa, poloha, majitel, cil, rozkaz) VALUES (?, ?, ?, ?, ?, ?)");
	$zapisDBd ->execute([$_POST["bodHod"], $planetaAkt[0]["frakce"], $_GET["planeta"], $planetaAkt[0]["vlastnik"], $_POST["cil"], time()]);	
	//							rasa				body				poloha				majitel						cil			rozkaz	
}

//výpis aktualizovaného stavu bodů na obrazovce
echo "<div class='planetaBody'>Zbývající stav bodů: <span class='bodovaHodnotaPlanety'>" . $planetaAkt[0]["body"] . "</span></div>";

//formulářová struktura vyslání flotily
echo "<div class='vyslaniFlotily'>
				Vyslání flotily:
				<br>
				<form method='post'>
					<label for='bodHod'>Bodová hodnota:</label>
					<input id='bodHod' name='bodHod' type='text' size='5'>b &nbsp
					<label for='cil'>Cíl:</label>
					<select id='cil' name='cil'>
					<option value=''>Zvol cíl</option>";
					foreach ($planety as $planeta){
						//var_dump($planeta);
						echo "<option value='" . $planeta->jmeno . "'>" . $planeta->jmeno . "</option>";
					}					
		echo	"
					</select>					
				
				<br>
				&nbsp<span class='errorLog'></span>
				<button name='vyslat' id='vyslatTl'>Vyslat flotilu</button>
				</form>
			</div>";

?>
	<script>		

		//selektování prvků formuláře
		let bodovaHodnota = document.getElementById("bodHod");
		let cil = document.getElementById("cil");
		let vyslatFlotilu = document.getElementById("vyslat");
		vyslatFlotilu.classList.add("skryj");		
		let errLog = document.querySelector(".errorLog");//pro vypisování chybových hlášek		
		//vyselektování aktuální bodové hodnoty planety - výstup je rovnou číslo
		let bodyPlanety = +document.querySelector(".bodovaHodnotaPlanety").textContent;
				
		//posluchač bodHod
		bodovaHodnota.addEventListener("keyup", kontrolaSpravnostiZadani);

		//posluchač cil
		cil.addEventListener("click", kontrolaSpravnostiZadani);

		
		function kontrolaSpravnostiZadani(){//zunkce zkontroluje správnsot zadaní parametrů pro vyslání flotily a zobrazí potvrzovací tlačítko
			
			let vstupBodyOk = false;
			//kontrola správnosti zadání bodové hodnoty flotily
			if (bodovaHodnota.value != ""){//pokud bude imput prázdny, chybové hlášky se nezobrazí
				if (Number.isInteger(+bodovaHodnota.value) == false) {
					errLog.innerText="Neplatné zadání";
					vstupBodyOk = false;
				}
				//console.log(Number.isInteger(+bodovaHodnota.value));
				else {
					if (+bodovaHodnota.value <= 0) {
						errLog.innerText="Neplatné zadání";
						vstupBodyOk = false;
					}
					else{
						if (+bodovaHodnota.value > bodyPlanety) {
							errLog.innerText="Nedostatek zdrojů";
							vstupBodyOk=false;
							//console.log((+bodovaHodnota.value))
						}
						else errLog.innerText="";
						vstupBodyOk=true;
					}
				}
			}
			else {
				errLog.innerText="";
				vstupBodyOk=false;
			}

			//finální kontrola správnosti a zobrazení tlačítka
			if (cil.value != "" && vstupBodyOk == true) {
				console.log("padlo");
				vyslatFlotilu.classList.remove("skryj");	
			}
			else {
				vyslatFlotilu.classList.add("skryj");	
			}
		}

	</script>