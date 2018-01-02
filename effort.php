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
echo '<div class="page-wrapper container-fluid page-effort">';
include('includes/navbar.php');

// Call initial database queries.
require_once("dbcontroller.php");
$table = 'effort';
$table1 = 'weeks';
$table2 = 'developers';
$table3 = 'task';
$db_handle = new DBController();
$sqldevlist = "SELECT * from " . $table2; // Get full list for drop down for filter.
$devlist = $db_handle->runQuery($sqldevlist);
$sqltasklist = "SELECT * from " . $table3; // Get full list for drop down for filter.
$tasklist = $db_handle->runQuery($sqltasklist);
?>

<header class="pageheader jumbotron text-center">
    <h1 class="display-5">Planned Effort</h1>
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

            <div class="filterby filterbytask col">
                <label><strong>Task: </strong></label><br>
                    <select name="task">
                    <option value="all">All</option>
                    <?php foreach ($tasklist as $tsk) {
                        $sqlepic = "SELECT systemid,title FROM epic WHERE id = ".$tsk["epicid"];
                        $epic = $db_handle->runQuery($sqlepic);
                        foreach ($epic as $e) {
                            $sysid=$e['systemid'];
                            $epictitle=$e['title'];
                        }
                        $sqlsystem = "SELECT system FROM system WHERE id = ".$sysid;
                        $system = $db_handle->runQuery($sqlsystem);
                        foreach ($system as $s) {
                            $systemname = $s['system'];
                        }
                        ?>
                        <option value="<?php echo $tsk['id'];?>">
                            <?php echo $epictitle.' '.$systemname.' '.$tsk['title'];?>
                        </option>
                    <?php } ?>
                    </select>
            </div>

            <div class="filterby filterbydev col">
                <label><strong>Developer: </strong></label><br>
                    <select name="dev">
                    <option value="all">All</option>
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
        $startdate = date('Y-m-d', time() - 1814400);
    }
    if (isset($_POST['enddate']) && $_POST['enddate'] !== '') {
        $enddate = $_POST['enddate'];
    } else {
        $enddate = date('Y-m-d', time() + 1814400);
    }
    if (isset($_POST['dev']) && $_POST['dev'] !== 'all') {
        $filterdev = " WHERE developerid = ". $_POST['dev']; // Apply filter.
    } else {
        $filterdev = " WHERE developerid > 0"; // Or select all.
    }
    if (isset($_POST['task']) && $_POST['task'] !== 'all') {
        $filtertask = " AND taskid = ". $_POST['task']; // Apply filter.
    } else {
        $filtertask = " AND taskid > 0"; // Or select all.
    }

    $sql1 = "SELECT * from " . $table1 . " WHERE weekcommencing >= '" . $startdate . "' AND weekcommencing <= '" . $enddate . "'";
    echo $sql1.'<br>';
    $weeks = $db_handle->runQuery($sql1);
    $sql2 = "SELECT CONCAT_WS(';',taskid,developerid) FROM " . $table . $filterdev . $filtertask ;
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
                    <th class="table-header" colspan=2>Week Commencing</th>
                        <?php
                        foreach ($weeks as $k=>$v) {
                            $event = str_replace('<br>','',$weeks[$k]['events']);
                            if (strlen($event) > 0) {
                        ?>
                                <th class="table-header weekheader" >
                                    <div class="rotated">
                                        <?php echo $weeks[$k]['weekcommencing']; ?>
                                        <a href="#" data-toggle="tooltip" title=" <?php echo $event; ?>">*</a>
                                    </div>
                                </th>
                            <?php
                            } else {
                            ?>
                                <th class="table-header weekheader" >
                                    <div class="rotated">
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
                    if($db_handle->numRows($sqltask)>0){
                    $tasksel = $db_handle->runQuery($sqltask);

                    foreach ($tasksel as $k=>$ta) {
                        $epicid = $tasksel[$k]['epicid'];
                        $tasktitle = $tasksel[$k]['title'];
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
                    // Developer.
                    $sql = "SELECT * FROM developers WHERE id = ".$devid;
                    if ($db_handle->numrows($sql) < 1) {
                        $devname = '';
                    } else {
                        $devdetails = $db_handle->runQuery($sql);
                        foreach ($devdetails as $d) {
                            $devname = $d['id'].':'.$d['firstname'].' '.$d['lastname'];
                        }
                    }
                    ?>
                    <tr class="table-row">
                        <td contenteditable="false"><?php echo $taskname; ?></td>
                        <td contenteditable = "false"><?php echo $devname; ?></td>
                        <?php
                        foreach($weeks as $kw=>$vw) {
                            $sql = "SELECT * from " . $table . " WHERE taskid= ".$taskid." AND weekid = ".$weeks[$kw]['id'] . " AND developerid = ".$devid;
                            if ($db_handle->numrows($sql) < 1) {
                                $sqlinsert = "INSERT INTO ".$table." (taskid,developerid,weekid,effort) VALUES (".$taskid.",".$devid.",".$weeks[$kw]['id'].",0)";
                                $db_handle->executeUpdate($sqlinsert);
                            }
                            $efftlist = $db_handle->runQuery($sql);
                            $efftid=$efftlist[0]['id'];
                            $eft=$efftlist[0]['effort'];
                            $class='';
                            if ($eft > 0) {
                                $class = 'text-success font-weight-bold';
                            }
                            ?>
                            <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','effort','<?php echo $efftid; ?>')" onClick="showEdit(this);" class="<?php echo $class; ?>"><?php echo $eft; ?></td>
                        <?php
                        }
                        ?>
                    </tr>
                <?php
                    }
                }
                ?>
                <tr>
                    <td colspan=250> <!-- Spacer row -->
                        <br><br>
                    </td>
                </tr>
                <!-- New Effort form -->
                <tr>
                    <th colspan=250><p>Add a new effort row - Add the Task and Developer to add a row to the main table to edit effort by week.</p></th>
                </tr>
                <form action="neweffort.php?t=effort" method="post">
                    <tr class="table-row">
                        <td colspan = 250>*
                            <label><strong>Task: </strong></label>
                            <select name="task">
                                <option value="all">All</option>
                                <?php
                                foreach ($tasklist as $tsk) {
                                    $type = $tsk["type"];
                                    $title = $tsk['title'];
                                    $sqlepic = "SELECT systemid,title FROM epic WHERE id = ".$tsk["epicid"];
                                    $epic = $db_handle->runQuery($sqlepic);
                                    foreach ($epic as $e) {
                                        $sysid=$e['systemid'];
                                        $epictitle=$e['title'];
                                    }
                                    $sqlsystem = "SELECT system FROM system WHERE id = ".$sysid;
                                    $system = $db_handle->runQuery($sqlsystem);
                                    foreach ($system as $s) {
                                        $systemname = $s['system'];
                                    }
                                    ?>
                                    <option value="<?php echo $tsk['id'];?>">
                                        <?php echo $type.' '.$epictitle.' '.$systemname.' '.$title;?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="table-row">
                        <td colspan = 250>*
                            <label><strong>Developer: </strong></label>
                            <select name="dev">
                                <option value="all">All</option>
                                <?php foreach ($devlist as $d) { ?>
                                    <option value="<?php echo $d['id'];?>">
                                        <?php echo $d['firstname'].' '.$d['lastname'];?>
                                    </option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr class="table-row">
                        <td>
                            <input type="submit" name="submit" value="&#xf0c7;" class="fa">
                        </td>
                    </tr>
                </form>
            </tbody>
        </table>
        <p><br><br></p>
    </div>
<?php
include('includes/foot.php');
?>
