<?php /* Smarty version 2.6.20, created on 2016-09-08 16:34:36
         compiled from sns_list_1.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'sns_list_1.htm', 5, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['sns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['num'] => $this->_tpl_vars['list']):
?>
<li class="fn-clear">
    <div class="fore1 fn-clear">
        <b><?php echo $this->_tpl_vars['list']['member_name']; ?>
</b>
        <span><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['create_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%m-%d&nbsp;%H:%M") : smarty_modifier_date_format($_tmp, "%m-%d&nbsp;%H:%M")); ?>
</span>
    </div>
    <div class="fore2 fn-clear">
    	<div class="txt"><?php echo $this->_tpl_vars['list']['title']; ?>
</div>
        <?php if ($this->_tpl_vars['list']['img']): ?>
        <div class="pic fn-clear">
            <?php $_from = $this->_tpl_vars['list']['img']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['slist']):
?>
            <?php if ($this->_tpl_vars['key'] < 3): ?>
                <img width="80" src="<?php echo $this->_tpl_vars['slist']; ?>
" />
            <?php endif; ?>  
            <?php endforeach; endif; unset($_from); ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="fore3 fn-clear">
        <span>评论(<?php echo $this->_tpl_vars['list']['comment_count']; ?>
)</span>
        <span>转发(<?php echo $this->_tpl_vars['list']['copy_count']; ?>
)</span>
    </div>
</li>
<?php endforeach; endif; unset($_from); ?>