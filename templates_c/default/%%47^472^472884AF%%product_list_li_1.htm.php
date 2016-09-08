<?php /* Smarty version 2.6.20, created on 2016-09-08 16:34:36
         compiled from product_list_li_1.htm */ ?>
<?php $_from = $this->_tpl_vars['pro']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['list']):
?>
<li class="fore<?php echo $this->_tpl_vars['num']+1; ?>
">
<div class="p-img">
    <a target="_blank" href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/?m=product&s=detail&id=<?php echo $this->_tpl_vars['list']['id']; ?>
">
        <img src='<?php echo $this->_tpl_vars['list']['pic']; ?>
' />
    </a>
</div>
<div class="p-name"><a target="_blank" href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/?m=product&s=detail&id=<?php echo $this->_tpl_vars['list']['id']; ?>
"><?php echo $this->_tpl_vars['list']['pname']; ?>
</a></div>
<div class="p-price">
    <strong><em><?php echo $this->_tpl_vars['config']['money']; ?>
</em><?php echo $this->_tpl_vars['list']['price']; ?>
</strong>
    <s><?php echo $this->_tpl_vars['config']['money']; ?>
<?php echo $this->_tpl_vars['list']['market_price']; ?>
</s>
</div>
<a class="btn" target="_blank" href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/?m=product&s=detail&id=<?php echo $this->_tpl_vars['list']['id']; ?>
"></a>
</li>
<?php endforeach; endif; unset($_from); ?>