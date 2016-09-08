<?php /* Smarty version 2.6.20, created on 2016-09-08 16:34:36
         compiled from friendlink.htm */ ?>
<?php $_from = $this->_tpl_vars['textlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
    <a href="<?php echo $this->_tpl_vars['list']['url']; ?>
" title="<?php echo $this->_tpl_vars['list']['name']; ?>
" target="_blank" ><?php echo $this->_tpl_vars['list']['name']; ?>
</a>
<?php endforeach; endif; unset($_from); ?>