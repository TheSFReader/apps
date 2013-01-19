<?xml version="1.0" encoding="UTF-8"?>
<?php 
	$thisLink = $_['thisLink'];
	$opdsLink = $_['opdsLink'];
	$newestLink = $_['newestLink'];
	$libraryName =$_['libraryName'];
	$userName =$_['userName'];
	$updateDate = $_['updateDate'];
	?>
<feed xmlns:dcterms="http://purl.org/dc/terms/" xmlns:thr="http://purl.org/syndication/thread/1.0" xmlns:opds="http://opds-spec.org/2010/catalog" xml:lang="fr" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:app="http://www.w3.org/2007/app" xmlns="http://www.w3.org/2005/Atom">
  <id><?php print_unescaped($userName .":" .$thisLink); ?></id>
  <title><?php p($libraryName) ?></title>
  <updated><?php p($updateDate) ?></updated>
  <!--  <icon>http://assets2.feedbooks.net/images/favicon.ico?t=1358508273</icon>-->
<?php if (isset($_['userName'])) { 
  	print_unescaped("  <author>\n\t<name>$userName</name>\n");
  	if (isset($_['userMail'])) { 
  		$userMail = $_['userMail'];
  		print_unescaped("\t<email>$userMail</email>\n"); 
  	}
  	print_unescaped("  </author>\n");
  }
  ?>
  <link type="application/atom+xml; profile=opds-catalog; kind=navigation" rel="self" href="<?php print_unescaped($_['thisLink']); ?>"/>
  <link type="application/atom+xml; profile=opds-catalog; kind=navigation" title="Home" rel="start" href="<?php print_unescaped($_['opdsLink']); ?>"/>
  <link type="application/atom+xml; profile=opds-catalog; kind=acquisition" title="Newest" rel="http://opds-spec.org/sort/new" href="<?php print_unescaped($_['newestLink']); ?>"/>
  <?php foreach( $_['ebooks'] as $ebook) { ?>
  <entry>
	<title><?php p($ebook->Title()); ?></title>
	<id><?php print_unescaped("/item/".$ebook->Id()); ?></id>
	<dcterms:identifier xsi:type="dcterms:URI"><?php p($ebook->ISBN()); ?></dcterms:identifier>
	<author>
  		<name>Gilles Tibo</name>
  		<uri>http://www.feedbooks.com/search?query=contributor%3A%22Gilles+Tibo%22</uri>
	</author>
	<published>2013-01-19T00:39:28Z</published>
	<updated>2013-01-19T00:39:28Z</updated>
	<dcterms:language>fr</dcterms:language>
	<dcterms:publisher>Les &#201;ditions Qu&#233;bec Am&#233;rique</dcterms:publisher>
	<dcterms:issued>2013-01-18</dcterms:issued>
<summary>Le cinqui&#232;me titre d'une s&#233;rie avec No&#233;mie et sa gardienne, Madame Lumbago.

&#8226; Un livre de la collection Bilbo, qui s'adresse aux enfants de 8 ans et plus.

&#8226; Gilles Tibo a re&#231;u le Prix du Gouverneur g&#233;n&#233;ral 1996 pour No&#233;mie, Le Secret de Mada...</summary>
<dcterms:extent>135 pages</dcterms:extent>
<dcterms:extent>3,8 Mo</dcterms:extent>
<category term="FBFIC000000" label="Fiction"/>
<category term="FBJUV000000" label="Jeunesse"/>
<link type="text/html" title="Voir sur Feedbooks" rel="alternate" href="http://www.feedbooks.com/item/416961"/>
<link type="image/png" rel="http://opds-spec.org/image" href="http://covers.feedbooks.net/item/416961.jpg?size=large&amp;t=1358555968"/>
<link type="image/png" rel="http://opds-spec.org/image/thumbnail" href="http://covers.feedbooks.net/item/416961.jpg?t=1358555968"/>
<link type="text/html" rel="http://opds-spec.org/acquisition/buy" href="https://www.feedbooks.com/item/416961/buy">
  <opds:price currencycode="EUR">5.99</opds:price>
  <opds:indirectAcquisition type="application/epub+zip"/>
</link>
<link type="application/atom+xml;type=entry;profile=opds-catalog" title="Entr&#233;e compl&#232;te" rel="alternate" href="http://www.feedbooks.com/item/416961.atom"/>
</entry>
  
  <?php  } ?>
  
  </feed>





