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
echo '<div class="page-wrapper container-fluid page-repbyepic">';
include('includes/navbar.php');

// Call initial database queries.
require_once("dbcontroller.php");
$table = 'effort';
$table1 = 'weeks';
$table2 = 'developers';
$table3 = 'task';
$table4 = 'velocity';
$table5 = 'epic';
$db_handle = new DBController();
$sqldevlist = "SELECT * from " . $table2; // Get full list for drop down for filter.
$devlist = $db_handle->runQuery($sqldevlist);
$sqlepiclist = "SELECT * from " . $table5; // Get full list for drop down for filter.
$epiclist = $db_handle->runQuery($sqlepiclist);
// User stories - separate from main backlog list for drop down list purposes.
$userstorylist = "SELECT * from " . $table3 . " WHERE type='UserStory'";
$userstories = $db_handle->runQuery($userstorylist);

?>

<header class="pageheader jumbotron text-center">
    <h1 class="display-5">Reporting - By Various filters</h1>
    <p>To edit any of these values, please go to the appropriate editable page</p>
</header>

    <div class="filtercontrols container">
        <form action="" method="post" >
          <div class="row">
            <div class="filterby filterbydate col-2">
                <label><strong>Start Date: </strong></label><br>
                <input type="date" name="startdate" min="2018-01-01">
            </div>
            <div class="filterby filterbydate col-2">
                <label><strong>End Date: </strong></label><br>
                <input type="date" name="enddate" max="2024-12-31">
            </div>
            <div class="filterby filterbydev col-4">
                <label><strong>Developer: </strong></label><br>
                    <select name="dev">
                    <option value="all">No Filter</option>
                    <?php foreach ($devlist as $d) { ?>
                        <option value="<?php echo $d['id'];?>">
                            <?php echo $d['firstname'].' '.$d['lastname'];?>
                        </option>
                    <?php } ?>
                    </select>
            </div>
                <!-- Progress drop down -->
                <div class="filterbyprogress col-4">
                    <label><strong>Progress:</strong></label><br>
                    <select name="completion">
                        <option value="all">All</option>
                        <option value="ToDo">To Do</option>
                        <option value="InDev">In Development</option>
                        <option value="InDevTest">Inhouse Testing</option>
                        <option value="inprogress">All active</option>
                        <option value="InUserTest">Released - User Testing</option>
                        <option value="Released">Released - Live</option>
                        <option value="completed">All completed</option>
                    </select>
                </div>

          </div>

          <div class="row">
            <div class="filterby filterbyepic col-4">
                <label><strong>Epic: </strong></label><br>
                <select name="epic">
                    <option value="all">No filter</option>
                    <?php
                    foreach($epiclist as $ep) {
                    ?>
                        <option value="<?php echo $ep['id']; ?>">
                            <?php echo $ep['title']; ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-1">
                <h6>OR</h6>
            </div>
            <div class="filterbyparent col-4">
                <label><strong>Parent US:</strong></label><br>
                <select name="parent">
                    <option value='all'>No Parent UserStory</option> <!-- No user story -->
                    <!-- Drop down of existing user stories -->
                    <?php foreach ($userstories as $b) { ?>
                        <option value="<?php echo $b['id'];?>"> <?php echo $b['id'].':'.$b['title'];?> </option>
                    <?php } ?>
                </select>
            </div>


            <div class="submitbutton">
                <input type="submit" value="Go">
            </div>
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

    if (isset($_POST['epic']) && $_POST['epic'] !== 'all') {
        $filterepic = " AND epicid = ". $_POST['epic']; // Apply filter.
    } else {
        $filterepic = " AND epicid >=0"; // Or select id>1 - must have some content for table.
    }
    if (isset($_POST['parent']) && $_POST['parent'] !== 'all') {
        $filterparent = " AND parent = ". $_POST['parent']; // Apply filter.
        $filterepic = " AND epicid >=0"; // Override Epic filter to prevent clashes.
    } else {
        $filterparent = " AND parent >=0"; // Or select id>0 - must have some content for table.
    }

    if (isset($_POST['dev']) && $_POST['dev'] !== 'all') {
        $filterdev = " AND developerid = ". $_POST['dev']; // Apply filter.
        $devid = $_POST['dev'];
    } else {
        $filterdev = " AND developerid > 0"; // Or select all.
    }

    // Get weeks.
    $sql1 = "SELECT * from " . $table1 . " WHERE weekcommencing >= '" . $startdate . "' AND weekcommencing <= '" . $enddate . "'";
    echo $sql1.'<br>';
    $weeks = $db_handle->runQuery($sql1);
    // Get tasks to list.
    $sqltasks = "SELECT id FROM ".$table3." WHERE id>0 ".$filterepic.$filterparent;
    echo $sqltasks.'<br>';
    $tasklist = $db_handle->runQuery($sqltasks);
    $tskl = '(';
    foreach($tasklist as $tk){
        $tskl = $tskl.$tk['id'].',';
    }
    $tskl = $tskl.'0)';
    // Get unique row identifiers for task+dev.
    $sql2 = "SELECT CONCAT_WS(';',taskid,developerid) FROM " . $table . " WHERE taskid IN ".$tskl . $filterdev;
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
            <tbody>
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
                    // Developer.
                    $sqldev = "SELECT * FROM developers WHERE id = ".$devid;
                    $devsel = $db_handle->runQuery($sqldev);
                    foreach ($devsel as $dv) {
                        $devname = $dv['firstname'].' '.$dv['lastname'];
                    }
                    $taskname = $epictitle.': '.$systemname.': '.$tasktitle.': '.$devname;
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
