<div id="app">
  <h1 class="heading">{{ libraryName}}</h1>
  <a href="{{ opdsLink }}">OPDS</a><BR/>
  Sort by : <a href="{{ newestLink}} ">Newest</a>, 
	<a href="{{ titleLink}} ">Title</a>, 
	<a href="{{ publisherLink}} ">Publisher</a>,
	<a href="{{ authorsLink}} ">Author Name</a> 
	<BR/>
	{% if ebooks |length > 0 %}
    <ul>
        {% for ebook in ebooks %}
            <li><a href="{{ ebook.DetailsLink}}">{{ ebook.Title|e }}</a>
            {% if ebook.Authors|length > 0 %} by: {{ ebook.Authors|join(', ') }}<BR/>{% endif %}
            </li>
        {% endfor %}
    </ul>
	{% endif %}
	<a href="{{ thisLink }}">This</a><BR/>
</div>



