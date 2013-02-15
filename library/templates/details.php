<div id="app">
	<h1 class="heading">Details</h1>
	<a href="{{ url('library_index') }}">Library</a><BR/>
	<a href="{{ ebook.DetailsLink }}">{{ ebook.Title }}</a><BR/>
	<a href="{{ ebook.formats.epub }}">Download</a><BR/>
	<!--  <a href="{{ ebook.CoverLink }}"><img src="{{ ebook.ThumbnailLink }}" {% if ebook.ImageSizes.thumbnail.width %}width="{{ ebook.ImageSizes.thumbnail.width }}" {% endif %}  {% if ebook.ImageSizes.thumbnail.height %}height="{{ ebook.ImageSizes.thumbnail.height }}" {% endif %}/></a><BR/>
	{% if ebook.Authors|length > 0 %} by: {{ ebook.Authors|join(', ') }}<BR/>{% endif %}
	{% if ebook.Subjects|length > 0 %} Subjects: {{ ebook.Subjects|join(', ') }}<BR/>{% endif %}
	{% if ebook.Updated|length > 0 %} Local File Update: {{ ebook.Updated }}<BR/>{% endif %}
	{% if ebook.Language|length > 0 %} Language: {{ ebook.Language }}<BR/>{% endif %}
	{% if ebook.Publisher|length > 0 %} Publisher: {{ ebook.Publisher }}<BR/>{% endif %}
	{% if ebook.Description|length > 0 %} Description: {{ ebook.Description }}<BR/>{% endif %}
	
	
	-->
	<form action="" method="get" id="bookpanel" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ebook.Id }}" />

        <table>
            <tr>
                <th>Title</th>
                <td><input type="text" name="title" value="{{ ebook.Title }}" /></td>
            </tr>
            <tr>
                <th>Authors</th>
                <td id="authors">
                {% set count = 0%}{%  for as, author in ebook.Authors %}
                            <p>
                                <input type="text" name="authorname[{{ count }}]" value="{{ author }}" />
                                (<input type="text" name="authoras[{{ count }}]" value="{{ as }}" />)
                            </p>
                    {% set count = count + 1  %} {% endfor %}
                </td>
            </tr>
            <tr>
                <th>Description<br />
                    <a href="{{ ebook.CoverLink }}"><img src="{{ ebook.ThumbnailLink }}" {% if ebook.ImageSizes.thumbnail.width %}width="{{ ebook.ImageSizes.thumbnail.width }}" {% endif %}  {% if ebook.ImageSizes.thumbnail.height %}height="{{ ebook.ImageSizes.thumbnail.height }}" {% endif %}/></a>
                </th>
                <td width="80%"><textarea name="description" rows="10" cols="100">{{ ebook.Description }}</textarea></td>
            </tr>
            <tr>
                <th>Subjects</th>
                <td><input id="subjects" type="text" name="subjects"  value="{{ ebook.Subjects|join(', ') }}" /></td>
            </tr>
            <tr>
                <th>Publisher</th>
                <td><input type="text" name="publisher" value="{{ ebook.Publisher }}" /></td>
            </tr>
            <tr>
                <th>Language</th>
                <td><p><input type="text" name="language"  value="{{ ebook.Language }}" /></p></td>
            </tr>
            <tr>
                <th>ISBN</th>
                <td><p><input type="text" name="isbn"      value="{{ ebook.Isbn }}" /></p></td>
            </tr>
            <tr>
                <th>Cover Image</th>
                <td><p>
                    <input type="file" name="coverfile" />
                    URL: <input type="text" name="coverurl" value="" />
                </p></td>
        </table>
        <div class="center">
            <input name="update" type="submit" />
        </div>
    </form>
</div>