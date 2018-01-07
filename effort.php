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
// Get head and navbar.
include('includes/head.php');
echo '<div class="page-wrapper container-fluid page-effort">';
include('includes/navbar.php');

// Call initial database queries.
require_once("dbcontroller.php");
$table = $tbl_eff;
$db_handle = new DBController();
$orderby = array();
?>

<header class="pageheader jumbotron text-center">
    <h1 class="display-5">Planned Effort</h1>
    <button class="btn btn-primary" data-toggle="collapse" data-target="#filtercontrols"><i class="fa fa-filter">&nbsp;</i>Filters</button>
</header>

    <?php

    echo filtercontrols($db_handle, $bydate = TRUE, $bydev = TRUE, $byprog = FALSE, $byepic = TRUE, $byus = TRUE, $byadv = FALSE, $orderby);

    // Get filters for weeks for top of table.
    $fsd = $fed = $fspr = $fdev = $fepic = $fpar = $fcom = '';
    if (isset($_POST['startdate'])) {$fsd = $_POST['startdate'];}
    if (isset($_POST['enddate'])) {$fed = $_POST['enddate'];}
    if (isset($_POST['sprintnumber'])) {$fspr = $_POST['sprintnumber'];}
    $startdate = filterprocess_startdate($db_handle, $fsd, $fspr);
    $enddate = filterprocess_enddate($db_handle, $fed, $fspr);
    $filterweeks = " AND weekcommencing >= '" . $startdate . "' AND weekcommencing <= '" . $enddate . "'";
    // Get filters for body of table.
    // Tasks.
    if (isset($_POST['epic'])) {$fepic = $_POST['epic'];}
    if (isset($_POST['parent'])) {$fpar = $_POST['parent'];}
    if (isset($_POST['completion'])) {$fcom = $_POST['completion'];}
    $filtertasks = filterprocess_tasks ($db_handle, $fepic, $fpar, $fcom);
    // Developer.
    if (isset($_POST['dev'])) {$fdev = $_POST['dev'];}
    $filterdev = filterprocess_devs ($db_handle, $fdev);
    if (isset($fdev) && $fdev !== '' && $fdev !== 'all') {
        $devidsel = " id = ".$fdev;
    } else {
        $devidsel = " id > 0";
    }

    // Get weeks.
    $weeks = weekslist ($db_handle, $tbl_wks, $filterweeks);

    // Get tasks to list.
    $tasknamelist = taskslist ($db_handle, $tbl_eff, $tbl_bklg, $filtertasks);    ?>

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
                include ('includes/editingbody.php');
                ?>
                <tr>
                    <td colspan=250> <!-- Spacer row -->
                        <br><br>
                    </td>
                </tr>
                <!-- New Effort form -->
                <tr>
                    <th colspan=250>
                        <p>Add a new effort row - Add the Task and Developer to add a row to the main table to edit effort by week.</p>
                        <p>Note: the filters at the top of the page can be used to restrict the dropdown lists below as well as the reporting in the main body of the table above.</p>
                    </th>
                </tr>
                <form action="neweffort.php?t=effort" method="post">
                    <tr class="table-row">
                        <td colspan = 250>*
                            <label><strong>Task: </strong></label>
                            <select name="task">
                                <option value="all">All</option>
                                <?php
                                $sqltasklist = "SELECT * FROM ".$tbl_bklg." ts WHERE id > 0".$filtertasks;
                                echo "<option>".$sqltasklist."</option>";
                                $tasklist = $db_handle->runQuery($sqltasklist);
                                foreach ($tasklist as $k=>$v) {
                                    $type = $tasklist[$k]['type'];
                                    $title = $tasklist[$k]['title'];
                                    $sqlepic = "SELECT systemid,title FROM epic WHERE id = ".$tasklist[$k]['epicid'];
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
                                    <option value="<?php echo $tasklist[$k]['id'];?>">
                                        <?php echo $type.': '.$epictitle.': '.$systemname.': '.$title;?>
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
                                <?php
                                $devlist = devslist ($db_handle, $tbl_dev, $devidsel);
                                foreach ($devlist as $d) { ?>
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
