<?php
/*************************************************************************
 * NOTICE OF COPYRI|GHT                                                  *
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
// Get head and navbar.
include('includes/head.php');
echo '<div class="page-wrapper container-fluid page-repbydev">';
include('includes/navbar.php');

// Call initial database queries.
require_once("dbcontroller.php");
$table = 'effort';
$table1 = 'weeks';
$table2 = 'developers';
$table3 = 'task';
$table4 = 'velocity';
$db_handle = new DBController();
$sqldevlist = "SELECT * from " . $table2; // Get full list for drop down for filter.
$devlist = $db_handle->runQuery($sqldevlist);
$sqltasklist = "SELECT * from " . $table3; // Get full list for drop down for filter.
$tasklist = $db_handle->runQuery($sqltasklist);
?>

<header class="pageheader jumbotron text-center">
    <h1 class="display-5">Reporting - By Developer</h1>
    <p>To edit any of these values, please go to the appropriate editable page</p>
</header>

    <div class="filtercontrols container">
        <form action="" method="post" class="row">
            <div class="filterby filterbydate col">
                <label><strong>Start Date: </strong></label><br>
                <input type="date" name="startdate" min="2018-01-01">
            </div>
            <div class="filterby filterbydate col">
                <label><strong>End Date: </strong></label><br>
                <input type="date" name="enddate" max="2024-12-31">
            </div>

            <div class="filterby filterbydev col">
                <label><strong>Developer: </strong></label><br>
                    <select name="dev">
                    <option value="none">None</option>
                    <?php foreach ($devlist as $d) { ?>
                        <option value="<?php echo $d['id'];?>">
                            <?php echo $d['firstname'].' '.$d['lastname'];?>
                        </option>
                    <?php } ?>
                    </select>
            </div>

            <div class="submitbutton">
                <input type="submit" value="Go">
            </div>
        </form>
        <br>
    </div>

    <?php

    if (isset($_POST['startdate']) && $_POST['startdate'] !== '') {
        $startdate = $_POST['startdate'];
    } else {
        $startdate = '2018-01-01';
    }
    if (isset($_POST['enddate']) && $_POST['enddate'] !== '') {
        $enddate = $_POST['enddate'];
    } else {
        $enddate = '2048-01-01';
    }
    if (isset($_POST['dev']) && $_POST['dev'] !== 'none') {
        $filterdev = " WHERE developerid = ". $_POST['dev']; // Apply filter.
        $devid = $_POST['dev'];
    } else {
        $filterdev = " WHERE developerid = 1"; // Or select id=1 - must have some content for table.
        $devid = 1;
    }
    // Get weeks.
    $sql1 = "SELECT * from " . $table1 . " WHERE weekcommencing >= '" . $startdate . "' AND weekcommencing <= '" . $enddate . "'";
    echo $sql1.'<br>';
    $weeks = $db_handle->runQuery($sql1);
    // Get Planned velocities
    $velwkstart = $velwkend = '';
    foreach ($weeks as $k => $v) {
        if ($velwkstart == '') {
            $velwkstart = $weeks[$k]['id'];
        }
        $velwkend = $weeks[$k]['id'];
    }
    $sqlvel = "SELECT * from " .  $table4 . " WHERE weekid >= '" . $velwkstart . "' AND weekid <= '" . $velwkend . "' AND developerid = '" . $devid ."'";
    echo $sqlvel.'<br>';
    $velocity = $db_handle->runQuery($sqlvel);
    // Get unique row identifiers for task+dev.
    $sql2 = "SELECT CONCAT_WS(';',taskid,developerid) FROM " . $table . " WHERE developerid = " . $devid;
    echo $sql2;
    $effort = $efforttdlist = $db_handle->runQuery($sql2);
    $efforttd = array();
    foreach($efforttdlist as $el) {
        $efforttdl[] = $el["CONCAT_WS(';',taskid,developerid)"];
    }
    $efforttd = array_unique($efforttdl);
    ?>

    <div class="main-content">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="table-header" style="height:85px" colspan=2>Week Commencing</th>
                        <?php
                        foreach ($weeks as $k=>$v) {
                            $event = str_replace('<br>','',$weeks[$k]['events']);
                            if (strlen($event) > 0) {
                        ?>
                                <th class="table-header weekheader" >
                                    <div class="rotated" style="height:12px;width:20px;white-space:nowrap;transform: rotate(-45deg);padding:0 15px 0 0;">
                                        <?php echo $weeks[$k]['weekcommencing']; ?>
                                        <a href="#" data-toggle="tooltip" title=" <?php echo $event; ?>">*</a>
                                    </div>
                                </th>
                            <?php
                            } else {
                            ?>
                                <th class="table-header weekheader" >
                                    <div class="rotated" style="height:12px;width:20px;white-space:nowrap;transform: rotate(-45deg);padding:0 15px 0 0;">
                                        <?php echo $weeks[$k]['weekcommencing']; ?>
                                    </div>
                                </th>
                            <?php
                            }
                        }
                        ?>
                </tr>
                    <tr>
                        <th class="table-header" colspan=2>Sprint</th>
                        <?php
                        foreach ($weeks as $k=>$v) {
                        ?>
                            <th class="table-header weeksheader"><?php echo $weeks[$k]['sprint']; ?></th>
                        <?php
                        }
                        ?>
                    </tr>
            </thead>
            <thead class="thead-light">
                <?php
                // Developer.
                $sql = "SELECT * FROM developers WHERE id = ".$devid;
                if ($db_handle->numrows($sql) < 1) {
                    $devname = '';
                } else {
                    $devdetails = $db_handle->runQuery($sql);
                    foreach ($devdetails as $d) {
                        $devname = $d['firstname'].' '.$d['lastname'];
                    }
                }
                ?>
                <th class="table-header" colspan=2><?php echo $devname; ?></th>
                <?php
                foreach($weeks as $kw=>$vw) {
                    $sql = "SELECT * from " . $table4 . " WHERE developerid= ".$devid." AND weekid= ".$weeks[$kw]['id'];
                    $vel = $db_handle->runQuery($sql);
                    $velid=$vel[0]['id'];
                    $pv=$vel[0]['plannedvelocity'];
                    ?>
                    <th class="table-header"><?php echo $pv; ?></th>
                <?php
                }
                ?>

                </th>
            </thead>
            <tbody>
                <tr class="table-row">
                    <td contenteditable="false" colspan=2>
                        <strong>Total Planned Effort</strong>
                    </td>
                    <?php
                    foreach($weeks as $kw=>$vw) {
                        $sql = "SELECT * from " . $table . " WHERE weekid = ".$weeks[$kw]['id'] . " AND developerid = ".$devid;
                        $eftotallist = $db_handle->runQuery($sql);
                        $eftotal = 0;
                        foreach ($eftotallist as $k=>$v){
                            $eftotal=$eftotal+$eftotallist[$k]['effort'];
                        }
                        $wkvel = $db_handle->runQuery("SELECT plannedvelocity FROM velocity WHERE developerid=".$devid." AND weekid=".$weeks[$kw]['id']);
                        foreach ($wkvel as $w) {
                            $wkv = $w['plannedvelocity'];
                        }
                        if ($eftotal > $wkv) {
                            $class="alert alert-danger";
                        } else {
                            $class='';
                        }
                        ?>

                        <td contenteditable="false" class="<?php echo $class; ?>"><?php echo $eftotal; ?></td>
                    <?php
                    }
                    ?>
                </tr>

                <?php

                foreach($efforttd as $eff) {
                    $tasks = explode(';',$eff);
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
                    $taskname = $epictitle.': '.$systemname.': '.$tasktitle;
                    ?>
                    <tr class="table-row">
                        <td contenteditable="false" colspan=2><?php echo $taskname; ?></td>
                        <?php
                        foreach($weeks as $kw=>$vw) {
                            $sql = "SELECT * from " . $table . " WHERE taskid= ".$taskid." AND weekid = ".$weeks[$kw]['id'] . " AND developerid = ".$devid;
                            $efftlist = $db_handle->runQuery($sql);
                            $efftid=$efftlist[0]['id'];
                            $eft=$efftlist[0]['effort'];
                            $class='';
                            if ($eft > 0) {
                                $class = 'text-success font-weight-bold';
                            }
                            ?>
                            <td contenteditable="false" class="<?php echo $class; ?>"><?php echo $eft; ?></td>
                        <?php
                        }
                        ?>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan=250> <!-- Spacer row -->
                        <br><br>
                    </td>
                </tr>
            </tbody>
        </table>
        <p><br><br></p>
    </div>
<?php
include('includes/foot.php');
?>
