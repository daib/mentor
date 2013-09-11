<?php /* Smarty version 2.6.18, created on 2013-09-11 00:52:32
         compiled from footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'footer.tpl', 11, false),array('modifier', 'escape', 'footer.tpl', 15, false),array('function', 'geturl', 'footer.tpl', 33, false),)), $this); ?>
            </div>
        </div>

        <div id="left-container" class="column">
            <div class="box">
                Left column placeholder
            </div>
        </div>

        <div id="right-container" class="column">
            <?php if (count($this->_tpl_vars['messages']) > 0): ?>
                <div id="messages" class="box">
                    <?php if (count($this->_tpl_vars['messages']) == 1): ?>
                        <strong>Status Message:</strong>
                        <?php echo ((is_array($_tmp=$this->_tpl_vars['messages']['0'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

                    <?php else: ?>
                        <strong>Status Messages:</strong>
                        <ul>
                            <?php $_from = $this->_tpl_vars['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
                                <li><?php echo ((is_array($_tmp=$this->_tpl_vars['row'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</li>
                            <?php endforeach; endif; unset($_from); ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div id="messages" class="box" style="display:none"></div>
            <?php endif; ?>

            <div class="box">
                <?php if ($this->_tpl_vars['authenticated']): ?>
                    Logged in as
                    <?php echo ((is_array($_tmp=$this->_tpl_vars['identity']->first_name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['identity']->last_name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

                    (<a href="<?php echo smarty_function_geturl(array('controller' => 'account','action' => 'logout'), $this);?>
">logout</a>).
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'account','action' => 'details'), $this);?>
">Update details</a>.
                <?php else: ?>
                    You are not logged in.
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'account','action' => 'login'), $this);?>
">Log in</a> or
                    <a href="<?php echo smarty_function_geturl(array('controller' => 'account','action' => 'register'), $this);?>
">register</a> now.
                <?php endif; ?>
            </div>
        </div>

        <div id="footer">
            Practical PHP Web 2.0 Applications, by Quentin Zervaas.
        </div>
    </body>
</html>