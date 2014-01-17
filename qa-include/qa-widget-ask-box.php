<?php
	class qa_ask_box {
		
		function allow_template($template)
		{
			$allow=false;
			
			switch ($template)
			{
				case 'activity':
				case 'categories':
				case 'custom':
				case 'feedback':
				case 'qa':
				case 'questions':
				case 'hot':
				case 'search':
				case 'tag':
				case 'tags':
				case 'unanswered':
					$allow=true;
					break;
			}
			
			return $allow;
		}

		
		function allow_region($region)
		{
			$allow=false;
			
			switch ($region)
			{
				case 'main':
				case 'side':
				case 'full':
					$allow=true;
					break;
			}
			
			return $allow;
		}
	
	
		function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
		{
			if (isset($qa_content['categoryids']))
				$params=array('cat' => end($qa_content['categoryids']));
			else
				$params=null;
?>
<DIV CLASS="qa-ask-box">
	<FORM METHOD="POST" ACTION="<?php echo qa_path_html('ask', $params); ?>">
		<TABLE CLASS="qa-form-tall-table" STYLE="width:100%">
			<TR STYLE="vertical-align:middle;">
				<TD CLASS="qa-form-tall-label" STYLE="padding:8px; white-space:nowrap; <?php echo ($region=='side') ? 'padding-bottom:0;' : 'text-align:right;'?>" WIDTH="1">
					<?php echo strtr(qa_lang_html('question/ask_title'), array(' ' => '&nbsp;'))?>:
				</TD>
<?php
			if ($region=='side') {
?>
			</TR>
			<TR>
<?php			
			}
?>
				<TD CLASS="qa-form-tall-data" STYLE="padding:8px;" WIDTH="*">
					<INPUT NAME="title" TYPE="text" CLASS="qa-form-tall-text" STYLE="width:95%;">
				</TD>
			</TR>
		</TABLE>
		<INPUT TYPE="hidden" NAME="doask1" VALUE="1">
	</FORM>
</DIV>
<?php
		}
	
	}
	

/*
	Omit PHP closing tag to help avoid accidental output
*/
