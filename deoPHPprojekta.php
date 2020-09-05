
<?php
	
		session_start();
		if(!isset($_SESSION['korisnikID'])|| $_SESSION['korisnikID']==0){
			header("location:prva.php");
		}else{
			$korisnikId=$_SESSION['korisnikID'];
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
						$d="(".$c."+".$c."*(z))";
						$f=str_replace("(x)",$d,$f);
					}else{
					$f=str_replace("(x)","(z)",$f);
					}
				}else{
					$f=str_replace("(x)","(z)",$f);
				}	
					
				$number = '(?:\d+(?:[,.]\d+)?|pi|π)'; // Sta je broj
				$functions = '(sin|cos|tan|abs|acos|asin|atan|exp|log10|deg|rad|sqrt|ceil|floor|round|log|pow)'; // Dozvoljene PHP functions
				$operators = '[+\/*\^%-]'; //operatori
				$regexp = '(('.$number.'|'.$functions.'\s*\((?1)+'.$number.'\)|\((?1)+\))(?:'.$operators.'('.$number.')?)+'; // Radni regexp, koriscenje ostalih paterna 
				$regexp1 = '/^(('.$number.'|'.$functions.'\s*\('.$regexp.')+\)|\((?1)+\))(?:'.$operators.'('.$number.'))?)+|'.$regexp.')?| pow('.$regexp.",".$number.'))?$/'; // Konacan regexp
				if (preg_match($regexp1,$f1) && preg_match($regexp1,$f2))
				{
					$f1 = preg_replace('!pi|π!', '3.14', $f1); // Zamjena pi sa pi vrednoscu
					$f2 = preg_replace('!pi|π!', '3.14', $f2);
					echo $f1." i ".$f2."<br>";
				try{
					
					if( is_null(eval('$result1 = '.$f2.';')) && is_null(eval('$result = '.$f1.';'))){
				
			
						echo $result1 ." ".$result."<br>";
            			
						if(is_nan($result) || is_nan($result1) || !is_finite($result) || !is_finite($result1)){	
							$pov="FUNKCIJA NIJE DEFINISANA NA INTERVALU ILI JE POGRESNO UNESENA!";
							return $pov;	
						}else{
							
							
							
					
							
							$f = preg_replace('/\s+/', '', $f);
							$f = preg_replace('!pi|π!', '3.14', $f); 
							if($metoda=="Gausova") {
								$f = preg_replace('/\s+/', '', $f);
								$f = preg_replace('!pi|π!', '3.14', $f);
								$x=array();
								$A=array();
								$x[1]=-0.960289856;
								$x[8]=$x[1]*(-1);
								$A[1]=0.101228536;
								$A[8]=$A[1];
								$x[2]=-0.79666477;
								$x[7]=$x[2]*(-1);
								$A[2]=0.222381034;
								$A[7]=$A[2];
								$x[3]=-0.525532409;
								$x[6]=$x[3]*(-1);
								$A[3]=0.313706645;
								$A[6]=$A[3];
								$x[4]=-0.183434642;
								$x[5]=$x[4]*(-1);
								$A[4]=0.362283783;
								$A[5]=$A[4];
								$F=array();
								for($i=1;$i<=8;$i++){
									$f1=str_replace("z",$x[$i],$f);
									echo $f1.'<br>';
									eval('$F[$i]='.$f1.';');
									echo $F[$i].'<br>';
									if($F[$i]<0)$F[$i]=$F[$i]*(-1);
									
								}
								$pov=(($b-$a)/2)*($A[1]*($F[1]+$F[8])+$A[2]*($F[2]+$F[7])+$A[3]*($F[3]+$F[6])+$A[4]*($F[4]+$F[5]));
								
								//echo $pov.'<br>';
					
								
								
								return $pov;//vraca rezultat izracunat gausovom metodom
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
									if($prvi<0)$prvi=$prvi*(-1);
									$v2+=$prvi;
								 }
								 echo $v2."<br>";
								 for($i=1;$i<=9;$i+=2){
									 $y=$a+$i*$h;
									 $f1=str_replace("z",$y,$f);
									eval('$prvi ='.$f1.';');
									if($prvi<0)$prvi=$prvi*(-1);
									$v1+=$prvi;
								 }
								  echo $v1."<br>";
								 $v = $fa+$fb+2*$v2+4*$v1;
								 $v = $v*$h/3;
								 return $v;//vraca rezultat izracunat simpsonovom metodom metodom
								 
							}
								
						}
					}else{
						//echo $f1."<br>";
						$pov="FUNKCIJA  JE POGRESNO UNESENA!";//ako eval funkcija ne moze da izracuna reultat
						return $pov;	
					}
				}catch(ParseError $p){//u slucaju da se pojavi greska pri zracunavanju eval metodom
					$pov= $p->getMessage();
					return $pov;
				}catch(TypeError $t){
					return $t->getMessage();
				}catch (Throwable $t){
					return $t->getMessage();
				}
				
			
				}else{
						$pov="Nepravilan oblik funkcije";//ako funkcija nije prosla regex validaciju
						return $pov;
				}
				
			}
			function upisivanje(){
				$this->rez=$this->izvodjenje($this->func ,$this->donja, $this->gornja,$this->metoda);
			
			}
			function poruka(){
				
				echo $_SESSION['rezultat']=$this->rez;
				echo "<br>";
				print_r($this->niz);
				header("location:index1.php");	
			}
			function upisIntegrala($korisnikID){
				include_once 'baza/konekcija.php';
				include_once 'integrali/integrali.php';	
				$baza=new Baza();
				$db=$baza->getConnection();
				if(is_numeric($this->rez) && !is_nan($this->rez) && is_finite($this->rez)){
					if($this->metoda=="Gausova"){
						$i=new Integrals($db,'gausova');
					}else{
						$i=new Integrals($db,'simpsonova');
						
					}
					
					$i->donja=$this->donja;
					$i->gornja=$this->gornja;
					$i->podintfunk=$this->func;
					$i->resenje=$this->rez;
					$i->korisnici_id=$korisnikID;
					echo $i->upis();
				}
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
		$_SESSION['donja']=$a;
		$_SESSION['gornja']=$b;
		$_SESSION['int']=$f;
		if($a>=$b){
			$_SESSION['rezultat']="netacne granice";
			header("location:index1.php");
		}else{
		
		
			$f = str_replace(' ','', $f);
			if ($f=="dx"){
					 $_SESSION['rezultat']=$b-$a;
					header("location:index1.php");//u ovomslucajju nije ni funkcija i  ne upisuje se u bayu
			}else{
				$I=new Integral($f,$a,$b,$metoda);
				$I->upisivanje();
				$I->upisIntegrala($korisnikId);
				$I->poruka();
		  }
	  }
	  }	
}
?>
