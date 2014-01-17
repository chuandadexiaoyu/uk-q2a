<?php
	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../');
		exit;
	}

	require_once QA_INCLUDE_DIR.'qa-db-selects.php';
	require_once QA_INCLUDE_DIR.'qa-app-format.php';
	require_once QA_INCLUDE_DIR.'qa-app-q-list.php';
	

//	Get list of unanswered questions, allow per-category if QA_ALLOW_UNINDEXED_QUERIES set in qa-config.php

	if (QA_ALLOW_UNINDEXED_QUERIES)
		$categoryslugs=qa_request_parts(1);
	else
		$categoryslugs=null;

	$countslugs=@count($categoryslugs);
	$by=qa_get('by');
	$start=qa_get_start();
	$userid=qa_get_logged_in_userid();
	
	switch ($by) {
		case 'selected':
			$selectby='selchildid';
			break;
			
		case 'upvotes':
			$selectby='amaxvote';
			break;
			
		default:
			$selectby='acount';
			break;
	}
	


	@list($questions, $categories, $categoryid)=qa_db_select_with_pending(
		qa_db_unanswered_qs_selectspec($userid, $selectby, $start, $categoryslugs, false, false, qa_opt_if_loaded('page_size_una_qs')),
		QA_ALLOW_UNINDEXED_QUERIES ? qa_db_category_nav_selectspec($categoryslugs, false, false, true) : null,
		$countslugs ? qa_db_slugs_to_category_id_selectspec($categoryslugs) : null
	);
/*
print_r($selectby);
print_r("|");
print_r($userid);
print_r("|");
print_r($start);
print_r("|");
print_r($categoryslugs);
exit("I AM HERE");

*/


	if ($countslugs) {
		if (!isset($categoryid))
			return include QA_INCLUDE_DIR.'qa-page-not-found.php';
		
		$categorytitlehtml=qa_html($categories[$categoryid]['title']);
	}
/*
print_r($countslugs);
print_r($start);
print_r(qa_opt_if_loaded('page_size_una_qs'));
print_r($questions);
exit("I AM HERE");
*/
	$feedpathprefix=null;
	$linkparams=array('by' => $by);
	
	switch ($by) {
		case 'selected':
			if ($countslugs) {
				$sometitle=qa_lang_html_sub('main/unselected_qs_in_x', $categorytitlehtml);
				$nonetitle=qa_lang_html_sub('main/no_una_questions_in_x', $categorytitlehtml);
			
			} else {
				$sometitle=qa_lang_html('main/unselected_qs_title');
				$nonetitle=qa_lang_html('main/no_unselected_qs_found');
				$count=qa_opt('cache_unselqcount');
			}
			break;
			
		case 'upvotes':
			if ($countslugs) {
				$sometitle=qa_lang_html_sub('main/unupvoteda_qs_in_x', $categorytitlehtml);
				$nonetitle=qa_lang_html_sub('main/no_una_questions_in_x', $categorytitlehtml);
			
			} else {
				$sometitle=qa_lang_html('main/unupvoteda_qs_title');
				$nonetitle=qa_lang_html('main/no_unupvoteda_qs_found');
				$count=qa_opt('cache_unupaqcount');
			}
			break;
			
		default:
			$feedpathprefix=qa_opt('feed_for_unanswered') ? 'unanswered' : null;
			$linkparams=array();

			if ($countslugs) {
				$sometitle=qa_lang_html_sub('main/unanswered_qs_in_x', $categorytitlehtml);
				$nonetitle=qa_lang_html_sub('main/no_una_questions_in_x', $categorytitlehtml);
			
			} else {
				$sometitle=qa_lang_html('main/unanswered_qs_title');
				$nonetitle=qa_lang_html('main/no_una_questions_found');
				$count=qa_opt('cache_unaqcount');
			}
			break;
	}
	
	
//	Prepare and return content for theme

	$qa_content=qa_q_list_page_content(
		$questions, // questions
		qa_opt('page_size_una_qs'), // questions per page
		$start, // start offset
		@$count, // total count
		$sometitle, // title if some questions
		$nonetitle, // title if no questions
		QA_ALLOW_UNINDEXED_QUERIES ? $categories : null, // categories for navigation (null since not shown on this page)
		QA_ALLOW_UNINDEXED_QUERIES ? $categoryid : null, // selected category id (null since not relevant)
		false, // show question counts in category navigation (null since not relevant)
		'unanswered/', // prefix for links in category navigation (null since no navigation)
		$feedpathprefix, // prefix for RSS feed paths (null to hide)
		qa_html_suggest_qs_tags(qa_using_tags()), // suggest what to do next
		$linkparams, // extra parameters for page links
		$linkparams // category nav params
	);
	
	$qa_content['navigation']['sub']=qa_unanswered_sub_navigation($by, $categoryslugs);
	
	
	return $qa_content;


/*
	Omit PHP closing tag to help avoid accidental output
*/
