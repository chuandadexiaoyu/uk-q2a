<?php
	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../');
		exit;
	}


//	Check we're not using Q2A's single-sign on integration and that we're not logged in
	
	if (QA_FINAL_EXTERNAL_USERS)
		qa_fatal_error('User login is handled by external code');
		
	if (qa_is_logged_in())
		qa_redirect('');
		

//	Process submitted form after checking we haven't reached rate limit
	
	$passwordsent=qa_get('ps');

//THIS IS A COPY OF DOLOGIN

if (qa_clicked('dologin')) {

		require_once QA_INCLUDE_DIR.'qa-app-limits.php';
                


		if (qa_limits_remaining(null, QA_LIMIT_LOGINS)) {
			require_once QA_INCLUDE_DIR.'qa-db-users.php';
			require_once QA_INCLUDE_DIR.'qa-db-selects.php';
require_once QA_INCLUDE_DIR.'qa-app-users-edit.php';//WE ADD LATER


require_once QA_BASE_DIR.'config.inc.php';//WE ADD LATER
require_once QA_UCCLIENT_DIR.'client.php';//WE ADD LATER

			qa_limits_increment(null, QA_LIMIT_LOGINS);

			$inemailhandle=qa_post_text('emailhandle');
			$inpassword=qa_post_text('password');
			$inremember=qa_post_text('remember');
			
			$errors=array();

			
//check for discuz
//list($uid, $username, $password, $email) = uc_user_login($)


//check for question2answer



			if (qa_opt('allow_login_email_only') || (strpos($inemailhandle, '@')!==false)) // handles can't contain @ symbols

				$matchusers=qa_db_user_find_by_email($inemailhandle);
			else
				$matchusers=qa_db_user_find_by_handle($inemailhandle);

	
			if (count($matchusers)==1) { // if matches more than one (should be impossible), don't log in
				$inuserid=$matchusers[0];
				$userinfo=qa_db_select_with_pending(qa_db_user_account_selectspec($inuserid, true));

				
				if (strtolower(qa_db_calc_passcheck($inpassword, $userinfo['passsalt'])) == strtolower($userinfo['passcheck'])) { // login and redirect

					require_once QA_INCLUDE_DIR.'qa-app-users.php';
	
					qa_set_logged_in_user($inuserid, $userinfo['handle'], $inremember ? true : false);

					
					$topath=qa_get('to');
					

					if (isset($topath))
						qa_redirect_raw(qa_path_to_root().$topath); // path already provided as URL fragment
					elseif ($passwordsent)

						qa_redirect('account');
					else
						qa_redirect('');
	
				} else
					$errors['password']=qa_lang('users/password_wrong');
	
			} else
{

list($uid, $username, $password, $email) = uc_user_login($inemailhandle, $inpassword);
//print($uid);

if($uid>0)
{
$userid=qa_create_new_user($email, $password, $username);


qa_set_logged_in_user($userid, $username, $inremember ? true : false);

					
					$topath=qa_get('to');
					

					if (isset($topath))
						qa_redirect_raw(qa_path_to_root().$topath); // path already provided as URL fragment
					elseif ($passwordsent)

						qa_redirect('account');
					else
						qa_redirect('');

}
else
{
switch($uid)
{
case -1:
$errors['emailhandle']=qa_lang('users/user_not_found');
break;
case -2:
$errors['password']=qa_lang('users/password_wrong');
break;
}

}

//				$errors['emailhandle']=qa_lang('users/user_not_found');
}				
		} else{
			$pageerror=qa_lang('users/login_limit');
}
		
	} else
		$inemailhandle=qa_get('e');

	
//	Prepare content for theme

	
	$qa_content=qa_content_prepare();

	$qa_content['title']=qa_lang_html('users/login_title');

	
	$qa_content['error']=@$pageerror;

	if (empty($inemailhandle) || isset($errors['emailhandle']))

		$forgotpath=qa_path('forgot');
	else
		$forgotpath=qa_path('forgot', array('e' => $inemailhandle));
	
	$forgothtml='<A HREF="'.qa_html($forgotpath).'">'.qa_lang_html('users/forgot_link').'</A>';
	
	$qa_content['form']=array(

		'tags' => 'METHOD="POST" ACTION="'.qa_self_html().'"',
		
		'style' => 'tall',
		

		'ok' => $passwordsent ? qa_lang_html('users/password_sent') : null,
		
		'fields' => array(

			'email_handle' => array(
				'label' => qa_opt('allow_login_email_only') ? qa_lang_html('users/email_label') : qa_lang_html('users/email_handle_label'),
				'tags' => 'NAME="emailhandle" ID="emailhandle"',

				'value' => qa_html(@$inemailhandle),
				'error' => qa_html(@$errors['emailhandle']),
			),
			

			'password' => array(
				'type' => 'password',
				'label' => qa_lang_html('users/password_label'),
				'tags' => 'NAME="password" ID="password"',
				'value' => qa_html(@$inpassword),
				'error' => empty($errors['password']) ? '' : (qa_html(@$errors['password']).' - '.$forgothtml),

				'note' => $passwordsent ? qa_lang_html('users/password_sent') : $forgothtml,
			),
			

			'remember' => array(
				'type' => 'checkbox',
				'label' => qa_lang_html('users/remember_label'),
				'tags' => 'NAME="remember"',

				'value' => @$inremember ? true : false,
			),
		),
		
		'buttons' => array(
			'login' => array(
				'label' => qa_lang_html('users/login_button'),
			),
		),
		
		'hidden' => array(

			'dologin' => '1',
		),
	);
	
	$loginmodules=qa_load_modules_with('login', 'login_html');
	
	foreach ($loginmodules as $module) {

		ob_start();
		$module->login_html(qa_opt('site_url').qa_get('to'), 'login');
		$html=ob_get_clean();
		

		if (strlen($html))
			@$qa_content['custom'].='<BR>'.$html.'<BR>';
	}











/*

	if (qa_clicked('dologin')) {
		require_once QA_INCLUDE_DIR.'qa-app-limits.php';

		if (qa_limits_remaining(null, QA_LIMIT_LOGINS)) {
			require_once QA_INCLUDE_DIR.'qa-db-users.php';
			require_once QA_INCLUDE_DIR.'qa-db-selects.php';
include QA_BASE_DIR.'config.inc.php';//WE ADD LATER
include QA_UCCLIENT_DIR.'client.php';//WE ADD LATER

			qa_limits_increment(null, QA_LIMIT_LOGINS);

			$inemailhandle=qa_post_text('emailhandle');
			$inpassword=qa_post_text('password');
			$inremember=qa_post_text('remember');
			
			$errors=array();
			
//check for discuz
//list($uid, $username, $password, $email) = uc_user_login($)


//check for question2answer



			if (qa_opt('allow_login_email_only') || (strpos($inemailhandle, '@')!==false)) // handles can't contain @ symbols
				$matchusers=qa_db_user_find_by_email($inemailhandle);
			else
				$matchusers=qa_db_user_find_by_handle($inemailhandle);
	
			if (count($matchusers)==1) { // if matches more than one (should be impossible), don't log in
				$inuserid=$matchusers[0];
				$userinfo=qa_db_select_with_pending(qa_db_user_account_selectspec($inuserid, true));
				
				if (strtolower(qa_db_calc_passcheck($inpassword, $userinfo['passsalt'])) == strtolower($userinfo['passcheck'])) { // login and redirect
					require_once QA_INCLUDE_DIR.'qa-app-users.php';
	
					qa_set_logged_in_user($inuserid, $userinfo['handle'], $inremember ? true : false);
					
					$topath=qa_get('to');
					
					if (isset($topath))
						qa_redirect_raw(qa_path_to_root().$topath); // path already provided as URL fragment
					elseif ($passwordsent)
						qa_redirect('account');
					else
						qa_redirect('');
	
				} else
					$errors['password']=qa_lang('users/password_wrong');
	
			} else
				$errors['emailhandle']=qa_lang('users/user_not_found');
				
		} else
			$pageerror=qa_lang('users/login_limit');
		
	} else
		$inemailhandle=qa_get('e');

	
//	Prepare content for theme
	
	$qa_content=qa_content_prepare();

	$qa_content['title']=qa_lang_html('users/login_title');
	
	$qa_content['error']=@$pageerror;

	if (empty($inemailhandle) || isset($errors['emailhandle']))
		$forgotpath=qa_path('forgot');
	else
		$forgotpath=qa_path('forgot', array('e' => $inemailhandle));
	
	$forgothtml='<A HREF="'.qa_html($forgotpath).'">'.qa_lang_html('users/forgot_link').'</A>';
	
	$qa_content['form']=array(
		'tags' => 'METHOD="POST" ACTION="'.qa_self_html().'"',
		
		'style' => 'tall',
		
		'ok' => $passwordsent ? qa_lang_html('users/password_sent') : null,
		
		'fields' => array(
			'email_handle' => array(
				'label' => qa_opt('allow_login_email_only') ? qa_lang_html('users/email_label') : qa_lang_html('users/email_handle_label'),
				'tags' => 'NAME="emailhandle" ID="emailhandle"',
				'value' => qa_html(@$inemailhandle),
				'error' => qa_html(@$errors['emailhandle']),
			),
			
			'password' => array(
				'type' => 'password',
				'label' => qa_lang_html('users/password_label'),
				'tags' => 'NAME="password" ID="password"',
				'value' => qa_html(@$inpassword),
				'error' => empty($errors['password']) ? '' : (qa_html(@$errors['password']).' - '.$forgothtml),
				'note' => $passwordsent ? qa_lang_html('users/password_sent') : $forgothtml,
			),
			
			'remember' => array(
				'type' => 'checkbox',
				'label' => qa_lang_html('users/remember_label'),
				'tags' => 'NAME="remember"',
				'value' => @$inremember ? true : false,
			),
		),
		
		'buttons' => array(
			'login' => array(
				'label' => qa_lang_html('users/login_button'),
			),
		),
		
		'hidden' => array(
			'dologin' => '1',
		),
	);
	
	$loginmodules=qa_load_modules_with('login', 'login_html');
	
	foreach ($loginmodules as $module) {
		ob_start();
		$module->login_html(qa_opt('site_url').qa_get('to'), 'login');
		$html=ob_get_clean();
		
		if (strlen($html))
			@$qa_content['custom'].='<BR>'.$html.'<BR>';
	}

*/

	$qa_content['focusid']=(isset($inemailhandle) && !isset($errors['emailhandle'])) ? 'password' : 'emailhandle';
	

	return $qa_content;


/*
	Omit PHP closing tag to help avoid accidental output
*/
