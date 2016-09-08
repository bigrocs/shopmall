
// 搜索框
$(function(){
	$(".s_main").find("input").focus(function(){
		$(".s-page").show().siblings().hide()
		$(".cc-search-hide").addClass("cc-search-show").removeClass("cc-search-hide")
		
	})
	$(".cc-search-tab > li").click(function(){
		$(this).addClass("cur").siblings("li").removeClass("cur");
		$("#m").val($(this).attr("data"))
		$("#s").val($(this).attr("data-s"))
	})
	$(".cc-back").click(function(){
		$(".s-page").hide().siblings().show()
		$(".cc-search-show").addClass("cc-search-hide").removeClass("cc-search-show")
	})

 	// 幻灯片效果
	$("#owl-demo").owlCarousel({
	  navigation : false, // Show next and prev buttons
	  slideSpeed : 200,
	  singleItem:true,
	  autoPlay:2000,
	});


	// js获取屏幕宽度，自动调节相应模块的宽高比例
 	var w=window.innerWidth|| document.documentElement.clientWidth|| document.body.clientWidth;
	if(w <=540)
	{
		var rate = w/540;
		var h = 67*100*rate/100;
		var h1 = 224*100*rate/100;
		$(".index_icon1").height(h+"px")
		$(".shopli").find("img").height(h1+"px")
	}
	else
	{
		$(".index_icon1").height("60px")
		$(".shopli").find("img").height("224px")
	}

});

