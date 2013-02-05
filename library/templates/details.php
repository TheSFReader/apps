<div id="app">
	<h1 class="heading">Details</h1>
	<a href="{{ url('library_index') }}">Library</a><BR/>
	<a href="{{ ebook.DetailsLink }}">{{ ebook.Title }}</a><BR/>
	<a href="{{ ebook.formats.epub }}">Download</a><BR/>
	<a href="{{ ebook.CoverLink }}"><img src="{{ ebook.ThumbnailLink }}" {% if ebook.ImageSizes.thumbnail.width %}width="{{ ebook.ImageSizes.thumbnail.width }}" {% endif %}  {% if ebook.ImageSizes.thumbnail.height %}height="{{ ebook.ImageSizes.thumbnail.height }}" {% endif %}/></a><BR/>
	{% if ebook.Authors|length > 0 %} by: {{ ebook.Authors|join(', ') }}<BR/>{% endif %}
	{% if ebook.Subjects|length > 0 %} Subjects: {{ ebook.Subjects|join(', ') }}<BR/>{% endif %}
	{% if ebook.Updated|length > 0 %} Local File Update: {{ ebook.Updated }}<BR/>{% endif %}
	{% if ebook.Language|length > 0 %} Language: {{ ebook.Language }}<BR/>{% endif %}
	{% if ebook.Publisher|length > 0 %} Publisher: {{ ebook.Publisher }}<BR/>{% endif %}
	{% if ebook.Description|length > 0 %} Description: {{ ebook.Description }}<BR/>{% endif %}
</div>