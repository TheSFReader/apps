<?xml version="1.0" encoding="UTF-8"?>
<?php 
	$thisLink = $_['thisLink'];
	$opdsLink = $_['opdsLink'];
	$newestLink = $_['newestLink'];
	$libraryName =$_['libraryName'];
	$userName =$_['userName'];
	$updateDate = $_['updateDate'];
	
	function printPartialEscape($var,$before,$after) {
		if(isset($var) && $var !== '') {
			print_unescaped($before);p($var);print_unescaped($after);
		}
	}
	
	?>
<feed xmlns:dcterms="http://purl.org/dc/terms/" xmlns:thr="http://purl.org/syndication/thread/1.0" xmlns:opds="http://opds-spec.org/2010/catalog" xml:lang="fr" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:app="http://www.w3.org/2007/app" xmlns="http://www.w3.org/2005/Atom">
  <id><?php print_unescaped($userName .":" .$thisLink); ?></id>
  <title><?php p($libraryName) ?></title>
  <updated><?php p($updateDate) ?></updated>
  <!--  <icon>http://assets2.feedbooks.net/images/favicon.ico?t=1358508273</icon>-->
<?php if (isset($_['userName'])) { 
	
	printPartialEscape($_['userName'],   "  <author>\n\t<name>","</name>\n");
	printPartialEscape($_['userMail'],   "\t<email>","</email>\n");
  	print_unescaped("  </author>\n");
  }
  ?>
  <link type="application/atom+xml; profile=opds-catalog; kind=navigation" rel="self" href="<?php print_unescaped($_['thisLink']); ?>"/>
  <link type="application/atom+xml; profile=opds-catalog; kind=navigation" title="Home" rel="start" href="<?php print_unescaped($_['opdsLink']); ?>"/>
  <link type="application/atom+xml; profile=opds-catalog; kind=acquisition" title="Newest" rel="http://opds-spec.org/sort/new" href="<?php print_unescaped($_['newestLink']); ?>"/>
  <?php 
  
  foreach( $_['ebooks'] as $ebook) { ?>
  <entry>
	<title><?php p($ebook->Title()); ?></title>
	<id><?php print_unescaped("/item/".$ebook->Id()); ?></id>
	<?php printPartialEscape( $ebook->ISBN(), "<dcterms:identifier xsi:type=\"dcterms:URI\">urn:ISBN:", "</dcterms:identifier>\n")?>
	<?php foreach ($ebook->Authors() as $author) {?>
<author><name><?php p($author); ?></name></author>
<?php } ?>
	<!--  <published>2013-01-19T00:39:28Z</published>-->
	<?php printPartialEscape($ebook->Updated(),"  <updated>","</updated>\n"); ?>
	<!--  <dcterms:issued>2013-01-18</dcterms:issued>-->
	<?php printPartialEscape( $ebook->Language(), "<dcterms:language>", "</dcterms:language>\n")?>
	<?php printPartialEscape( $ebook->Publisher(), "<dcterms:publisher>", "</dcterms:publisher>\n")?>
	<?php printPartialEscape( $ebook->Description(), "<summary>", "</summary>\n")?>
	<?php $cover = $ebook->CoverLink(); if($cover !== null) {print_unescaped("<link type=\"image/png\" rel=\"http://opds-spec.org/image\" href=\"$cover\"/>\n"); } ?>
	<?php $thumbnail = $ebook->ThumbnailLink(); if($thumbnail !== null) {print_unescaped("<link type=\"image/png\" rel=\"http://opds-spec.org/image/thumbnail\" href=\"$thumbnail\"/>\n"); } ?>
	<?php $details = $ebook->DetailsLink(); if($details !== null) {print_unescaped("<link type=\"text/html\" title=\"Details\" rel=\"alternate\" href=\"$details\"/>\n"); } ?>
	<?php $formats=$ebook->Formats(); $epubFormat = $formats['epub'];if(isset($epubFormat)) {
	print_unescaped("<link rel=\"http://opds-spec.org/acquisition\" type=\"application/epub+zip\" href=\"$epubFormat\"/>"); }
	?>
		<!-- 
	<dcterms:extent>135 pages</dcterms:extent>
	<dcterms:extent>3,8 Mo</dcterms:extent>
	<category term="FBFIC000000" label="Fiction"/>
	<category term="FBJUV000000" label="Jeunesse"/>
	<link type="text/html" title="Voir sur Feedbooks" rel="alternate" href="http://www.feedbooks.com/item/416961"/>
	<link type="text/html" rel="http://opds-spec.org/acquisition/buy" href="https://www.feedbooks.com/item/416961/buy">
	  <opds:price currencycode="EUR">5.99</opds:price>
	  <opds:indirectAcquisition type="application/epub+zip"/>
	</link>
	<link type="application/atom+xml;type=entry;profile=opds-catalog" title="Entr&#233;e compl&#232;te" rel="alternate" href="http://www.feedbooks.com/item/416961.atom"/>
	-->
  </entry>
  
  <?php  } ?>
  
  </feed>
