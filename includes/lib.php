<?php
/*************************************************************************
 * NOTICE OF COPYRIGHT                                                  *
 * Agile Planner - Copyright (C) 2017 onwards: R Oelmann                 *
 *                 oelmann.richard@gmail.com                             *
 *                                                                       *
 * This program is free software; you can redistribute it and/or modify  *
 * it under the terms of the GNU General Public License as published by  *
 * the Free Software Foundation; either version 3 of the License, or     *
 * (at your option) any later version.                                   *
 *                                                                       *
 * This program is distributed in the hope that it will be useful,       *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 * GNU General Public License for more details:                          *
 *                                                                       *
 *          http://www.gnu.org/copyleft/gpl.html                         *
 *                                                                       *
 *************************************************************************/

function testing() {
    $print = "This lib finction gets called";
    return $print;
}

function filtercontrols($db_handle, $bydate, $bydev, $byprog, $byepic, $byus, $byadv, $orderby) {

    $sqldevlist = "SELECT * from " . $GLOBALS['tbl_dev'];
    $f_devlist = $db_handle->runQuery($sqldevlist);
    $sqlepiclist = "SELECT * from " . $GLOBALS['tbl_epic'];
    $f_epiclist = $db_handle->runQuery($sqlepiclist);

    $sqluserstorylist = "SELECT * from " . $GLOBALS['tbl_bklg'] . " WHERE type='UserStory'";
    $f_userstorieslist = $db_handle->runQuery($sqluserstorylist);


    $startform  = '<form action="" method="post">';
    $startform .= '<div class="row">';
    $startnewrow  = '</div>';
    $startnewrow .= '<div class="row">';
    $endform  = '</div>';
    $endform .= '<div class="row">';
    $endform .= '<div class="submitbutton col-12">';
    $endform .= '<input type="submit" value="Apply Filters" style="width:100%;">';
    $endform .= '</div>';
    $endform .= '</div>';
    $endform .= '</form>';

    // Filter by date.
    $filtercontrolsdate  = '<div class="filterbydate col-3">';
    $filtercontrolsdate .= '<label><strong>Start Date:&nbsp;</strong></label>';
    $filtercontrolsdate .= '<input type="date" name="startdate" min="2018-01-01">';
    $filtercontrolsdate .= '</div>';
    $filtercontrolsdate .= '<div class="filterbydate col-3">';
    $filtercontrolsdate .= '<label><strong>End Date:&nbsp;</strong></label>';
    $filtercontrolsdate .= '<input type="date" name="enddate" max="2024-12-31">';
    $filtercontrolsdate .= '</div>';
    $filtercontrolsdate .= '<div class="filterbydate col-3">';
    $filtercontrolsdate .= '<label><strong>Sprint:&nbsp;</strong></label>';
    $filtercontrolsdate .= '<input type="number" name="sprintnumber">';
    $filtercontrolsdate .= '</div>';
    $filtercontrolsdate .= '<div class="filterbydate col-3">';
    $filtercontrolsdate .= '<p>Note: filtering by sprint number will override filtering by dates.</p>';
    $filtercontrolsdate .= '</div>';

    // Filter by Developer.
    $filtercontrolsdev  = '<div class="filterbydev col-6">';
    $filtercontrolsdev .= '<label><strong>Developer:&nbsp;</strong></label>';
    $filtercontrolsdev .= '<select name="dev">';
    $filtercontrolsdev .= '<option value="all">No Filter</option>';
    foreach ($f_devlist as $d) {
        $devid = $d['id'];
        $devfirstname = $d['firstname'];
        $devlastname = $d['lastname'];
        $filtercontrolsdev .= '<option value="'.$devid.'">';
        $filtercontrolsdev .= $devfirstname.' '.$devlastname;
        $filtercontrolsdev .= '</option>';
    }
    $filtercontrolsdev .= '</select>';
    $filtercontrolsdev .= '</div>';

    // Filter by completion stage (progress).
    $filtercontrolsprogress  = '<div class="filterbyprogress col-6">';
    $filtercontrolsprogress .= '<label><strong>Progress:&nbsp;</strong></label>';
    $filtercontrolsprogress .='<select name="completion">';
    $filtercontrolsprogress .='<option value="inprogress">All active</option>';
    $filtercontrolsprogress .='<option value="all">All</option>';
    $filtercontrolsprogress .='<option value="ToDo">To Do</option>';
    $filtercontrolsprogress .='<option value="InDev">In Development</option>';
    $filtercontrolsprogress .='<option value="InDevTest">Inhouse Testing</option>';
    $filtercontrolsprogress .='<option value="InUserTest">Released - User Testing</option>';
    $filtercontrolsprogress .='<option value="Released">Released - Live</option>';
    $filtercontrolsprogress .='<option value="completed">All completed</option>';
    $filtercontrolsprogress .='</select>';
    $filtercontrolsprogress .='</div>';

    // Filter by Epic.
    $filtercontrolsepic  = '<div class="filterbyepic col-6">';
    $filtercontrolsepic .= '<label><strong>Epic:&nbsp;</strong></label>';
    $filtercontrolsepic .= '<select onload="fetch_select(all);" onchange="fetch_select(this.value);" name="epic">';
    $filtercontrolsepic .= '<option value="all">No filter</option>';
    foreach($f_epiclist as $ep) {
        $epid = $ep['id'];
        $eptitle = $ep['title'];
        $filtercontrolsepic .= '<option value="'.$epid.'">';
        $filtercontrolsepic .= $eptitle;
        $filtercontrolsepic .= '</option>';
    }
    $filtercontrolsepic .= '</select>';
    $filtercontrolsepic .= '</div>';

    // Filter by User Story.
    $filtercontrolsus  = '<div class="filterbyparent col-6">';
    $filtercontrolsus .= '<label><strong>Parent US:&nbsp;</strong></label>';
    $filtercontrolsus .= '<select id="taskselect" name="parent">';
    $filtercontrolsus .= '<option value="all">All User Stories</option>';
    foreach ($f_userstorieslist as $b) {
        $bid = $b['id'];
        $btitle = $b['title'];
        $buserstory = $bid.': '.$btitle;
        $filtercontrolsus .= '<option value="'.$bid.'">'.$buserstory.'</option>';
    }
    $filtercontrolsus .= '</select>';
    $filtercontrolsus .= '</div>';

    // Advanced filter.
    $filtercontrolsadv  = '<div class="filteradv col-9">';
    $filtercontrolsadv .= '<label><strong>Advanced: </strong></label>';
    $filtercontrolsadv .= '<input type="text" name="advancedfilter">';
    $filtercontrolsadv .= '</div>';

    // Order by.
    $filterorder  = '<div class="filterorder col-3">';
    $filterorder .= '<label><strong>Order by: </strong></label>';
    $filterorder .= '<select name="orderby">';
    foreach ($orderby as $ob) {
        $filterorder .= '<option value="'.$ob.'">'.$ob.'</option>';
    }
    $filterorder .= '</select>';
    $filterorder .= '</div>';

    // Put filter controls together as required.
    // $bydate, $bydev, $byprog, $byepic, $byus, $byadv
    $filtercontrols  = '<div id="filtercontrols" class="filterby container collapse alert alert-primary">';
    $filtercontrols .= $startform;
    if ($bydate) {
        $filtercontrols .= $filtercontrolsdate;
        $filtercontrols .= $startnewrow;
    }
    if ($bydev) {$filtercontrols .= $filtercontrolsdev;}
    if ($byprog) {$filtercontrols .= $filtercontrolsprogress;}
    if ($bydev || $byprog) {$filtercontrols .= $startnewrow;}
    if ($byepic) {$filtercontrols .= $filtercontrolsepic;}
    if ($byus) {$filtercontrols .= $filtercontrolsus;}
    if ($byepic || $byus) {$filtercontrols .= $startnewrow;}
    if ($byadv) {$filtercontrols .= $filtercontrolsadv;}
    if (count($orderby)>0) {$filtercontrols .= $filterorder;}
    if ($byadv || count($orderby)>0) {
        $filtercontrols .= $startnewrow;
    }
    $filtercontrols .= $endform;
    $filtercontrols .= '</div>';

    return $filtercontrols;
}
function filterprocess_startdate ($db_handle, $sdt, $spr) {
    if (isset($sdt) && $sdt !== '') {
        $startdate = $sdt;
    } else {
        $startdate = date('Y-m-d', time() - 1814400); // 3 weeks before current date
    }
    // Override if sprint is set.
    if (isset($spr) && $spr !='') {
        $wksql = "SELECT weekcommencing FROM ".$GLOBALS['tbl_wks']." WHERE sprint = ".$spr;
        $wkslist = $db_handle->runQuery($wksql);
        $startdate = '';
        foreach ($wkslist as $k=>$v) {
            if ($startdate == '') {
                $startdate = $wkslist[$k]['weekcommencing'];
            }
        }
    }
    return $startdate;
}
function filterprocess_enddate ($db_handle, $edt, $spr) {
    if (isset($edt) && $edt !== '') {
        $enddate = $edt;
    } else {
        $enddate = date('Y-m-d', time() + 3628800); // 6 weeks after current date
    }
    // Override if sprint is set.
    if (isset($spr) && $spr !='') {
        $wksql = "SELECT weekcommencing FROM ".$GLOBALS['tbl_wks']." WHERE sprint = ".$spr;
        $wkslist = $db_handle->runQuery($wksql);
        foreach ($wkslist as $k=>$v) {
            $enddate = $wkslist[$k]['weekcommencing'];
        }
    }
    return $enddate;
}
function filterprocess_tasks ($db_handle, $epc, $ust, $prog) {
    if (isset($epc) && $epc !== '' && $epc !=='all') {
        $filterepic = " AND epicid = ".$epc;
    } else {
        $filterepic = "";
    }
    if(isset($ust) && $ust !=='' && $ust!=='all') {
        $filterparent = " AND parent = ".$ust;
        $filterepic = ""; // Override epic filter to avoid clashes.
    } else {
        $filterparent = ""; // ie No parent filter
    }
    if(isset($prog) && $prog !=='') {
        if ($prog === 'all') {
            $filterprog = "";
        } elseif ($prog === 'inprogress') {
            $filterprog = " AND completion IN ('ToDo','InDev','InDevTest','')";
        } elseif ($prog === 'completed') {
            $filterprog = " AND completion IN ('InUserTest','Released')";
        } else {
            $filterprog = " AND completion = '".$prog;
        }
    } else {
        $filterprog = "";
    }

    $filtertasks = $filterepic.$filterparent.$filterprog;
    return $filtertasks;
}
function filterprocess_devs ($db_handle, $dev) {
        if (isset($dev) && $dev !== '' && $dev !=='all') {
        $filterdev = " AND developerid = ".$dev;
    } else {
        $filterdev = "";
    }
    return $filterdev;
}
function weekslist ($db_handle, $tbl_wks, $filterweeks) {
    // Get weeks.
    $sqlweeks = "SELECT * FROM " . $tbl_wks . " WHERE id>0 ".$filterweeks;
    $weeks = $db_handle->runQuery($sqlweeks);
    return $weeks;
}
function devslist ($db_handle, $tbl_dev, $devidsel) {
    // Get developers.
    $sqldevs = "SELECT * FROM " . $tbl_dev . " WHERE " . $devidsel;
    $devs = $db_handle->runQuery($sqldevs);
    return $devs;
}
function taskslist ($db_handle, $tbl_eff, $tbl_bklg, $filtertasks) {
    // Get tasks to list.
    $sqltasks = "SELECT t.epicid, CONCAT_WS(':',e.taskid,e.developerid) FROM ".$tbl_eff." e
        JOIN ".$tbl_bklg." t ON e.taskid = t.id
        WHERE e.taskid IN (SELECT ts.id FROM ".$tbl_bklg." ts WHERE id > 0".$filtertasks.")";

    $efforttdlist = $db_handle->runQuery($sqltasks);
    $efforttd = array();
    foreach($efforttdlist as $el) {
        $efforttdl[] = $el["CONCAT_WS(':',e.taskid,e.developerid)"];
    }
    $efforttd = array_unique($efforttdl);
    $tasknamelist = array();

    foreach($efforttd as $eff) {
        $tasks = explode(':',$eff);
        $taskid = $tasks[0];
        $devid = $tasks[1];
        // Get task title, epic and system.
        // Task name.
        $sqltask = "SELECT epicid, title FROM task WHERE id = ".$taskid;
        $tasksel = $db_handle->runQuery($sqltask);
        foreach ($tasksel as $ta) {
            $epicid = $ta['epicid'];
            $tasktitle = $ta['title'];
        }
        // Epic name.
        $sqlepic = "SELECT systemid,title FROM epic WHERE id = ". $epicid;
        $epicsel = $db_handle->runQuery($sqlepic);
        foreach ($epicsel as $e) {
            $sysid=$e['systemid'];
            $epictitle=$e['title'];
        }
        // System.
        $sqlsystem = "SELECT system FROM system WHERE id = ".$sysid;
        $systemsel = $db_handle->runQuery($sqlsystem);
        foreach ($systemsel as $s) {
            $systemname = $s['system'];
        }
        // Developer.
        $sqldev = "SELECT * FROM developers WHERE id = ".$devid;
        $devsel = $db_handle->runQuery($sqldev);
        foreach ($devsel as $dv) {
            $devname = $dv['firstname'].' '.$dv['lastname'];
        }
        $tasknamelist[] = $epictitle.': '.$systemname.': '.$taskid.':'.$tasktitle.': '.$devid.':'.$devname;
    }
    usort($tasknamelist, 'strnatcasecmp');

    return $tasknamelist;
}