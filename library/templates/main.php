<div id="app">

	<h1 class="heading">Library</h1>

	<?php
	foreach ($_['ebooks'] as $ebook) {
		$link = $ebook->DetailsLink();
		print_unescaped("<a href=\"$link\">");  p ($ebook->Title()); print_unescaped( "</a>"); 
		$authors = $ebook->Authors();
		if(! empty( $authors)) {
			p(" by " . reset($authors));
		}
		print_unescaped("<BR/>");
	}
?>
</div>



