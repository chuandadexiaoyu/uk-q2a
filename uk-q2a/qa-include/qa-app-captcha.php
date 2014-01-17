<?php
	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../');
		exit;
	}


	function qa_captcha_available()
/*
	Return whether a captcha module has been selected and it indicates that it is fully set up to go
*/
	{
		$module=qa_load_module('captcha', qa_opt('captcha_module'));
		
		return isset($module) && ( (!method_exists($module, 'allow_captcha')) || $module->allow_captcha());
	}
	
	
	function qa_set_up_captcha_field(&$qa_content, &$fields, $errors, $note=null)
/*
	Prepare $qa_content for showing a captcha, adding the element to $fields, given previous $errors, and a $note to display
*/
	{
		if (qa_captcha_available()) {
			$captcha=qa_load_module('captcha', qa_opt('captcha_module'));
			
			$count=@++$qa_content['qa_captcha_count']; // work around fact that reCAPTCHA can only display per page
			
			if ($count>1)
				$html='[captcha placeholder]'; // single captcha will be moved about the page, to replace this
			else {
				$qa_content['script_var']['qa_captcha_in']='qa_captcha_div_1';
				$html=$captcha->form_html($qa_content, @$errors['captcha']);
			}
			
			$fields['captcha']=array(
				'type' => 'custom',
				'label' => qa_lang_html('misc/captcha_label'),
				'html' => '<DIV ID="qa_captcha_div_'.$count.'">'.$html.'</DIV>',
				'error' => @array_key_exists('captcha', $errors) ? qa_lang_html('misc/captcha_error') : null,
				'note' => $note,
			);
					
			return "if (qa_captcha_in!='qa_captcha_div_".$count."') { document.getElementById('qa_captcha_div_".$count."').innerHTML=document.getElementById(qa_captcha_in).innerHTML; document.getElementById(qa_captcha_in).innerHTML=''; qa_captcha_in='qa_captcha_div_".$count."'; }";
		}
		
		return '';
	}


	function qa_captcha_validate_post(&$errors)
/*
	Check if captcha is submitted correctly, and if not, set $errors['captcha'] to a descriptive string
*/
	{
		if (qa_captcha_available()) {
			$captcha=qa_load_module('captcha', qa_opt('captcha_module'));
			
			if (!$captcha->validate_post($error)) {
				$errors['captcha']=$error;
				return false;
			}
		}
		
		return true;
	}

