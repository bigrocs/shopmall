// 漂浮插件
$(".taoplus").click(function(){

	var obj = $(this).find("div.circle")
	var obj2 = $(this).find("div.tpbtn")
	
	if(obj.hasClass("hide"))
	{
		obj.removeClass("hide").addClass("show")
		obj2.removeClass("on").addClass("off")
	}
	else
	{
		obj.removeClass("show").addClass("hide")
		obj2.removeClass("off").addClass("on")
	}
})
$(".tpicons").find("li").click(function(){
	location.href=$(this).find("a").attr("dataurl")
})

// 漂浮插件
$("body").append("<script type='text/javascript' src='script/taoplus.js'></script>");

