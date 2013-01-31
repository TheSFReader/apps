<div id="app">
  <h1 class="heading">{{ trans("%s's Library",userName) }}</h1>
<a href="{{ thisLink }}">This</a><BR/>
<a href="{{ url('library_opds') }}">OPDS</a><BR/>
{% if ebooks |length > 0 %}
    <table>
	<thead>
		<tr>
			<th id='headerName'><span class='name'><a href="{{ url('library_index_sort', {'sortby': 'title'}) }}">{{ trans('Title') }} (sort)</a></span></th>
			<th id="headerSize"><a href="{{ url('library_index_sort', {'sortby': 'authors'}) }}">{{ trans( 'Authors' ) }} (sort)</a></th>
			<th id="headerDate"><span id="modified"><a href="{{ url('library_index_sort', {'sortby': 'newest'}) }}">{{ trans( 'Updated' ) }} (sort)</a></span></th>
			<th id="headerDate"><span id="modified"><a href="{{ url('library_index_sort', {'sortby': 'publisher'}) }}">{{ trans( 'Publisher' ) }} (sort)</a></span></th>
		</tr>
	</thead>
	<tbody id="fileList">
	{% for ebook in ebooks %}
		<tr>
		<td><a href="{{ ebook.DetailsLink}}">{{ ebook.Title|e }}</a></td>
		<td>{% if ebook.Authors|length > 0 %}{{ ebook.Authors|join(', ') }}{% endif %}</td>
		<td>{{ ebook.Updated }}</td>
		<td>{{ ebook.Publisher }}</td>
		</tr>
    {% endfor %}
	</tbody>
</table>
{% else %}{{ trans('Empty ! You need to add some books.') }}
{% endif %}
</div>



