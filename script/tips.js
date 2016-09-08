$(".fl").append("<script src='script/my_lightbox.js'></script>")
$(".fl").append("<script src='script/jquery.cookie.js'></script>")
var str="<style>.titleBar td{padding:5px;}.titleBar b{color:#fff}.titleBar{background:#DD1515;}.closeBtn{display:none;}</style>";
$(".fl").append(str)
$(function(){
	if(!$.cookie("alttips"))
		alertWin('绑定邮箱提醒', '', 322, 153, weburl+"/?m=member&s=bind_email_tips");
})