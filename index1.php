<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
	<title>Numeričke metode</title>
	<link href="style.css" rel="stylesheet" type="text/css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
	</head>
	<body>
		<?php
	session_start();
	
	if(!isset($_SESSION['korisnikID'])|| $_SESSION['korisnikID']==0){
		$_SESSION['korisnikID']=0;
		header("location:prva.php");
	}else{
		if (!isset($_SESSION['rezultat']) || !isset($_SESSION['donja']) || !isset($_SESSION['gornja']) ||  !isset($_SESSION['int']) ){
		$_SESSION['rezultat']=0;
		$_SESSION['donja']=0;
		$_SESSION['gornja']=0;
		$_SESSION['int']="dx";
		}
	
	?>
		<div id="wrapping">
		<?php include_once 'header.php';?> 
		<div class="opis">Izračunavanje odredjenih integrala Gausovom ili Simpsonovom metodom</div>
		<form action="transformClass.php" method="get">
			<span>Unesite gornju granicu:</span><input type="text" class="granica" id="b" name="b" value ="<?php echo $_SESSION['gornja'] ?>" pattern="-?[0-9]+\.[0-9]+|-?[0-9]+"><br>
			
			<div id="znak"><img  id="slika" src="integral.jpg" alt=""><input type="text" id="podint"   name="podint" value ="<?php echo $_SESSION['int'] ?>" pattern="[|x|dx|sin|cos|tan|abs|pow|acos|asin|atan|exp|log10|deg|rad|sqrt|ceil|floor|round|log|pi|+|(|)|.|,|0-9|*|\-|\/]{0,}"  >
			<span id="pom">=<?php echo $_SESSION['rezultat']; ?></span>
			</div>
			<!--<div ><table id="pom"><tr><td></td></tr></table></div>-->
			<span>Unesite donju granicu:</span><input type="text" class="granica" id="a" name="a" value ="<?php echo $_SESSION['donja'] ?>" pattern="-?[0-9]+\.[0-9]+|-?[0-9]+"><br>
			<span> Izaberite kojo metodom želite da izračunate integral</span><br>
			<input type="radio" name="metoda" value="Gausova">Gausova<br><input type="radio" name="metoda" value="Simsonova">Simsonova<br>

			<br><br>
			<input type="submit" value="Izracunaj">
		</form>
		<div class="omot">
		<div class="uravni">
		<form action="ponovo.php" method="get">
		<input type="submit" value="novi unos">
		</form>
		</div>
		<div class="uravni">
		<form action="logout.php" method="get">
		<input type="submit" value="Logout">
		</form>
		</div>
		<div class="uravni">
		<form action="stampajSaJquery.php" method="post">
			<input type="submit" value="Štampaj moje integrale">
			</form>
		</div>
		</div>
		<div id="zaFunk">Ovde ce biti smestena slova za unos funkcija na primer ako zelite da unesete kvadratni koren pritisnete dugme Koren i pise vam  u posebnom polju sta da unesete u polje za funkciju koren...Za gornju i donju granicu upisite konkretne brojeve ne na primer 1/2 vec 0.5, ne pi vec 3.14 ,ne pi/2 vec 1.57 , ne 2pi 6.28 itd.
			<table border="1px">
				<tr><td colspan="5" id="unos"></td></tr>
				<tr>
					<td><button class= "dugme" onclick="unesi('sqrt(x)')">Koren</button></td>
					<td><button class= "dugme" onclick="unesi('log(x)')">Logoritam ln</button></td>
					<td><button class= "dugme" onclick="unesi('exp(x)')">Eksponencijalna</button></td>
					<td><button class= "dugme" onclick="unesi('abs(x)')">Apsolutna</button></td>
					<td><button class= "dugme" onclick="unesi('sin(x)')">sinus</button></td>
				</tr>
				<tr>
					<td><button class= "dugme" onclick="unesi('(1+x)')">1+x</button></td>
					<td><button class= "dugme" onclick="unesi('log10(x)')">Logoritam sa osnovom 10 </button></td>
					<td><button class= "dugme" onclick="unesi('(1+2*x)')">1+2x</button></td>
					<td><button class= "dugme" onclick="unesi('cos(x)')">cosinus</button></td>
					<td><button class= "dugme" onclick="unesi('atan(x)')">arcustangens</button></td>
				</tr>
				<tr>
					<td><button class= "dugme" onclick="unesi('3.14')">broj pi</button></td>
					<td><button class= "dugme" onclick="unesi('2/x')">2:x</button></td>
					<td><button class= "dugme" onclick="unesi('x*x)')">x*x</button></td>
					<td><button class= "dugme" onclick="unesi('1/2 u funkciju a 0.5 za granicu')">1/2</button></td>
					<td><button class= "dugme"  onclick="unesi('tan(x)')">tangens</button></td>
				</tr>
			</table>
		</div>
		
		<br>
		<br>
		<?php 
			if(is_numeric($_SESSION['rezultat'])&& !is_nan($_SESSION['rezultat']) && is_finite($_SESSION['rezultat'])){?>
		<button id="kreni" onclick="crtaj()">crtaj</button><br>
		<canvas id="xy-graph" width="535" height="400">
		CANVAS NOT SUPPORTED IN THIS BROWSER!
		</canvas>
			<?php } ?>	
		
		</div>
		
	</body>
	</html>
	<?php  }?>
		<script>
			function unesi(znak){
				document.getElementById('unos').innerHTML=znak;
			}
			
			function MaxX() {
			  return 10 ;
			}
			function MinX() {
			  return -10 ;
			}
			var Canvas = document.getElementById('xy-graph');  
			var Ctx = null ;

			var Width = Canvas.width ;
			var Height = Canvas.height ;

			// Vraca gornju logicku granicu:
			function MaxY() {
			  return MaxX() * Height / Width;
			}

			// Vraca doljnju logicku granicu:
			function MinY() {
			   return MinX() * Height / Width;
			}


			// Vraca fizicke x-koordinate na osnovul logickih x-koordinata:
			function XC(x) {
			  return (x - MinX()) / (MaxX() - MinX()) * Width ;
			}

			// Vraca fizicke y-koordinate na osnovul logickih y-koordinata:
			function YC(y) {
			  return Height - (y - MinY()) / (MaxY() - MinY()) * Height ;
			}
			function XTickDelta() {//x prirastaj na grafiku
			  return 1 ;
			}
			function YTickDelta() {// y prirastaj n agrafiku
			  return 1 ;
			}
			function CrtaOse() {
				Ctx.save() ;
				Ctx.lineWidth = 2 ;
				// +Y osa
				Ctx.beginPath() ;
				Ctx.moveTo(XC(0),YC(0)) ;
				Ctx.lineTo(XC(0),YC(MaxY())) ;
				Ctx.stroke() ;

				// -Y osa
				Ctx.beginPath() ;
				Ctx.moveTo(XC(0),YC(0)) ;
				Ctx.lineTo(XC(0),YC(MinY())) ;
				Ctx.stroke() ;

				// Y markeri koordinata
				var delta = YTickDelta() ;
				for (var i = 1; (i * delta) < MaxY() ; ++i) {
					Ctx.beginPath() ;
					Ctx.moveTo(XC(0) - 5,YC(i * delta)) ;
					Ctx.lineTo(XC(0) + 5,YC(i * delta)) ;
					Ctx.stroke() ;  
				}

				var delta = YTickDelta() ;
				for (var i = 1; (i * delta) > MinY() ; --i) {
					Ctx.beginPath() ;
					Ctx.moveTo(XC(0) - 5,YC(i * delta)) ;
					Ctx.lineTo(XC(0) + 5,YC(i * delta)) ;
					Ctx.stroke() ;  
				}  

				// +X osa
				Ctx.beginPath() ;
				Ctx.moveTo(XC(0),YC(0)) ;
				Ctx.lineTo(XC(MaxX()),YC(0)) ;
				Ctx.stroke() ;

				// -X osa
				Ctx.beginPath() ;
				Ctx.moveTo(XC(0),YC(0)) ;
				Ctx.lineTo(XC(MinX()),YC(0)) ;
				Ctx.stroke() ;

			 // X markeri koordinata
				var delta = XTickDelta() ;
				for (var i = 1; (i * delta) < MaxX() ; ++i) {
					Ctx.beginPath() ;
					Ctx.moveTo(XC(i * delta),YC(0)-5) ;
					Ctx.lineTo(XC(i * delta),YC(0)+5) ;
					Ctx.stroke() ;  
				}

				var delta = XTickDelta() ;
				 for (var i = 1; (i * delta) > MinX() ; --i) {
					  Ctx.beginPath() ;
					  Ctx.moveTo(XC(i * delta),YC(0)-5) ;
					  Ctx.lineTo(XC(i * delta),YC(0)+5) ;
					  Ctx.stroke() ;  
				}
				Ctx.restore() ;
			}
			var XSTEP = (MaxX()-MinX())/Width;


			function CrtanjePovrsine(f,a,b) {
				var prvi = true;

				Ctx.beginPath() ;
				
				var a1=a*1;
				var b1=b*1;
				if ( b1-a1<XSTEP){
					a1=a1*5;
					b1=b1*5;
				}
				
				if(a1>=b1) {
						Ctx.font = "30px Arial";
						Ctx.strokeText("Nepravilne granice",10,50);
						return false;
					}
				for (var x=a1; x <= b1; x += XSTEP) {
				   var y = 0 ;
					var mojRex=/(document|window|eval|console|event|http|\<|HTTP|\.\.)/g ;
					var myRe = /(Math|x|dx|sin|cos|tan|abs|pow|acos|asin|atan|exp|log10|sqrt|ceil|floor|round|log|pi|\d)/g ;
					var myArray = myRe.exec(f);//ovo treba dasadrzi d abi prosao
					var myArray1 = mojRex.exec(f);//ako ovo sadrzi ne prolazi dalje
					if(myArray==null || myArray1!=null ) {
						Ctx.font = "30px Arial";
						Ctx.strokeText("Nepravilan oblik funkcije",10,50);
						return false;
					}
					try{		
						y=eval(f);
						if(!isFinite(y) || isNaN(y)){
							Ctx.font = "30px Arial";
							Ctx.strokeText(y,10,50);
							break;
						}
					
					  
						if (prvi) {
							Ctx.moveTo(XC(a1),YC(0));
							Ctx.lineTo(XC(x),YC(y)) ;
							prvi = false ;
						} else {
							Ctx.lineTo(XC(x),YC(y)) ;
						}
					   if(y>MaxY() || y<MinY() || x>MaxX() || x<MinX()){
						  Ctx.font = "30px Arial";
						 Ctx.fillText("Grafik prelazi okvir prozora", 10, 50); 
					  }
					}
					catch(err){
						Ctx.font = "30px Arial";
						 Ctx.strokeText("Pogresna funkcija",10,50);
						 break;
					}
				}
				Ctx.lineTo(XC(x),YC(0));
				Ctx.closePath();
				Ctx.fill() ;
			}	
	

			function crtaj() {
				// dozvoljene funkcije su:sin,cos,tan,abs,acos,asin,atan,exp,log10,deg,rad,sqrt,ceil,floor,round,log
				 
				var F1 = document.getElementById('podint').value ;

				var F=F1.replace("dx","");


				F=F.replace(/exp/g,"Math.exp");
				F=F.replace(/log/g,"Math.log");
				F=F.replace(/sqrt/g,"Math.sqrt");
				F=F.replace(/sin/g,"Math.sin");
				F=F.replace(/cos/g,"Math.cos");
				F=F.replace(/tan/g,"Math.tan");
				F=F.replace(/abs/g,"Math.abs");
				F=F.replace(/floor/g,"Math.floor");
				F=F.replace(/asin/g,"Math.asin");
				F=F.replace(/acos/g,"Math.acos");
				F=F.replace(/aMath.sin/g,"Math.asin");
				F=F.replace(/aMath.cos/g,"Math.acos");
				F=F.replace(/aMath.tan/g,"Math.atan");			
				F=F.replace(/ceil/g,"Math.ceil");
				F=F.replace(/floor/g,"Math.floor");
				F=F.replace(/pow/g,"Math.pow");
				F=F.replace("pi",3.14);


				 var a= document.getElementById('a').value;
				 var b = document.getElementById('b').value;

				  
				   // Set up the canvas:
				   Ctx = Canvas.getContext('2d');
				   Ctx.clearRect(0,0,Width,Height) ;
				 if (Canvas.getContext) {
				   // Draw:
					CrtaOse() ;
					var izr= CrtanjePovrsine(F,a,b) ;
				  
				  } else {
					//ne radi nista
				  }
				}	
		</script>
</html>		
