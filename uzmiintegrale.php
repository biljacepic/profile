<?php
	include_once 'baza/konekcija.php';
	include_once 'integrali/integrali.php';
	session_start();
	if($_SESSION['korisnikID']==0 || !isset($_SESSION['korisnikID'])){
		header("location:prva.php");
	}else{
	$korisnici_id=$_SESSION['korisnikID'];
	
	$baza=new Baza();
	$db=$baza->getConnection();
	
	
		if($_POST['tabela_name']=="gausova"){
			$i1=new Integrals($db,'gausova');
		}else{
			$i1=new Integrals($db,'simpsonova');
		}
		$rezultat_array = array();
		$stmt1=$i1->UzimanjeIntegrala($korisnici_id);
		if($stmt1->rowCount()>0){
			$rez=$stmt1->fetchall();
			foreach ($rez as $value){
				//array_push($rezultat_array,array("donja": $value['donja'],"gornja":$value['gornja'],"podintfunk":$value['podintfunk'],"resenje":$value['resenje']));
				array_push($rezultat_array,$value);
			}	
			}	
		
		//print_r( $rezultat_array);
		print_r( json_encode($rezultat_array));
	}
?>