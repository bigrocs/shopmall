<?php /* Smarty version 2.6.20, created on 2016-09-08 16:34:36
         compiled from pro_cat_shop_left.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'pro_cat_shop_left.htm', 7, false),)), $this); ?>
<div id="J_Category" class="category">
    <div class="navcatgory">
        <ul>
      	  	<?php $_from = $this->_tpl_vars['cat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['cList']):
?>
            <li class="j_MenuItem">
                <p>
                    <em style="<?php if ($this->_tpl_vars['cList']['pic']): ?>background-image:url(<?php echo $this->_tpl_vars['cList']['pic']; ?>
);><?php else: ?> padding-left:10px;<?php endif; ?>"><a target="_blank" href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/?m=product&s=list&id=<?php echo $this->_tpl_vars['cList']['catid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['cList']['cat'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 8, "") : smarty_modifier_truncate($_tmp, 8, "")); ?>
</a></em>
                </p>
            </li>
            <?php endforeach; endif; unset($_from); ?>
        </ul>
    </div>
    <div id="J_SubCategory" class="subCategory">
        <?php $_from = $this->_tpl_vars['cat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['cList']):
?>
        <div class="j_SubView fn-clear">
            <div class="catlist">  
                <?php $_from = $this->_tpl_vars['cList']['scat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['n'] => $this->_tpl_vars['sublist']):
?>
                <dl class="fore<?php echo $this->_tpl_vars['n']+1; ?>
">
                    <dt><a target="_blank" href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/?m=product&s=list&id=<?php echo $this->_tpl_vars['sublist']['catid']; ?>
"><?php echo $this->_tpl_vars['sublist']['cat']; ?>
</a></dt>
                    <dd>
                    <?php $_from = $this->_tpl_vars['sublist']['scat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['slist']):
?>
                    <a target="_blank" href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/?m=product&s=list&id=<?php echo $this->_tpl_vars['slist']['catid']; ?>
"><?php echo $this->_tpl_vars['slist']['cat']; ?>
</a>
                    <?php endforeach; endif; unset($_from); ?>
                    </dd>
                    
                    <?php if ($this->_tpl_vars['sublist']['brand']): ?>
                    <dd class="barnd">
                        <?php $_from = $this->_tpl_vars['sublist']['brand']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['lists']):
?>
                        <a target="_blank" href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/?m=product&s=list&brand=<?php echo $this->_tpl_vars['lists']['name']; ?>
"><?php echo $this->_tpl_vars['lists']['name']; ?>
</a>
                        <?php endforeach; endif; unset($_from); ?>
                    </dd>
                    <?php endif; ?>
                </dl>
                <?php endforeach; endif; unset($_from); ?>
            </div>
            <div class="advcat">
            <script src='<?php echo $this->_tpl_vars['config']['weburl']; ?>
/api/ad.php?id=6&catid=<?php echo $this->_tpl_vars['num']+1; ?>
&name=<?php echo $_GET['key']; ?>
'></script>
            </div>
        </div>
        <?php endforeach; endif; unset($_from); ?>
    </div>   
</div>