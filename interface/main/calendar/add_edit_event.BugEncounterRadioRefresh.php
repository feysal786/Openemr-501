<?php
/*
 * Add or edit an event in the calendar.
 *
 * The event editor looks something like this:
 *
 * //------------------------------------------------------------//
 * // Category __________________V   O All day event             //
 * // Date     _____________ [?]     O Time     ___:___ __V      //
 * // Title    ___________________     duration ____ minutes     //
 * // Patient  _(Click_to_select)_                               //
 * // Provider __________________V   X Repeats  ______V ______V  //
 * // Status   __________________V     until    __________ [?]   //
 * // Comments ________________________________________________  //
 * //                                                            //
 * //       [Save]  [Find Available]  [Delete]  [Cancel]         //
 * //------------------------------------------------------------//
 *
 *
 * Copyright (C) 2005-2013 Rod Roark <rod@sunsetsystems.com>
 * Copyright (C) 2017 Brady Miller <brady.g.miller@gmail.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author Rod Roark <rod@sunsetsystems.com>
 * @author Brady Miller <brady.g.miller@gmail.com>
 * @link http://www.open-emr.org
 */



require_once('../../globals.php');
require_once($GLOBALS['srcdir'].'/patient.inc');
require_once($GLOBALS['srcdir'].'/forms.inc');
require_once($GLOBALS['srcdir'].'/calendar.inc');
require_once($GLOBALS['srcdir'].'/options.inc.php');
require_once($GLOBALS['srcdir'].'/encounter_events.inc.php');
require_once($GLOBALS['srcdir'].'/acl.inc');
require_once($GLOBALS['srcdir'].'/patient_tracker.inc.php');
require_once($GLOBALS['incdir']."/main/holidays/Holidays_Controller.php");
require_once($GLOBALS['srcdir'].'/group.inc');

 //Check access control
if (!acl_check('patients', 'appt', '', array('write','wsome'))) {
    die(xl('Access not allowed'));
}

use OpenEMR\Core\Header;

/* Things that might be passed by our opener. */
 $eid           = $_GET['eid'];         // only for existing events
 $date          = $_GET['date'];        // this and below only for new events
 $userid        = $_GET['userid'];
 $default_catid = $_GET['catid'] ? $_GET['catid'] : '5';
//feysal autosave from demographics
 $autosave        = $_GET['autosave'];
 $_POST['form_date'] = DateToYYYYMMDD($_POST['form_date']);
 $_POST['form_enddate'] = DateToYYYYMMDD($_POST['form_enddate']);
 //
if ($date) {
    $date = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6);
} else {
    $date = date("Y-m-d");
}

 //
 //feysal
	$hour = date("H:i");
	$minutes = '5';
	//$format = "H:i";
	$seconds = strtotime($hour);
    $rounded = round($seconds / ($minutes * 60)) * ($minutes * 60);
    echo date("H:i", $rounded);
	$starttimem = date("i",$rounded);
	$starttimeh = date("H",$rounded);
 // Top of the hour bugs
 //if ($starttimem=='0'){$starttimem='55';}
 //$starttimem = '00';
if (isset($_GET['starttimem'])) {
    $starttimem = substr('00' . $_GET['starttimem'], -2);
}

 //
if (isset($_GET['starttimeh'])) {
    $starttimeh = $_GET['starttimeh'];
    if (isset($_GET['startampm'])) {
        if ($_GET['startampm'] == '2' && $starttimeh < 12) {
            $starttimeh += 12;
        }
    }
} else {
    $starttimeh = date("G");
}


 $startampm = '';

 $info_msg = "";

    ?>

<?php $g_edit = acl_check("groups", "gcalendar", false, 'write');?>
<?php $g_view = acl_check("groups", "gcalendar", false, 'view');?>

<?php Header::setupHeader(['common', 'datetime-picker', 'opener']); ?>

<!-- validation library -->
<!--//Not lbf forms use the new validation, please make sure you have the corresponding values in the list Page validation-->
<?php    $use_validate_js = 1;?>
<?php  require_once($GLOBALS['srcdir'] . "/validation/validation_script.js.php"); ?>
<?php
//Gets validation rules from Page Validation list.
//Note that for technical reasons, we are bypassing the standard validateUsingPageRules() call.
$have_group_global_enabled = true;
if ((!$g_edit && !$g_view) || (!$GLOBALS['enable_group_therapy'])) {
    $_GET['group'] = false;
    $have_group_global_enabled = false;
}

if ($_GET['group'] == true) {
    //groups tab
    $collectthis = collectValidationPageRules("/interface/main/calendar/add_edit_event.php?group=true");
} elseif ($_GET['prov']) {
    //providers tab
    $collectthis = collectValidationPageRules("/interface/main/calendar/add_edit_event.php?prov=true");
} else { //patient tab
    $collectthis = collectValidationPageRules("/interface/main/calendar/add_edit_event.php");
}

if (empty($collectthis)) {
    $collectthis = "undefined";
} else {
    $collectthis = $collectthis[array_keys($collectthis)[0]]["rules"];
}
?>
<?php $disabled = (!$g_edit && $have_group_global_enabled )?' disabled=true; ':'';?>
<?php if ($disabled) {
    echo '<script>$( document ).ready(function(){
    $("input").prop("disabled", true);
    $("select").prop("disabled", true);
}) </script>';
}?>

    <?php



    function InsertEventFull()
    {
        global $new_multiple_value,$provider,$event_date,$duration,$recurrspec,$starttime,$endtime,$locationspec;
        // =======================================
        // multi providers case
        // =======================================
        if (is_array($_POST['form_provider'])) {
            // obtain the next available unique key to group multiple providers around some event
            $q = sqlStatement("SELECT MAX(pc_multiple) as max FROM openemr_postcalendar_events");
            $max = sqlFetchArray($q);
            $new_multiple_value = $max['max'] + 1;

            $pc_eid = null;
            foreach ($_POST['form_provider'] as $provider) {
                $args = $_POST;
                // specify some special variables needed for the INSERT
                $args['new_multiple_value'] = $new_multiple_value;
                $args['form_provider'] = $provider;
                $args['event_date'] = $event_date;
                $args['duration'] = $duration * 60;
                $args['recurrspec'] = $recurrspec;
                $args['starttime'] = $starttime;
                $args['endtime'] = $endtime;
                $args['locationspec'] = $locationspec;
                $pc_eid_temp = InsertEvent($args);
                if ($pc_eid == null) {
                    $pc_eid = $pc_eid_temp;
                }
            }

            return $pc_eid;


            // ====================================
        // single provider
        // ====================================
        } else {
            $args = $_POST;
            // specify some special variables needed for the INSERT
            $args['new_multiple_value'] = "";
            $args['event_date'] = $event_date;
            $args['duration'] = $duration * 60;
            $args['recurrspec'] = $recurrspec;
            $args['starttime'] = $starttime;
            $args['endtime'] = $endtime;
            $args['locationspec'] = $locationspec;
            $pc_eid = InsertEvent($args);
            return $pc_eid;
        }
    }
    function DOBandEncounter($pc_eid)
    {
           global $event_date,$info_msg;
         // Save new DOB if it's there.
         $patient_dob = trim($_POST['form_dob']);
         $tmph = $_POST['form_hour'] + 0;
         $tmpm = $_POST['form_minute'] + 0;
        if ($_POST['form_ampm'] == '2' && $tmph < 12) {
            $tmph += 12;
        }

         $appttime = "$tmph:$tmpm:00";

        if ($patient_dob && $_POST['form_pid']) {
            sqlStatement("UPDATE patient_data SET DOB = ? WHERE " .
                                    "pid = ?", array($patient_dob,$_POST['form_pid']));
        }

        // Manage tracker status.
        // And auto-create a new encounter if appropriate.
        if (!empty($_POST['form_pid'])) {
            if ($GLOBALS['auto_create_new_encounters'] && $event_date == date('Y-m-d') && (is_checkin($_POST['form_apptstatus']) == '1') && !is_tracker_encounter_exist($event_date, $appttime, $_POST['form_pid'], $_GET['eid'])) {
                $encounter = todaysEncounterCheck($_POST['form_pid'], $event_date, $_POST['form_comments'], $_POST['facility'], $_POST['billing_facility'], $_POST['form_provider'], $_POST['form_category'], false);
                if ($encounter) { ?>
					<script language="javascript">
					document.location.href = 	'../../patient_file/front_payment.php ' ;
					</script>
					<?php
					echo "lllllllllllllllllllllllll";
                        $info_msg .= xl("New encounter created with id");
                        //$info_msg .= " $encounter";
						
                }else{?>
					<script language="javascript">
					document.location.href = 	'../../patient_file/summary/demographics.php ' ;
					</script>
					<?php
					
				}


                 # Capture the appt status and room number for patient tracker. This will map the encounter to it also.
                if (isset($GLOBALS['temporary-eid-for-manage-tracker']) || !empty($_GET['eid'])) {
                    // Note that the temporary-eid-for-manage-tracker is used to capture the eid for new appointments and when separate a recurring
                    // appointment. It is set in the InsertEvent() function. Note that in the case of spearating a recurrent appointment, the get eid
                    // parameter is actually erroneous(is eid of the recurrent appt and not the new separated appt), so need to use the
                    // temporary-eid-for-manage-tracker global instead.
                    $temp_eid = (isset($GLOBALS['temporary-eid-for-manage-tracker'])) ? $GLOBALS['temporary-eid-for-manage-tracker'] : $_GET['eid'];
                    manage_tracker_status($event_date, $appttime, $temp_eid, $_POST['form_pid'], $_SESSION["authUser"], $_POST['form_apptstatus'], $_POST['form_room'], $encounter);
                }
            } else {
				// feysal go to demographics otherwise giveing blank page
			?>
					<script language="javascript">
					document.location.href = 	'../../patient_file/summary/demographics.php ' ;
					</script>
					<?php
			
                    # Capture the appt status and room number for patient tracker.
                if (!empty($_GET['eid'])) {
                    manage_tracker_status($event_date, $appttime, $_GET['eid'], $_POST['form_pid'], $_SESSION["authUser"], $_POST['form_apptstatus'], $_POST['form_room']);
                }
            }
        }

        // auto create encounter for therapy group
        if (!empty($_POST['form_gid'])) {
                                                                                // status Took Place is the check in of therapy group
            if ($GLOBALS['auto_create_new_encounters'] && $event_date == date('Y-m-d') && $_POST['form_apptstatus'] == '=') {
                $encounter = todaysTherapyGroupEncounterCheck($_POST['form_gid'], $event_date, $_POST['form_comments'], $_POST['facility'], $_POST['billing_facility'], $_POST['form_provider'], $_POST['form_category'], false, $pc_eid);
                if ($encounter) {
                    $info_msg .= xl("New group encounter created with id");
                    $info_msg .= " $encounter";
                }
            }
        }
    }


 /*This function is used for setting the date of the first event when using the "day_every_week" repetition mechanism.
 When the 'start date' is not one of the days chosen for the repetition, the start date needs to be changed to the first
 occurrence of one of these set days. */
    function setEventDate($start_date, $recurrence)
    {
        $timestamp = strtotime($start_date);
        $day = date('w', $timestamp);
        //If the 'start date' is one of the set days
        if (in_array(($day+1), explode(',', $recurrence))) {
            return $start_date;
        }

        //else: (we need to change start date to first occurrence of one of the set days)

        $new_date = getTheNextAppointment($start_date, $recurrence);

        return $new_date;
    }
//================================================================================================================

// EVENTS TO FACILITIES (lemonsoftware)
//(CHEMED) get facility name
// edit event case - if there is no association made, then insert one with the first facility
    if ($eid) {
        $selfacil = '';
        $facility = sqlQuery("SELECT pc_facility, pc_multiple, pc_aid, facility.name
                            FROM openemr_postcalendar_events
                              LEFT JOIN facility ON (openemr_postcalendar_events.pc_facility = facility.id)
                              WHERE pc_eid = ?", array($eid));
            // if ( !$facility['pc_facility'] ) {
        if (is_array($facility) && !$facility['pc_facility']) {
            $qmin = sqlQuery("SELECT facility_id as minId, facility FROM users WHERE id = ?", array($facility['pc_aid']));
            $min  = $qmin['minId'];
            $min_name = $qmin['facility'];

            // multiple providers case
            if ($GLOBALS['select_multi_providers']) {
                $mul  = $facility['pc_multiple'];
                sqlStatement("UPDATE openemr_postcalendar_events SET pc_facility = ? WHERE pc_multiple = ?", array($min,$mul));
            }

            // EOS multiple

            sqlStatement("UPDATE openemr_postcalendar_events SET pc_facility = ? WHERE pc_eid = ?", array($min,$eid));
            $e2f = $min;
            $e2f_name = $min_name;
        } else {
              // not edit event
            if (!$facility['pc_facility'] && $_SESSION['pc_facility']) {
                $e2f = $_SESSION['pc_facility'];
            } elseif (!$facility['pc_facility'] && $_COOKIE['pc_facility'] && $GLOBALS['set_facility_cookie']) {
                    $e2f = $_COOKIE['pc_facility'];
            } else {
                $e2f = $facility['pc_facility'];
                $e2f_name = $facility['name'];
            }
        }
    }


// EOS E2F
// ===========================
//=============================================================================================================================
    if ($_POST['form_action'] == "duplicate" || $_POST['form_action'] == "save") {
        // Compute start and end time strings to be saved.
        if ($_POST['form_allday']) {
            $tmph = 0;
            $tmpm = 0;
            $duration = 24 * 60;
        } else {
            $tmph = $_POST['form_hour'] + 0;
            $tmpm = $_POST['form_minute'] + 0;
            if ($_POST['form_ampm'] == '2' && $tmph < 12) {
                $tmph += 12;
            }

            $duration = abs($_POST['form_duration']); // fixes #395
        }

            $starttime = "$tmph:$tmpm:00";
            //
            $tmpm += $duration;
        while ($tmpm >= 60) {
            $tmpm -= 60;
            ++$tmph;
        }


            $endtime = "$tmph:$tmpm:00";

            // Set up working variables related to repeated events.
            $my_recurrtype = 0;
            $my_repeat_freq = 0 + $_POST['form_repeat_freq'];
            $my_repeat_type = 0 + $_POST['form_repeat_type'];
            $my_repeat_on_num  = 1;
            $my_repeat_on_day  = 0;
            $my_repeat_on_freq = 0;

             // the starting date of the event, pay attention with this value
             // when editing recurring events -- JRM Oct-08
             $event_date = $_POST['form_date'];

             //If used new recurrence mechanism of set days every week
        if (!empty($_POST['days_every_week'])) {
            $my_recurrtype = 3;
           //loop through checkboxes and insert encounter days into array
            $days_every_week_arr = array();
            for ($i=1; $i<=7; $i++) {
                if (!empty($_POST['day_' . $i])) {
                    array_push($days_every_week_arr, $i);
                }
            }

            $my_repeat_freq = implode(",", $days_every_week_arr);
            $my_repeat_type = 6;
            $event_date = setEventDate($_POST['form_date'], $my_repeat_freq);
        } elseif (!empty($_POST['form_repeat'])) {
            $my_recurrtype = 1;
            if ($my_repeat_type > 4) {
                $my_recurrtype = 2;
                $time = strtotime($event_date);
                $my_repeat_on_day = 0 + date('w', $time);
                $my_repeat_on_freq = $my_repeat_freq;
                if ($my_repeat_type == 5) {
                    $my_repeat_on_num = intval((date('j', $time) - 1) / 7) + 1;
                } else {
                    // Last occurence of this weekday on the month
                    $my_repeat_on_num = 5;
                }

                // Maybe not needed, but for consistency with postcalendar:
                $my_repeat_freq = 0;
                $my_repeat_type = 0;
            }
        }


            // Useless garbage that we must save.
            $locationspecs = array("event_location" => "",
                            "event_street1" => "",
                            "event_street2" => "",
                            "event_city" => "",
                            "event_state" => "",
                            "event_postal" => ""
                        );
            $locationspec = serialize($locationspecs);

            // capture the recurring specifications
            $recurrspec = array("event_repeat_freq" => "$my_repeat_freq",
                        "event_repeat_freq_type" => "$my_repeat_type",
                        "event_repeat_on_num" => "$my_repeat_on_num",
                        "event_repeat_on_day" => "$my_repeat_on_day",
                        "event_repeat_on_freq" => "$my_repeat_on_freq",
                        "exdate" => $_POST['form_repeat_exdate']
                    );

            //
        if ($my_recurrtype == 2) { // Added by epsdky 2016 (details in commit)
            if ($_POST['old_repeats'] == 2) {
                if ($_POST['rt2_flag2']) {
                    $recurrspec['rt2_pf_flag'] = "1";
                }
            } else {
                $recurrspec['rt2_pf_flag'] = "1";
            }
        } // End of addition by epsdky
            //
            // no recurr specs, this is used for adding a new non-recurring event
            $noRecurrspec = array("event_repeat_freq" => "",
                        "event_repeat_freq_type" => "",
                        "event_repeat_on_num" => "1",
                        "event_repeat_on_day" => "0",
                        "event_repeat_on_freq" => "0",
                        "exdate" => ""
                    );
    }//if ($_POST['form_action'] == "duplicate" || $_POST['form_action'] == "save")
//=============================================================================================================================
    if ($_POST['form_action'] == "duplicate") {
        $eid = InsertEventFull();
        DOBandEncounter($eid);
    }

// If we are saving, then save and close the window.
//
    if ($_POST['form_action'] == "save") {
        /* =======================================================
         *                    UPDATE EVENTS
         * =====================================================*/
        if ($eid) {
            // what is multiple key around this $eid?
            $row = sqlQuery("SELECT pc_multiple FROM openemr_postcalendar_events WHERE pc_eid = ?", array($eid));

            // ====================================
            // multiple providers
            // ====================================
            if ($GLOBALS['select_multi_providers'] && $row['pc_multiple']) {
                // obtain current list of providers regarding the multiple key
                $up = sqlStatement("SELECT pc_aid FROM openemr_postcalendar_events WHERE pc_multiple=?", array($row['pc_multiple']));
                while ($current = sqlFetchArray($up)) {
                    $providers_current[] = $current['pc_aid'];
                }

                // get the new list of providers from the submitted form
                $providers_new = $_POST['form_provider'];

                // ===== Only current event of repeating series =====
                if ($_POST['recurr_affect'] == 'current') {
                    // update all existing event records to exlude the current date
                    foreach ($providers_current as $provider) {
                        // update the provider's original event
                        // get the original event's repeat specs
                        $origEvent = sqlQuery("SELECT pc_recurrspec FROM openemr_postcalendar_events ".
                        " WHERE pc_aid = ? AND pc_multiple=?", array($provider,$row['pc_multiple']));
                        $oldRecurrspec = unserialize($origEvent['pc_recurrspec']);
                        $selected_date = date("Ymd", strtotime($_POST['selected_date']));
                        if ($oldRecurrspec['exdate'] != "") {
                            $oldRecurrspec['exdate'] .= ",".$selected_date;
                        } else {
                            $oldRecurrspec['exdate'] .= $selected_date;
                        }


                        // mod original event recur specs to exclude this date
                            sqlStatement("UPDATE openemr_postcalendar_events SET " .
                            " pc_recurrspec = ? ".
                            " WHERE pc_aid = ? AND pc_multiple=?", array(serialize($oldRecurrspec),$provider,$row['pc_multiple']));
                    }

                    // obtain the next available unique key to group multiple providers around some event
                    $q = sqlStatement("SELECT MAX(pc_multiple) as max FROM openemr_postcalendar_events");
                    $max = sqlFetchArray($q);
                    $new_multiple_value = $max['max'] + 1;

                    // insert a new event record for each provider selected on the form
                    foreach ($providers_new as $provider) {
                        // insert a new event on this date with POST form data
                        $args = $_POST;
                        // specify some special variables needed for the INSERT
                        $args['new_multiple_value'] = $new_multiple_value;
                        $args['form_provider'] = $provider;
                        $args['event_date'] = $event_date;
                        $args['duration'] = $duration * 60;
                        // this event is forced to NOT REPEAT
                        $args['form_repeat'] = "0";
                        $args['days_every_week'] = "0";
                        $args['recurrspec'] = $noRecurrspec;
                        $args['form_enddate'] = "0000-00-00";
                        $args['starttime'] = $starttime;
                        $args['endtime'] = $endtime;
                        $args['locationspec'] = $locationspec;
                        InsertEvent($args);
                    }
                } // ===== Future Recurring events of a repeating series =====
                else if ($_POST['recurr_affect'] == 'future') {
                    // update all existing event records to
                    // stop recurring on this date-1
                    $selected_date = date("Y-m-d", (strtotime($_POST['selected_date'])-24*60*60));
                    foreach ($providers_current as $provider) {
                        // In case of a change in the middle of the event
                        if (strcmp($_POST['event_start_date'], $_POST['selected_date'])!=0) {
                            // mod original event recur specs to end on this date
                            sqlStatement("UPDATE openemr_postcalendar_events SET " .
                            " pc_enddate = ? " .
                            " WHERE pc_aid = ? AND pc_multiple=?", array($selected_date, $provider, $row['pc_multiple']));
                        } // In case of a change in the event head
                        else {
                            sqlStatement("DELETE FROM openemr_postcalendar_events " .
                            " WHERE pc_aid = ? AND pc_multiple=?", array($provider, $row['pc_multiple']));
                        }
                    }

                    // obtain the next available unique key to group multiple providers around some event
                    $q = sqlStatement("SELECT MAX(pc_multiple) as max FROM openemr_postcalendar_events");
                    $max = sqlFetchArray($q);
                    $new_multiple_value = $max['max'] + 1;

                    // insert a new event record for each provider selected on the form
                    foreach ($providers_new as $provider) {
                        // insert a new event on this date with POST form data
                        $args = $_POST;
                        // specify some special variables needed for the INSERT
                        $args['new_multiple_value'] = $new_multiple_value;
                        $args['form_provider'] = $provider;
                        $args['event_date'] = $event_date;
                        $args['duration'] = $duration * 60;
                        $args['recurrspec'] = $recurrspec;
                        $args['starttime'] = $starttime;
                        $args['endtime'] = $endtime;
                        $args['locationspec'] = $locationspec;
                        InsertEvent($args);
                    }
                } else {
                    /* =================================================================== */
                    // ===== a Single event or All events in a repeating series ==========
                    /* =================================================================== */

                    // this difference means that some providers from current was UNCHECKED
                    // so we must delete this event for them
                    $r1 = array_diff($providers_current, $providers_new);
                    $old_eid = null;
                    if (count($r1)) {
                        foreach ($r1 as $to_be_removed) {
                            if (!empty($_POST['form_gid'])) {
                                $old_eid =  sqlQuery("SELECT pc_eid FROM openemr_postcalendar_events WHERE pc_aid=? AND pc_multiple=?", array($to_be_removed,$row['pc_multiple']));
                            }
                            sqlQuery("DELETE FROM openemr_postcalendar_events WHERE pc_aid=? AND pc_multiple=?", array($to_be_removed,$row['pc_multiple']));
                        }
                    }
print_r ($row['pc_apptstatus']);
                    // perform a check to see if user changed event date
                    // this is important when editing an existing recurring event
                    // oct-08 JRM
                    if ($_POST['form_date'] == $_POST['selected_date']) {
                        // user has NOT changed the start date of the event (and not recurrtype 3)
                        if ($my_recurrtype != 3) {
                            $event_date = $_POST['event_start_date'];
                        }
                    }

                    // this difference means that some providers were added
                    // so we must insert this event for them
                    $r2 = array_diff($providers_new, $providers_current);
                    if (count($r2)) {
                        foreach ($r2 as $to_be_inserted) {
                            $args = $_POST;
                            // specify some special variables needed for the INSERT
                            $args['new_multiple_value'] = $row['pc_multiple'];
                            $args['form_provider'] = $to_be_inserted;
                            $args['event_date'] = $event_date;
                            $args['duration'] = $duration * 60;
                            $args['recurrspec'] = $recurrspec;
                            $args['starttime'] = $starttime;
                            $args['endtime'] = $endtime;
                            $args['locationspec'] = $locationspec;
                            $new_eid = InsertEvent($args);
                        }
                    }

                    if (!is_null($old_eid) && !empty($old_eid)) {
                       // update group encounter is connected to event.
                        sqlQuery("UPDATE form_groups_encounter SET appt_id = ? WHERE appt_id = ?", array($new_eid, $old_eid['pc_eid']));
                    }

                    // after the two diffs above, we must update for remaining providers
                    // those who are intersected in $providers_current and $providers_new
                    foreach ($_POST['form_provider'] as $provider) {
                        sqlStatement("UPDATE openemr_postcalendar_events SET " .
                        "pc_catid = '" . add_escape_custom($_POST['form_category']) . "', " .
                        "pc_pid = '" . add_escape_custom($_POST['form_pid']) . "', " .
                        "pc_title = '" . add_escape_custom($_POST['form_title']) . "', " .
                        "pc_time = NOW(), " .
                        "pc_hometext = '" . add_escape_custom($_POST['form_comments']) . "', " .
                        "pc_room = '" . add_escape_custom($_POST['form_room']) . "', " .
                        "pc_informant = '" . add_escape_custom($_SESSION['authUserID']) . "', " .
                        "pc_eventDate = '" . add_escape_custom($event_date) . "', " .
                        "pc_endDate = '" . add_escape_custom($_POST['form_enddate']) . "', " .
                        "pc_duration = '" . add_escape_custom(($duration * 60)) . "', " .
                        "pc_recurrtype = '" . add_escape_custom($my_recurrtype) . "', " .
                        "pc_recurrspec = '" . add_escape_custom(serialize($recurrspec)) . "', " .
                        "pc_startTime = '" . add_escape_custom($starttime) . "', " .
                        "pc_endTime = '" . add_escape_custom($endtime) . "', " .
                        "pc_alldayevent = '" . add_escape_custom($_POST['form_allday']) . "', " .
                        "pc_apptstatus = '" . add_escape_custom($_POST['form_apptstatus']) . "', "  .
                        "pc_prefcatid = '" . add_escape_custom($_POST['form_prefcat']) . "' ,"  .
                        "pc_facility = '" . add_escape_custom((int)$_POST['facility']) ."' ,"  . // FF stuff
                        "pc_billing_location = '" . add_escape_custom((int)$_POST['billing_facility']) ."' "  .
                        "WHERE pc_aid = '" . add_escape_custom($provider) . "' AND pc_multiple = '" . add_escape_custom($row['pc_multiple'])  . "'");
                   
					} // foreach
                }

            // ====================================
            // single provider
            // ====================================
            } elseif (!$row['pc_multiple']) {
                if ($GLOBALS['select_multi_providers']) {
                    $prov = $_POST['form_provider'][0];
                } else {
                    $prov =  $_POST['form_provider'];
                }

                if ($_POST['recurr_affect'] == 'current') {
                    // get the original event's repeat specs
                    $origEvent = sqlQuery("SELECT pc_recurrspec FROM openemr_postcalendar_events WHERE pc_eid = ?", array($eid));
                    $oldRecurrspec = unserialize($origEvent['pc_recurrspec']);
                    $selected_date = date("Ymd", strtotime($_POST['selected_date']));
                    if ($oldRecurrspec['exdate'] != "") {
                        $oldRecurrspec['exdate'] .= ",".$selected_date;
                    } else {
                        $oldRecurrspec['exdate'] .= $selected_date;
                    }

                    // mod original event recur specs to exclude this date
                        sqlStatement("UPDATE openemr_postcalendar_events SET " .
                        " pc_recurrspec = ? ".
                        " WHERE pc_eid = ?", array(serialize($oldRecurrspec),$eid));

                    // insert a new event on this date with POST form data
                    $args = $_POST;
                    // specify some special variables needed for the INSERT
                    $args['event_date'] = $event_date;
                    $args['duration'] = $duration * 60;
                    // this event is forced to NOT REPEAT
                    $args['form_repeat'] = "0";
                    $args['days_every_week'] = "0";
                    $args['recurrspec'] = $noRecurrspec;
                    $args['form_enddate'] = "0000-00-00";
                    $args['starttime'] = $starttime;
                    $args['endtime'] = $endtime;
                    $args['locationspec'] = $locationspec;
                    InsertEvent($args);
                } else if ($_POST['recurr_affect'] == 'future') {
                    // mod original event to stop recurring on this date-1
                    $selected_date = date("Ymd", (strtotime($_POST['selected_date'])-24*60*60));
                    sqlStatement("UPDATE openemr_postcalendar_events SET " .
                    " pc_enddate = ? ".
                    " WHERE pc_eid = ?", array($selected_date,$eid));

                    // insert a new event starting on this date with POST form data
                    $args = $_POST;
                    // specify some special variables needed for the INSERT
                    $args['event_date'] = $event_date;
                    $args['duration'] = $duration * 60;
                    $args['recurrspec'] = $recurrspec;
                    $args['starttime'] = $starttime;
                    $args['endtime'] = $endtime;
                    $args['locationspec'] = $locationspec;
                    InsertEvent($args);
                } else {
        // perform a check to see if user changed event date
        // this is important when editing an existing recurring event
        // oct-08 JRM
                    if ($_POST['form_date'] == $_POST['selected_date']) {
                        // user has NOT changed the start date of the event (and not recurrtype 3)
                        if ($my_recurrtype != 3) {
                            $event_date = $_POST['event_start_date'];
                        }
                    }


                    // mod the SINGLE event or ALL EVENTS in a repeating series
                    // simple provider case
                    sqlStatement("UPDATE openemr_postcalendar_events SET " .
                    "pc_catid = '" . add_escape_custom($_POST['form_category']) . "', " .
                    "pc_aid = '" . add_escape_custom($prov) . "', " .
                    "pc_pid = '" . add_escape_custom($_POST['form_pid']) . "', " .
                    "pc_title = '" . add_escape_custom($_POST['form_title']) . "', " .
                    "pc_time = NOW(), " .
                    "pc_hometext = '" . add_escape_custom($_POST['form_comments']) . "', " .
                    "pc_room = '" . add_escape_custom($_POST['form_room']) . "', " .
                    "pc_informant = '" . add_escape_custom($_SESSION['authUserID']) . "', " .
                    "pc_eventDate = '" . add_escape_custom($event_date) . "', " .
                    "pc_endDate = '" . add_escape_custom($_POST['form_enddate']) . "', " .
                    "pc_duration = '" . add_escape_custom(($duration * 60)) . "', " .
                    "pc_recurrtype = '" . add_escape_custom($my_recurrtype) . "', " .
                    "pc_recurrspec = '" . add_escape_custom(serialize($recurrspec)) . "', " .
                    "pc_startTime = '" . add_escape_custom($starttime) . "', " .
                    "pc_endTime = '" . add_escape_custom($endtime) . "', " .
                    "pc_alldayevent = '" . add_escape_custom($_POST['form_allday']) . "', " .
                    "pc_apptstatus = '" . add_escape_custom($_POST['form_apptstatus']) . "', "  .
                    "pc_prefcatid = '" . add_escape_custom($_POST['form_prefcat']) . "' ,"  .
                    "pc_facility = '" . add_escape_custom((int)$_POST['facility']) ."' ,"  . // FF stuff
                    "pc_billing_location = '" . add_escape_custom((int)$_POST['billing_facility']) ."' "  .
                    "WHERE pc_eid = '" . add_escape_custom($eid) . "'");
                }
            }

            // =======================================
            // end Update Multi providers case
            // =======================================

            // EVENTS TO FACILITIES
            $e2f = (int)$eid;
        } else {
            /* =======================================================
         *                    INSERT NEW EVENT(S)
         * ======================================================*/

            $eid = InsertEventFull();
        }

            // done with EVENT insert/update statements

            DOBandEncounter(isset($eid) ? $eid : null);
    } // =======================================
//    DELETE EVENT(s)
// =======================================
    else if ($_POST['form_action'] == "delete") {
        // =======================================
        //  multi providers event
        // =======================================
        if ($GLOBALS['select_multi_providers']) {
            // what is multiple key around this $eid?
            $row = sqlQuery("SELECT pc_multiple FROM openemr_postcalendar_events WHERE pc_eid = ?", array($eid));

            // obtain current list of providers regarding the multiple key
            $providers_current = array();
            $up = sqlStatement("SELECT pc_aid FROM openemr_postcalendar_events WHERE pc_multiple=?", array($row['pc_multiple']));
            while ($current = sqlFetchArray($up)) {
                $providers_current[] = $current['pc_aid'];
            }

            // establish a WHERE clause
            if ($row['pc_multiple']) {
                $whereClause = "pc_multiple = '{$row['pc_multiple']}'";
            } else {
                $whereClause = "pc_eid = '$eid'";
            }

            if ($_POST['recurr_affect'] == 'current') {
                // update all existing event records to exlude the current date
                foreach ($providers_current as $provider) {
                    // update the provider's original event
                    // get the original event's repeat specs
                    $origEvent = sqlQuery("SELECT pc_recurrspec FROM openemr_postcalendar_events ".
                    " WHERE pc_aid <=> ? AND pc_multiple=?", array($provider,$row['pc_multiple']));
                    $oldRecurrspec = unserialize($origEvent['pc_recurrspec']);
                    $selected_date = date("Y-m-d", strtotime($_POST['selected_date']));
                    if ($oldRecurrspec['exdate'] != "") {
                        $oldRecurrspec['exdate'] .= ",".$selected_date;
                    } else {
                            $oldRecurrspec['exdate'] .= $selected_date;
                    }

                    // mod original event recur specs to exclude this date
                        sqlStatement("UPDATE openemr_postcalendar_events SET " .
                        " pc_recurrspec = ? ".
                        " WHERE ". $whereClause, array(serialize($oldRecurrspec)));
                }
            } else if ($_POST['recurr_affect'] == 'future') {
                // update all existing event records to stop recurring on this date-1
                $selected_date = date("Y-m-d", (strtotime($_POST['selected_date'])-24*60*60));
                foreach ($providers_current as $provider) {
                    // In case of a change in the middle of the event
                    if (strcmp($_POST['event_start_date'], $_POST['selected_date'])!=0) {
                        // update the provider's original event
                        sqlStatement("UPDATE openemr_postcalendar_events SET " .
                        " pc_enddate = ? " .
                        " WHERE " . $whereClause, array($selected_date));
                    } // In case of a change in the event head
                    else {
                        sqlStatement("DELETE FROM openemr_postcalendar_events WHERE ".$whereClause);
                    }
                }
            } else {
                // really delete the event from the database
                sqlStatement("DELETE FROM openemr_postcalendar_events WHERE ".$whereClause);
            }
        } // =======================================
        //  single provider event
        // =======================================
        else {
            if ($_POST['recurr_affect'] == 'current') {
                // mod original event recur specs to exclude this date

                // get the original event's repeat specs
                $origEvent = sqlQuery("SELECT pc_recurrspec FROM openemr_postcalendar_events WHERE pc_eid = ?", array($eid));
                $oldRecurrspec = unserialize($origEvent['pc_recurrspec']);
                $selected_date = date("Ymd", strtotime($_POST['selected_date']));
                if ($oldRecurrspec['exdate'] != "") {
                    $oldRecurrspec['exdate'] .= ",".$selected_date;
                } else {
                    $oldRecurrspec['exdate'] .= $selected_date;
                }

                    sqlStatement("UPDATE openemr_postcalendar_events SET " .
                    " pc_recurrspec = ? ".
                    " WHERE pc_eid = ?", array(serialize($oldRecurrspec),$eid));
            } else if ($_POST['recurr_affect'] == 'future') {
                // mod original event to stop recurring on this date-1
                $selected_date = date("Ymd", (strtotime($_POST['selected_date'])-24*60*60));
                sqlStatement("UPDATE openemr_postcalendar_events SET " .
                    " pc_enddate = ? ".
                    " WHERE pc_eid = ?", array($selected_date,$eid));
            } else {
                // fully delete the event from the database
                sqlStatement("DELETE FROM openemr_postcalendar_events WHERE pc_eid = ?", array($eid));
            }
        }
    }

    if ($_POST['form_action'] != "") {
        // Close this window and refresh the calendar (or the patient_tracker) display.
        echo "<html>\n<body>\n<script language='JavaScript'>\n";
        if ($info_msg) {
            echo " alert('" . addslashes($info_msg) . "');\n";
        }


        echo " if (opener && !opener.closed && opener.refreshme) {\n " .
          "  opener.refreshme();\n " . // This is for standard calendar page refresh
          " } else {\n " .
          " if(window.opener.pattrk){" .
         "  window.opener.pattrk.submit()\n " . // This is for patient flow board page refresh}
          " }};\n";
        echo " dlgclose();\n";
        echo "</script>\n</body>\n</html>\n";
        exit();
    }

 //*********************************
 // If we get this far then we are displaying the form.
 //*********************************

/*********************************************************************
        This has been migrate to the administration->lists
 $statuses = array(
  '-' => '',
  '*' => xl('* Reminder done'),
  '+' => xl('+ Chart pulled'),
  'x' => xl('x Cancelled'), // added Apr 2008 by JRM
  '?' => xl('? No show'),
  '@' => xl('@ Arrived'),
  '~' => xl('~ Arrived late'),
  '!' => xl('! Left w/o visit'),
  '#' => xl('# Ins/fin issue'),
  '<' => xl('< In exam room'),
  '>' => xl('> Checked out'),
  '$' => xl('$ Coding done'),
   '%' => xl('% Cancelled <  24h ')
 );
*********************************************************************/

    $repeats = 0; // if the event repeats
    $repeattype = '0';
    $repeatfreq = '0';
    $patientid = '';
    if ($_REQUEST['patientid']) {
        $patientid = $_REQUEST['patientid'];
    }

    $patientname = null;
    $patienttitle = array();
    $pcroom = "";
    $hometext = "";
    $row = array();
    $informant = "";
    $groupid = '';
    if ($_REQUEST['groupid']) {
        $groupid = $_REQUEST['groupid'];
    }

    $groupname = null;
    $group_statuses = getGroupStatuses();



 // If we are editing an existing event, then get its data.
    if ($eid) {
        // $row = sqlQuery("SELECT * FROM openemr_postcalendar_events WHERE pc_eid = $eid");

        $row = sqlQuery("SELECT e.*, u.fname, u.mname, u.lname " .
          "FROM openemr_postcalendar_events AS e " .
          "LEFT OUTER JOIN users AS u ON u.id = e.pc_informant " .
          "WHERE pc_eid = ?", array($eid));
        $informant = $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'];

        // instead of using the event's starting date, keep what has been provided
        // via the GET array, see the top of this file
        if (empty($_GET['date'])) {
            $date = $row['pc_eventDate'];
        }

        $eventstartdate = $row['pc_eventDate']; // for repeating event stuff - JRM Oct-08
        $userid = $row['pc_aid'];
        $patientid = $row['pc_pid'];
        $groupid = $row['pc_gid'];
        $starttimeh = substr($row['pc_startTime'], 0, 2) + 0;
        $starttimem = substr($row['pc_startTime'], 3, 2);
        $repeats = $row['pc_recurrtype'];
        $multiple_value = $row['pc_multiple'];

        // parse out the repeating data, if any
        $rspecs = unserialize($row['pc_recurrspec']); // extract recurring data
        $repeattype = $rspecs['event_repeat_freq_type'];
        $repeatfreq = $rspecs['event_repeat_freq'];
        $repeatexdate = $rspecs['exdate']; // repeating date exceptions

        // Adjustments for repeat type 2, a particular weekday of the month.
        if ($repeats == 2) {
                $repeatfreq = $rspecs['event_repeat_on_freq'];
            if ($rspecs['event_repeat_on_num'] < 5) {
                $repeattype = 5;
            } else {
                 $repeattype = 6;
            }
        }

        $recurrence_end_date = ($row['pc_endDate'] && $row['pc_endDate'] != '0000-00-00') ? $row['pc_endDate'] : null;
        $pcroom = $row['pc_room'];
        $hometext = $row['pc_hometext'];
        if (substr($hometext, 0, 6) == ':text:') {
            $hometext = substr($hometext, 6);
        }
    } else {
          // a NEW event
          $eventstartdate = $date; // for repeating event stuff - JRM Oct-08

          //-------------------------------------
          //(CHEMED)
          //Set default facility for a new event based on the given 'userid'
        if ($userid) {
            /*************************************************************
            $pref_facility = sqlFetchArray(sqlStatement("SELECT facility_id, facility FROM users WHERE id = $userid"));
            *************************************************************/
            if ($_SESSION['pc_facility']) {
                $pref_facility = sqlFetchArray(sqlStatement(
                    "
		        SELECT f.id as facility_id,
		        f.name as facility
		        FROM facility f
		        WHERE f.id = ?
	          ",
                    array($_SESSION['pc_facility'])
                ));
            } else {
                $pref_facility = sqlFetchArray(sqlStatement("
            SELECT u.facility_id,
	          f.name as facility
            FROM users u
            LEFT JOIN facility f on (u.facility_id = f.id)
            WHERE u.id = ?
            ", array($userid)));
            }

             /************************************************************/
             $e2f = $pref_facility['facility_id'];
             $e2f_name = $pref_facility['facility'];
        }

          //END of CHEMED -----------------------
    }

 // If we have a patient ID, get the name and phone numbers to display.
    if ($patientid) {
        $prow = sqlQuery("SELECT lname, fname, phone_home, phone_biz, DOB " .
         "FROM patient_data WHERE pid = ?", array($patientid));
        $patientname = $prow['fname'] . ", " . $prow['lname'];
        if ($prow['phone_home']) {
            $patienttitle['phone_home'] = xl("Home Phone").": " . $prow['phone_home'];
        }

        if ($prow['phone_biz']) {
            $patienttitle['phone_biz'] = xl("Work Phone").": " . $prow['phone_biz'];
        }
    }

 // If we have a group id, get group data
    if ($groupid) {
        $group_data = getGroup($groupid);
        $groupname = $group_data['group_name'];
        $group_end_date = $group_data['group_end_date'];
        if (!$recurrence_end_date && $group_end_date && $group_end_date != '0000-00-00') {
            $recurrence_end_date = $group_end_date;// If there is no recurr end date get group's end date as default (only if group has an end date)
        }
    }

 // Get the providers list.
    $ures = sqlStatement("SELECT id, username, fname, lname FROM users WHERE " .
    "authorized != 0 AND active = 1 ORDER BY lname, fname");

 // Get event categories.
    $cres = sqlStatement("SELECT pc_catid, pc_catname, pc_recurrtype, pc_duration, pc_end_all_day " .
    "FROM openemr_postcalendar_categories where pc_active = 1 ORDER BY pc_seq");

 // Fix up the time format for AM/PM.
    $startampm = '1';
    if ($starttimeh >= 12) { // p.m. starts at noon and not 12:01
        $startampm = '2';
        if ($starttimeh > 12) {
            $starttimeh -= 12;
        }
    }

?>
<!DOCTYPE html>
<html>
<head>

<title><?php echo $eid ? xlt('Edit') : xlt('Add New') ?> <?php echo xlt('Event');?></title>

<style>
td { font-size:0.8em; }
</style>

<script language="JavaScript">

<?php require $GLOBALS['srcdir'] . "/formatting_DateToYYYYMMDD_js.js.php" ?>

 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

 var durations = new Array();
 // var rectypes  = new Array();
<?php
 // Read the event categories, generate their options list, and get
 // the default event duration from them if this is a new event.
 $cattype=0;
if ($_GET['prov']==true) {
    $cattype=1;
}

if ($_GET['group'] == true) {
    $cattype=3;
}

$cres = sqlStatement("SELECT pc_catid, pc_cattype, pc_catname, " .
"pc_recurrtype, pc_duration, pc_end_all_day " .
"FROM openemr_postcalendar_categories where pc_active = 1 ORDER BY pc_seq");
$catoptions = "";
$prefcat_options = "    <option value='0'>-- " . xlt("None") . " --</option>\n";
$thisduration = 0;
if ($eid) {
    $thisduration = $row['pc_alldayevent'] ? 1440 : round($row['pc_duration'] / 60);
}

while ($crow = sqlFetchArray($cres)) {
    $duration = round($crow['pc_duration'] / 60);
    if ($crow['pc_end_all_day']) {
        $duration = 1440;
    }

    // This section is to build the list of preferred categories:
    if ($duration) {
        $prefcat_options .= "    <option value='" . attr($crow['pc_catid']) . "'";
        if ($eid) {
            if ($crow['pc_catid'] == $row['pc_prefcatid']) {
                $prefcat_options .= " selected";
            }
        }

        $prefcat_options .= ">" . text(xl_appt_category($crow['pc_catname'])) . "</option>\n";
    }

    if ($crow['pc_cattype'] != $cattype) {
        continue;
    }

    echo " durations[" . attr($crow['pc_catid']) . "] = " . attr($duration) . "\n";
    // echo " rectypes[" . $crow['pc_catid'] . "] = " . $crow['pc_recurrtype'] . "\n";
    $catoptions .= "    <option value='" . attr($crow['pc_catid']) . "'";
    if ($eid) {
        if ($crow['pc_catid'] == $row['pc_catid']) {
            $catoptions .= " selected";
        }
    } else {
        if ($crow['pc_catid'] == $default_catid) {
            $catoptions .= " selected";
            $thisduration = $duration;
        }
    }

    $catoptions .= ">" . text(xl_appt_category($crow['pc_catname'])) . "</option>\n";
}
?>

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

 // This is for callback by the find-patient popup.
 function setpatient(pid, lname, fname, dob) {
  var f = document.forms[0];
  f.form_patient.value = lname + ', ' + fname;
  f.form_pid.value = pid;
  dobstyle = (dob == '' || dob.substr(5, 10) == '00-00') ? '' : 'none';
  document.getElementById('dob_row').style.display = dobstyle;
 }

 // This invokes the find-patient popup.
 function sel_patient() {
  let title = '<?php echo xlt('Patient Search'); ?>';
  dlgopen('find_patient_popup.php', 'findPatient', 650, 300, '', title);
 }

 // This is for callback by the find-group popup.
 function setgroup(gid, name, end_date) {
     var f = document.forms[0];
     f.form_group.value = name;
     f.form_gid.value = gid;
     if(f.form_enddate.value == ""){
        f.form_enddate.value = end_date;
     }

 }

 // This invokes the find-group popup.
 function sel_group() {
     top.restoreSession();
     let title = '<?php echo xlt('Group Search'); ?>';
     dlgopen('find_group_popup.php', '_blank', 650, 300, '', title);
 }

 // Do whatever is needed when a new event category is selected.
 // For now this means changing the event title and duration.
 function set_display() {
  var f = document.forms[0];
  var s = f.form_category;
  if (s.selectedIndex >= 0) {
   var catid = s.options[s.selectedIndex].value;
   var style_apptstatus = document.getElementById('title_apptstatus').style;
   var style_prefcat = document.getElementById('title_prefcat').style;
   if (catid == '2') { // In Office
    style_apptstatus.display = 'none';
    style_prefcat.display = '';
    f.form_apptstatus.style.display = 'none';
    f.form_prefcat.style.display = '';
   } else {
    style_prefcat.display = 'none';
    style_apptstatus.display = '';
    f.form_prefcat.style.display = 'none';
    f.form_apptstatus.style.display = '';
   }
  }
 }

 // Do whatever is needed when a new event category is selected.
 // For now this means changing the event title and duration.
 function set_category() {
  var f = document.forms[0];
  var s = f.form_category;
  if (s.selectedIndex >= 0) {
   var catid = s.options[s.selectedIndex].value;
   f.form_title.value = s.options[s.selectedIndex].text;
   f.form_duration.value = durations[catid];
   set_display();
  }
 }

 // Modify some visual attributes when the all-day or timed-event
 // radio buttons are clicked.
 function set_allday() {
  var f = document.forms[0];
  var color1 = '#777777';
  var color2 = '#777777';
  var disabled2 = true;
  if (document.getElementById('rballday1').checked) {
   color1 = '#000000';
  }
  if (document.getElementById('rballday2').checked) {
   color2 = '#000000';
   disabled2 = false;
  }
  document.getElementById('tdallday1').style.color = color1;
  document.getElementById('tdallday2').style.color = color2;
  document.getElementById('tdallday3').style.color = color2;
  document.getElementById('tdallday4').style.color = color2;
  document.getElementById('tdallday5').style.color = color2;
  f.form_hour.disabled     = disabled2;
  f.form_minute.disabled   = disabled2;
  f.form_ampm.disabled     = disabled2;
  f.form_duration.disabled = disabled2;
 }

 // Modify some visual attributes when the Repeat checkbox is clicked.
 function set_repeat() {
  var f = document.forms[0];
  var isdisabled = true;
  var mycolor = '#777777';
  var myvisibility = 'hidden';
  if (f.form_repeat.checked) {
      f.days_every_week.checked = false;
      document.getElementById("days_label").style.color = mycolor;
      var days = document.getElementById("days").getElementsByTagName('input');
      var labels = document.getElementById("days").getElementsByTagName('label');
      for(var i=0; i < days.length; i++){
          days[i].disabled = isdisabled;
          labels[i].style.color = mycolor;
      }
      isdisabled = false;
      mycolor = '#000000';
      myvisibility = 'visible';
  }
  f.form_repeat_type.disabled = isdisabled;
  f.form_repeat_freq.disabled = isdisabled;
  f.form_enddate.disabled = isdisabled;
  document.getElementById('tdrepeat1').style.color = mycolor;
  document.getElementById('tdrepeat2').style.color = mycolor;
 }

 // Event when days_every_week is checked.
 function set_days_every_week() {
     var f = document.forms[0];
     if (f.days_every_week.checked) {
         //disable regular repeat
         f.form_repeat.checked = false;
         f.form_repeat_type.disabled = true;
         f.form_repeat_freq.disabled = true;
         document.getElementById('tdrepeat1').style.color = '#777777';

         //enable end_date setting
         document.getElementById('tdrepeat2').style.color = '#000000';
         f.form_enddate.disabled = false;

         var isdisabled = false;
         var mycolor = '#000000';
         var myvisibility = 'visible';
     }
     else{
         var isdisabled = true;
         var mycolor = '#777777';
         var myvisibility = 'hidden';
     }
     document.getElementById("days_label").style.color = mycolor;
     var days = document.getElementById("days").getElementsByTagName('input');
     var labels = document.getElementById("days").getElementsByTagName('label');
     for(var i=0; i < days.length; i++){
         days[i].disabled = isdisabled;
         labels[i].style.color = mycolor;
     }

     //If no repetition is checked, disable end_date setting.
     if(!f.days_every_week.checked  && !f.form_repeat.checked){
         //disable end_date setting
         document.getElementById('tdrepeat2').style.color = mycolor;
         f.form_enddate.disabled = isdisabled;
     }


 }

 // Constants used by dateChanged() function.
 var occurNames = new Array(
  '<?php echo xls("1st"); ?>',
  '<?php echo xls("2nd"); ?>',
  '<?php echo xls("3rd"); ?>',
  '<?php echo xls("4th"); ?>'
 );

var weekDays = new Array(
  '<?php echo xls("Sunday"); ?>',
  '<?php echo xls("Monday"); ?>',
  '<?php echo xls("Tuesday"); ?>',
  '<?php echo xls("Wednesday"); ?>',
  '<?php echo xls("Thursday"); ?>',
  '<?php echo xls("Friday"); ?>',
  '<?php echo xls("Saturday"); ?>'
 );

 // Monitor start date changes to adjust repeat type options.
 function dateChanged() {
  var f = document.forms[0];
  if (!f.form_date.value) return;
  var d = new Date(DateToYYYYMMDD_js(f.form_date.value));
  var downame = weekDays[d.getUTCDay()];
  var nthtext = '';
  var occur = Math.floor((d.getUTCDate() - 1) / 7);
  if (occur < 4) { // 5th is not allowed
   nthtext = occurNames[occur] + ' ' + downame;
  }
  var lasttext = '';
  var tmp = new Date(d.getUTCFullYear(), d.getUTCMonth() + 1, 0);
  if (tmp.getDate() - d.getUTCDate() < 7) { // Modified by epsdky 2016 (details in commit)
   // This is a last occurrence of the specified weekday in the month,
   // so permit that as an option.
   lasttext = '<?php echo xls("Last"); ?> ' + downame;
  }
  var si = f.form_repeat_type.selectedIndex;
  var opts = f.form_repeat_type.options;
  opts.length = 5; // remove any nth and Last entries
  if (nthtext ) opts[opts.length] = new Option(nthtext , '5');
  if (lasttext) opts[opts.length] = new Option(lasttext, '6');
  if (si < opts.length) f.form_repeat_type.selectedIndex = si;
  else f.form_repeat_type.selectedIndex = 5; // Added by epsdky 2016 (details in commit)
 }

 // This is for callback by the find-available popup.
 function setappt(year,mon,mday,hours,minutes) {
  var f = document.forms[0];
  f.form_date.value = '' + year + '-' +
   ('' + (mon  + 100)).substring(1) + '-' +
   ('' + (mday + 100)).substring(1);
  f.form_ampm.selectedIndex = (hours >= 12) ? 1 : 0;
  f.form_hour.value = (hours > 12) ? hours - 12 : hours;
  f.form_minute.value = ('' + (minutes + 100)).substring(1);
 }

    // Invoke the find-available popup.
    function find_available(extra) {
        top.restoreSession();
        // (CHEMED) Conditional value selection, because there is no <select> element
        // when making an appointment for a specific provider
        var s = document.forms[0].form_provider;
        var f = document.forms[0].facility;
        <?php if ($userid != 0) { ?>
            s = document.forms[0].form_provider.value;
            f = document.forms[0].facility.value;
        <?php } else {?>
            s = document.forms[0].form_provider.options[s.selectedIndex].value;
            f = document.forms[0].facility.options[f.selectedIndex].value;
        <?php }?>
        var c = document.forms[0].form_category;
        var formDate = document.forms[0].form_date;
        let title = '<?php echo xlt('Find Appointment'); ?>';
        dlgopen('<?php echo $GLOBALS['web_root']; ?>/interface/main/calendar/find_appt_popup.php' +
                '?providerid=' + s +
                '&catid=' + c.options[c.selectedIndex].value +
                '&facility=' + f +
                '&startdate=' + formDate.value +
                '&evdur=' + document.forms[0].form_duration.value +
                '&eid=<?php echo 0 + $eid; ?>' + extra,
                '', 725, 200, '', title);
		//feysal Version 1 for Payment removed. Version 2 added buttonin demographic
		//dlgopen('<?php echo $GLOBALS['web_root']; ?>/interface/patient_file/front_payment.php' +
        //        '?providerid=' + s +
        //        '&catid=' + c.options[c.selectedIndex].value +
        //        '&facility=' + f +
        //        '&startdate=' + formDate.value +
        //        '&evdur=' + document.forms[0].form_duration.value +
        //        '&eid=<?php echo 0 + $eid; ?>' + extra,
        //        '', 725, 200, '', title);
    }

</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body class="body_top main-calendar-add_edit_event">
<div class="container-responsive">
<form class="form-inline" method='post' name='theform' id='theform' action='add_edit_event.php?eid=<?php echo attr($eid) ?>' />
<!-- ViSolve : Requirement - Redirect to Create New Patient Page -->
<input   type='hidden' size='2' name='resname' value='empty' />
<?php
if ($_POST["resname"]=="noresult") {
    echo '
<script language="Javascript">
			// refresh and redirect the parent window
			if (!opener.closed && opener.refreshme) opener.refreshme();
			top.restoreSession();
			opener.document.location="../../new/new.php";
			// Close the window
			dlgclose();
</script>';
}

$classprov='current';
$classpati='';
?>
<!-- ViSolve : Requirement - Redirect to Create New Patient Page -->
<input   type="hidden" name="form_action" id="form_action" value="">
<input   type="hidden" name="recurr_affect" id="recurr_affect" value="">
<!-- used for recurring events -->
<input   type="hidden" name="selected_date" id="selected_date" value="<?php echo attr($date); ?>">
<input   type="hidden" name="event_start_date" id="event_start_date" value="<?php echo attr($eventstartdate); ?>">
<!-- Following added by epsdky 2016 (details in commit) -->
<input   type="hidden" name="old_repeats" id="old_repeats" value="<?php echo attr($repeats); ?>">
<input   type="hidden" name="rt2_flag2" id="rt2_flag2" value="<?php echo attr(isset($rspecs['rt2_pf_flag']) ? $rspecs['rt2_pf_flag'] : '0'); ?>">
<!-- End of addition by epsdky -->
<center>
<table class="table table-condensed" border='0' >
<?php
    $provider_class='';
    $group_class='';
    $normal='';
if ($_GET['prov']==true) {
    $provider_class="class='current'";
} elseif ($_GET['group']==true) {
    $group_class="class='current'";
} else {
    $normal="class='current'";
}
?>
<tr><th><ul class="tabNav">
<?php
    $eid=$_REQUEST["eid"];
    $startm=$_REQUEST["startampm"];
    $starth=$_REQUEST["starttimeh"];
    $uid=$_REQUEST["userid"];
    $starttm=$_REQUEST["starttimem"];
    $dt=$_REQUEST["date"];
    $cid=$_REQUEST["catid"];
	print $pc_apptstatus;
?>
         <li <?php echo $normal;?>>
         <a href='add_edit_event.php?startampm=<?php echo attr($startm);?>&starttimeh=<?php echo attr($starth);?>&userid=<?php echo attr($uid);?>&starttimem=<?php echo attr($starttm);?>&date=<?php echo attr($dt);?>&catid=<?php echo attr($cid);?>'>
            <?php echo xlt('Patient');?></a>
         </li>
         <li <?php echo $provider_class;?>>
         <a href='add_edit_event.php?prov=true&startampm=<?php echo attr($startm);?>&starttimeh=<?php echo attr($starth);?>&userid=<?php echo attr($uid);?>&starttimem=<?php echo attr($starttm);?>&date=<?php echo attr($dt);?>&catid=<?php echo attr($cid);?>'>
            <?php echo xlt('Provider');?></a>
         </li>
            <?php if ($have_group_global_enabled) :?>
         <li <?php echo $group_class ;?>>
            <a href='add_edit_event.php?group=true&startampm=<?php echo attr($startm);?>&starttimeh=<?php echo attr($starth);?>&userid=<?php echo attr($uid);?>&starttimem=<?php echo attr($starttm);?>&date=<?php echo attr($dt);?>&catid=<?php echo attr($cid);?>'>
            <?php echo xlt('Group');?></a>
         </li>
            <?php endif ?>
        </ul>
</th></tr>
<tr><td colspan='10'>
<table class="table table-condensed" border='0' width='100%' bgcolor='#DDDDDD'>
    <?php
    if ($_GET['prov']!=true && $_GET['group']!=true) {
        ?>
    <tr id="patient_details">
     <td nowrap>
         <b><?php echo xlt('Patient'); ?>:</b>
     </td>
     <td nowrap>
      <input class='input-sm'    type='text' size='10' name='form_patient' id="form_patient" style='width:100%;cursor:pointer;cursor:hand' placeholder='<?php echo xla('Click to select');?>' value='<?php echo is_null($patientname) ? '' : attr($patientname); ?>' onclick='sel_patient()' title='<?php echo xla('Click to select patient'); ?>' readonly />
      <input class='input-sm'    type='hidden' name='form_pid' value='<?php echo attr($patientid) ?>' />
     </td>
     <td colspan='3' nowrap style='font-size:8pt'>
      <span class="infobox">
        <?php
        foreach ($patienttitle as $value) {
            if ($value != "") {
                echo text(trim($value));
            }

            if (count($patienttitle) > 1) {
                echo "<br />";
            }
        }
        ?>
      </span>
     </td>
    </tr>
        <?php
    }
    ?> 
 <tr>
        <td width='1%' nowrap>
            <b><?php echo xlt('Category'); ?>:</b>
        </td>
        <td nowrap>
            <select class='input-sm' name='form_category' onchange='set_category()' style='width:100%'>
                <?php echo $catoptions ?>
            </select>
        </td>
        <td width='1%' nowrap>
            &nbsp;&nbsp;
            <input type='radio' name='form_allday' onclick='set_allday()' value='1' id='rballday1'<?php echo ($thisduration == 1440) ? " checked" : ""; ?>/>
        </td>
        <td colspan='2' nowrap id='tdallday1'>
            <?php echo xlt('All day event'); ?>
        </td>
    </tr>
    <tr>
        <td nowrap>
            <b><?php echo xlt('Date'); ?>:</b>
        </td>
        <td nowrap>
            <input   type='text' size='10' class='datepicker input-sm' name='form_date' id='form_date'
                    value='<?php echo attr(oeFormatShortDate($date)) ?>'
                    title='<?php echo xla('event date or starting date'); ?>'
                    onchange='dateChanged()' />
        </td>
        <td nowrap>
            &nbsp;&nbsp;
            <input type='radio' name='form_allday' onclick='set_allday()' value='0' id='rballday2'<?php echo ($thisduration != 1440) ? " checked " : ""; ?>/>
        </td>
        <td width='1%' nowrap id='tdallday2'>
            <?php echo xlt('Time'); ?>
        </td>
        <td width='1%' nowrap id='tdallday3'>
            <span>
                <input class='input-sm'    type='text' size='2' name='form_hour' value='<?php echo attr($starttimeh) ?>'
                 title='<?php echo xla('Event start time'); ?>' /> :
                <input class='input-sm'    type='text' size='2' name='form_minute' value='<?php echo attr($starttimem) ?>'
                 title='<?php echo xla('Event start time'); ?>' />&nbsp;
            </span>
            <select class='input-sm'    name='form_ampm' title='<?php echo xla("Note: 12:00 noon is PM, not AM"); ?>'>
                <option value='1'><?php echo xlt('AM'); ?></option>
                <option value='2'<?php echo ($startampm == '2') ? " selected" : ""; ?>><?php echo xlt('PM'); ?></option>
            </select>
        </td>
    </tr>


    <tr>
      <td nowrap><b><?php echo xlt('Facility'); ?>:</b></td>
      <td>
      <select class="input-sm" name="facility" id="facility" >
        <?php

      // ===========================
      // EVENTS TO FACILITIES
      //(CHEMED) added service_location WHERE clause
      // get the facilities
      /***************************************************************
      $qsql = sqlStatement("SELECT * FROM facility WHERE service_location != 0");
      ***************************************************************/
        $facils = getUserFacilities($_SESSION['authId']);
        $qsql = sqlStatement("SELECT id, name FROM facility WHERE service_location != 0");
      /**************************************************************/
        while ($facrow = sqlFetchArray($qsql)) {
            /*************************************************************
          $selected = ( $facrow['id'] == $e2f ) ? 'selected="selected"' : '' ;
          echo "<option value={$facrow['id']} $selected>{$facrow['name']}</option>";
            *************************************************************/
            if ($_SESSION['authorizedUser'] || in_array($facrow, $facils)) {
                $selected = ( $facrow['id'] == $e2f ) ? 'selected="selected"' : '' ;
                echo "<option value='" . attr($facrow['id']) . "' $selected>" . text($facrow['name']) . "</option>";
            } else {
                $selected = ( $facrow['id'] == $e2f ) ? 'selected="selected"' : '' ;
                echo "<option value='" . attr($facrow['id']) . "' $selected>" . text($facrow['name']) . "</option>";
            }

            /************************************************************/
        }

      // EOS E2F
      // ===========================
        ?>
        <?php
      //END (CHEMED) IF ?>
      </td>
      </select>

  <td nowrap style="display:none;">
   <b><?php echo xlt('Title'); ?>:</b>
  </td>
  <td nowrap style="display:none;">
   <input class="input-sm" type='text' size='10' name='form_title' value='<?php echo attr($row['pc_title']); ?>'
    style='width:100%'
    title='<?php echo xla('Event title'); ?>' />
  </td>
  <td nowrap>&nbsp;

  </td>

  <td nowrap id='tdallday4'><?php echo xlt('duration'); ?>
  </td>
  <td nowrap id='tdallday5'>
   <input class='input-sm'    type='text' size='4' name='form_duration' value='<?php echo attr($thisduration) ?>' title='<?php echo xla('Event duration in minutes'); ?>' />
    <?php echo xlt('minutes'); ?>
  </td>
 </tr>
    <tr style="display:none;">
        <td nowrap>
        <b><?php echo xlt('Billing Facility'); ?>:</b>
        </td>
        <td>
            <?php
            billing_facility('billing_facility', $row['pc_billing_location']);
            ?>
        </td>
    </tr>

<?php
if ($_GET['group']==true &&  $have_group_global_enabled) {
    ?>
 <tr id="group_details">
  <td nowrap>
   <b><?php echo xlt('Group'); ?>:</b>
  </td>
  <td nowrap>
   <input class='input-sm'    type='text' size='10' name='form_group' id="form_group" style='width:100%;cursor:pointer;cursor:hand' placeholder='<?php echo xla('Click to select');?>' value='<?php echo is_null($groupname) ? '' : attr($groupname); ?>' onclick='sel_group()' title='<?php echo xla('Click to select group'); ?>' readonly />
   <input class='input-sm'    type='hidden' name='form_gid' value='<?php echo attr($groupid) ?>' />
  </td>
  <td colspan='3' nowrap style='font-size:8pt'>
   <span class="infobox">
    <?php
    foreach ($patienttitle as $value) {
        if ($value != "") {
              echo trim($value);
        }

        if (count($patienttitle) > 1) {
              echo "<br />";
        }
    }
    ?>
   </span>
  </td>
 </tr>
<?php
}
?>
 <tr>
  <td nowrap>
   <b>
<?php if ($_GET['group']==true) {
        echo xlt('Coordinating Counselors');
} else {
    echo xlt('Doctor');
} ?>:</b>
  </td>
  <td nowrap>

<?php

// =======================================
// multi providers
// =======================================
if ($GLOBALS['select_multi_providers']) {
    //  there are two posible situations: edit and new record
    $providers_array = array();
    // this is executed only on edit ($eid)
    if ($eid) {
        if ($multiple_value) {
            // find all the providers around multiple key
            $qall = sqlStatement("SELECT pc_aid AS providers FROM openemr_postcalendar_events WHERE pc_multiple = ?", array($multiple_value));
            while ($r = sqlFetchArray($qall)) {
                $providers_array[] = $r['providers'];
            }
        } else {
            $qall = sqlStatement("SELECT pc_aid AS providers FROM openemr_postcalendar_events WHERE pc_eid = ?", array($eid));
            $providers_array = sqlFetchArray($qall);
        }
    }

    // build the selection tool
    echo "<select class='input-sm' name='form_provider[]' style='width:100%' multiple='multiple' size='5' >";

    while ($urow = sqlFetchArray($ures)) {
        echo "    <option value='" . attr($urow['id']) . "'";

        if ($userid) {
            if (in_array($urow['id'], $providers_array) || ($urow['id'] == $userid)) {
                echo " selected";
            }
        }

        echo ">" . text($urow['lname']);
        if ($urow['fname']) {
            echo ", " . text($urow['fname']);
        }

        echo "</option>\n";
    }

    echo '</select>';

// =======================================
// single provider
// =======================================
} else {
    if ($eid) {
        // get provider from existing event
        $qprov = sqlStatement("SELECT pc_aid FROM openemr_postcalendar_events WHERE pc_eid = ?", array($eid));
        $provider = sqlFetchArray($qprov);
        $defaultProvider = $provider['pc_aid'];
    } else {
      // this is a new event so smartly choose a default provider
    /*****************************************************************
      if ($userid) {
        // Provider already given to us as a GET parameter.
        $defaultProvider = $userid;
      }
        else {
        // default to the currently logged-in user
        $defaultProvider = $_SESSION['authUserID'];
        // or, if we have chosen a provider in the calendar, default to them
        // choose the first one if multiple have been selected
        if (count($_SESSION['pc_username']) >= 1) {
          // get the numeric ID of the first provider in the array
          $pc_username = $_SESSION['pc_username'];
          $firstProvider = sqlFetchArray(sqlStatement("select id from users where username='".$pc_username[0]."'"));
          $defaultProvider = $firstProvider['id'];
        }
      }
    }

    echo "<select   name='form_provider' style='width:100%' />";
    while ($urow = sqlFetchArray($ures)) {
        echo "    <option value='" . $urow['id'] . "'";
        if ($urow['id'] == $defaultProvider) echo " selected";
        echo ">" . $urow['lname'];
        if ($urow['fname']) echo ", " . $urow['fname'];
        echo "</option>\n";
    }
    echo "</select>";
    *****************************************************************/
      // default to the currently logged-in user
        $defaultProvider = $_SESSION['authUserID'];
      // or, if we have chosen a provider in the calendar, default to them
      // choose the first one if multiple have been selected
        if (count($_SESSION['pc_username']) >= 1) {
            // get the numeric ID of the first provider in the array
            $pc_username = $_SESSION['pc_username'];
            $firstProvider = sqlFetchArray(sqlStatement("select id from users where username=?", array($pc_username[0])));
            $defaultProvider = $firstProvider['id'];
        }

      // if we clicked on a provider's schedule to add the event, use THAT.
        if ($userid) {
            $defaultProvider = $userid;
        }
    }

    echo "<select class='input-sm' name='form_provider' style='width:100%' />";
    while ($urow = sqlFetchArray($ures)) {
        echo "    <option value='" . attr($urow['id']) . "'";
        if ($urow['id'] == $defaultProvider) {
            echo " selected";
        }

        echo ">" . text($urow['lname']);
        if ($urow['fname']) {
            echo ", " . text($urow['fname']);
        }

        echo "</option>\n";
    }

    echo "</select>";
    /****************************************************************/
}

?>

  </td>
  <td nowrap>
   &nbsp;&nbsp;
        <?php
      //Check if repeat is using the new 'days every week' mechanism.
        function isDaysEveryWeek($repeat)
        {
            if ($repeat == 3) {
                return true;
            } else {
                return false;
            }
        }

      //Check if using the regular repeat mechanism.
        function isRegularRepeat($repeat)
        {
            if ($repeat == 1 || $repeat == 2) {
                return true;
            } else {
                return false;
            }
        }


      /*
      If the appointment was set with the regular (old) repeat mechanism (using 'every', 'every 2', etc.), then will be
      checked when editing and will select the proper recurrence pattern. If using the new repeat mechanism, then only that box (and the proper set
      days) will be checked. That's why I had to add the functions 'isRegularRepeat' and 'isDaysEveryWeek', to check which
      repeating mechanism is being used, and load settings accordingly.
      */
        ?>
   <input type='checkbox' name='form_repeat' id="form_repeat" onclick='set_repeat(this)' value='1'<?php echo (isRegularRepeat($repeats)) ? " checked" : "";?>/>
   <input type='hidden' name='form_repeat_exdate' id='form_repeat_exdate' value='<?php echo attr($repeatexdate); ?>' /> <!-- dates excluded from the repeat -->
  </td>
  <td nowrap id='tdrepeat1'><?php echo xlt('Repeats'); ?>
  </td>
  <td nowrap>

   <select class='input-sm'  name='form_repeat_freq' title='<?php echo xla('Every, every other, every 3rd, etc.'); ?>'>
<?php
foreach (array(1 => xl('every'), 2 => xl('2nd'), 3 => xl('3rd'), 4 => xl('4th'), 5 => xl('5th'), 6 => xl('6th'))
 as $key => $value) {
    echo "    <option value='" . attr($key) . "'";
    if ($key == $repeatfreq && isRegularRepeat($repeats)) {
        echo " selected";
    }

    echo ">" . text($value) . "</option>\n";
}
?>
   </select>

   <select class='input-sm'  name='form_repeat_type'>
<?php
 // See common.api.php for these. Options 5 and 6 will be dynamically filled in
 // when the start date is set.
foreach (array(0 => xl('day') , 4 => xl('workday'), 1 => xl('week'), 2 => xl('month'), 3 => xl('year'),
   5 => '?', 6 => '?') as $key => $value) {
    echo "    <option value='" . attr($key) . "'";
    if ($key == $repeattype && isRegularRepeat($repeats)) {
        echo " selected";
    }

    echo ">" . text($value) . "</option>\n";
}
?>
   </select>

  </td>
 </tr>

    <style>
        #days_every_week_row input[type="checkbox"]{float:right;}
        #days_every_week_row div{display: inline-block; text-align: center; width: 12%;}
        #days_every_week_row div input{width: 100%;}
    </style>

<tr id="days_every_week_row">
    <td></td>
    <td></td>
    <td><input type='checkbox' id='days_every_week' name='days_every_week' onclick='set_days_every_week()' <?php echo (isDaysEveryWeek($repeats)) ? " checked" : ""; ?>/></td>
    <td id="days_label"><?php echo xlt('Days Of Week') . ": "; ?></td>
    <td id="days">
        <?php
        foreach (array(1 => xl('Su{{Sunday}}') , 2 => xl('Mo{{Monday}}'), 3 => xl('Tu{{Tuesday}}'), 4 => xl('We{{Wednesday}}'),
                     5 => xl('Th{{Thursday}}'), 6 => xl('Fr{{Friday}}'), 7 => xl('Sa{{Saturday}}')) as $key => $value) {
            echo " <div><input type='checkbox' name='day_". attr($key) ."'";
            //Checks appropriate days according to days in recurrence string.
            if (in_array($key, explode(',', $repeatfreq)) && isDaysEveryWeek($repeats)) {
                echo " checked";
            }

            echo " /><label>" . text($value) . "</label></div>\n";
        }
        ?>
    </td>

</tr>


 <tr>
  <td nowrap>
   <span id='title_apptstatus'><b><?php echo xlt('Status'); ?>:</b></span>
   <span id='title_prefcat' style='display:none'><b><?php echo xlt('Pref Cat'); ?>:</b></span>
  </td>
  <td nowrap>

<?php

if ($_GET['group']!=true) {
    //feysal Change Appointment Status None from demographics
	$row['pc_apptstatus']=$_GET['apptStatus'];
	//$row['pc_apptstatus']="-";
	//$autosave        = $_GET['autosave'];
	//print_r ($row['pc_apptstatus']);
	generate_form_field(array('data_type' => 1, 'field_id' => 'apptstatus', 'list_id' => 'apptstat', 'empty_title' => 'SKIP'), $row['pc_apptstatus']);
} else {
    generate_form_field(array('data_type' => 1, 'field_id' => 'apptstatus', 'list_id' => 'groupstat', 'empty_title' => 'SKIP'), $row['pc_apptstatus']);
}
?>

   <!--
    The following list will be invisible unless this is an In Office
    event, in which case form_apptstatus (above) is to be invisible.
   -->
   <select class='input-sm'  name='form_prefcat' style='width:100%;display:none' title='<?php echo xla('Preferred Event Category');?>'>
<?php echo $prefcat_options ?>
   </select>

  </td>
  <td nowrap>&nbsp;

  </td>
  <td nowrap id='tdrepeat2'><?php echo xlt('until date'); ?>
  </td>
  <td nowrap>
   <input   type='text' size='10' class='datepicker' name='form_enddate' id='form_enddate' value='<?php echo attr(oeFormatShortDate($recurrence_end_date)) ?>' title='<?php echo xla('last date of this event');?>' />
<?php
if ($repeatexdate != "") {
    $tmptitle = "The following dates are excluded from the repeating series";
    if ($multiple_value) {
        $tmptitle .= " for one or more providers:\n";
    } else {
        $tmptitle .= "\n";
    }

        $max = $GLOBALS['number_of_ex_appts_to_show'];

        $exdates = explode(",", $repeatexdate);
    if (!empty($exdates)) {
        $exdates=array_slice($exdates, 0, $max, true);
    }

    foreach ($exdates as $exdate) {
        $tmptitle .= date("d M Y", strtotime($exdate))."\n";
    }

        echo "<a href='#' title='" . attr($tmptitle) . "' alt='" . attr($tmptitle) . "'><img src='../../pic/warning.gif' title='" . attr($tmptitle) . "' alt='*!*' style='border:none;'/></a>";
}
?>
  </td>
 </tr>
    <?php
    if ($_GET['prov']!=true) {
        ?>
    <tr style="display:none;">
     <td nowrap>
         <b><?php echo xlt('Room Number'); ?>:</b>
     </td>
     <td colspan='4' nowrap>
        <?php
        echo generate_select_list('form_room', 'patient_flow_board_rooms', $pcroom, xl('Room Number'));
        ?>
     </td>
    </tr>
    <?php
    } ?>
 <tr>
  <td nowrap>
   <b><?php echo xlt('Comments'); ?>:</b>
  </td>
  <td colspan='4' nowrap>
   <input class='input-sm' type='text' size='40' name='form_comments' style='width:100%' value='<?php echo attr($hometext); ?>' title='<?php echo xla('Optional information about this event');?>' />
  </td>
 </tr>


<?php
 // DOB is important for the clinic, so if it's missing give them a chance
 // to enter it right here.  We must display or hide this row dynamically
 // in case the patient-select popup is used.
 $patient_dob = trim($prow['DOB']);
 $is_group = $groupname;
 $dobstyle = ($prow && (!$patient_dob || substr($patient_dob, 5) == '00-00') && !$is_group) ?
  '' : 'none';
?>
 <tr id='dob_row' style='display:<?php echo $dobstyle ?>'>
  <td colspan='4' nowrap>
   <b><font color='red'><?php echo xlt('DOB is missing, please enter if possible'); ?>:</font></b>
  </td>
  <td nowrap>
   <input   type='text' size='10' class='datepicker' name='form_dob' id='form_dob' title='<?php echo xla('yyyy-mm-dd date of birth');?>' />
  </td>
 </tr>

</table></td></tr>

<tr class='text'><td colspan='10' class="buttonbar">
<p>
<input    type='button' name='form_save' id='form_save' value='<?php echo xla('Save');?>' />
&nbsp;

<?php if (!($GLOBALS['select_multi_providers'])) { //multi providers appt is not supported by check slot avail window, so skip ?>
  <input     type='button' id='find_available' value='<?php echo xla('Find Available');?>' />
<?php } ?>

&nbsp;
<input type='button' name='form_delete' id='form_delete' value='<?php echo xla('Delete');?>'<?php echo (!$eid) ? " disabled" : "";?> />
&nbsp;
<input type='button' id='cancel' value='<?php echo xla('Cancel');?>' />
&nbsp;
<input type='button' name='form_duplicate' id='form_duplicate' value='<?php echo xla('Create Duplicate');?>' />
</p></td></tr></table>
<?php if ($informant) {
    echo "<p class='text'>" . xlt('Last update by') . " " .
    text($informant) . " " . xlt('on') . " " . text($row['pc_time']) . "</p>\n";
} ?>
</center>
</form>

<div id="recurr_popup" style="visibility: hidden; position: absolute; top: 50px; left: 50px; width: 400px; border: 3px outset yellow; background-color: yellow; padding: 5px;">
<?php echo xlt('Apply the changes to the Current event only, to this and all Future occurrences, or to All occurrences?') ?>
<br>
<?php if ($GLOBALS['submit_changes_for_all_appts_at_once']) {?>
    <input type="button" name="all_events" id="all_events" value="  <?php echo xla('All'); ?>  ">
<?php } ?>
<input type="button" name="recurr_cancel" id="recurr_cancel" value="<?php echo xla('Cancel'); ?>">
<input type="button" name="future_events" id="future_events" value="<?php echo xla('Future'); ?>">
<input type="button" name="current_event" id="current_event" value="<?php echo xla('Current'); ?>">
</div>
</div>
</body>

<script language='JavaScript'>
<?php if ($eid) { ?>
 set_display();
<?php } else { ?>
 set_category();
<?php } ?>
 set_allday();
 set_repeat();
 set_days_every_week();

</script>

<script language="javascript">
// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $("#form_save").click(function(e) { validateform(e,"save"); });
    $("#form_duplicate").click(function(e) { validateform(e,"duplicate"); });
    $("#find_available").click(function() { find_available(''); });
    $("#form_delete").click(function() { deleteEvent(); });
 //feysal Cancel button work in both tab and popup
    $("#cancel").click(function() { document.location.href = 	'../../patient_file/summary/demographics.php '  ; dlgclose(); });

    // buttons affecting the modification of a repeating event
    $("#all_events").click(function() { $("#recurr_affect").val("all"); EnableForm(); SubmitForm(); });
    $("#future_events").click(function() { $("#recurr_affect").val("future"); EnableForm(); SubmitForm(); });
    $("#current_event").click(function() { $("#recurr_affect").val("current"); EnableForm(); SubmitForm(); });
    $("#recurr_cancel").click(function() { $("#recurr_affect").val(""); EnableForm(); HideRecurrPopup(); });

    // Initialize repeat options.
    dateChanged();

    $('.datepicker').datetimepicker({
        <?php $datetimepicker_timepicker = false; ?>
        <?php $datetimepicker_showseconds = false; ?>
        <?php $datetimepicker_formatInput = true; ?>
        <?php require($GLOBALS['srcdir'] . '/js/xl/jquery-datetimepicker-2-5-4.js.php'); ?>
        <?php // can add any additional javascript settings to datetimepicker here; need to prepend first setting with a comma ?>
    });

});

function are_days_checked(){
    var days = document.getElementById("days").getElementsByTagName('input');
    var counter = 0;
    for(var i=0; i < days.length; i++){
       if(days[i].checked){
           counter++;
       }
    }
    return counter;
}

/*
* validation on the form with new client side validation (using validate.js).
* this enable to add new rules for this form in the pageValidation list.
* */
<?php 
// feysal click save button Method 2
if ($autosave) { ?>
$(document).ready(function(){
    $("#form_save").trigger('click'); 
});
<?php } ?>
var collectvalidation = <?php echo($collectthis); ?>;
function validateform(event,valu){

    $('#form_save').attr('disabled', true);

    //Make sure if days_every_week is checked that at least one weekday is checked.
    if($('#days_every_week').is(':checked') && !are_days_checked()){
        alert('<?php echo xls("Must choose at least one day!"); ?>');
        $('#form_save').attr('disabled', false);
        return false;
    }

    <?php if (!$GLOBALS['allow_early_check_in']) { ?>
        //Prevent from user to change status to Arrive before the time
        //Dependent in globals setting - allow_early_check_in
        if($('#form_apptstatus').val() == '@' && new Date(DateToYYYYMMDD_js($('#form_date').val())).getTime() > new Date().getTime()){
            alert('<?php echo xls("You can not change status to 'Arrive' before the appointment's time") .'.'; ?>');
            $('#form_save').attr('disabled', false);
            return false;
        }
    <?php } ?>

    //add rule if choose repeating event
    if ($('#form_repeat').is(':checked') || $('#days_every_week').is(':checked')){

        var format = '<?php echo DateFormatRead('validateJS'); ?>';

        collectvalidation.form_enddate = {
            datetime: {
                dateOnly: true,
                earliest: $('#form_date').val(),
                format: format,
                message: "An end date later than the start date is required for repeated events!"
            },
            presence: true
        }
    } else {
        if(typeof (collectvalidation) != 'undefined'){
            delete collectvalidation.form_enddate;
        }
    }


    <?php
    if ($GLOBALS['select_multi_providers']) {
    ?>
    //If multiple providers is enabled, create provider validation (Note: if no provider is chosen it causes bugs when deleting recurrent events).
    if(typeof (collectvalidation) == 'undefined'){
        collectvalidation = {form_provider:{presence: true}};
    }
    else{
        collectvalidation.form_provider = {presence: true};
    }
    <?php
    }
    ?>


    var submit = submitme(1, event, 'theform', collectvalidation);
    if(!submit)return $('#form_save').attr('disabled', false);

    $('#form_action').val(valu);

    <?php if ($repeats) : ?>
    // existing repeating events need additional prompt
    if ($("#recurr_affect").val() == "") {
        DisableForm();
        // show the current/future/all DIV for the user to choose one
        $("#recurr_popup").css("visibility", "visible");
        $('#form_save').attr('disabled', false);
        return false;
    }
    <?php endif; ?>

    SubmitForm();

}

// disable all the form elements outside the recurr_popup
function DisableForm() {
    $("#theform").children().attr("disabled", "true");
}
function EnableForm() {
    $("#theform").children().removeAttr("disabled");
}
// hide the recurring popup DIV
function HideRecurrPopup() {
    $("#recurr_popup").css("visibility", "hidden");
}

function deleteEvent() {
    if (confirm("<?php echo addslashes(xl('Deleting this event cannot be undone. It cannot be recovered once it is gone. Are you sure you wish to delete this event?')); ?>")) {
        $('#form_action').val("delete");

        <?php if ($repeats) : ?>
        // existing repeating events need additional prompt
        if ($("#recurr_affect").val() == "") {
            DisableForm();
            // show the current/future/all DIV for the user to choose one
            $("#recurr_popup").css("visibility", "visible");
            return false;
        }
        <?php endif; ?>

        return SubmitForm();
    }
    return false;
}

function SubmitForm() {
    var f = document.forms[0];
    <?php if (!($GLOBALS['select_multi_providers']) && !$_GET['prov']) { // multi providers appt is not supported by check slot avail window, so skip. && is not provider tab. ?>
    if (f.form_action.value != 'delete') {
        // Check slot availability.
        var mins = parseInt(f.form_hour.value) * 60 + parseInt(f.form_minute.value);
        if (f.form_ampm.value == '2' && mins < 720) mins += 720;
        find_available('&cktime=' + mins);
    }
    else {
        top.restoreSession();
        f.submit();
    }
    <?php } else { ?>
    <?php
    /*Support Multi-Provider Events in features*/
    $sdate=$date;
    $edate=new DateTime($date);
    $edate->modify('tomorrow');
    $edate=$edate->format('Y-m-d');
    $is_holiday=false;
    $holidays_controller = new Holidays_Controller();
    $holidays = $holidays_controller->get_holidays_by_date_range($sdate, $edate);
    if (in_array($sdate, $holidays)) {
        $is_holiday=true;
    }?>
    if (f.form_action.value != 'delete') {
        <?php if ($is_holiday) {?>
        if (!confirm('<?php echo xls('On this date there is a holiday, use it anyway?'); ?>')) {
            top.restoreSession();
        }
        <?php }?>
    }
    top.restoreSession();
    f.submit();
    <?php } ?>

    return true;
}

</script>

</html>
