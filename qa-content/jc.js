/*
$(document).ready(function(){
	console.log("Hello World");
  	$.getJSON("./qa-content/jc.json",function(data){
  		console.log(data);
  		});
});
*/

function getdata(string)
{
	$.getJSON("./qa-content/jc.json",function(data){
  		console.log(data[string]);
  		return data[string];
  		});
}