<div id="app">

	<h1 class="heading">Details</h1>

	<?php

	$ebook = $_['ebook'];
	$link = $ebook->DetailsLink();
	$indexLink = $_['indexLink'];
	$thumbnailLink = $ebook->ThumbnailLink();
	print_unescaped("<a href=\"$indexLink\">");  p("Library"); print_unescaped( "</a><BR/>");
	print_unescaped("<a href=\"$link\">");  p ($ebook->Title()); print_unescaped( "</a><BR/>");
	print_unescaped("<img src=\"$thumbnailLink\"/>");//  p ($ebook->Title()); 
	print_unescaped( "<BR/>");
	
	
	$authors = $ebook->Authors();
	if(! empty( $authors)) {
		p("by ");
		foreach ($authors as $author) {
			p($author.",");
		}
	}
	print_unescaped("<BR/>");
	$subjects = $ebook->Subjects();
	if(! empty( $subjects)) {
		p("Subjects: ");
		foreach ($subjects as $subject) {
			p($subject.",");
		}
	}
	print_unescaped("<BR/>");
	print_unescaped($ebook->Description());
?>
</div>



