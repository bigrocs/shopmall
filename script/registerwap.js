// JavaScript Document
var pwlength = pwlength ? pwlength : 4;
var tips_user="请输入手机号。";
var tips_password="请填写密码,最小长度为"+pwlength+" 个字符。";
var tips_re_password="再输一次密码";
var tips_yzm="";
var tips_ckyzwt="";
var tips_email="请输入您常用的电子邮箱，以方便日后找回密码。";
var error="不能为空";
var right="";

var error_user="该用户名已被使用，请重新输入。";
var error_user1="用户名长度只能在4-20位字符之间";
var error_user2="用户名不能是纯数字，请确认输入的是手机号或者重新输入";
var error_user3="用户名只能由中文、英文、数字及\"_\"、\"-\"组成";

var error_password1="密码太短，不得少于 "+pwlength+" 个字符";
var error_password2="该密码比较简单，密码中必须包含";
var error_re_password="两次密码输入不一致";

var error_yzm="验证码错误";
var error_ckyzwt="验证问题错误";

var error_email="该邮箱已注册，请更换其他邮箱";
var error_email1="邮箱格式错误";

var error_mobile="该手机已注册，请更换其他手机";
var error_mobile1="手机格式错误";

var flag;
var flag1;
var flag2;
$(function(){
	$("form").find(".text").each(function(){
		$(this).focus(function(){ 
			var val=$(this).val();
			var name=$(this).attr("name");
			var div=$(this).parent().next().children("div");
			if(name)
			{
				if(!val){
					div.attr('class','tips').html(eval('tips_'+name));
				}
			}
		});	
		$(this).blur(function(){ 
			var name=$(this).attr("name");
			eval("check_"+name+"($(this))");
		});
	});
	$(".submit").click(function(){
		var arr_flag = new Array();
		$("form").find(".text").each(function(){
			var name=$(this).attr("name");
			if(name)
			{
				arr_flag.push(eval("check_"+name+"($(this))"));
			}
		});
		if(arr_flag.in_array(false)){
			return false;	
		}
		else{
			$("form").submit();		
		}
	});
	$("form .read em").click(function(){
		if($(".agreement").css("display")=='block')
			$(".agreement").hide();
		else
			$(".agreement").show();
	});
});
function check_email(obj)
{
	var val=obj.val();
	var div=obj.parent().next().children("div");
	var patrn = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(!val){
		obj.addClass('red');
		div.attr('class','error').html(error);return false;
	}
	else if(!patrn.test(val)){  
		obj.addClass('red');
		div.attr('class','error').html(error_email1);return false;
	}
	else{
		var url = 'ajax_back_end.php';
		var sj = new Date();
		var pars = 'shuiji=' + sj+'&verify_type=email&verify_field='+val; 
		$.post(url, pars, function (originalRequest)
		{
			if(originalRequest=="true"){
				obj.removeClass('red');
				div.attr('class','true').html(right);
				flag=true;
			}
			else{				
				obj.addClass('red');
				div.attr('class','error').html(error_email);
				flag=false;
			}
			return flag;
		});
		return flag;
	}
}
function check_mobile(obj)
{
	var val=obj.val();
	var div=obj.parent().next().children("div");
	var patrn = /^1\d{10}$/;
	if(!val){
		obj.addClass('red');
		div.attr('class','error').html(error);return false;
	}
	else if(!patrn.test(val)){  
		obj.addClass('red');
		div.attr('class','error').html(error_mobile1);return false;
	}
	else{
		var url = 'ajax_back_end.php';
		var sj = new Date();
		var pars = 'shuiji=' + sj+'&verify_type=mobile&verify_field='+val; 
		$.post(url, pars, function (originalRequest)
		{
			if(originalRequest=="true"){
				obj.removeClass('red');
				div.attr('class','true').html(right);
				flag=true;
			}
			else{			
				obj.addClass('red');
				div.attr('class','error').html(error_mobile);
				flag=false;
			}
			return flag;
		});
		return flag;
	}
}
function check_user(obj)
{
	
	var val	= obj.val();
	var div	= obj.parent().next().children("div");
	var mobile = new RegExp("^0?(13|15|17|18|14)[0-9]{9}$");//手机
	var username = new RegExp("^[A-Za-z0-9_\\-\\u4e00-\\u9fa5]+$");//用户名
	var fullNumber= new RegExp("^[0-9]+$");//数字
	if(!val){
		obj.addClass('red');
		div.attr('class','error').html(error);return false;
	}
	else if(val.indexOf("@") > -1){
		showcode("email");
		return check_email(obj);
	}
	else if(mobile.test(val)){
		showcode("mobile");
		return check_mobile(obj);
	}
	else{
		showcode("username");
		if(!betweenLength(val.replace(/[^\x00-\xff]/g, "**"), 4, 20)){
			obj.addClass('red');
			div.attr('class','error').html(error_user1);return false;
		}
		else if(!username.test(val)){
			obj.addClass('red');
			div.attr('class','error').html(error_user3);return false;
		}
		else if(fullNumber.test(val)){
			obj.addClass('red');
			div.attr('class','error').html(error_user2);return false;
		}
		else{
			var url = 'ajax_back_end.php';
			var sj = new Date();
			var pars = 'shuiji=' + sj+'&verify_type=user&verify_field='+val; 
			$.post(url, pars, function (originalRequest)
			{
				if(originalRequest=="true"){
					obj.removeClass('red');
					div.attr('class','true').html(right);
					flag=true;
				}
				else{			
					obj.addClass('red');
					div.attr('class','error').html(error_user);
					flag=false;
				}
				return flag;
			});
			return flag;
		}
	}
}
function check_password(obj){
	var val=obj.val();
	var div=obj.parent().next().children("div");
	if(!val){
		obj.addClass('red');
		div.attr('class','error').html(error);
		return false;
	}
	else if(getByteLen(val) < pwlength ){
		obj.addClass('red');
		div.attr('class','error').html(error_password1);
		return false;
	}
	if(strongpw) {
		var strongpw_error = false, j = 0;
		var strongpw_str = new Array();
		for(var i in strongpw) {
			if(strongpw[i] === 1 && !val.match(/\d+/g)) {
				strongpw_error = true;
				strongpw_str[j] = '数字';
				j++;
			}
			if(strongpw[i] === 2 && !val.match(/[a-z]+/g)) {
				strongpw_error = true;
				strongpw_str[j] = '小写字母';
				j++;
			}
			if(strongpw[i] === 3 && !val.match(/[A-Z]+/g)) {
				strongpw_error = true;
				strongpw_str[j] = '大写字母';
				j++;
			}
			if(strongpw[i] === 4 && !val.match(/[^A-Za-z0-9]+/g)) {
				strongpw_error = true;
				strongpw_str[j] = '特殊符号';
				j++;
			}
		}
		if(strongpw_error) {
			msg = error_password2 + strongpw_str.join('，');
			div.attr('class','error').html(msg);
			return false;
		}
	}
	obj.removeClass('red');
	div.attr('class','true').html(right);return true;
}
function check_re_password(obj){
	var val=obj.val();
	var div=obj.parent().next().children("div");
	if(!val){
		obj.addClass('red');
		div.attr('class','error').html(error);return false;
	}
	else if(val!=$("#password").val()){
		obj.addClass('red');
		div.attr('class','error').html(error_re_password);return false;
	}
	else{
		obj.removeClass('red');
		div.attr('class','true').html(right);return true;
	}
}
function check_yzm(obj){
	
	var val=obj.val();
	var div=obj.parent().next().children("div");
	var url = 'ajax_back_end.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&yzm='+val; 
	if(!val){
		obj.addClass('red');
		div.attr('class','error').html(error);return false;
	}
	else 
	{
		$.get(url, pars, function(originalRequest){
			if(originalRequest>0)
			{	
				obj.addClass('red');
				div.attr('class','error').html(error_yzm);
				flag1=false;
			}
			else
			{
				obj.removeClass('red');
				div.attr('class','true').html(right);
				flag1=true;
			}
			return flag1;
		});
		return flag1;
	}
}
function check_ckyzwt(obj)
{
	
	var val=obj.val();
	var div=obj.parent().next().children("div");
	var url = 'ajax_back_end.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&ckyzwt='+$('#ckyzwt').val(); 
	if(!val){
		obj.addClass('red');
		div.attr('class','error').html(error);return false;
	}
	else 
	{
		$.post(url, pars,function (originalRequest)
		{
			if(originalRequest=='true')
			{	
				obj.removeClass('red');
				div.attr('class','true').html(right);
				flag2=true;
			}
			else
			{
				obj.addClass('red');
				obj.val('');
				div.attr('class','error').html(error_ckyzwt);
				flag2=false;
			}
			return flag2;
		});
		return flag2;
	}
}
function getByteLen(val){
	var len = 0;
	for (var i = 0; i < val.length; i++){
		var a = val.charAt(i);
		if (a.match(/[^\x00-\xff]/ig) != null){
			len += 2;
		}
		else{
			len += 1;
		}
	}
	return len;
}
function show_yzwt()
{
	var url = 'ajax_back_end.php';
	var sj = new Date();
	var pars = 'shuiji=' + sj+'&wtyz=1'; 
	$.post(url, pars,function (originalRequest){
		if(originalRequest)
			$('#yzwt').html(originalRequest);
	});
}
function betweenLength(str, _min, _max) {
	return (str.length >= _min && str.length <= _max);
}
function showcode(type)
{
	if(type == "mobile" && user_reg== 3){
		$(".fore4 span").html('短信验证码：');
		$(".fore4 .yzm input").val('获取短信验证码');
		$(".fore4 .yzm input").attr('data-type','mobile');
		$(".fore4 .yzm input").show();
		$(".fore4 .yzm img").hide();
	}
	else if(type == "email" && user_reg == 2){
		$(".fore4 span").html('邮件验证码：');
		$(".fore4 .yzm input").val('获取邮件验证码');
		$(".fore4 .yzm input").attr('data-type','email');
		$(".fore4 .yzm input").show();
		$(".fore4 .yzm img").hide();
	}
	else{
		$(".fore4 span").html('验证码：');
		$(".fore4 .yzm input").hide();
		$(".fore4 .yzm img").show();
	}
}
Array.prototype.in_array = function(e)
{
	
    for(i=0;i<this.length;i++)
    {
		if(this[i] == e)
		{
			return true;
		}
    }
    return false;
}
$(".send").click(function(){
						
	var type = $(this).attr("data-type");
		
	var F = check_user($("#user"));
	if(F == false) return false;
	var val = $("#user").val();
	if(type == 'email')
	{		
		msg = "获取邮件验证码";
		$(".send").attr("disabled", "disabled");
		$("#user").attr("disabled", "disabled");
		$("#user").attr("readonly", "readonly");
		t=setTimeout(countDown,1000);

		var url = 'ajax_back_end.php';
		var sj = new Date();
		var pars = 'shuiji=' + sj +'&email='+val; 
		$.post(url, pars, function (originalRequest){})
	}
	if(type == 'mobile')
	{
		msg = "获取短信验证码";
		$(".send").attr("disabled", "disabled");
		t=setTimeout(countDown,1000);
		var url = 'ajax_back_end.php';
		var sj = new Date();
		var pars = 'shuiji=' + sj +'&mobile='+val; 
		$.post(url, pars, function (originalRequest){})
	}
})
var delayTime = 60;
function countDown()
{
	delayTime--;
	$(".send").val(delayTime + '秒后重新获取');
	if (delayTime == 0) {
		delayTime = 60;
		$(".send").val(msg);
		$(".send").removeAttr("disabled");
		$("#user").removeAttr("disabled");
		clearTimeout(t);
	}
	else
	{
		t=setTimeout(countDown,1000);
	}
}