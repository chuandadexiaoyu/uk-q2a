<?php

	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../');
		exit;
	}

	require_once QA_INCLUDE_DIR.'qa-db-selects.php';
	require_once QA_INCLUDE_DIR.'qa-app-format.php';


//	Get popular tags
	
	$start=qa_get_start();
	$populartags=qa_db_select_with_pending(qa_db_popular_tags_selectspec($start, qa_opt_if_loaded('page_size_tags')));

	$tagcount=qa_opt('cache_tagcount');
	$pagesize=qa_opt('page_size_tags');
	
	
//	Prepare content for theme

	$qa_content=qa_content_prepare();

	$qa_content['title']=qa_lang_html('main/popular_tags');
	
	$qa_content['ranking']=array(
		'items' => array(),
		'rows' => ceil($pagesize/qa_opt('columns_tags')),
		'type' => 'tags'
	);
	
	if (count($populartags)) {
		$output=0;
		foreach ($populartags as $word => $count) {
			$qa_content['ranking']['items'][]=array(
				'label' => qa_tag_html($word),
				'count' => number_format($count),
			);
			
			if ((++$output)>=$pagesize)
				break;
		}

	} else
		$qa_content['title']=qa_lang_html('main/no_tags_found');
	
	$qa_content['page_links']=qa_html_page_links(qa_request(), $start, $pagesize, $tagcount, qa_opt('pages_prev_next'));
	
	if (empty($qa_content['page_links']))
		$qa_content['suggest_next']=qa_html_suggest_ask();
		

	return $qa_content;


/*
	Omit PHP closing tag to help avoid accidental output
*/
