<!--<html>
<head>
	<link href="style.css" rel="stylesheet" type="text/css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
</head>
<body>-->
<?php
		session_start();
		class Integral{
			private $func;
			private $donja;
			private $gornja;
			private $metoda;
			private $rez=0;
			private $niz=array();
			
			public function __construct($f,$a,$b,$metoda) {
			$this->func = $f;
			$this->donja = $a;
			$this->fpom = $f;
			$this->gornja = $b;
			$this->rez=0;
			$this->metoda=$metoda;
			}
			public function __set($atribut, $vrednost) {
				if (property_exists($this, $atribut)) {
					$this->$atribut = $vrednost;
				}
			}
		
			public function __get($atribut) {		
				if (property_exists($this, $atribut)) {
					return $this->$atribut;
				} else {
					return;
				}
			}
			public function sredjivanje($f,$a,$b){
				$f=str_replace("dx","",$f);
				$f=str_replace("x","(x)",$f);
				$f=str_replace("((x))","(x)",$f);
				$f=str_replace("e(x)p","exp",$f);
				for ($i=0;$i<10;$i++){
				$f=str_replace($i."(x)",$i."*(x)",$f);
				$f=str_replace($i."a",$i."*a",$f);
				$f=str_replace($i."c",$i."*c",$f);
				$f=str_replace($i."l",$i."*l",$f);
				$f=str_replace($i."s",$i."*s",$f);	
				$f=str_replace($i."e",$i."*e",$f);	
				$f=str_replace($i."p",$i."*p",$f);		
				}
				$f = preg_replace('/\s+/', '', $f);
				return $f;
			}
			
			public function povratak($pov){
				$_SESSION['rezultat']=$pov;
				header("location:index.php");
			}
			public function izvodjenje($f,$a,$b,$metoda){
				
				$f=str_replace("dx","",$f);
				$f1=$this->sredjivanje($f,$a,$b);
				$f2=$this->sredjivanje($f,$a,$b);
				$f1=str_replace("(x)",'('.$a.')',$f1);

				$f2=str_replace("(x)",'('.$b.')',$f2);
				$f1=str_replace("x",$a,$f);

				$f2=str_replace("x",$b,$f);
				$f1=str_replace("e".$a."p","exp",$f1);

				$f2=str_replace("e".$b."p","exp",$f2);
				$f=$this->sredjivanje($f,$a,$b);
				$f1 = preg_replace('/\s+/', '', $f1);
				$f2=preg_replace('/\s+/', '', $f2);
				if($metoda=="Gausova"){
					if($a!=-1 || $b!=1){
						$c=($b-$a)/2;
						$d="(".$c."+".$c."*z)";
						$f=str_replace("(x)",$d,$f);
					}else{
					$f=str_replace("(x)","(z)",$f);
					}
				}else{
					$f=str_replace("(x)","(z)",$f);
				}	
				$number = '(?:\d+(?:[,.]\d+)?|pi|π)'; // Sta je broj
				$functions = '(?:sin?|cos?|tan?|abs?|acos?|asin?|atan?|exp?|log10?|deg?|rad?|sqrt?|ceil?|floor?|round?|log|pow)?'; // Dozvoljene PHP functions
				$operators = '[+\/*\^%-]'; //operatori
				$regexp = '(('.$number.'|'.$functions.'\s*\((?1)+'.$number.'\)|\((?1)+\))(?:'.$operators.'('.$number.')?)+'; // Radni regexp, koriscenje ostalih paterna 
				$regexp1 = '/^(('.$number.'|'.$functions.'\s*\('.$regexp.')+\)|\((?1)+\))(?:'.$operators.'(?2))?)+|'.$regexp.')?| pow('.$regexp.",".$number.'))?/'; // Konacan regexp
				if (preg_match($regexp1,$f1) && preg_match($regexp1,$f2))
				{
					$f1 = preg_replace('!pi|π!', '3.14', $f1); // Zamjena pi sa pi vrednoscu
					$f2 = preg_replace('!pi|π!', '3.14', $f2);
					echo $f1." i ".$f2."<br>";
				try{
					if( is_null(eval('$result1 = '.$f2.';')) && is_null(eval('$result = '.$f1.';'))){
				
			
						//echo $result1 ." ".$result."<br>";
            			
						if(is_nan($result) || is_nan($result1) || !is_finite($result) || !is_finite($result1)){	
							$pov="FUNKCIJA NIJE DEFINISANA NA INTERVALU ILI JE POGRESNO UNESENA!";
							return $pov;	
						}else{
							$korak=($b-$a)/10;
							for($i=$a;$i<=$b;$i+=$korak){
							$f1=str_replace("z",$i,$f);
					
							//echo $f1."<br>";
							$f1 = preg_replace('/\s+/', '', $f1);

					
							$f1 = preg_replace('!pi|π!', '3.14', $f1); // Zamjena pi sa pi vrednoscu
							eval('$result = '.$f1.';');
										
					
					
							array_push($this->niz,$result);
							
							//echo $result."<br>";
					
							}
							$f = preg_replace('/\s+/', '', $f);
							$f = preg_replace('!pi|π!', '3.14', $f); 
							if($metoda=="Gausova") {
								$f = preg_replace('/\s+/', '', $f);
								$f = preg_replace('!pi|π!', '3.14', $f); 
								$f1=str_replace("z","-sqrt(3/5)",$f);
								echo $f1."<br>";
								$f1=str_replace("z","0",$f);
								$f3=str_replace("z","sqrt(3/5)",$f);
								eval('$prvi ='.$f1.';');
								eval('$drugi ='.$f2.';');
								eval('$treci ='.$f3.';');
								$pov=(($b-$a)/2)*(5/9*$prvi+8/9*$drugi+5/9*$treci);
								//echo $pov.'<br>';
					
								
								
								return $pov;
							}else{
								 $fa=$result;
								 $fb=$result1;
								 $v2=0;
								 $v1=0;
								 $h=$b-$a;
								 $h=$h/10;
								
								 for($i=2;$i<=8;$i+=2){
									 $y=$a+$i*$h;
									 $f1=str_replace("z",$y,$f);
									  
									eval('$prvi ='.$f1.';');
									$v2+=$prvi;
								 }
								 echo $v2."<br>";
								 for($i=1;$i<=9;$i+=2){
									 $y=$a+$i*$h;
									 $f1=str_replace("z",$y,$f);
									eval('$prvi ='.$f1.';');
									$v1+=$prvi;
								 }
								  echo $v1."<br>";
								 $v = $fa+$fb+2*$v2+4*$v1;
								 $v = $v*$h/3;
								 return $v;
								 
							}
								
						}
					}else{
						//echo $f1."<br>";
						$pov="FUNKCIJA  JE POGRESNO UNESENA!";
						return $pov;	
					}
				}catch(Exception $e){
					$pov="Greska";
					return $pov;
				}
				}else{
						$pov="Nepravilan oblik funkcije";
						return $pov;
				}
				
			}
			function upisivanje(){
				$this->rez=$this->izvodjenje($this->func ,$this->donja, $this->gornja,$this->metoda);
			
			}
			function poruka(){
				
				echo $_SESSION['rezultat']=$this->rez."<br>";
				print_r($this->niz);
				header("location:index1.php");	
			}
		}

	  if (!isset($_GET['a']) || !isset($_GET['b']) || !isset($_GET['podint']) || !isset($_GET['metoda'])){
		  
		  echo $_SESSION['rezultat']="niste uneli sve parametre";
				header("location:index1.php");
	  }else{
		  
		 $metoda="";
		
		echo "integral od". $_GET['a'] ."do". $_GET['b'] ."od funkcije" .$_GET['podint']."metodom".$_GET['metoda']."<br>";
		$a=$_GET['a'];
		$b=$_GET['b'];
		$f=$_GET['podint'];
		$metoda=$_GET['metoda'];
		$a=str_replace('pi','3.14',$a);
		$b=str_replace('pi','3.14',$b);
		$a=$a*1;
		$b=$b*1;
		if($a>=$b){
			$_SESSION['rezultat']="netacne granice";
			header("location:index1.php");
		}
		
		$_SESSION['donja']=$a;
		$_SESSION['gornja']=$b;
		$_SESSION['int']=$f;
		$f = str_replace(' ','', $f);
		if ($f=="dx"){
				 $_SESSION['rezultat']=$b-$a;
				header("location:index1.php");
		}
		$I=new Integral($f,$a,$b,$metoda);
		$I->upisivanje();
		$I->poruka();
		/*$f=str_replace("dx","",$f);
		
		
		$f1=str_replace("x",$a,$f);
		$f2=str_replace("x",$b,$f);
		$f1=str_replace("e".$a."p","exp",$f1);
		$f2=str_replace("e".$b."p","exp",$f2);
		$f1 = preg_replace('!pi|π!', 'pi()', $f1); // Zamjena pi sa pi function
		$f2 = preg_replace('!pi|π!', 'pi()', $f2); // Zamjena pi sa pi function
		$f=str_replace("x","(x)",$f);
		$f=str_replace("((x))","(x)",$f);
		$f=str_replace("e(x)p","exp",$f);
		for ($i=0;$i<10;$i++){
			$f=str_replace($i."(x)",$i."*(x)",$f);
			$f=str_replace($i."a",$i."*a",$f);
			$f=str_replace($i."c",$i."*c",$f);
			$f=str_replace($i."l",$i."*l",$f);
			$f=str_replace($i."s",$i."*s",$f);	
			$f=str_replace($i."e",$i."*e",$f);	
			$f=str_replace($i."p",$i."*p",$f);
		
		}
		echo $f."<br>";
		if($a!=-1 || $b!=1){
			$c=($b-$a)/2;
			$d="(".$c."+".$c."*z)";
			$f=str_replace("(x)",$d,$f);
		}	
		$f1 = preg_replace('/\s+/', '', $f1);
		$f2=preg_replace('/\s+/', '', $f2);
		//$f=str_replace("(x)",$d,$f);
		echo $f."<br>";
		
		$ukupno=0;
		$niz=array();
		$korak=($b-$a)/10;
		$number = '(?:\d+(?:[,.]\d+)?|pi|π)'; // Sta je broj
		$functions = '(?:sin?|cos?|tan?|abs?|acos?|asin?|atan?|exp?|log10?|deg?|rad?|sqrt?|ceil?|floor?|round?|log|pow)?'; // Dozvoljene PHP functions
		$operators = '[+\/*\^%-]'; //operatori
		$regexp = '(('.$number.'|'.$functions.'\s*\((?1)+'.$number.'\)|\((?1)+\))(?:'.$operators.'('.$number.')?)+'; // Radni regexp, koriscenje ostalih paterna 
		$regexp1 = '/^(('.$number.'|'.$functions.'\s*\('.$regexp.')+\)|\((?1)+\))(?:'.$operators.'(?2))?)+|'.$regexp.')?$/'; // Konacan regexp
			
		echo $f1."<br>";
		echo $f2."<br>";
		if (preg_match($regexp1,$f1) && preg_match($regexp1,$f2))
		{
			$f1 = preg_replace('!pi|π!', 'pi()', $f1); // Zamjena pi sa pi function
			
			if( is_null(eval('$result1 = '.$f2.';')) && is_null(eval('$result = '.$f1.';'))){
				
			
				echo $result1 ." ".$result."<br>";
            			
				if(is_nan($result) || is_nan($result1) || !is_finite($result) || !is_finite($result1)){	
					echo $_SESSION['rezultat']="FUNKCIJA NIJE DEFINISANA NA INTERVALU ILI JE POGRESNO UNESENA!";
					header("location:index.php");	
				}else{
					for($i=$a;$i<=$b;$i+=$korak){
						$f1=str_replace("z",$i,$f);
					
						echo $f1."<br>";
						$f1 = preg_replace('/\s+/', '', $f1);

					/*$number = '(?:\d+(?:[,.]\d+)?|pi|π)'; // Sta je broj
					$functions = '(?:sin?|cos?|tan?|abs?|acos?|asin?|atan?|exp?|log10?|deg?|rad?|sqrt?|ceil?|floor?|round?|log)'; // Dozvoljene PHP functions
					$operators = '[+\/*\^%-]'; //operatori
					$regexp = '(('.$number.'|'.$functions.'\s*\((?1)+'.$number.'\)|\((?1)+\))(?:'.$operators.'('.$number.')?)+'; // Radni regexp, koriscenje ostalih paterna 
					$regexp1 = '/^(('.$number.'|'.$functions.'\s*\('.$regexp.')+\)|\((?1)+\))(?:'.$operators.'(?2))?)+$/'; // Konacan regexp*/
					//eval('$result = '.$f1.';');	
					/*if (preg_match($regexp1,$f1))
					{
						$f1 = preg_replace('!pi|π!', 'pi()', $f1); // Zamjena pi sa pi function
						eval('$result = '.$f1.';');
					}
					else
					{
						$result = false;
					}
							
					
					
						$niz[]=$result;
						$ukupno+=$result;
						echo $result."<br>";
					
					}
					$f1=str_replace("z","-sqrt(3/5)",$f);
					$f1=str_replace("z","0",$f);
					$f3=str_replace("z","sqrt(3/5)",$f);
					eval('$prvi ='.$f1.';');
					eval('$drugi ='.$f2.';');
					eval('$treci ='.$f3.';');
					$pov=(($b-$a)/2)*(5/9*$prvi+8/9*$drugi+5/9*$treci);
					echo $pov.'<br>';
				
					echo $_SESSION['rezultat']=$pov;
					header("location:index.php");
			
				}
			}else{
				echo $f1."<br>";
				echo $_SESSION['rezultat']="FUNKCIJA  JE POGRESNO UNESENA!";
				header("location:index.php");	
			}
			*/
		}
		//echo '<div id="rez"></div>';	
	  
	  
?>
<!--
</body>

	<script>
		var uk=0;
		function sqrt(x){
			var z= Math.sqrt(x);
			
			uk=uk+z;
			
	
			  
		}
		function log(x){
			uk+=Math.log(x);
		}
		$("#rez").click(function(){
			document.getElementById('rez').innerHTML=uk;
		});
	</script>
</html>-->