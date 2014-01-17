var xmlHttp;

function showGonggao(request)
{
	var req=request;
	xmlHttp=GetXmlHttpObject();
	
	if(xmlHttp==null)
	{
		alert("浏览器不支持Http请求");
		return
	}
	var url="http://127.0.0.1/uk-q2a/qa-include/qa-process-gonggao.php";
	url=url+"?qa="+req;
	
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}

function stateChanged()
{
	if(xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{
		var response=xmlHttp.response;
		show(response);
	}
}

function show(response)
{
		//console.log(response);
		if(response.length==0)
		{
			$("#gonggao").append("没有公告，嘻嘻");
		}
		else
		{
			var $tr_1=$("<tr class=\"tr_title\"></tr>");
			var $td_1=$("<td></td>");
			$("#gonggao").append($tr_1);
			$(".tr_title").append($td_1).text("month");
		}
		
}

function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 // Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}