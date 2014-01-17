
<?php

echo "<script type='text/javascript' language='javascript' src='./qa-theme/SnowT/js/home_store.js'></script>";

	class qa_html_theme extends qa_html_theme_base
	{	
		

		function qa_html_theme($template, $content, $rooturl, $request)
	/*
		Initialize the object and assign local variables
	*/
		{
			$this->template=$template;
			$this->content=$content;
			$this->rooturl=$rooturl;
			$this->request=$request;
		}



		function html()
		{
			$this->output(
				'<HTML>',
				'<!-- Powered by Question2Answer - http://www.question2answer.org/ -->'
			);
			
			$this->head();
			$this->body();
			
			$this->output(
				'<!-- Powered by Question2Answer - http://www.question2answer.org/ -->',
				'</HTML>'
			);

		}




		function head_script() // change style of WYSIWYG editor to match theme better
		{
			qa_html_theme_base::head_script();
			
			$this->output(
				'<SCRIPT TYPE="text/javascript"><!--',
				'if (qa_wysiwyg_editor_config)',
				'qa_wysiwyg_editor_config.skin="kama";',
				'//--></SCRIPT>'
			);
		}
		
		function nav_user_search() // outputs login form if user not logged in
		{
			if(!qa_is_logged_in()){
				$login=@$this->content['navigation']['user']['login'];			
				if(isset($login)){

					$this->output(
						'<!--[Begin: login form]-->',
						'<form id="qa-loginform" action="'.$login['url'].'" method="post">',
						'<DIV class="y">',
						'<table cellspace="0" cellpadding="1">',
						'<tbody>',




						'<tr>',
						'<td><label>用户名&nbsp&nbsp&nbsp</label></td>',
						'<td><input type="text" id="qa-userid" name="emailhandle" placeholder="'.trim(qa_lang_html('users/email_handle_label'), ':').'" /></td>',
						'<td><div id="qa-rememberbox"><input type="checkbox" name="remember" id="qa-rememberme" value="1"/><label for="qa-rememberme" id="qa-remember">自动登陆</label></div></td>',
						/*'<td><a href="http://127.0.0.1/discuz/member.php?mod=register&fromquestion2answer=question2answer">立即注册</a></td>',*/
						'</tr>',

						'<tr>',
						'<td><label>密码&nbsp&nbsp&nbsp</label></td>',
						'<td><input type="password" id="qa-password" name="password" placeholder="'.trim(qa_lang_html('users/password_label'), ':').'" /></td>',
						'<td><input type="submit" value="'.$login['label'].'" id="qa-login" name="dologin" /></td>',
						'</tr>',

						
						'<tr>',
					
						'<td><a class="qa-register" href="http://210.209.123.136/discuz/member.php?mod=register&fromq2a=q2a">注册</a></td>',
						'<td></td>',
						'<td></td>',
						'</tr>',



						'</tbody>',						
						'</table>',
						'</DIV>',
						'</form>',
						'<!--[End: login form]-->'
					);
				}
				unset($this->content['navigation']['user']['login']);
			}
			$this->nav('user');
		}

function nav($navtype, $level=null)
		{
			$navigation=@$this->content['navigation'][$navtype];
			
			if (($navtype=='user') || isset($navigation)) {
				$this->output('<DIV CLASS="qa-nav-'.$navtype.'">');
				
				if ($navtype=='user')
					$this->logged_in();
					
				// reverse order of 'opposite' items since they float right
				foreach (array_reverse($navigation, true) as $key => $navlink)
					if (@$navlink['opposite']) {
						unset($navigation[$key]);
						$navigation[$key]=$navlink;
					}
				
				$this->set_context('nav_type', $navtype);
				$this->nav_list($navigation, 'nav-'.$navtype, $level);
				$this->nav_clear($navtype);
				$this->clear_context('nav_type');
	
				$this->output('</DIV>');
			}
		}
		
function q_list_item($q_item)
		{
			$this->output('<DIV CLASS="qa-q-list-item'.rtrim(' '.@$q_item['classes']).'" '.@$q_item['tags'].'>');

	//		$this->q_item_stats($q_item);
			$this->q_item_main($q_item);
			$this->q_item_clear();

			$this->output('</DIV> <!-- END qa-q-list-item -->', '');
		}


		function logged_in() // adds points count after logged in username
		{
			qa_html_theme_base::logged_in();
			
			if (qa_is_logged_in()) {
				$userpoints=qa_get_logged_in_points();
				
				$pointshtml=($userpoints==1)
					? qa_lang_html_sub('main/1_point', '1', '1')
					: qa_lang_html_sub('main/x_points', qa_html(number_format($userpoints)));
						
				$this->output(
					'<SPAN CLASS="qa-logged-in-points">',
					'('.$pointshtml.')',
					'</SPAN>'
				);
			}
		}
    
/*
		function body_header() // adds login bar, user navigation and search at top of page in place of custom header content
		{
			$this->output('<div id="qa-login-bar"><div id="qa-login-group">');
			$this->nav_user_search();
            $this->output('</div></div>');
        }
		
*/




	function main()
		{

			$content=$this->content;

			$this->output('<DIV CLASS="qa-main'.(@$this->content['hidden'] ? ' qa-main-hidden' : '').'">');
			
			$this->widgets('main', 'top');
			
			//$this->page_title_error();		
			
			$this->widgets('main', 'high');

			/*if (isset($content['main_form_tags']))
				$this->output('<FORM '.$content['main_form_tags'].'>');*/
				
	

//print_r($_SERVER["REQUEST_URI"]);
//exit("<br>I AM HERE");
		
if($_SERVER["REQUEST_URI"]=="/uk-q2a/index.php"||$_SERVER["REQUEST_URI"]=="/uk-q2a/"||$_SERVER["REQUEST_URI"]=="/uk-q2a/index.php?fromq2a=q2a")
{
$this->myfunction();
}
else
{
$this->main_parts($content);
}		
			/*if (isset($content['main_form_tags']))
				$this->output('</FORM>');*/
				
			$this->widgets('main', 'low');

			$this->page_links();
			$this->suggest_next();
			
			$this->widgets('main', 'bottom');

			$this->output('</DIV> <!-- END qa-main -->', '');
		}



function myfunction()
{
$this->output('<DIV class="mybox" style="width:100%;height: 600px;">');
$this->left();
$this->right();
$this->output('</DIV>');
}

function left()
{
$this->output('<DIV class="left" style="float:left;">');
$this->announcement();
$this->commonproblems();
$this->problemcategory();
$this->output('</DIV>');
}

function right()
{
$this->output('<DIV class="right" style="">');
$this->coursecategory();
$this->latestproblems();
$this->latestreply();
$this->noreplyproblems();
$this->output('</DIV>');
}

function announcement()
{
$this->output('<DIV class="qa_uknotice" style="">');
$this->output('<H4 class="left_h4" id="notice_h4"><span class="h4_img"><img src="./qa-theme/SnowT/images/z_h4_lifrontn.jpg"/></span>公告<SPAN class="h4_span" ><A id="more_notice" HREF="./index.php?qa="questions"&qa_1="常-见-问-题"">MORE>></A></SPAN></H4>');

$this->output('<UL>');
$this->output('<LI id="f_a"><A>长株潭IT技术沙龙第一届成功举办</A></LI>');
$this->output('<LI><A>长株潭IT技术沙龙第二届成功举办</A></LI>');
$this->output('<LI><A>长株潭IT技术沙龙第三届成功举办</A></LI>');
$this->output('</UL>');

$this->output('</DIV>');

}

function commonproblems()
{
$commonproblem=$this->content['ukcommonproblem'];
$this->output('<DIV class="qa_ukcp" style="">');
$this->output('<H4 class="left_h4 problem_h4"><span class="h4_img"><img src="./qa-theme/SnowT/images/z_h4_lifront.jpg"/></span>常见问题<SPAN class="h4_span"><A HREF="./index.php?qa="questions"&qa_1="常-见-问-题"">MORE>></A></SPAN></H4>');
$this->output('<br>');
$this->output('<UL class="cp_ul">');
foreach($commonproblem as $commonpro)
{
	
$pre_link = explode("/",$commonpro['categorybackpath']);
$number = count($pre_link);
$problem_link = "./index.php?qa=questions";

for($i=$number-1,$j=1;$i>=0;$i--,$j++)
{
$problem_link =$problem_link."&qa_".$j."=".$pre_link[$i];
}	
	
$this->output('<LI><img src="./qa-theme/SnowT/images/z_dian.png"/><A HREF="./index.php?qa='.$commonpro['postid'].'&qa_1='.$commonpro['title'].'">');
/*$this->output($commonpro['title']);*/

if(strlen($commonpro['title'])>48) 
{
	$this->output(mb_substr($commonpro['title'],0,16,'utf-8')."…");
}
else{
	
	$this->output($commonpro['title']);
}


$this->output('</A>');

$this->output('<A class="qa_ukcnlink" href="'.$problem_link.'">');
$this->output('['.$commonpro['categoryname'].']');
$this->output('</A>');

/*$this->output($commonpro['tags']);*/
$this->output('</li>');
$this->output('<br>');
}
$this->output('</UL>');
$this->output('</DIV>');

}

function problemcategory()
{
$problemcategory=$this->content['ukproblemcategory'];
$this->output('<DIV class="qa_ukpc" style="">');
$this->output('<H4 class="left_h4 problem_h4" ><span class="h4_img"><img src="./qa-theme/SnowT/images/z_h4_lifront.jpg"/></span>问题种类<SPAN class="h4_span"><A HREF="./index.php?qa=categories">MORE>></A></SPAN></H4>');
$this->output('<br><div class="qa_ukpc1">');
foreach($problemcategory as $category)
{
$this->output('<img src="./qa-theme/SnowT/images/z_h4_lifront.jpg"/><A HREF="./index.php?qa=questions&qa_1='.$category["tags"].'">');
$this->output($category['title']);
$this->output('</A>');
$this->output('<SPAN>[');
$this->output($category['qcount']);
$this->output('个问题]</SPAN>');
$this->output('<br>');
}
$this->output('</div></DIV>');

}


function coursecategory()
{
$this->output('<DIV class="qa_ukcc" style="">');
$this->output('<H4 class="right_h4" id="cc_h4"><span class="h4_img"><img src="./qa-theme/SnowT/images/z_h4_lifrontc.jpg"/></span>教程分类<SPAN class="h4_span"><A id="more_cc" HREF="./index.php?qa=questions">MORE>></A></SPAN></H4>');

$this->output('<A href="#" class="c_img" id="f_cc"><img src="./qa-theme/SnowT/images/z_xitonanzhuang.png"></img><P>系统安装</P></A>');
$this->output('<A href="#" class="c_img"><img src="./qa-theme/SnowT/images/z_kuaishurumen.png"></img><P>快速入门</P></A>');
$this->output('<A href="#" class="c_img"><img src="./qa-theme/SnowT/images/z_yingyong.png"></img><P>软件应用</P></A>');
$this->output('<A href="#" class="c_img"><img src="./qa-theme/SnowT/images/z_gaojianzhuan.png"></img><P>高级操作</P></A>');

$this->output('</DIV>');


}

function latestproblems()
{
$problems=$this->content['uklatestproblem'];

//print_r($latesproblems);
//exit("I AM HERE");

$this->output('<DIV class="qa_uknp" style="">');
$this->output('<H4 class="right_h4 problem_h4"><span class="h4_img"><img src="./qa-theme/SnowT/images/z_h4_lifront.jpg"/></span>最新问题<SPAN class="h4_span"><A HREF="./index.php?qa=questions">MORE>></A></SPAN></H4>');
$this->output('<br><div class="qa_uknpli">');

foreach($problems as $problem)
{
	$pre_link = explode("/",$problem['categorybackpath']);
$number = count($pre_link);
$problem_link = "./index.php?qa=questions";

for($i=$number-1,$j=1;$i>=0;$i--,$j++)
{
$problem_link =$problem_link."&qa_".$j."=".$pre_link[$i];
}
	
$this->output('<img src="./qa-theme/SnowT/images/z_dian.png"/><A HREF="./index.php?qa='.$problem["postid"].'&qa_1='.$problem["title"].'">');
if(strlen($problem["title"])>96) 
{
	$this->output(mb_substr($problem['title'],0,32,'utf-8')."…");
}
else{
	
	$this->output($problem['title']);
}
$this->output('</A>');

$this->output('<A class="qa_ukcnlink" href="'.$problem_link.'">');
$this->output('['.$problem['categoryname'].']');
$this->output('</A>');


$this->output('<span id="qa_uktime">');
$this->output(qa_time_to_string(time()-$problem['created']));
$this->output('</span>');
$this->output('<br>');
}
$this->output('</DIV></DIV>');


}



function latestreply()
{

$reply=$this->content['ukreply'];
$x=0;

$this->output('<DIV class="qa_uklr">');

$this->output('<H4 class="right_h4 problem_h4"><span class="h4_img"><img src="./qa-theme/SnowT/images/z_h4_lifront.jpg"/></span>最新回复<SPAN class="h4_span"><A HREF="./index.php?qa=questions">MORE>></A></SPAN></H4>');
$this->output('<br><DIV class="qa_uklrli" >');


foreach($reply as $re)
{
if($x==7)
{
break;
}
else
{
$x++;
}

$pre_link = explode("/",$re['categorybackpath']);
$number = count($pre_link);
$problem_link = "./index.php?qa=questions";

for($i=$number-1,$j=1;$i>=0;$i--,$j++)
{
$problem_link =$problem_link."&qa_".$j."=".$pre_link[$i];
}


if(empty($re['handle']))
{
$this->output('匿名');
}
else
{
$this->output('<A HREF="./index.php?qa=user&qa_1='.$re['handle'].'">');
$this->output($re['handle']);
$this->output('</A>');
}






$this->output("　回答了　");
$this->output('<A HREF="./index.php?qa='.$re['postid'].'&qa_1='.$re['title'].'">');
if(strlen($re["title"])>75) 
{
	$this->output(mb_substr($re['title'],0,25,'utf-8')."…");
}
else{
	
	$this->output($re['title']);
}
$this->output("</A>");

$this->output('<A  class="qa_ukcnlink" href="'.$problem_link.'">');
$this->output('['.$re['categoryname'].']');
$this->output('</A>');

$this->output('<span id="qa_uktime">');
$this->output(qa_time_to_string(time()-$re['created']));
$this->output('</span>');
$this->output('<br>');


}


$this->output('</DIV></DIV>');

}

function noreplyproblems()
{
$noreply=$this->content['uknoreply'];

$this->output('<DIV class="qa_uknr">');

$this->output('<H4 class="right_h4 problem_h4"><LABLE><span class="h4_img"><img src="./qa-theme/SnowT/images/z_h4_lifront.jpg"/></span>零回复</LABLE><SPAN class="h4_span"><A HREF="./index.php?qa=unanswered">MORE>></A></SPAN></H4>');
$this->output('<br><DIV class="qa_uknrli">');

foreach($noreply as $zeroan)
{
$pre_link = explode("/",$zeroan['categorybackpath']);
$number = count($pre_link);
$problem_link = "./index.php?qa=questions";

for($i=$number-1,$j=1;$i>=0;$i--,$j++)
{
$problem_link =$problem_link."&qa_".$j."=".$pre_link[$i];
}	
	
$this->output('<img src="./qa-theme/SnowT/images/z_dian.png"/><A HREF="./index.php?qa='.$zeroan["postid"].'&qa_1='.$zeroan["title"].'">');

if(strlen($zeroan["title"])>96) 
{
	$this->output(mb_substr($zeroan["title"],0,32,'utf-8')."…");
}
else{
	
	$this->output($zeroan["title"]);
}
$this->output('</A>');

$this->output('<A class="qa_ukcnlink" href="'.$problem_link.'">');
$this->output('['.$zeroan['categoryname'].']');
$this->output('</A>');

$this->output('<span id="qa_uktime">');
$this->output(qa_time_to_string(time()-$zeroan['created']));
$this->output('</span>');
$this->output('<br>');
}
$this->output('</DIV></DIV>');
}


function page_title_error()
		{
			$favorite=@$this->content['favorite'];
			
			if (isset($favorite))
				$this->output('<FORM '.$favorite['form_tags'].'>');
				
			$this->output('<H4 class="mt">');
			$this->favorite();
			$this->title();
			$this->output('</H4>');

			if (isset($this->content['error']))
				$this->error(@$this->content['error']);

			if (isset($favorite))
				$this->output('</FORM>');
		}


		function nav_list($navigation, $class, $level=null)
		{
			$this->output('<UL CLASS="qa-'.$class.'-list'.(isset($level) ? (' qa-'.$class.'-list-'.$level) : '').'">');

			$index=0;
			
			foreach ($navigation as $key => $navlink) {
				if($key=="register")
				{
					continue;
				}
				$this->set_context('nav_key', $key);
				$this->set_context('nav_index', $index++);
				$this->nav_item($key, $navlink, $class, $level);
			}

			$this->clear_context('nav_key');
			$this->clear_context('nav_index');
			
			$this->output('</UL>');
		}
/*
		function nav_item($key, $navlink, $class, $level=null)
		{
		}
*/

		function header_custom() // allows modification of custom element shown inside header after logo
		{
			if (isset($this->content['body_header'])) {
				$this->output('<DIV CLASS="header-banner">');
				$this->output_raw($this->content['body_header']);
				$this->output('</DIV>');
			}
		}
		
		/*<a onclick="setHomepage('http://www.ubuntukylin.com/ukylin/');" href="javascript:;">设为首页</a>
<a onclick="addFavorite(this.href, 'UbuntuKylin技术论坛');return false;" href="http://www.ubuntukylin.com/ukylin/">收藏本站</a>*/
		
		function header() // removes user navigation and search from header and replaces with custom header content. Also opens new <DIV>s
		{	
			$this->output('<DIV CLASS="qa-header">');
			$this->output('<div class="qa_ukheadtop"><span class="qa_uktopleft"><a href="#" onclick="SetHome(this,window.location)" >设为首页</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#"    onclick="shoucang(document.title,window.location)" >收藏本站</a></span><span class="qa_uktopright"><img src="./qa-theme/SnowT/images/z_home.png"><a href="http://www.ubuntukylin.com" target="_blank">官方主页</a>&nbsp;&nbsp;<img src="./qa-theme/SnowT/images/z_weibo.png"><a href="http://weibo.com/u/3265288504" target="_blank">官方微博</a>&nbsp;&nbsp;<img src="./qa-theme/SnowT/images/z_weixing.png"><a href="#" target="_blank">官方微信</a></span>');
			$this->output('</div>');
			$this->logo();						
			$this->header_clear();

$this->nav_user_search();//WE ADD 

			$this->header_custom();

			$this->output('</DIV> <!-- END qa-header -->', '');

			$this->output('<DIV CLASS="qa-main-shadow">', '');
			$this->output('<DIV CLASS="qa-main-wrapper">', '');
			$this->nav_main_sub();

		}
		
		function footer() // prevent display of regular footer content (see body_suffix()) and replace with closing new <DIV>s
		{
			$this->output('</DIV> <!-- END main-wrapper -->');
			$this->output('</DIV> <!-- END main-shadow -->');		
		}		
		
		function title() // add RSS feed icon after the page title
		{
			qa_html_theme_base::title();
			
			$feed=@$this->content['feed'];
			
			if (!empty($feed))
				$this->output('<a href="'.$feed['url'].'" title="'.@$feed['label'].'"><img src="'.$this->rooturl.'images/rss.jpg" alt="" width="16" height="16" border="0" CLASS="qa-rss-icon"/></a>');
		}
		
		function q_item_stats($q_item) // add view count to question list
		{
			$this->output('<DIV CLASS="qa-q-item-stats">');
			
			$this->voting($q_item);
			$this->a_count($q_item);
			qa_html_theme_base::view_count($q_item);

			$this->output('</DIV>');
		}


function body()
		{
			$this->output('<BODY');
			$this->body_tags();
			$this->output('>');
			
			$this->body_script();
		//	$this->body_header();
			$this->body_content();
			$this->body_footer();
			$this->body_hidden();
			$this->output('</BODY>');
		}


		
		function view_count($q_item) // prevent display of view count in the usual place
		{
		}
		
		function body_suffix() // to replace standard question2answer footer
        {
			$this->output('<div class="qa-footer-bottom-group">');
			qa_html_theme_base::footer();
			$this->output('</DIV> <!-- END footer-bottom-group -->', '');
        }

function body_content()
		{
			$this->body_prefix();
			$this->notices();
			
			$this->output('<DIV CLASS="qa-body-wrapper">', '');

			$this->widgets('full', 'top');
			$this->header();
			$this->widgets('full', 'high');
//			$this->sidepanel();
			$this->main();

			$this->widgets('full', 'low');
			$this->footer();
			$this->widgets('full', 'bottom');
		
			$this->output('</DIV> <!-- END body-wrapper -->');
			
			$this->body_suffix();

		}
		
		function attribution()
		{
			$this->output(
				'<DIV CLASS="qa-attribution">',
				'&nbsp;|Copyright©2013 Ubuntu&nbsp;Kylin. All Rights Reserved .
 <a href="http://www.ubuntukylin.com">Ubuntu&nbsp;Kylin</a>版权所有',
				'</DIV>'
			);

			qa_html_theme_base::attribution();
		}

//下面是为了 ask-page的tag输出做准备
function form_fields($form, $columns)
		{
			if (!empty($form['fields'])) {
				foreach ($form['fields'] as $key => $field) {

					$this->set_context('field_key', $key);
					
					if (@$field['type']=='blank')
						$this->form_spacer($form, $columns);
					else
{
if(($key=="tags")&&(!empty($field["error"])))
{
$this->form_field_rows_for_ask_page_tag($form, $columns, $field);
}
else
{
						$this->form_field_rows($form, $columns, $field);
}

}
				}
						
				$this->clear_context('field_key');

			}
		}





function form_field_rows_for_ask_page_tag($form, $columns, $field)
		{



			$style=$form['style'];
			
			if (isset($field['style'])) { // field has different style to most of form
				$style=$field['style'];
				$colspan=$columns;
				$columns=($style=='wide') ? 3 : 1;
			} else
				$colspan=null;
			
			$prefixed=((@$field['type']=='checkbox') && ($columns==1) && !empty($field['label']));
			$suffixed=(((@$field['type']=='select') || (@$field['type']=='number')) && ($columns==1) && !empty($field['label'])) && (!@$field['loose']);
			$skipdata=@$field['tight'];
			$tworows=($columns==1) && (!empty($field['label'])) && (!$skipdata) &&
				( (!($prefixed||$suffixed)) || (!empty($field['error'])) || (!empty($field['note'])) );
			
			if (($columns==1) && isset($field['id']))
				$this->output('<TBODY ID="'.$field['id'].'">', '<TR>');
			elseif (isset($field['id']))
				$this->output('<TR ID="'.$field['id'].'">');
			else
				$this->output('<TR>');
			
			if (($columns>1) || !empty($field['label']))
				$this->form_label($field, $style, $columns, $prefixed, $suffixed, $colspan);
			
			if ($tworows)
				$this->output(
					'</TR>',
					'<TR>'
				);
			
			if (!$skipdata)
				$this->form_data_for_ask_page_tag($field, $style, $columns, !($prefixed||$suffixed), $colspan);
			
			$this->output('</TR>');
			
			if (($columns==1) && isset($field['id']))
				$this->output('</TBODY>');
		}





function form_data_for_ask_page_tag($field, $style, $columns, $showfield, $colspan)
		{
			if ($showfield || (!empty($field['error'])) || (!empty($field['note']))) {
				$this->output(
					'<TD CLASS="qa-form-'.$style.'-data"'.(isset($colspan) ? (' COLSPAN="'.$colspan.'"') : '').'>'
				);
							
				if ($showfield)
					$this->form_field($field, $style);
	
				if (!empty($field['error'])) {
					if (@$field['note_force'])
						$this->form_note($field, $style, $columns);
						
					$this->form_error($field, $style, $columns);
				$this->form_note($field, $style, $columns);
				} elseif (!empty($field['note']))
					$this->form_note($field, $style, $columns);
				
				$this->output('</TD>');
			}
		}






		
	}
	 

