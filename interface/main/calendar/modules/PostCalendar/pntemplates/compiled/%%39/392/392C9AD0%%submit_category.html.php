<?php /* Smarty version 2.6.31, created on 2019-05-24 10:28:21
         compiled from default/admin/submit_category.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'default/admin/submit_category.html', 4, false),array('modifier', 'escape', 'default/admin/submit_category.html', 28, false),)), $this); ?>
<!-- main navigation -->
<?php echo smarty_function_config_load(array('file' => "lang.".($this->_tpl_vars['USER_LANG'])), $this);?>


<script LANGUAGE="Javascript" SRC="modules/<?php echo $this->_tpl_vars['pcDir']; ?>
/pnincludes/AnchorPosition.js"></SCRIPT>
    <script LANGUAGE="Javascript" SRC="modules/<?php echo $this->_tpl_vars['pcDir']; ?>
/pnincludes/PopupWindow.js"></SCRIPT>
    <script LANGUAGE="Javascript" SRC="modules/<?php echo $this->_tpl_vars['pcDir']; ?>
/pnincludes/ColorPicker2.js"></SCRIPT>
    <script LANGUAGE="JavaScript">
    var cp = new ColorPicker('window');
    // Runs when a color is clicked
    function pickColor(color) {
    	document.getElementById(field).value = color;


	}

    var field;
    function pick(anchorname,target) {
    	field=target;
	    cp.show(anchorname);
	}
    </SCRIPT>

<html>
<head>

<title><?php echo ((is_array($_tmp=$this->_tpl_vars['_EDIT_PC_CONFIG_CATDETAILS'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</title>

<link rel="stylesheet" href="<?php echo $this->_tpl_vars['css_header']; ?>
" type="text/css">

</head>
<body bgcolor="<?php echo $this->_tpl_vars['BGCOLOR2']; ?>
"/>
<?php echo $this->_tpl_vars['AdminMenu']; ?>

<form name="cats" action="<?php echo $this->_tpl_vars['action']; ?>
" method="post" enctype="application/x-www-form-urlencoded">
<!-- GATHER NEW DATA START -->
	<table border="1" cellpadding="5" cellspacing="0">
			<tr>
				<td>
					<table  width ='%100' border='1'>
						<tr>
							<td colspan ='5'>
								<table width ='%100'>
									<th align="center"><?php echo $this->_tpl_vars['_PC_NEW_CAT_TITLE_S']; ?>
</th>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table cellspacing='8' cellpadding='2'>
            						<tr>
            							<td valign="top" align="left">
            								<input type="hidden" name="newid" value=""/>
            								<?php echo $this->_tpl_vars['_PC_CAT_NAME']; ?>
:<br />
            								&nbsp;<input type="text" name="newname" value="" size="20"/><br />
            								<?php echo $this->_tpl_vars['_PC_CAT_TYPE']; ?>
:<br />
            								&nbsp;<select name="new<?php echo $this->_tpl_vars['InputCatType']; ?>
">
               									<?php $_from = $this->_tpl_vars['cat_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']['value']; ?>
" >
                    									<?php echo $this->_tpl_vars['repeat']['name']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                								</select>
            							</td>
            							<td valign="top" align="left">
											<?php echo $this->_tpl_vars['_PC_CAT_CONSTANT_ID']; ?>
:<br />
											&nbsp;<input type="text" name="newconstantid" value="" size="20"/><br />
                							<?php echo $this->_tpl_vars['_PC_CAT_COLOR']; ?>
:<br />
                							&nbsp;<input type="text" name="newcolor" id='newcolor' value="#FFFFFF" size="10"/>
                                                                        [<a href="javascript:void(0);" onClick="pick('pick','newcolor');return false;" NAME="pick" ID="pick"><?php echo $this->_tpl_vars['_PC_COLOR_PICK_TITLE']; ?>
</a>]
            							</td>
            							<td valign="top" align="left">
                							<?php echo $this->_tpl_vars['_PC_CAT_DESC']; ?>
:<br />
                							&nbsp;<textarea name="newdesc" rows="3" cols="20"></textarea>
            							</td>
            							<td  valign="top" align="left">
            								<?php echo $this->_tpl_vars['ALL_DAY_CAT_TITLE']; ?>
<br />
            								&nbsp;<?php echo $this->_tpl_vars['ALL_DAY_CAT_YES']; ?>
<input type="radio" name="new<?php echo $this->_tpl_vars['InputAllDay']; ?>
" value="<?php echo $this->_tpl_vars['ValueAllDay']; ?>
"/>
            								<br />
            								&nbsp;<?php echo $this->_tpl_vars['ALL_DAY_CAT_NO']; ?>
<input type="radio" name="new<?php echo $this->_tpl_vars['InputAllDay']; ?>
" value="<?php echo $this->_tpl_vars['ValueAllDayNo']; ?>
" checked />
            							</td>
            							<td  valign="top" align="left">
            								<?php echo $this->_tpl_vars['_PC_CAT_DUR']; ?>
:<br />
                							&nbsp;	<?php echo $this->_tpl_vars['DurationHourTitle']; ?>

                							<input type="text" name="new<?php echo $this->_tpl_vars['InputDurationHour']; ?>
" value="0" size="4" />
                							<br />
                							&nbsp;	<?php echo $this->_tpl_vars['DurationMinTitle']; ?>

                							<input type="text" name="new<?php echo $this->_tpl_vars['InputDurationMin']; ?>
" value="0" size="4" />
                						</td>
                						<td valign="top" align="left">
                							<?php echo $this->_tpl_vars['_PC_ACTIVE']; ?>
:<br/>
                							<input type="radio" name="newactive" value="1"/> <?php echo $this->_tpl_vars['ActiveTitleYes']; ?>
<br/>
                							<input type="radio" name="newactive" value="0"/> <?php echo $this->_tpl_vars['ActiveTitleNo']; ?>
<br/>
                						</td>
                						<td valign="top" align="left">
                							<?php echo $this->_tpl_vars['_PC_SEQ']; ?>
:<br/>
                							<input type="text" name="newsequence" value="0" size="4" />
										</td>
										<td valign="top" align="left">
											<?php echo $this->_tpl_vars['_ACO']; ?>
:<br/>
											&nbsp;<select name="new<?php echo $this->_tpl_vars['InputACO']; ?>
">
               									<?php $_from = $this->_tpl_vars['ACO_List']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['acoGroupKey'] => $this->_tpl_vars['acoGroup']):
?>
													<optgroup label="<?php echo $this->_tpl_vars['acoGroupKey']; ?>
" >
													<?php $_from = $this->_tpl_vars['acoGroup']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aco']):
?>
														<option value="<?php echo $this->_tpl_vars['aco']['value']; ?>
" <?php if ($this->_tpl_vars['cat']['aco'] == $this->_tpl_vars['aco']['value']): ?>selected <?php endif; ?>>
															<?php echo $this->_tpl_vars['aco']['name']; ?>

														</option>
													<?php endforeach; endif; unset($_from); ?>
													</optgroup>
                								<?php endforeach; endif; unset($_from); ?>
											</select>
										</td>

            						</tr>
            					</table>
            				</td>
            			</tr>
						<tr>
							<td>
            					<table width='%100'>
            						<tr>
                						<td colspan="4" align="left" valign="top" >
                							<?php echo $this->_tpl_vars['RepeatingHeader']; ?>

                						</td>
                					</tr>
            						<tr>
                						<td colspan="4" align="left" valign="middle" >
                							<input type="radio" name="new<?php echo $this->_tpl_vars['InputNoRepeat']; ?>
" value="<?php echo $this->_tpl_vars['ValueNoRepeat']; ?>
" />
                							<?php echo $this->_tpl_vars['NoRepeatTitle']; ?>
<br />
                							<input type="radio" name="new<?php echo $this->_tpl_vars['InputRepeat']; ?>
" value="<?php echo $this->_tpl_vars['ValueRepeat']; ?>
"/>
                							<?php echo $this->_tpl_vars['RepeatTitle']; ?>

                							<input type="text" name="new<?php echo $this->_tpl_vars['InputRepeatFreq']; ?>
" value="0" size="4" />

                							<select name="new<?php echo $this->_tpl_vars['InputRepeatFreqType']; ?>
">
                								<?php $_from = $this->_tpl_vars['repeat_freq_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']['value']; ?>
">
                    									<?php echo $this->_tpl_vars['repeat']['name']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                							</select>
               								<br />
	                						<input type="radio" name="new<?php echo $this->_tpl_vars['InputRepeatOn']; ?>
" value="<?php echo $this->_tpl_vars['ValueRepeatOn']; ?>
" />
	                						 <?php echo $this->_tpl_vars['RepeatOnTitle']; ?>
:<br />
                                			<select name="new<?php echo $this->_tpl_vars['InputRepeatOnNum']; ?>
">
                                				<?php $_from = $this->_tpl_vars['repeat_on_num']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']['value']; ?>
">
                    									<?php echo $this->_tpl_vars['repeat']['name']; ?>

                    								</option>
                    							<?php endforeach; endif; unset($_from); ?>
                							</select>
                							<select name="new<?php echo $this->_tpl_vars['InputRepeatOnDay']; ?>
">
               									<?php $_from = $this->_tpl_vars['repeat_on_day']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']['value']; ?>
" >
                    									<?php echo $this->_tpl_vars['repeat']['name']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                							</select>
                							&nbsp;<?php echo $this->_tpl_vars['OfTheMonthTitle']; ?>
&nbsp;
                							<input type="text" name="new<?php echo $this->_tpl_vars['InputRepeatOnFreq']; ?>
" value="0" size="4" />
                							<?php echo $this->_tpl_vars['MonthsTitle']; ?>

											<br />
											</td>
											<td >
											<!--End Date Start-->
											<table width ='%100'>
												<tr>
													<td>
													<?php echo $this->_tpl_vars['NoEndDateTitle']; ?>

                										<input type="radio" name="new<?php echo $this->_tpl_vars['InputEndOn']; ?>
" value="<?php echo $this->_tpl_vars['ValueNoEnd']; ?>
" checked />
                										<br />
														<?php echo $this->_tpl_vars['EndDateTitle']; ?>

                										<input type="radio" name="new<?php echo $this->_tpl_vars['InputEndOn']; ?>
" value="<?php echo $this->_tpl_vars['ValueEnd']; ?>
"/>
                										<br />

                										<input type="text" name="new<?php echo $this->_tpl_vars['InputEndDateFreq']; ?>
" value="0" size="4" />

                										<select name="new<?php echo $this->_tpl_vars['InputEndDateFreqType']; ?>
">
                											<?php $_from = $this->_tpl_vars['repeat_freq_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    											<option value="<?php echo $this->_tpl_vars['repeat']['value']; ?>
" <?php echo $this->_tpl_vars['repeat']['selected']; ?>
>
                    												<?php echo $this->_tpl_vars['repeat']['name']; ?>

                    											</option>
                											<?php endforeach; endif; unset($_from); ?>
                										</select>
                										<br />
                									</td>
                								</tr>

                							</table>
                					 		<!-- /End Date End -->
               							 </td>

           							 </tr>

            					</table>
            				</td>
            			</tr>
            			 <tr><td valign='bottom'><?php echo $this->_tpl_vars['FormSubmit']; ?>
</td></tr>
            		</table>
            	</td>
            </tr>
	</table>
<table border="1" cellpadding="5" cellspacing="0">
	<!--START REPEATION SECTION -->
		<?php $_from = $this->_tpl_vars['all_categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cat']):
?>
			<tr>
				<td>
					<table  width ='%100' border='1'>
						<tr>
							<td colspan ='5'>
                <table width ='100%' border='0' cellpadding='0' cellspacing='0'>
                  <tr bgcolor="<?php echo $this->_tpl_vars['cat']['color']; ?>
">
                    <td align='left' width='20%'>
                      &nbsp;
                    </td>
                    <th align="center"><?php echo $this->_tpl_vars['_PC_REP_CAT_TITLE_S']; ?>
 <?php echo $this->_tpl_vars['cat']['id']; ?>
</th>
                    <td align='right' width='20%'>
                      <?php if ($this->_tpl_vars['cat']['id'] > 4 && $this->_tpl_vars['cat']['id'] != 8 && $this->_tpl_vars['cat']['id'] != 11 && $this->_tpl_vars['cat']['id'] != 6 && $this->_tpl_vars['cat']['id'] != 7): ?>
                      <!-- allow non-required categories to be deleted -->
                      <input type="checkbox" name="del[]" value="<?php echo $this->_tpl_vars['cat']['id']; ?>
"/>
                      <?php echo $this->_tpl_vars['_PC_CAT_DELETE']; ?>

                      <?php endif; ?>
                      &nbsp;
                    </td>
                  </tr>
                </table>
							</td>
						</tr>
						<tr>
							<td>
								<table cellspacing='8' cellpadding='2'>
            						<tr>
            							<td valign="top" align="left">
            								<input type="hidden" name="id[]" value="<?php echo $this->_tpl_vars['cat']['id']; ?>
"/>
            								<?php echo $this->_tpl_vars['_PC_CAT_NAME']; ?>
:<br />
            								&nbsp;<input type="text" name="name[]" value="<?php echo $this->_tpl_vars['cat']['name']; ?>
" size="20"/><br />
            								<?php echo $this->_tpl_vars['_PC_CAT_TYPE']; ?>
:<br />
            								&nbsp;<select name="<?php echo $this->_tpl_vars['InputCatType']; ?>
[]">
               									<?php $_from = $this->_tpl_vars['cat_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']['value']; ?>
" <?php if ($this->_tpl_vars['cat']['value_cat_type'] == $this->_tpl_vars['repeat']['value']): ?>selected <?php endif; ?>>
                    									<?php echo $this->_tpl_vars['repeat']['name']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                								</select>
            							</td>
                                                                <?php if (( $this->_tpl_vars['globals']['translate_appt_categories'] && ( $_SESSION['language_choice'] > 1 ) )): ?>
                                                                 <td valign="top" align="left"><?php echo $this->_tpl_vars['_PC_CAT_NAME_XL']; ?>
:<br />
                                                                  <span style="color:green"><?php echo $this->_tpl_vars['cat']['nameTranslate']; ?>
</span>
                                                                 </td>
                                                                <?php endif; ?>
            							<td valign="top" align="left">
											<?php echo $this->_tpl_vars['_PC_CAT_CONSTANT_ID']; ?>
:<br />
											&nbsp;<input type="text" name="constantid[]" value="<?php echo $this->_tpl_vars['cat']['constantid']; ?>
" size="20"/><br />
                							<?php echo $this->_tpl_vars['_PC_CAT_COLOR']; ?>
:<br />
                							&nbsp;<input type="text" name="color[]" id='color<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
' value="<?php echo $this->_tpl_vars['cat']['color']; ?>
" size="10"/>
                                                                        [<a href="javascript:void(0);" onClick="pick('pick','color<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
');return false;" NAME="pick" ID="pick"><?php echo $this->_tpl_vars['_PC_COLOR_PICK_TITLE']; ?>
</a>]
            							</td>
            							<td valign="top" align="left">
                							<?php echo $this->_tpl_vars['_PC_CAT_DESC']; ?>
:<br />
                							&nbsp;<textarea name="desc[]" rows="3" cols="20"><?php echo $this->_tpl_vars['cat']['desc']; ?>
</textarea>
            							</td>
                                                                <?php if (( $this->_tpl_vars['globals']['translate_appt_categories'] && ( $_SESSION['language_choice'] > 1 ) )): ?>
                                                                 <td valign="top" align="left"><?php echo $this->_tpl_vars['_PC_CAT_DESC_XL']; ?>
:<br />
                                                                  <span style="color:green"><?php echo $this->_tpl_vars['cat']['descTranslate']; ?>
</span>
                                                                 </td>
                                                                <?php endif; ?>
            							<td  valign="top" align="left">
            								<?php echo $this->_tpl_vars['ALL_DAY_CAT_TITLE']; ?>
<br />
            								<?php echo $this->_tpl_vars['ALL_DAY_CAT_YES']; ?>

            								<input type="radio" name="<?php echo $this->_tpl_vars['InputAllDay']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
" value="<?php echo $this->_tpl_vars['ValueAllDay']; ?>
" <?php if ($this->_tpl_vars['cat']['end_all_day'] == 1): ?>checked<?php endif; ?>/>
            								<br />
            								&nbsp;<?php echo $this->_tpl_vars['ALL_DAY_CAT_NO']; ?>
<input type="radio" name="<?php echo $this->_tpl_vars['InputAllDay']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
" value="<?php echo $this->_tpl_vars['ValueAllDayNo']; ?>
" <?php if ($this->_tpl_vars['cat']['end_all_day'] == 0): ?>checked<?php endif; ?>/>
            								</td>
                						<td  valign="top" align="left">
            								<?php echo $this->_tpl_vars['_PC_CAT_DUR']; ?>
:<br />
                							&nbsp;	<?php echo $this->_tpl_vars['DurationHourTitle']; ?>

                							<input type="text" name="<?php echo $this->_tpl_vars['InputDurationHour']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
" value="<?php echo $this->_tpl_vars['cat']['event_durationh']; ?>
" size="4" />
                							<br />
                							&nbsp;	<?php echo $this->_tpl_vars['DurationMinTitle']; ?>

                							<input type="text" name="<?php echo $this->_tpl_vars['InputDurationMin']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
" value="<?php echo $this->_tpl_vars['cat']['event_durationm']; ?>
" size="4" />
                						</td>
                						<td valign="top" align="left">
                							<?php echo $this->_tpl_vars['_PC_ACTIVE']; ?>
:<br/>
                							<input type="radio" name="active<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
" value="1" data='<?php echo $this->_tpl_vars['cat']['active']; ?>
' <?php if ($this->_tpl_vars['cat']['active'] == 1): ?>checked<?php endif; ?>/>  <?php echo $this->_tpl_vars['ActiveTitleYes']; ?>
<br/>
                							<input type="radio" name="active<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
" value="0" data='<?php echo $this->_tpl_vars['cat']['active']; ?>
' <?php if ($this->_tpl_vars['cat']['active'] == 0): ?>checked<?php endif; ?>/>  <?php echo $this->_tpl_vars['ActiveTitleNo']; ?>
<br/>
                						</td>
                						<td valign="top" align="left">
                							<?php echo $this->_tpl_vars['_PC_SEQ']; ?>
:<br/>
                							<input type="text" name="sequence[]" value="<?php echo $this->_tpl_vars['cat']['sequence']; ?>
" size="4" />
                						</td>
										<td valign="top" align="left">
											<?php echo $this->_tpl_vars['_ACO']; ?>
:<br/>
											&nbsp;<select name="<?php echo $this->_tpl_vars['InputACO']; ?>
[]">
               									<?php $_from = $this->_tpl_vars['ACO_List']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['acoGroupKey'] => $this->_tpl_vars['acoGroup']):
?>
													<optgroup label="<?php echo $this->_tpl_vars['acoGroupKey']; ?>
" >
													<?php $_from = $this->_tpl_vars['acoGroup']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aco']):
?>
														<option value="<?php echo $this->_tpl_vars['aco']['value']; ?>
" <?php if ($this->_tpl_vars['cat']['aco'] == $this->_tpl_vars['aco']['value']): ?>selected <?php endif; ?>>
															<?php echo $this->_tpl_vars['aco']['name']; ?>

														</option>
													<?php endforeach; endif; unset($_from); ?>
													</optgroup>
                								<?php endforeach; endif; unset($_from); ?>
											</select>
										</td>

            						</tr>
            					</table>
            				</td>
            			</tr>
						<tr>
							<td>
            					<table width='%100'>
            						<tr>
                						<td colspan="4" align="left" valign="top" >
                							<?php echo $this->_tpl_vars['RepeatingHeader']; ?>

                						</td>
                					</tr>
            						<tr>
                						<td colspan="4" align="left" valign="middle" >
                							<input type="radio" name="<?php echo $this->_tpl_vars['InputNoRepeat']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
" value="<?php echo $this->_tpl_vars['ValueNoRepeat']; ?>
" <?php if ($this->_tpl_vars['cat']['event_repeat'] == 0): ?>checked<?php endif; ?>/>
                							<?php echo $this->_tpl_vars['NoRepeatTitle']; ?>
<br />
                							<input type="radio" name="<?php echo $this->_tpl_vars['InputRepeat']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
" value="<?php echo $this->_tpl_vars['ValueRepeat']; ?>
" <?php if ($this->_tpl_vars['cat']['event_repeat'] == 1): ?>checked<?php endif; ?> />
                							<?php echo $this->_tpl_vars['RepeatTitle']; ?>

                							<input type="text" name="<?php echo $this->_tpl_vars['InputRepeatFreq']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
" value="<?php echo $this->_tpl_vars['cat']['event_repeat_freq']; ?>
" size="4" />

                							<select name="<?php echo $this->_tpl_vars['InputRepeatFreqType']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
">
                								<?php $_from = $this->_tpl_vars['repeat_freq_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']['value']; ?>
" <?php if ($this->_tpl_vars['cat']['event_repeat_freq_type'] == $this->_tpl_vars['repeat']['value']): ?>selected<?php endif; ?>>
                    									<?php echo $this->_tpl_vars['repeat']['name']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                							</select>
               								<br />
	                						<input type="radio" name="<?php echo $this->_tpl_vars['InputRepeatOn']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
" value="<?php echo $this->_tpl_vars['ValueRepeatOn']; ?>
"<?php if ($this->_tpl_vars['cat']['event_repeat'] == 2): ?>checked<?php endif; ?> />
	                						 <?php echo $this->_tpl_vars['RepeatOnTitle']; ?>
:<br />
                                			<select name="<?php echo $this->_tpl_vars['InputRepeatOnNum']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
">
                                				<?php $_from = $this->_tpl_vars['repeat_on_num']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']['value']; ?>
" <?php if ($this->_tpl_vars['cat']['event_repeat_on_num'] == $this->_tpl_vars['repeat']['value']): ?>selected<?php endif; ?>>
                    									<?php echo $this->_tpl_vars['repeat']['name']; ?>

                    								</option>
                    							<?php endforeach; endif; unset($_from); ?>
                							</select>
                							<select name="<?php echo $this->_tpl_vars['InputRepeatOnDay']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
">
               									<?php $_from = $this->_tpl_vars['repeat_on_day']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    								<option value="<?php echo $this->_tpl_vars['repeat']['value']; ?>
" <?php if ($this->_tpl_vars['cat']['event_repeat_on_day'] == $this->_tpl_vars['repeat']['value']): ?>selected <?php endif; ?>>
                    									<?php echo $this->_tpl_vars['repeat']['name']; ?>

                    								</option>
                								<?php endforeach; endif; unset($_from); ?>
                							</select>
                							&nbsp;<?php echo $this->_tpl_vars['OfTheMonthTitle']; ?>
&nbsp;
                							<input type="text" name="<?php echo $this->_tpl_vars['InputRepeatOnFreq']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
" value="<?php echo $this->_tpl_vars['cat']['event_repeat_on_freq']; ?>
" size="4" />
                							<?php echo $this->_tpl_vars['MonthsTitle']; ?>

											<br />
											</td>
											<td >
											<!--End Date Start-->
											<table width ='%100'>
												<tr>
													<td>
														<?php echo $this->_tpl_vars['NoEndDateTitle']; ?>

                										<input type="radio" name="<?php echo $this->_tpl_vars['InputEndOn']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
" value="<?php echo $this->_tpl_vars['ValueNoEnd']; ?>
"  <?php if ($this->_tpl_vars['cat']['end_date_flag'] == 0): ?> checked<?php endif; ?> />
                										<br />
														<?php echo $this->_tpl_vars['EndDateTitle']; ?>

                										<input type="radio" name="<?php echo $this->_tpl_vars['InputEndOn']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
" value="<?php echo $this->_tpl_vars['ValueEnd']; ?>
"  <?php if ($this->_tpl_vars['cat']['end_date_flag'] == 1): ?> checked<?php endif; ?> />
                										<br />

                										<input type="text" name="<?php echo $this->_tpl_vars['InputEndDateFreq']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
" value="<?php echo $this->_tpl_vars['cat']['end_date_freq']; ?>
" size="4" />
                										<select name="<?php echo $this->_tpl_vars['InputEndDateFreqType']; ?>
<?php echo '['; ?>
<?php echo $this->_tpl_vars['cat']['id']; ?>
<?php echo ']'; ?>
">
                											<?php $_from = $this->_tpl_vars['repeat_freq_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['repeat']):
?>
                    											<option value="<?php echo $this->_tpl_vars['repeat']['value']; ?>
" <?php if ($this->_tpl_vars['cat']['end_date_type'] == $this->_tpl_vars['repeat']['value']): ?>selected <?php endif; ?>>
                    												<?php echo $this->_tpl_vars['repeat']['name']; ?>

                    											</option>
                											<?php endforeach; endif; unset($_from); ?>
                										</select>
                									</td>
                								</tr>

                							</table>
                					 		<!-- /End Date End -->
               							 </td>
           							 </tr>
           							             					</table>
            				</td>
            			</tr>
            			<tr><td valign='bottom'><?php echo $this->_tpl_vars['FormSubmit']; ?>
</td></tr>
            		</table>
            	</td>
            </tr>
 		<!-- /REPEATING ROWS -->
		<?php endforeach; endif; unset($_from); ?>
	</table>

<input type="hidden" name="pc_html_or_text" value="text" selected>

<?php echo $this->_tpl_vars['FormHidden']; ?>


<?php echo $this->_tpl_vars['FormSubmit']; ?>

</form>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['TPL_NAME'])."/views/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>