<?php /* Smarty version 2.6.31, created on 2019-10-02 16:47:04
         compiled from /homepages/2/d766239827/htdocs/public_html/afc/templates/document_categories/general_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', '/homepages/2/d766239827/htdocs/public_html/afc/templates/document_categories/general_list.html', 17, false),array('modifier', 'escape', '/homepages/2/d766239827/htdocs/public_html/afc/templates/document_categories/general_list.html', 17, false),)), $this); ?>
<?php echo '
 <style type="text/css" title="mystyles" media="all">
<!--
.treeMenuDefault {
	font-style: italic;
}

.treeMenuBold {
	font-style: italic;
	font-weight: bold;
}

-->
</style>
'; ?>

<script type="text/javascript">
var deleteLabel="<?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Delete')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
";
var editLabel="<?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Edit')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
";
</script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['GLOBALS']['webroot']; ?>
/library/js/CategoryTreeMenu.js?v=<?php echo $this->_tpl_vars['V_JS_INCLUDES']; ?>
"></script>
<table>
	<tr>
		<td height="20" valign="top"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Document Categories')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</td>
	</tr>
	<tr>
		<td valign="top"><?php echo $this->_tpl_vars['tree_html']; ?>
</td>
		<?php if ($this->_tpl_vars['message']): ?>
		<td valign="top"><?php echo $this->_tpl_vars['message']; ?>
</td>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['add_node'] == true || $this->_tpl_vars['edit_node'] == true): ?>
		<td width="25"></td>
		<td valign="top">
    <?php if ($this->_tpl_vars['add_node'] == true): ?>
		<?php echo smarty_function_xl(array('t' => ((is_array($_tmp="This new category will be a sub-category of ")) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['parent_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
<br>
		<?php endif; ?>
		<form method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
" onsubmit="return top.restoreSession()">

    <table>
      <tr>
        <td><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Category Name')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
&nbsp;</td>
        <td><input type="text" name="name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['NAME'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" onKeyDown="PreventIt(event)" /></td>
      </tr>
      <tr>
        <td><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Value')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
&nbsp;</td>
		    <td><input type="text" name="value" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['VALUE'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" onKeyDown="PreventIt(event)" ></td>
      </tr>
      <tr>
        <td><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Access Control')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
&nbsp;</td>
		    <td><select name="aco_spec"><?php echo $this->_tpl_vars['ACO_OPTIONS']; ?>
</select></td>
      </tr>
    </table>
    &nbsp;<br />

		<button type="submit" name="Add Category" class="btn btn-default btn-save"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Save Category')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</button>
		<input type="hidden" name="parent_is" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['parent_is'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
		<input type="hidden" name="process" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['PROCESS'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
		</form>
		</td>
		<?php endif; ?>
	</tr>

</table>