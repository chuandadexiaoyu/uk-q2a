<?php
	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../');
		exit;
	}

	require_once QA_INCLUDE_DIR.'qa-db-selects.php';
	require_once QA_INCLUDE_DIR.'qa-app-format.php';
	require_once QA_INCLUDE_DIR.'qa-app-updates.php';


//	Determine whether path begins with qa or not (question and answer listing can be accessed either way)

	$requestparts=explode('/', qa_request());
	$explicitqa=(strtolower($requestparts[0])=='qa');


	
	if ($explicitqa)
		$slugs=array_slice($requestparts, 1);
	elseif (strlen($requestparts[0]))
		$slugs=$requestparts;
	else
		$slugs=array();
	
	$countslugs=count($slugs);


/*		

*/
//	Get list of questions, other bits of information that might be useful
	
	$userid=qa_get_logged_in_userid();


	@list($questions1, $questions2, $categories, $categoryid, $favorite, $custompage)=qa_db_select_with_pending(
		qa_db_qs_selectspec($userid, 'created', 0, $slugs, null, false, false, qa_opt_if_loaded('page_size_activity')),
		qa_db_recent_a_qs_selectspec($userid, 0, $slugs),
		qa_db_category_nav_selectspec($slugs, false, false, true),
		$countslugs ? qa_db_slugs_to_category_id_selectspec($slugs) : null,
		($countslugs && isset($userid)) ? qa_db_is_favorite_selectspec($userid, QA_ENTITY_CATEGORY, $slugs) : null,
		(($countslugs==1) && !$explicitqa) ? qa_db_page_full_selectspec($slugs[0], false) : null
	);




/*********************************************************************************************
					our datas for index
**********************************************************************************************/
@list($ukproblemcategory, $ukcommonproblem, $uklatestproblem, $uknoreply,$ukreply)=qa_db_select_with_pending(
		qa_db_category_nav_selectspec($slugs, false, false, true),
		qa_db_qs_selectspec($userid, 'created', 0, '常-见-问-题', null, false, false, 12),
		qa_db_qs_selectspec($userid, 'created', 0, $slugs, null, false, false, 7),
		qa_db_unanswered_qs_selectspec($userid, 'acount', 0, null, false, false, 6),
		qa_db_recent_a_qs_selectspec(null,0,null,null,false,null)
	);
/*********************************************************************************************
					our datas for index
**********************************************************************************************/


//print_r($ukreply);
//exit("I AM HERE");


//	First, if this matches a custom page, return immediately with that page's content
	
	if ( isset($custompage) && !($custompage['flags']&QA_PAGE_FLAGS_EXTERNAL) ) {
		qa_set_template('custom-'.$custompage['pageid']);

		$qa_content=qa_content_prepare();
		
		$level=qa_get_logged_in_level();

		if ( (!qa_permit_value_error($custompage['permit'], $userid, $level, qa_get_logged_in_flags())) || !isset($custompage['permit']) ) {
			$qa_content['title']=qa_html($custompage['heading']);
			$qa_content['custom']=$custompage['content'];
			
			if ($level>=QA_USER_LEVEL_ADMIN) {
				$qa_content['navigation']['sub']=array(
					'admin/pages' => array(
						'label' => qa_lang('admin/edit_custom_page'),
						'url' => qa_path_html('admin/pages', array('edit' => $custompage['pageid'])),
					),
				);
			}
		
		} else
			$qa_content['error']=qa_lang_html('users/no_permission');
		
		return $qa_content;
	}




//	Then, see if we should redirect because the 'qa' page is the same as the home page

	if ($explicitqa && (!qa_is_http_post()) && !qa_has_custom_home())
		qa_redirect(qa_category_path_request($categories, $categoryid), $_GET);
		

//	Then, if there's a slug that matches no category, check page modules provided by plugins

	if ( (!$explicitqa) && $countslugs && !isset($categoryid) ) {
		$pagemodules=qa_load_modules_with('page', 'match_request');
		$request=qa_request();
		
		foreach ($pagemodules as $pagemodule)
			if ($pagemodule->match_request($request)) {
				qa_set_template('plugin');
				return $pagemodule->process_request($request);
			}
	}
	
	
//	Then, check whether we are showing a custom home page

	if ( (!$explicitqa) && (!$countslugs) && qa_opt('show_custom_home') ) {
	

		qa_set_template('custom');
		$qa_content=qa_content_prepare();
		$qa_content['title']=qa_html(qa_opt('custom_home_heading'));
		if (qa_opt('show_home_description'))
			$qa_content['description']=qa_html(qa_opt('home_description'));
		$qa_content['custom']=qa_opt('custom_home_content');
		return $qa_content;
	}


//	If we got this far, it's a good old-fashioned Q&A listing page
	
	require_once QA_INCLUDE_DIR.'qa-app-q-list.php';




	qa_set_template('qa');
//	$questions=qa_any_sort_and_dedupe(array_merge($questions1, $questions2));
	$questions=$questions1;


	$pagesize=qa_opt('page_size_home');
	
	if ($countslugs) {
		if (!isset($categoryid))
			return include QA_INCLUDE_DIR.'qa-page-not-found.php';

		$categorytitlehtml=qa_html($categories[$categoryid]['title']);
		$sometitle=qa_lang_html_sub('main/recent_qs_as_in_x', $categorytitlehtml);
		$nonetitle=qa_lang_html_sub('main/no_questions_in_x', $categorytitlehtml);

	} else {
		$sometitle=qa_lang_html('main/recent_qs_as_title');
		$nonetitle=qa_lang_html('main/no_questions_found');
	}
	
	
//	Prepare and return content for theme for Q&A listing page


/************************************our data**************************/
/************************************our data**************************/
	$qa_content=qa_q_list_page_content(
		//$ukproblemcategory, 
		//$ukcommonproblem, 
		//$uklatestproblem, 
		//$uknoreply,
		$questions, // questions
		$pagesize, // questions per page
		0, // start offset
		null, // total count (null to hide page links)
		$sometitle, // title if some questions
		$nonetitle, // title if no questions
		$categories, // categories for navigation
		$categoryid, // selected category id
		true, // show question counts in category navigation
		$explicitqa ? 'qa/' : '', // prefix for links in category navigation
		qa_opt('feed_for_qa') ? 'qa' : null, // prefix for RSS feed paths (null to hide)
		(count($questions)<$pagesize) // suggest what to do next
			? qa_html_suggest_ask($categoryid)
			: qa_html_suggest_qs_tags(qa_using_tags(), qa_category_path_request($categories, $categoryid)),
		null, // page link params
		null, // category nav params
		$favorite // has user favorited this category
	);

/***************************************************************零回复，最新回复的时间处理****************************************************************///零回复的时间处理


/***************************************************************零回复，最新回复的时间处理****************************************************************/

$qa_content['ukcommonproblem']=$ukcommonproblem;
$qa_content['ukproblemcategory']=$ukproblemcategory;
$qa_content['uklatestproblem']=$uklatestproblem;
$qa_content['uknoreply']=$uknoreply;
$qa_content['ukreply']=$ukreply;

//print_r($uklatestproblem);
//exit("I AM HERE");
	
	if ( (!$explicitqa) && (!$countslugs) && qa_opt('show_home_description') )
		$qa_content['description']=qa_html(qa_opt('home_description'));

	
	return $qa_content;


