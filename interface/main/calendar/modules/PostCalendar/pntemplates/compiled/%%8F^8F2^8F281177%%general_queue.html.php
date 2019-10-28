<?php /* Smarty version 2.6.31, created on 2019-10-02 16:47:01
         compiled from /homepages/2/d766239827/htdocs/public_html/afc/templates/documents/general_queue.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', '/homepages/2/d766239827/htdocs/public_html/afc/templates/documents/general_queue.html', 3, false),array('modifier', 'escape', '/homepages/2/d766239827/htdocs/public_html/afc/templates/documents/general_queue.html', 3, false),)), $this); ?>
<div class="btn-group">
    <a href="controller.php?practice_settings&<?php echo $this->_tpl_vars['TOP_ACTION']; ?>
document_category&action=list" onclick="top.restoreSession()" class="btn btn-default btn-edit" >
        <?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Edit Categories')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>

    </a>
    <a href="#" onclick="submit_documents();" class="btn btn-default btn-update" target="_self" onclick="top.restoreSession()">
        <?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Update files')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>

    </a>
</div>
<input type="hidden" name="process" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['PROCESS'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /><br><br>

<form name="queue" method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
" onsubmit="return top.restoreSession()">
<table class="table table-responsive table-striped">
	<tr class="center_display">
		<td colspan="6"><?php echo $this->_tpl_vars['messages']; ?>
</td>
	</tr>
	<tr class="showborder_head">
		<th colspan="2" width="110px"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Name')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</td>
		<th width="100px"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Date')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</td>
		<th width="200px"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Patient')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</td>
		<th colspan="2"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Category')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</td>
	</tr>
	<?php $_from = $this->_tpl_vars['queue_files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['queue_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['queue_list']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['file']):
        $this->_foreach['queue_list']['iteration']++;
?>
	<tr>
		<td><input type="checkbox" name="files[<?php echo $this->_tpl_vars['file']['document_id']; ?>
][active]" value="1" <?php if (is_numeric ( $this->_tpl_vars['file']['patient_id'] )): ?>checked<?php endif; ?>></td>

		<td><a href="<?php echo $this->_tpl_vars['file']['web_path']; ?>
" onclick="top.restoreSession()"><?php echo ((is_array($_tmp=$this->_tpl_vars['file']['filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a><input type="hidden" name="files[<?php echo ((is_array($_tmp=$this->_tpl_vars['file']['document_id'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
][name]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['file']['filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"></td>

		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['file']['mtime'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>

		<td><input type="text" name="files[<?php echo ((is_array($_tmp=$this->_tpl_vars['file']['document_id'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
][patient_id]" size="5" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['file']['patient_id'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><input type="hidden" name="patient_name" value=""></td>

		<td><a href="javascript:<?php echo '{}'; ?>
" onclick="top.restoreSession();var URL='controller.php?patient_finder&find&form_id=queue<?php echo ((is_array($_tmp="['files[".($this->_tpl_vars['file']['document_id'])."][patient_id]']")) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&form_name=patient_name'; window.open(URL, 'queue', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=450,height=400,left = 425,top = 250');"><img src="images/stock_search-16.png" border="0"</a>&nbsp;&nbsp;&nbsp;</td>
		<td><select name="files[<?php echo $this->_tpl_vars['file']['document_id']; ?>
][category_id]"><?php echo $this->_tpl_vars['tree_html_listbox']; ?>
</select></td>

	</tr>
	<?php endforeach; else: ?>
	<tr height="25" class="center_display">
		<td colspan="6"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='No Documents Found')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</td>
	</tr>
	<?php endif; unset($_from); ?>

</table><br><br>

</form>

<?php echo '
<head>
<script language="javascript">
function submit_documents()
{
    top.restoreSession();
    document.queue.submit();
}
</script>
</head>
'; ?>
