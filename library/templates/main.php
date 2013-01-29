<div id="app">
  <h1 class="heading">{{ t("%s's Library",userName) }}</h1>
  <a href="{{ url('library_opds') }}">OPDS</a><BR/>
  Sort by : <a href="{{ url('library_index_sort', {'sortby': 'newest'}) }}">Newest</a>,
	<a href="{{ url('library_index_sort', {'sortby': 'title'}) }}">Title</a>, 
	<a href="{{ url('library_index_sort', {'sortby': 'publisher'}) }}">Publisher</a>,
	<a href="{{ url('library_index_sort', {'sortby': 'author'}) }}">Author Name</a> 
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



