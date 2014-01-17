// JavaScript Document
var staticflag=1;
var ullength=0;
var h_idnumber=1;
$(document).ready(function(){
	changecont(staticflag);  //XXX是id号为XXX的元素，注意b.html的路径	
	ullength=$("#myul li").length;
	document.getElementById("co_a"+1).style.background = "url('./qa-theme/SnowT/images/c_ul_li.png')";
document.getElementById("co_a"+1).style.color = "#53a5ce";
//var ullength=document.getElementById('myul').getElementsByTagName('li').length();//获取

ul的li的长度

	});

function changecont(idnumber)//改变内容和title值
{
	//行赋给一个中间值
	
    $('#frameContent1').load('./qa-content/course/course'+idnumber+'.html',null,function(){}) ; 
	
	document.getElementById("co_rh2").innerHTML=document.getElementById

("co_a"+idnumber).innerHTML;//改变title		
	document.getElementById("co_a"+idnumber).style.background = "url('./qa-theme/SnowT/images/c_ul_li.png')";

document.getElementById("co_a"+idnumber).style.color = "#53a5ce";

	
	document.getElementById("co_a"+h_idnumber).style.background="#dfeef6";

document.getElementById("co_a"+h_idnumber).style.color = "#1c3f5a";		/*var c="'#co_a"+idnumber+"'";
   $(c).css("background","red");*/
   staticflag=idnumber;
   h_idnumber=idnumber;
}
function changeNext(){//改变下一节
	
	if(staticflag==ullength)  
	{
		staticflag=1;
		
		
		}
		else{
	staticflag=staticflag+1;
		}

	changecont(staticflag);
	
	}
function changeLast(){//改变上一节
	
	if(staticflag<=1)  
	{
		staticflag=ullength;
		
		}
		else
		{
	staticflag=staticflag-1;
		}
	changecont(staticflag);
	}
