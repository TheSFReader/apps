<div id="app">

	<h1 class="heading">Details</h1>

	<?php

	function printPartialEscape($var,$before,$after) {
		if(isset($var) && $var !== '') {
			print_unescaped($before);p($var);print_unescaped($after);
		}
	}
	
	$ebook = $_['ebook'];
	$link = $ebook->DetailsLink();
	$indexLink = $_['indexLink'];
	$thumbnailLink = $ebook->ThumbnailLink();
	$coverLink = $ebook->CoverLink();
	$formats = $ebook->Formats();
	$downloadLink = $formats['epub'];
	$download = 'Download';
	print_unescaped("<a href=\"$indexLink\">");  p("Library"); print_unescaped( "</a><BR/>\n");
	print_unescaped("<a href=\"$link\">");  p ($ebook->Title()); print_unescaped( "</a><BR/>\n");
	print_unescaped("<a href=\"$downloadLink\">$download</a><BR/>\n");
	print_unescaped("<a href=\"$coverLink\"><img src=\"$thumbnailLink\"/></a><BR/>\n");//  p ($ebook->Title()); 
	print_unescaped( "<BR/>");
	
	
	$authors = $ebook->Authors();
	if(! empty( $authors)) {
		p("by ");
		foreach ($authors as $author) {
			p($author.",");
		}
	}
	print_unescaped("<BR/>");

	printPartialEscape($ebook->Updated(),"File Update :","<BR/>\n");	
	printPartialEscape( $ebook->Language(), "Language :", "<BR/>\n");
	printPartialEscape( $ebook->Publisher(), "Publisher :", "<BR/>\n");
		
	$subjects = $ebook->Subjects();
	if(! empty( $subjects)) {
		p("Subjects: ");
		foreach ($subjects as $subject) {
			p($subject.",");
		}
	}
	print_unescaped("<BR/>\n");
	print_unescaped($ebook->Description());
?>
</div>



