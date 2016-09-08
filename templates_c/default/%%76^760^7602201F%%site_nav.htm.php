<?php /* Smarty version 2.6.20, created on 2016-09-08 16:34:36
         compiled from site_nav.htm */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php if ($this->_tpl_vars['title']): ?><?php echo $this->_tpl_vars['title']; ?>
,<?php echo $this->_tpl_vars['config']['company']; ?>
<?php else: ?><?php echo $this->_tpl_vars['config']['title']; ?>
,<?php echo $this->_tpl_vars['config']['company']; ?>
<?php endif; ?>- Powered by MallBuilder</title>
<meta name="description" content="<?php echo $this->_tpl_vars['config']['description']; ?>
" />
<meta name="keywords" content="<?php echo $this->_tpl_vars['config']['keyword']; ?>
" />
<meta name="copyright" content="MallBuilder" />
<script src="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/script/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/script/base.js" type="text/javascript"></script>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/templates/<?php echo $this->_tpl_vars['config']['temp']; ?>
/css/page.css" rel="stylesheet" type="text/css" />
</head>
<body <?php if ($this->_tpl_vars['current'] == 'index'): ?>class="gray"<?php endif; ?>>
<div class="site-nav">
    <div class="w fn-clear">
    	<ul class="fn-left">
            <li class="nav<?php if ($_COOKIE['USER']): ?> drop-down<?php endif; ?> user">
				<script src="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/login_statu.php?m=index"></script>
            </li>
        	<li class="nav drop-down">
            	<div class="nav-fore1">
                    <a>手机逛<?php echo $this->_tpl_vars['config']['company']; ?>
</a>
                    <i><em></em></i>
                </div>
                <div class="nav-fore2">
                    <ul>
                        <li><a href="wap.php">扫一扫进入</a></li>
                        <li><a href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/?temp=wap">直接进入</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    	<ul class="fn-right">
        	<?php if ($this->_tpl_vars['current'] != 'index'): ?>
        	<li class="nav">
            	<div class="nav-fore1">
                	<a href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
"><?php echo $this->_tpl_vars['config']['company']; ?>
首页</a>
                </div>
            </li>
            <?php endif; ?>
        	<li class="nav drop-down">
            	<div class="nav-fore1">
                    <a href="main.php?cg_u_type=1">我的商城</a>
                    <i><em></em></i>
                </div>
                <div class="nav-fore2">
                    <ul>
                        <li><a href="main.php?m=product&s=admin_buyorder&cg_u_type=1">已买到的商品</a></li>
                        <li><a href="main.php?m=product&s=admin_footprint&cg_u_type=1">我的足迹</a></li>
                        <li><a href="<?php echo $this->_tpl_vars['config']['pay_url']; ?>
"><?php echo $this->_tpl_vars['config']['pay_name']; ?>
</a></li>
                    </ul>
                </div>
            </li>
        	<li class="nav cart drop-down">
            	<div class="nav-fore1">
                	<a href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
?m=product&s=cart">
                    <span>购物车</span><font><script src="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/?m=product&s=cart_number"></script></font>件
                    </a>
                    <i><em></em></i>
                </div>
                <div class="nav-fore2"></div>
            </li>
        	<li class="nav collect drop-down">
            	<div class="nav-fore1">
                    <a href="main.php?m=sns&s=admin_share_product&cg_u_type=1">
                    <span>收藏夹</span>
                    </a>
                    <i><em></em></i>
                </div>
                <div class="nav-fore2">
                    <ul>
                        <li><a href="main.php?m=sns&s=admin_share_product&cg_u_type=1">收藏的商品</a></li>
                        <li><a href="main.php?m=sns&s=admin_share_shop&cg_u_type=1">收藏的店铺</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav">
            	<div class="nav-fore1">
                <a href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
?m=product&s=cat">商品分类</a>
                </div>
            </li>
            <li class="site-nav-pipe">|</li>
            <li class="nav drop-down">
            	<div class="nav-fore1">
                    <?php if ($this->_tpl_vars['cominfo']['shop_type'] == 1): ?>
                    <a href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/main.php?m=shop&s=admin_step&shop_type=2&cg_u_type=2">卖家中心</a>
                    <?php else: ?>
                    <a href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/main.php?cg_u_type=2">卖家中心</a>
                    <?php endif; ?>
                   	<i><em></em></i>
                </div>
                <div class="nav-fore2">
                    <ul>
                        <li><a href="main.php?m=shop&s=admin_step&cg_u_type=2">免费开店</a></li>
                        <li><a href="main.php?m=product&s=admin_sellorder&cg_u_type=2">已卖出的商品</a></li>
                        <li><a href="main.php?m=product&s=admin_product_list&cg_u_type=2">出售中的商品</a></li>
                    </ul>
                </div>
            </li>
        	<li class="nav">
            	<div class="nav-fore1"><a href="aboutus.php?type=8">联系客服</a></div>
            </li>
        </ul>
	</div>
</div>
<script type="text/javascript">
$('.drop-down').hover(function(){
	$(this).addClass("hover");					 
	if($(this).hasClass("cart"))
	{
		$('.cart .nav-fore2').load('<?php echo $this->_tpl_vars['config']['weburl']; ?>
/?m=product&s=cart_number&act=list');
	}
},function(){
	$(this).removeClass("hover");	
});
</script>