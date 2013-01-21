<div id="app">
<?php $libraryName =$_['libraryName']; 
	$opdsLink = $_['opdsLink'];
	
	print_unescaped("  <h1 class=\"heading\">$libraryName</h1>\n");
	print_unescaped("  <a href=\"$opdsLink\">OPDS\n</a><BR/>\n"); ?>
	Sort by : <?php 
	$thisLink = $_['thisLink'];
	$newestLink = $_['newestLink'];
	$authorsLink = $_['authorsLink'];
	print_unescaped("<a href=\"$newestLink\">Newest</a>, ");
	print_unescaped("<a href=\"$authorsLink\">Author Name</a> <BR/>");

	foreach ($_['ebooks'] as $ebook) {
		$link = $ebook->DetailsLink();
		print_unescaped("<a href=\"$link\">");  p ($ebook->Title()); print_unescaped( "</a>"); 
		$authors = $ebook->Authors();
		if(! empty( $authors)) {
			p(" by " . reset($authors));
		}
		print_unescaped("<BR/>");
	}
	
	print_unescaped("<a href=\"$thisLink\">this</a> ");
?>
</div>



