    
		$(function() {
			$('#tags_1').tagsInput({
				width:'auto',
				'defaultText':'点击我增加标签',
				autocomplete_url:'qa-content/tags-text.html'
				});
		});
	
	
function qa_click(link)
{
	var str=link.firstChild.innerHTML;
	$('#tags_1').addTag(str);
}