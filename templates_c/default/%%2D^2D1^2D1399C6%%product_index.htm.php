<?php /* Smarty version 2.6.20, created on 2016-09-08 16:34:36
         compiled from product_index.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('insert', 'getNotice', 'product_index.htm', 21, false),array('insert', 'label', 'product_index.htm', 68, false),)), $this); ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/script/jquery.flexslider-min.js"></script>
<link href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/script/jcarousel/skins/tango/skin.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/templates/<?php echo $this->_tpl_vars['config']['temp']; ?>
/css/product.css" rel="stylesheet" type="text/css" />
<div class="w fn-clear">
	<div class="adv">
    	<div class="adv787X327">
			<script src='<?php echo $this->_tpl_vars['config']['weburl']; ?>
/api/ad.php?id=1&catid=<?php echo $_GET['id']; ?>
&name=<?php echo $_GET['key']; ?>
'></script>
        </div>
        <div class="adv787X161">
        	<script src='<?php echo $this->_tpl_vars['config']['weburl']; ?>
/api/ad.php?id=2'></script>
        </div>
    </div>
    <div class="right_side">
		<?php if ($this->_tpl_vars['member']['user']): ?>
        <div class="notice">
        	<div class="notice-hd fn-clear">
            	<ul>
            		<li class="cur">公告</li>
            	</ul>
            </div>
            <ul class="notice-bd fn-clear"><?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'getNotice', 'limit' => 4)), $this); ?>
</ul>
        </div> 
        <dl class="member">
          	<dt>
                <a target="_blank" href="main.php">
                <img width="60" height="60" src="<?php echo $this->_tpl_vars['member']['logo']; ?>
">
                Hi! <strong><?php echo $this->_tpl_vars['member']['user']; ?>
</strong> 
                </a>
                <img align="absmiddle" src="<?php echo $this->_tpl_vars['member']['pic']; ?>
">
                <p class="fn-clear">
                <a target="_blank" href="main.php">赚积分</a>
                <a target="_blank" href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
?m=points" class="last">积分商城</a>
                </p>
          	</dt>
            <dd class="fn-clear">
                <a href="main.php?m=product&s=admin_buyorder&status=3&cg_u_type=1" target="_blank"><strong><?php echo $this->_tpl_vars['count']['1']; ?>
</strong>待收货</a>
                <a class="pay" href="main.php?m=product&s=admin_buyorder&status=1&cg_u_type=1" target="_blank"><strong><?php echo $this->_tpl_vars['count']['0']; ?>
</strong>待付款</a>
                <a href="main.php?m=product&s=admin_buyorder&rate=1&cg_u_type=1" target="_blank"><strong><?php echo $this->_tpl_vars['count']['2']; ?>
</strong>待评价</a>
            </dd>
		</dl>         
		<?php else: ?>
		<dl class="right_side_1">
            <dt>公告</dt>
            <dd class="fore1"><?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'getNotice', 'limit' => 4)), $this); ?>
</dd>
            <dd class="fore2">
                <a class="login" href="login.php"></a>
                <a class="register" href="<?php echo $this->_tpl_vars['config']['regname']; ?>
"></a>
            </dd>
        </dl>
		<?php endif; ?>
        <dl class="buying">
            <dt>即时抢购</dt>
            <dd class="adv196X110">
            <script src='<?php echo $this->_tpl_vars['config']['weburl']; ?>
/api/ad.php?id=3'></script>
            </dd>
        </dl>
	</div>
</div>

<div class="w mt20 fn-clear">
    <dl class="hot">
        <dt class="dt">
        	<span class="current">疯狂抢购<b></b></span>
        	<span>热卖产品<b></b></span>
        	<span>新品上架<b></b></span>
        </dt>    
        <dd>
            <div class="i-mc fn-clear"><?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'label', 'type' => 'product', 'proid' => $this->_tpl_vars['config']['newpro'], 'temp' => 'product_list_li_1', 'limit' => 3)), $this); ?>
</div>
            <div class="i-mc fn-clear fn-hide"><?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'label', 'type' => 'product', 'proid' => $this->_tpl_vars['config']['hotpro'], 'temp' => 'product_list_li_1', 'limit' => 3)), $this); ?>
</div>
            <div class="i-mc fn-clear fn-hide"><?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'label', 'type' => 'product', 'temp' => 'product_list_li_1', 'limit' => 3)), $this); ?>
</div>
        </dd>
    </dl>
    <div class="right_side h470">
    	<div class="adv226X470">
    	<script src='<?php echo $this->_tpl_vars['config']['weburl']; ?>
/api/ad.php?id=4'></script>
        </div>
    </div>
</div>

<div class="w adv1200X119">
    <script src='<?php echo $this->_tpl_vars['config']['weburl']; ?>
/api/ad.php?id=5'></script>
</div>

<div class="w mt20 fn-clear">
	<div class="left fn-clear">
    <?php $_from = $this->_tpl_vars['categorys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['slist']):
?>
    <dl>
    	<div class="i-m">
            <dt style="border-left-color:<?php echo $this->_tpl_vars['slist']['color']; ?>
"><a target="_blank" href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
?m=product&s=list&id=<?php echo $this->_tpl_vars['slist']['catid']; ?>
"><?php echo $this->_tpl_vars['slist']['name']; ?>
</a></dt>
            <dd class="fn-clear">
                <ul class="fn-clear"><?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'label', 'catid' => $this->_tpl_vars['slist']['catid'], 'type' => 'product', 'o' => 6, 'temp' => 'product_list_li_2', 'limit' => 3)), $this); ?>
</ul>
                <ul class="cat fn-clear">
                <?php $_from = $this->_tpl_vars['slist']['scat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['cat']):
?>
                		<li><a target="_blank" href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
?m=product&s=list&id=<?php echo $this->_tpl_vars['cat']['catid']; ?>
"><?php echo $this->_tpl_vars['cat']['cat']; ?>
</a></li>
				<?php endforeach; endif; unset($_from); ?>
                </ul>        
            </dd>
		</div>
    </dl>
    <?php endforeach; endif; unset($_from); ?>
	</div>
    <div class="right">
    	<dl class="sns">
        	<dt class="fn-clear">
            	<a target="_blank" href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/?m=sns">去广场看看</a>
            	<h2>广场动态</h2>
            </dt>
            <dd><?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'label', 'type' => 'sns', 'temp' => 'sns_list_1', 'limit' => 7)), $this); ?>
</dd>
        </dl>
    </div>
</div>
<script type="text/javascript">
$(".dt span").hover(function(){
	var index=$(this).index();
	$(this).addClass("current").siblings().removeClass("current");
	$(this).parent().parent().find(".i-mc").eq(index).show().siblings(".i-mc").hide();
});
</script>