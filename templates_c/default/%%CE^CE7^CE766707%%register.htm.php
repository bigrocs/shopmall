<?php /* Smarty version 2.6.20, created on 2016-09-08 16:44:07
         compiled from register.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'unserialize', 'register.htm', 6, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "site_nav.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<link href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/templates/default/css/register.css" rel="stylesheet" type="text/css" />
<script>
var strongpw = new Array();
<?php if ($this->_tpl_vars['config']['strongpw']): ?>
<?php $this->assign('array', ((is_array($_tmp=$this->_tpl_vars['config']['strongpw'])) ? $this->_run_mod_handler('unserialize', true, $_tmp) : unserialize($_tmp))); ?>
<?php $_from = $this->_tpl_vars['array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['list']):
?>
strongpw[<?php echo $this->_tpl_vars['key']; ?>
] = <?php echo $this->_tpl_vars['list']; ?>
;
<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
var pwlength = "<?php echo $this->_tpl_vars['config']['pwlength']; ?>
";
var user_reg = "<?php echo $this->_tpl_vars['config']['user_reg']; ?>
";
var only_mobole_reg = false;
</script>
<div class="w">
    <div class="header">
        <a hidefocus="true" title="<?php echo $this->_tpl_vars['config']['company']; ?>
" href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
"><img height="35" src="<?php if ($this->_tpl_vars['config']['logo']): ?><?php echo $this->_tpl_vars['config']['logo']; ?>
<?php else: ?><?php echo $this->_tpl_vars['config']['weburl']; ?>
/image/logo.gif<?php endif; ?>" /></a>
        <h2>账户注册</h2>
    </div>
    <?php if ($this->_tpl_vars['config']['closetype'] == 2): ?>
    <div style="height:200px;padding-top:150px;border: 1px solid #A9B9D3;text-align:center;font-size:16px"><?php echo $this->_tpl_vars['config']['closecon']; ?>
</div>
    <?php else: ?>
    <div class="steps step-1">
        <ol>
            <li class="step1">1 填写账户信息</li>
            <li class="step2">2 验证账户信息</li>
            <li class="step3">3 注册成功</li>
        </ol>	
	</div>
    <div class="register clearfix bg">
        <form action="" method="post">
            <div class="item fore1">
                <span>账户名：</span>
                <div class="item-ifo">
                    <input name="user" type="text" id="user" class="text" placeholder="邮箱/用户名/手机号" />
                    <div class="i-name ico"></div>
                </div>
                <div class="msg-box"><div></div></div>
            </div>
            
			<div class="item fore2">
                <span>请设置密码：</span>
                <div class="item-ifo">
                    <input type="password"  name="password" id="password" class="text" />
                    <div class="i-pass ico"></div>
                </div>
                <div class="msg-box"><div></div></div>
            </div>
            
            <div class="item fore3">
                <span>请确认密码：</span>
                <div class="item-ifo">
                    <input type="password" name="re_password" id="re_password" class="text" />
                    <div class="i-pass ico"></div>
                </div>
                <div class="msg-box"><div></div></div>
            </div>
            
            <div class="item fore4">
                <span>验证码：</span>
                <div class="item-ifo">
                    <input name="yzm" type="text" id="yzm" class="text w100" />
                    <div class="yzm">
                        <input type="button" class="send hidden" data-type="email" value="获取邮件验证码" />
                        <img onclick="get_randfunc(this);" src='<?php echo $this->_tpl_vars['config']['weburl']; ?>
/includes/rand_func.php?h=37&w=100'/>
                    </div>
                </div>
				<div class="msg-box"><div></div></div>
            </div>
            
            <?php if ($this->_tpl_vars['config']['user_reg_verf']): ?>
            <div class="item fore7">
                <span>验证问题：</span>
                <div class="item-ifo">
                    <input onFocus="show_yzwt();" name="ckyzwt" type="text" id="ckyzwt" class="text w100" onclick="show_yzwt();"  />
                    <span id="yzwt"></span>
                </div>
				<div class="msg-box"><div></div></div>
            </div> 
            <?php endif; ?> 
            
            <div class="item register-btn">
                <input type="button" class="submit" value="同意协议并注册" />
                <p class="read"><em><?php echo $this->_tpl_vars['config']['company']; ?>
用户注册协议</em></p>
                <div class='agreement hidden'><?php echo $this->_tpl_vars['config']['association']; ?>
</div>
                <input name="forward" type="hidden" id="forward" value="<?php echo $_GET['forward']; ?>
" />
                <input name="connect_id" type="hidden" id="forward" value="<?php echo $_GET['connect_id']; ?>
" />
            </div>
        </form>
    </div> 
 	<?php endif; ?>
</div>
<script src="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/script/register.js" type="text/javascript"></script>
<div class="clear"></div>
<div class="footer clearfix">
    <div class="w">
        <div class="links"><?php echo $this->_tpl_vars['web_con']; ?>
</div>
        <div class="copyright"><?php echo $this->_tpl_vars['bt']; ?>
</div>
    </div>
</div>
</body>
</html>