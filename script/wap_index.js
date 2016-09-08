
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

	function getPositionError(error) {
      //  HTML5 定位失败时，调用百度地图定位   
        var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function(r){
            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                var mk = new BMap.Marker(r.point);
                var pt = r.point;

                $.cookie("lng",pt.lng,{expires:7});
                $.cookie("lat",pt.lat,{expires:7});

                $.post("ajax_back_end.php",{"act":"reposition","lng":pt.lng,"lat":pt.lat},function(){})
                              
            }
        },{enableHighAccuracy: true});

    }

    function getPositionSuccess( position ){
		var lat = position.coords.latitude;
		var lng = position.coords.longitude;

		var ggPoint = new BMap.Point(lng,lat);

		//转换成百度地图坐标
		var trunback = function (point){

		     $.cookie("lng",point.lng,{expires:7});
		     $.cookie("lat",point.lat,{expires:7});

		     $.post("ajax_back_end.php",{"act":"reposition","lng":point.lng,"lat":point.lat},function(){})
		}

		BMap.Convertor.translate(ggPoint,0,trunback);     
    }



	// 先HTML5定位，定位不到再百度地图定位
	if(!$.cookie("lng")  || !$.cookie("lat"))
	{
		var position_option = {enableHighAccuracy: true,maximumAge: 30000,timeout: 20000};
        navigator.geolocation.getCurrentPosition(getPositionSuccess, getPositionError, position_option);
	}
	else
	{
	    var t_lng = $.cookie("lng")
	    var t_lat = $.cookie("lat")

	    $.post("ajax_back_end.php",{"act":"reposition","lng":$.cookie("lng"),"lat":$.cookie("lat")},function(){})
	    
	}

	// 首页附近的店铺异步获取信息
	function getHtmlSecond()
	{
		$.post("ajax_back_end.php",{"act":"getNearInfo"},function(data){
			if(data == "1")
			{
				var t = setTimeout(getHtmlSecond,800);
			}
			else
			{
				$(".nearShop").html(data);
			}
		});
	}

	if($(".nearShop").length > 0)
	{
		getHtmlSecond();
	}
});

$(document).scroll(function(){
	var h = $(document).scrollTop();
	if(h > 50)
	{
		$(".main").css({"position":"fixed","top":0,"width":"100%"})
		$(".m_main").eq(0).css({"margin-top":"50px"})
	}
})
