<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
	<title>Vaši integrali zapisani u bazi su:</title>
	<link href="style.css" rel="stylesheet" type="text/css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
	</head>
	<body>
		<div id="wrapping">
	
	
	<?php
		include_once 'header.php';
		include_once 'baza/konekcija.php';
		include_once 'integrali/integrali.php';
		include_once 'korisnik/korisnici.php';
		session_start();
		$baza=new Baza();
		$db=$baza->getConnection();
		$kor=new Korisnik($db);
		if(isset($_POST['username']) && isset($_POST['password'])){
			$kor->user = $kor->srediti_ulaz($_POST['username']);
			$kor->password = $kor->srediti_ulaz(base64_encode($_POST['password']));
			if($kor->prazno()){
				$korisnici_id=0;
			}else{
			
				$kor->upis();
				$korisnici_id=$kor->uzimanjeID();
			}	
			$_SESSION['korisnikID']=$korisnici_id;
		}else  if(isset($_SESSION['korisnikID'])){
			$korisnici_id=$_SESSION['korisnikID'];
		}
		if($korisnici_id==0){
			$_SESSION['korisnikID']=0;
			header("location:prva.php");
		}else{
			$_SESSION['korisnikID']=$korisnici_id;
		?>
	
			<div class="omot">
			<div class="uravni">
			<button id="gaus">Gausovi integrali</button></div>
			<div class="uravni">
			<button id="simpson">Simpsonovi integrali</button></div>
			<div class="uravni"><form action="logout.php">
				<input type="submit" value="Logout"></form></div>
			<div class="uravni">	
			<form action="ponovo.php">
				<input type="submit" value="Novi integral"></form></div>
			</div>	
			<div id="omotac1">
			<div id="tabela1" class="prikaz">
				
			</div>
			<div id="tabela2" class="prikaz">
				
			</div>
			
			</div>
			
		<?php } ?>
			</div>		
		</body>		
		<script>
			function popunjavanje(tabela, br){
				$.ajax({ method: "POST",
				url:'http://localhost/projekatAj/uzmiintegrale.php',
				data: {tabela_name: tabela},
				dataType: 'json',
				success: function(data) { 
				  // alert('Uspeli');
                //var result = JSON.parse(data);
				var result = data;
				var string='<table class="bela">';
				if(tabela=="gausova"){
					string+='<tr><td colspan="3">Gausovom metodom izračunati</td></tr>';
				}else{
					string+='<tr><td colspan="3">Simpsonovom metodom izračunati</td></tr>';
				}
				$.each( result, function( key, value ) { 
				     //alert(value.podintfunk);
					string+='<tr><td class="granica1">'+value.gornja+'</td><td colspan="2"</td></tr>';
					string+='<tr><td><img class="slika2" src="manji.png" alt=""></td><td class="vert">'+value.podintfunk+'</td><td class="vert">= '+value.resenje+'</td></tr>';
					string+='<tr><td class="granica1">'+value.donja+'</td><td colspan="2"></td></tr>';
					string+='<tr><td  class="siva" colspan="3"> </td></tr>';
				});
				string+='</table>';
				/*if(tabela=="gausova"){
				 $("#tabela1").html(string);
				}else{
				$("#tabela2").html(string);	
				}*/
				$("#tabela"+br).html(string);	
				},
				});
			}
			$("#gaus").click(function(){
				var tabela="gausova";
				var br=1;
				popunjavanje(tabela,br);
			});
			$("#simpson").click(function(){
				var tabela="simpsonova";
				var br=2;
				popunjavanje(tabela,br);
			});
		</script>
</html>		
		