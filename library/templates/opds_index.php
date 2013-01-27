<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns:dcterms="http://purl.org/dc/terms/" xmlns:thr="http://purl.org/syndication/thread/1.0" xmlns:opds="http://opds-spec.org/2010/catalog" xml:lang="fr" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:app="http://www.w3.org/2007/app" xmlns="http://www.w3.org/2005/Atom">
	<id>{{ userName }}:{{ thisLink }}</id>
	<title>{{ libraryName }}</title>
	<updated>{{ updateDate }}</updated>
	{% if userName %}<author>
		<name>{{userName}}</name>
		{% if userMail %}<email>{{ userMail }}</email>{% endif %}
	</author>
	{% endif %}
  <link type="application/atom+xml; profile=opds-catalog; kind=navigation" rel="self" href="{{ thisLink }}"/>
  <link type="application/atom+xml; profile=opds-catalog; kind=navigation" title="Home" rel="start" href="{{ opdsLink }}"/>
  <link type="application/atom+xml; profile=opds-catalog; kind=acquisition" title="Newest" rel="http://opds-spec.org/sort/new" href="{{ newestLink }}"/>
</feed>




