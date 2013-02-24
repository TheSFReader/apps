<div id="controls">
	<div class="crumb svg last">
		<a href="<?php echo $_['baseURL']; ?>">
			{{ trans("%s's Library",userName) }}
		</a>
	</div>
</div>
<div id="app">
  <h1 class="heading">{{ trans("%s's Library",userName) }}</h1>

 <table>
 <tr><td><a href="{{ thisLink }}">This</a></td>
<td><a href="{{ url('library_opds') }}">OPDS</a></td>
<td><a href="{{ url('library_authors') }}">{{ trans("Authors") }}</a></td>
<td><a href="{{ url('library_index_rescan') }}">{{ trans("Re-Scan") }}</a></td>
</tr></table>
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
		<td><a href="{{ url('library_details', {'id': ebook.getId}) }}">{{ ebook.Title|e }}</a></td>
		<td>{% if ebook.Authors|length > 0 %}{{ ebook.Authors|join(', ') }}{% endif %}</td>
		<td>{{ ebook.Updated }}</td>
		<td>{{ ebook.Publisher }}</td>
		</tr>
    {% endfor %}
	</tbody>
</table>
{% else %}<div id="emptyfolder">{{ trans('Empty ! You need to add some books.') }}</div>
{% endif %}
</div>



