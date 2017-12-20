<?php
$database = "if17_lutsmeel";
require("../../config.php");
session_start();

//$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]); LOOB UUE ÜHENDUSE ANDMEBAASIGA
//kivi-paber-käärid

//--- --- --- --- --- --- --- --- --- --- --- --- --- LOGIN LEHE FUNKTSIOONID --- --- --- --- --- --- --- --- --- --- --- --- --- --- 

function signIn($email, $password){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT firstname, lastname, id, email, password FROM testusers WHERE email = ?");   
		$stmt->bind_param("s",$email);
		$stmt->bind_result($firstnameFromDb, $lastnameFromDb, $idFromDb, $emailFromDb, $passwordFromDb);
		$stmt->execute();
		
		//kontrollime kasutajat
		if($stmt->fetch()){
			$hash = hash("sha512", $password);
			if($hash == $passwordFromDb){
				$notice = "LOGIN KONTROLL";
				
				//salvestame sessioonimuutujaid
				$_SESSION["firstname"] = $firstnameFromDb;
				$_SESSION["lastname"] = $lastnameFromDb;
				$_SESSION["userId"] = $idFromDb;
				$_SESSION["userEmail"] = $emailFromDb;
				
				//liigume pealehele
				header("Location: avaleht.php");
				exit();
			} else {
				$notice = "Sisestasite vale salasõna!";
			}
		} else {
			$notice = "Sellist kasutajat (" .$email .") ei ole!";
		}
		return $notice;
	}
	
	function signUp($signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupPassword){
		//loome andmebaasiühenduse
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//valmistame ette käsu andmebaasiserverile
		$stmt = $mysqli->prepare("INSERT INTO testusers (firstname, lastname, birthday, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
		echo $mysqli->error;
		//s - string
		//i - integer
		//d - decimal
		$stmt->bind_param("sssiss", $signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupPassword);
		///$stmt->execute();
		if ($stmt->execute()){
			echo "\n Õnnestus!";
		} else {
			echo "\n Tekkis viga : " .$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
	}
	
	function Kontroll($data){
		$data = trim($data);//eemaldab lõpust tühiku, tab vms
		$data = stripslashes($data);//eemaldab "\"
		$data = htmlspecialchars($data);//eemaldab keelatud märgid
		return $data;
	}
	
	//------------------------------------------------------------------------
	
	//kas klõpsati kinnitamise nuppu
	function MELU($nadalapaevKeel){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]); //andmebaasi ühendus 
	$stmt = $mysqli->prepare("SELECT sunday, monday, tuesday, wednesday, thursday, friday, saturday FROM vpnadalapaevad WHERE language = ?");//mida baasist tahame
	$stmt->bind_param("s", $nadalapaevKeel); // see paneb muutujad andmebaasi käsku
	$stmt->execute(); // annab käsu korraldus täita
	$stmt->bind_result($MLyks, $MLkaks, $MLkolm, $MLneli, $MLviis, $MLkuus, $MLseitse); // paneb muutujatesse väärtused
	$stmt->fetch(); // SEE SIIN ON FETCH, MIS ON KURADI OLULINE! KASUTAME SELLEKS ET TÖÖDELDA ROHKEM KUI ÜHTE RIDA
	$nadalapaevad = [];
	array_push($nadalapaevad, $MLyks, $MLkaks, $MLkolm, $MLneli, $MLviis, $MLkuus, $MLseitse); // paigutab andmebaasist saadud andmed järjest listi lõppu 
	$stmt->close(); // sulgeb statementi
	return $nadalapaevad; // lõpetab funktsiooni ja tagastab funktsioonis nädalapäevad
	
	// bind_result on juhtnöör. Kui tehakse fetch (tõmmatakse read alla), siis bind_result paigutab muutujatesse väärtused
	//array_push vajab AINULT MUUTUJAID, ta ei taha juhtnööre nagu näiteks bind result
	}

	function VAIN(){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]); //andmebaasi ühendus 
		$stmt = $mysqli->prepare("SELECT language FROM vpnadalapaevad"); //mida baasist tahame
		$stmt->execute(); //käivitab päringu, tõmbav read alla
		$stmt->bind_result($keel); //paneb keele muutujasse, et kõik keeled jõuaksid listi 
		$keeled = []; //tühi list
		while($stmt->fetch() == true){
		//$stmt->fetch(); Ebavajalik. See on juba while tingimuses (expect the unexcpected)
		array_push($keeled, $keel); //paneb elemendi listi $keeled lõppu 
		} //siin lõppeb while-funktsioon 
		$stmt->close(); //statement close 
		return $keeled; //tagastab keeled 
	}
	
// -------------------------------------------  MÄNGU FUNKTSIOONID  ---------------------------------------------------

    function GAME($kasutaja_valik){
		$tulemus = "";
		$arvuti_valik = mt_rand(1,3);
		echo $arvuti_valik;
		if($kasutaja_valik == "1" and $arvuti_valik == "2"){
			$tulemus = "Arvuti võitis!";
		} elseif($kasutaja_valik == "2" and $arvuti_valik == "3"){
			$tulemus = "Arvuti võitis!";
		} elseif($kasutaja_valik == "3" and $arvuti_valik == "1"){
			$tulemus = "Arvuti võitis!";
		} elseif($kasutaja_valik == "2" and $arvuti_valik == "1"){
			$tulemus = "Võitsid!";
		} elseif($kasutaja_valik == "3" and $arvuti_valik == "2"){
			$tulemus = "Võitsid!";
		} elseif($kasutaja_valik == "1" and $arvuti_valik == "3"){
			$tulemus = "Võitsid!";
		} else {
			$tulemus = "Viik!";
		}
		return $tulemus;
	}
?>