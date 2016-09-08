<?php /* Smarty version 2.6.20, created on 2016-09-08 16:34:36
         compiled from search_word.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'urlencode', 'search_word.htm', 2, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['sword']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['list']):
?>
<a  <?php if ($this->_tpl_vars['num'] == 0): ?> class='first' <?php endif; ?> target="_blank" href="<?php echo $this->_tpl_vars['config']['weburl']; ?>
/?m=product&s=list&key=<?php echo ((is_array($_tmp=$this->_tpl_vars['list']['keyword'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
"><?php echo $this->_tpl_vars['list']['keyword']; ?>
</a>
<?php endforeach; endif; unset($_from); ?>