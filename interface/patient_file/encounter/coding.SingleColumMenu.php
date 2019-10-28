<?php
include_once("../../globals.php");
include_once("../../../custom/code_types.inc.php");
?>
<html>
<head>
<?php html_header_show();?>

<link rel=stylesheet href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['assets_static_relative']; ?>/jquery-min-1-2-1/index.js"></script>

<!-- DBC STUFF ================ -->

<script type="text/javascript">
$(document).ready(function(){

$('#closeztn').bind('click', function(){
    if ( confirm("Do you really want to close the ZTN?") ) {
        $.ajax({ 
            type: 'POST',
            url: 'as.php',
            data: 'cztn=1',
            async: false
        }); 
    }
    window.location.reload(true);
});

});
</script>

<script language="JavaScript">
<!-- hide from JavaScript-challenged browsers

function selas() {
  popupWin = window.open('dbc_aschoose.php', 'remote', 'width=800,height=700,scrollbars');
};

function selcl() {
  popupWin = window.open('dbc_close.php', 'remote', 'width=960,height=630,left=200,top=100,scrollbars');
};

function selfl() {
  popupWin = window.open('dbc_showfollowup.php', 'remote', 'width=500,height=270,left=200,top=100,scrollbars');
}
// done hiding --></script>


<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
</head>
<body class="body_bottom">


<?php
$pres = "prescription";
?>

<dl>
<dt><span href="coding.php" class="title"><?php xl('','e'); ?></span></dt>

<dd><a class="text" href="superbill_codes.php"
 target="_parent"
 onclick="top.restoreSession()">


<?php foreach ($code_types as $key => $value) { ?>
<dt></dt>
<?php } ?>



<?php if (!$GLOBALS['disable_prescriptions']) { ?>



 
<a href="search_code.php?type=<?php echo $key ?>" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('Search Code','e'); ?>">
</a>

<dt><span href="coding.php" class="title"><?php xl('Fee','e'); ?></span></dt>
 <dd><a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=001&modifier=&units=1&fee=2000.00&text=consultation" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('Consult','e'); ?>">
</a></dd>
<dd><a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=002&modifier=&units=1&fee=800.00&text=ECG" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('ECG','e'); ?>">
</a></dd>
<dd><a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=003&modifier=&units=1&fee=1500.00&text=ETT" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('ETT','e'); ?>">
</a></dd>
<dd><a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=004&modifier=&units=1&fee=3000.00&text=ECHO" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('ECHO','e'); ?>">
</a></dd>
 
 <a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/load_form.php?formname=vitals" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('Add Vitals','e'); ?>">
</a>
 <a href="<?php echo $GLOBALS['webroot']?>/controller.php?<?php echo $pres?>&edit&id=&pid=<?php echo $pid?>" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('Print Presc','e'); ?>">
</a>

<?php }; // if (!$GLOBALS['disable_prescriptions']) ?>
</dl>

</body>
</html>
