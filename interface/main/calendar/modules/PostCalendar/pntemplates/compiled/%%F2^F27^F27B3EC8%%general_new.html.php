<?php /* Smarty version 2.6.31, created on 2019-10-02 11:32:22
         compiled from /homepages/2/d766239827/htdocs/public_html/afc/interface/forms/soap/templates/general_new.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', '/homepages/2/d766239827/htdocs/public_html/afc/interface/forms/soap/templates/general_new.html', 3, false),array('function', 'headerTemplate', '/homepages/2/d766239827/htdocs/public_html/afc/interface/forms/soap/templates/general_new.html', 4, false),array('modifier', 'escape', '/homepages/2/d766239827/htdocs/public_html/afc/interface/forms/soap/templates/general_new.html', 3, false),)), $this); ?>
<html>
<head>
<title><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='SOAP')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</title>
<?php echo smarty_function_headerTemplate(array(), $this);?>

</head>
<body class="body_top">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="page-header">
                    <h2><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='SOAP')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <form name="soap" method="post" action="<?php echo $this->_tpl_vars['FORM_ACTION']; ?>
/interface/forms/soap/save.php" onsubmit="return top.restoreSession()">
                    <fieldset>
                            <legend><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Subjective')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</legend>
                                <div class="form-group" >
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <textarea name="subjective" class="form-control" cols="60" rows="6"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']->get_subjective())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea>
                                    </div>
                                </div>
                    </fieldset>
                    <fieldset>
                            <legend><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Objective')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</legend>
                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <textarea name="objective" class="form-control" cols="60" rows="6"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']->get_objective())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea>
                                    </div>
                                </div>
                    </fieldset>
                    <fieldset>
                            <legend><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Assessment')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</legend>
                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <textarea name="assessment" class="form-control" cols="60" rows="6"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']->get_assessment())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea>
                                    </div>
                                </div>
                    </fieldset>
                    <fieldset>
                            <legend><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Plan')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</legend>
                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <textarea name="plan" class="form-control" cols="60" rows="6"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']->get_plan())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea>
                                    </div>
                                </div>
                    </fieldset>
                    <div class="form-group clearfix">
                        <div class="col-sm-10 col-sm-offset-1 position-override">
                            <div class="btn-group oe-opt-btn-group-pinch" role="group">
                                <button type="submit" class="btn btn-default btn-save" name="Submit"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Save')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</button>
                                <button type="button" class="btn btn-link btn-cancel oe-opt-btn-separate-left" onclick="top.restoreSession(); location.href='<?php echo $this->_tpl_vars['DONT_SAVE_LINK']; ?>
';"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</button>
                            </div>
                            <input type="hidden" name="id" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['data']->get_id())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
                            <input type="hidden" name="activity" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['data']->get_activity())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
                            <input type="hidden" name="pid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['data']->get_pid())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
                            <input type="hidden" name="process" value="true">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>