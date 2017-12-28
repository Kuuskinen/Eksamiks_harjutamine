<?php

$database = "if17_lutsmeel";
require("../../config.php");
session_start();

//SISSELOGIMINE
function signIn($email, $password){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT firstname, lastname, id, email, password FROM diary_users WHERE email = ?");   
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
				header("Location: main.php");
				exit();
			} else {
				$notice = "Sisestasite vale salasõna!";
			}
		} else {
			$notice = "Sellist kasutajat (" .$email .") ei ole!";
		}
		return $notice;
	}
	
	//REGISTREERUMINE
	function signUp($signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupPassword){
		//loome andmebaasiühenduse
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//valmistame ette käsu andmebaasiserverile
		$stmt = $mysqli->prepare("INSERT INTO diary_users (firstname, lastname, birthday, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
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
	
	//AUTODE LISAMINE
	function carAdding($car_number){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO cars (regplate) VALUES (?)");
		echo $mysqli->error;
		$stmt->bind_param("s", $car_number);
		if ($stmt->execute()){
			echo "\n Õnnestus!";
		} else {
			echo "\n Tekkis viga : " .$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
	}
	
	//---------------------------------------------------------------------------------------------------------------------------------------------
	//SÕIDUPÄEVIKUTE LISAMINE
	
	//SÕIDUPÄEVIKU LISAMINE
	function AddDiary($regplate, $Date, $mileage, $comment){
		echo $Date;
		echo $regplate;
		//loome andmebaasiühenduse
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//valmistame ette käsu andmebaasiserverile
		$stmt = $mysqli->prepare("INSERT INTO car_data (regplate, date, mileage, comment) VALUES (?, ?, ?, ?)");
		echo $mysqli->error;
		//s - string
		//i - integer
		//d - decimal
		$stmt->bind_param("ssis", $regplate, $Date, $mileage, $comment);
		///$stmt->execute();
		if ($stmt->execute()){
			echo "\n Õnnestus!";
		} else {
			echo "\n Tekkis viga : " .$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
	}
	
	//SISESTUSE JÄRELEVALVE
	function Kontroll($data){
		$data = trim($data);//eemaldab lõpust tühiku, tab vms
		$data = stripslashes($data);//eemaldab "\"
		$data = htmlspecialchars($data);//eemaldab keelatud märgid
		return $data;
	}
	?>