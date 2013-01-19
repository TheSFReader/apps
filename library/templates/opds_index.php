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
</feed>




