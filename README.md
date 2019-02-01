BottayV3
========

PHP IRC Bot for penetration test with multithread

Turkish Instruction
/*
*	@Bottay V3 Ultimate Hacking IRC Bot
*	@Coded By Burtay
*	
*	@Mail 	: admin@burtay.org
*	@Jabber : burtay@jabber.org
*
*	@Personal Website : http://www.burtay.Org
*	@Comunity Website : http://www.janissaries.Org
*	Bottay V3 Direct Link = www.http://www.burtay.org/development_area/bottay/BottayV3B3.zip
*
*	(Turkish Manual)
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
