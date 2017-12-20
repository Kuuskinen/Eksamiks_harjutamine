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
	if(isset($_POST["confirmButton"])){
	
	//kas on keel sisestatud
			
	if (isset ($_POST["nadalapaevKeel"])){
        if (empty ($_POST["nadalapaevKeel"])){ //kui nädalapäev on sisestamata
            $nadalapaevKeelError =" Sisesta sobiv keel"; //veateade
		} else {
            $nadalapaevKeel = $_POST["nadalapaevKeel"]; //Posti päringu andmetest nädalapäeva keele 
            $notice = MELU($nadalapaevKeel); // <--- MELU(sisend) ja $notice muutub nädalapäevade listiks
            //echo $notice[date("w")];			
	    }
	}
	}
	if(isset($_POST["fiaskoButton"])){
	    $teade = VAIN(); //päring VAIN funktsioonile, mis küsib keeled
		$loendur = count($teade); //loendab, mitu keelt on 
		$loto = mt_rand(0, $loendur-1); // valib saadud vahemikust suvalise keele  
		$valitud_keel = $teade[$loto]; //valitud keel 
		// echo $valitud_keel;
		$teadaanne = MELU($valitud_keel); //teeb saadud keelega päringu MELU funktsioonile, mis annab praeguse nädalapäeva antud keeles
		//echo $teadaanne[$loto];
	}	
?>