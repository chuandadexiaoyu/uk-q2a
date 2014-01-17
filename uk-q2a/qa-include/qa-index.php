<?php
	if (!defined('QA_BASE_DIR'))
		define('QA_BASE_DIR', dirname(empty($_SERVER['SCRIPT_FILENAME']) ? dirname(__FILE__) : $_SERVER['SCRIPT_FILENAME']).'/');

	if (@$_POST['qa']=='ajax')
{
	require 'qa-ajax.php';

}
	elseif (@$_GET['qa']=='image')
{
		require 'qa-image.php';
}
	elseif (@$_GET['qa']=='blob')
{
		require 'qa-blob.php';
}
	else {

	//	Otherwise, load the Q2A base file which sets up a bunch of crucial stuff
		
		require 'qa-base.php';
		
	
	//	Determine the request and root of the installation, and the requested start position used by many pages
		
		function qa_index_set_request()
		{
			if (qa_to_override(__FUNCTION__)) { $args=func_get_args(); return qa_call_override(__FUNCTION__, $args); }
		
			$relativedepth=0;
			
			if (isset($_GET['qa-rewrite'])) { // URLs rewritten by .htaccess
				$urlformat=QA_URL_FORMAT_NEAT;
				$requestparts=explode('/', qa_gpc_to_string($_GET['qa-rewrite']));
				unset($_GET['qa-rewrite']);
				$relativedepth=count($requestparts);
				
				// Workaround for fact that Apache unescapes characters while rewriting, based on assumption that $_GET['qa-rewrite'] has
				// right path depth, which is true do long as there are only escaped characters in the last part of the path
				if (!empty($_SERVER['REQUEST_URI'])) {
					$origpath=$_SERVER['REQUEST_URI'];
					$_GET=array();
					
					$questionpos=strpos($origpath, '?');
					if (is_numeric($questionpos)) {
						$params=explode('&', substr($origpath, $questionpos+1));
						
						foreach ($params as $param)
							if (preg_match('/^([^\=]*)(\=(.*))?$/', $param, $matches))
								$_GET[urldecode($matches[1])]=qa_string_to_gpc(urldecode(@$matches[3]));
		
						$origpath=substr($origpath, 0, $questionpos);
					}
					
					$requestparts=array_slice(explode('/', urldecode($origpath)), -count($requestparts));
				}
				
			} elseif (isset($_GET['qa'])) {



				if (strpos($_GET['qa'], '/')===false) {

					$urlformat=( (empty($_SERVER['REQUEST_URI'])) || (strpos($_SERVER['REQUEST_URI'], '/index.php')!==false) )
						? QA_URL_FORMAT_SAFEST : QA_URL_FORMAT_PARAMS;


					$requestparts=array(qa_gpc_to_string($_GET['qa']));
					

					for ($part=1; $part<10; $part++)
						if (isset($_GET['qa_'.$part])) {
							$requestparts[]=qa_gpc_to_string($_GET['qa_'.$part]);
							unset($_GET['qa_'.$part]);
						}

				
				} else {
					$urlformat=QA_URL_FORMAT_PARAM;
					$requestparts=explode('/', qa_gpc_to_string($_GET['qa']));
				}
				
				unset($_GET['qa']);
			
			} else {


				$phpselfunescaped=strtr($_SERVER['PHP_SELF'], '+', ' '); // seems necessary, and plus does not work with this scheme
				$indexpath='/index.php/';
				$indexpos=strpos($phpselfunescaped, $indexpath);
//				print_r($indexpos);
//exit("I AM HERE");

				if (is_numeric($indexpos)) {
					$urlformat=QA_URL_FORMAT_INDEX;
					$requestparts=explode('/', substr($phpselfunescaped, $indexpos+strlen($indexpath)));
					$relativedepth=1+count($requestparts);
			
				} else {
					$urlformat=null; // at home page so can't identify path type
					$requestparts=array();
//print_r($requestparts);
//exit("I AM HERE");
				}
			}
			





			foreach ($requestparts as $part => $requestpart) // remove any blank parts
			{

				if (!strlen($requestpart))
				{

					unset($requestparts[$part]);
				}
			}		
			reset($requestparts);

			$key=key($requestparts);
			


			$replacement=array_search(@$requestparts[$key], qa_get_request_map());
		


	
			if ($replacement!==false)
				$requestparts[$key]=$replacement;
		
			qa_set_request(
				implode('/', $requestparts),
				($relativedepth>1) ? str_repeat('../', $relativedepth-1) : './',
				$urlformat
			);

		}

		qa_index_set_request();



	//	Branch off to appropriate file for further handling
	
		$requestlower=strtolower();


		if ($requestlower=='install')
{		
			require QA_INCLUDE_DIR.'qa-install.php';
}			
		elseif ($requestlower==('url/test/'.QA_URL_TEST_STRING))
			require QA_INCLUDE_DIR.'qa-url-test.php';
		
		else {
	
		//	Enable gzip compression for output (needs to come early)
	
			if (QA_HTML_COMPRESSION) // on by default
			{
				if (substr($requestlower, 0, 6)!='admin/') // not for admin pages since some of these contain lengthy processes
				{		
					if (extension_loaded('zlib') && !headers_sent())
						{

						ob_start('ob_gzhandler');
						}
				}
			}
			if (substr($requestlower, 0, 5)=='feed/')
				require QA_INCLUDE_DIR.'qa-feed.php';
			else
				require QA_INCLUDE_DIR.'qa-page.php';
		}
	}
	
	qa_report_process_stage('shutdown');

