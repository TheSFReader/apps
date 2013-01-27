<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns:dcterms="http://purl.org/dc/terms/" xmlns:thr="http://purl.org/syndication/thread/1.0" xmlns:opds="http://opds-spec.org/2010/catalog" xml:lang="fr" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:app="http://www.w3.org/2007/app" xmlns="http://www.w3.org/2005/Atom">
	<id>{{ userName }}:{{ thisLink }}</id>
	<title>{{ libraryName }}</title>
	<updated>{{ updateDate }}</updated>
	
{% if userName %}	<author>
		<name>{{userName}}</name>
		{% if userMail %}<email>{{ userMail }}</email>{% endif %}

	</author>{% endif %}
	
	<link type="application/atom+xml; profile=opds-catalog; kind=navigation" rel="self" href="{{ thisLink }}"/>
	<link type="application/atom+xml; profile=opds-catalog; kind=navigation" title="Home" rel="start" href="{{ opdsLink }}"/>
	<link type="application/atom+xml; profile=opds-catalog; kind=acquisition" title="Newest" rel="http://opds-spec.org/sort/new" href="{{ newestLink }}"/>
  	{% for ebook in ebooks %}
	<entry>
		<title>{{ ebook.Title }}</title>
		<id>/item/{{ ebook.getId }}</id>
		{% if ebook.ISBN %}<dcterms:identifier xsi:type="dcterms:URI">urn:ISBN:{{ ebook.ISBN }}</dcterms:identifier>{% endif %}
		
		{% for author in ebook.Authors %}<author><name>{{ author}}</name></author>{% endfor %}
		
		{% if ebook.Updated %}<updated>{{ ebook.Updated }}</updated>{% endif %}
		
		{% if ebook.Language %}<dcterms:language>{{ ebook.Language }}</dcterms:language>{% endif %}
		
		{% if ebook.Publisher %}<dcterms:publisher>{{ ebook.Publisher }}</dcterms:publisher>{% endif %}
		
		{% if ebook.Description %}<summary>{{ ebook.Description }}</summary>{% endif %}
		
		{% if ebook.CoverLink %}<link type="image/png" rel="http://opds-spec.org/image" href="{{ ebook.CoverLink }}"/>{% endif %}
		
		{% if ebook.ThumbnailLink %}<link type="image/png" rel="http://opds-spec.org/image/thumbnail" href="{{ ebook.ThumbnailLink }}"/>{% endif %}
		
		{% if ebook.DetailsLink %}<link type="text/html" title="Details" rel="alternate" href="{{ ebook.DetailsLink }}"/>{% endif %}
		
		{% if ebook.Formats.epub %}<link rel="http://opds-spec.org/acquisition" type="application/epub+zip" href="{{ebook.Formats.epub}}"/>{% endif%}
		
	</entry>
  {% endfor %} 
  </feed>
