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
function showhide(id) {
   	var e = document.getElementById(id);
   	e.style.display = (e.style.display == 'block') ? 'none' : 'block';
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
<a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter//superbill_custom_full.php" target='Codes' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('NEW','e'); ?>">
</a>

 <dt><a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/encounter_bottom.php" target='_parent' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('Cons','e'); ?>">
</a></dt> <dd>
<?php 
//feysal Auto Fee buttons from Code 
    $query = "SELECT c.code,c.code_text, p.pr_price FROM codes as c, prices as p where c.id=p.pr_id and c.code_type=1 and c.active=1 and (c.superbill='cons') order by c.superbill" ;//.
	$res = sqlStatement($query);
	//foreach ($res) { 
    //print_r ($res);

    while ($row = sqlFetchArray($res)) {
		//print_r ($row['code_text']);
		//print_r ($row);?>
		 <a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=<?php echo $row['code']?>&modifier=&units=1&fee=<?php echo $row['pr_price']?>&text=<?php echo $row['code_text']?>" target='Codes' onclick='top.restoreSession()'>
		<input type="button" style="width: 75px; height: 20px;" name="lm" value="<?php xl($row['code'],'e'); ?>">
		</a>
		<?php }
	//}
?>
 <dt><dd><a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/load_form.php?formname=vitals" target='Codes' onclick='top.restoreSession()'>
<input type="button" style="width: 75px; height: 20px;background-color:#FF4500; color:#000;" name="lm" value="<?php xl('Add Vitals','e'); ?>">
</a>
 <a href="<?php echo $GLOBALS['webroot']?>/controller.php?<?php echo $pres?>&edit&id=&pid=<?php echo $pid?>" target='Codes' onclick='top.restoreSession()'>
<input type="button" style="width: 75px; height: 20px;background-color:#a7cb00; color:#f00;" name="lm" value="<?php xl('Print Presc','e'); ?>" >
</a>

 <dt><a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/encounter_bottom.php" target='_parent' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('Fertelity','e'); ?>">
<a href="javascript:showhide('Fertelity')">
    		Show/Hide.
</a>
</a></dt> <dd>
<div id="Fertelity" style="display:none;"> 
<?php 
//feysal Auto Fee buttons from Code 
    $query = "SELECT c.code,c.code_text, p.pr_price FROM codes as c, prices as p where c.id=p.pr_id and c.code_type=1 and c.active=1 and ( c.superbill='Fertelity') order by c.superbill" ;//.
	$res = sqlStatement($query);
	//foreach ($res) { 
    //print_r ($res);

    while ($row = sqlFetchArray($res)) {
		//print_r ($row['code_text']);
		//print_r ($row);?>
		 <a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=<?php echo $row['code']?>&modifier=&units=1&fee=<?php echo $row['pr_price']?>&text=<?php echo $row['code_text']?>" target='Codes' onclick='top.restoreSession()'>
		<input type="button" style="width: 75px; height: 20px;" name="lm" value="<?php xl($row['code'],'e'); ?>">
		</a>
		<?php }
	//}
?>
</div>
 <dt><a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/encounter_bottom.php" target='_parent' onclick='top.restoreSession()'>
<input type="button" name="lm" value="<?php xl('Proc_IDC','e'); ?>">
<a href="javascript:showhide('Proc_IDC')">
    		Show/Hide.
</a>
</a></dt> <dd>
<div id="Proc_IDC" style="display:none;"> 
<?php 
//feysal Auto Fee buttons from Code 
    $query = "SELECT c.code,c.code_text, p.pr_price FROM codes as c, prices as p where c.id=p.pr_id and c.code_type=1 and c.active=1 and ( c.superbill='Proc_IDC') order by c.superbill" ;//.
	$res = sqlStatement($query);
	//foreach ($res) { 
    //print_r ($res);

    while ($row = sqlFetchArray($res)) {
		//print_r ($row['code_text']);
		//print_r ($row);?>
		 <a href="<?php echo $GLOBALS['webroot']?>/interface/patient_file/encounter/diagnosis.php?mode=add&type=CPT4&code=<?php echo $row['code']?>&modifier=&units=1&fee=<?php echo $row['pr_price']?>&text=<?php echo $row['code_text']?>" target='Codes' onclick='top.restoreSession()'>
		<input type="button" style="width: 75px; height: 20px;" name="lm" value="<?php xl($row['code'],'e'); ?>">
		</a>
		<?php }
	//}
?>
</div>

 


<?php }; // if (!$GLOBALS['disable_prescriptions']) ?>
</dl>

</body>
</html>
