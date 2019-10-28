<?php /* Smarty version 2.6.31, created on 2019-10-02 16:46:38
         compiled from /homepages/2/d766239827/htdocs/public_html/afc/templates/practice_settings/general_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', '/homepages/2/d766239827/htdocs/public_html/afc/templates/practice_settings/general_list.html', 5, false),array('function', 'headerTemplate', '/homepages/2/d766239827/htdocs/public_html/afc/templates/practice_settings/general_list.html', 7, false),array('modifier', 'escape', '/homepages/2/d766239827/htdocs/public_html/afc/templates/practice_settings/general_list.html', 5, false),)), $this); ?>
<!DOCTYPE html>
<html>
<head>

    <title><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Practice Settings')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</title>

    <?php echo smarty_function_headerTemplate(array('assets' => 'bootstrap-sidebar|common'), $this);?>


</head>
<body class="body_top" style="padding-top: 35px;">

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle visible-xs" data-toggle="sidebar" data-target=".sidebar">
                <span class="sr-only"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Toggle navigation')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</span>
                <i class="fa fa-bars fa-inverted"></i>
            </button>
            <a class="navbar-brand" href="#"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Practice Settings')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</a>
        </div>

        <div class="collapse navbar-collapse" id="practice-setting-nav">
            <ul class="nav navbar-nav">
            </ul>
            <ul class="nav navbar-nav navbar-right">
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-2 sidebar sidebar-<?php echo $this->_tpl_vars['direction']; ?>
 sidebar-sm-show">
            <ul class="nav navbar-stacked">
                <li><a href="<?php echo $this->_tpl_vars['TOP_ACTION']; ?>
pharmacy&action=list"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Pharmacies')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</a></li>
                <li><a href="<?php echo $this->_tpl_vars['TOP_ACTION']; ?>
insurance_company&action=list"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Insurance Companies')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</a></li>
                <li><a href="<?php echo $this->_tpl_vars['TOP_ACTION']; ?>
insurance_numbers&action=list"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Insurance Numbers')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</a></li>
                <li><a href="<?php echo $this->_tpl_vars['TOP_ACTION']; ?>
x12_partner&action=list"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='X12 Partners')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</a></li>
                <li><a href="<?php echo $this->_tpl_vars['TOP_ACTION']; ?>
document&action=queue"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='Documents')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</a></li>
                <li><a href="<?php echo $this->_tpl_vars['TOP_ACTION']; ?>
hl7&action=default"><?php echo smarty_function_xl(array('t' => ((is_array($_tmp='HL7 Viewer')) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))), $this);?>
</a></li>
            </ul>
        </div>
        <div class="col-xs-12 col-sm-10 col-sm-offset-2">
            <div class="page-header section-header">
                <h2><?php echo $this->_tpl_vars['ACTION_NAME']; ?>
</h2>
            </div>
            <div>
                <?php echo $this->_tpl_vars['display']; ?>

            </div>
        </div>
    </div>
</div>
</body>
</html>