<div id="controls">
	<div class="crumb svg">
		<a href="{{ url('library_index') }}">
			{{ trans("%s's Library",userName) }}
		</a>
	</div>
	<div class="crumb svg last">
		<a href="{{ url('library_authors') }}">
			{{ trans("Authors") }}
		</a>
	</div>
</div>
<!-- 
<div id="app">
  <h1 class="heading">{{ trans("Author's List") }}</h1>

 <table>
 <tr><td><a href="{{ thisLink }}">This</a></td>
</tr></table>
-->
{% if authors |length > 0 %}
<table>
	<thead>
		<tr>
			<th id='headerName'><span class='name'>{{ trans('Name') }}</span></th>
			<th id="headerSize">{{ trans( 'NameAs' ) }}</th>
		</tr>
	</thead>
	<tbody id="fileList">
	{% for author in authors %}
		<tr>
		<td><a href="{{ url('library_author', {'author': author.getId}) }}">{{ author.Name|e }}</a></td>
		<td>{{ author.NameAs|e }}</td>
		</tr>
    {% endfor %}
	</tbody>
</table>
{% else %}{{ trans('Empty ! You need to add some books.') }}
{% endif %}
</div>



