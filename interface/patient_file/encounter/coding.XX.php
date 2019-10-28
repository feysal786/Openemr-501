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

 <dt><a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/encounter_bottom.php" target='_parent' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('FEE','e'); ?>">
</a></dt>

 <dd><a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=CONS2&modifier=&units=1&fee=2000.00&text=Consult" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('2Consult','e'); ?>">
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=CONS15&modifier=&units=1&fee=1500.00&text=Consult" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('15Consult','e'); ?>">
</a></dd>

<dd><a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=CONS1&modifier=&units=1&fee=1000.00&text=Consult" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('1Consult','e'); ?>">
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=CONS8&modifier=&units=1&fee=800.00&text=Consult" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('8Consult','e'); ?>">
</a></dd>


<dd><a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=ECG&modifier=&units=1&fee=500.00&text=ECG" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('ECG','e'); ?>">
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=ETT&modifier=&units=1&fee=3000.00&text=ETT" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('ETT','e'); ?>">
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=ECHO&modifier=&units=1&fee=3000.00&text=ECHO" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('ECHO','e'); ?>">
</a></dd>

<dd><a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=24HOLTER&modifier=&units=1&fee=3000.00&text=24HOLTER" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('24HOLTER','e'); ?>">
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=24ABPM&modifier=&units=1&fee=3000.00&text=24ABPM" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('24ABPM','e'); ?>">

<dd><a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=PFT&modifier=&units=1&fee=3000.00&text=PFT" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('PFT','e'); ?>">
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=PT+INR&modifier=&units=1&fee=1500.00&text=PT+INR" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('PT+INR','e'); ?>">
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=VS&modifier=&units=1&fee=1000.00&text=VS" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('VS','e'); ?>">

<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=CHOLTG&modifier=&units=1&fee=1000.00&text=CHOLTG" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('CHOLTG','e'); ?>">
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=DIMERS&modifier=&units=1&fee=1500.00&text=DIMERS" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('DIMERS','e'); ?>">
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=TROPI&modifier=&units=1&fee=1500.00&text=TROPI" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('TROPI','e'); ?>">
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=PROBNP&modifier=&units=1&fee=2500.00&text=PROBNP" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('PROBNP','e'); ?>">
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=USABOD&modifier=&units=1&fee=1000.00&text=USABOD" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('USABOD','e'); ?>">

<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=USPELABD&modifier=&units=1&fee=1500.00&text=USPELABD" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('USPELABD','e'); ?>">
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=IV&modifier=&units=1&fee=300.00&text=IV" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('IV','e'); ?>">
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=NBL&modifier=&units=1&fee=200.00&text=NBL" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('NBL','e'); ?>">
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=CATH&modifier=&units=1&fee=500.00&text=CATH" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('CATH','e'); ?>">
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=UBAG&modifier=&units=1&fee=500.00&text=UBAG" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('UBAG','e'); ?>">

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
