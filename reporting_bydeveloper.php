<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

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
echo '<div class="page-wrapper container-fluid page-repbydev">';
include('includes/navbar.php');

// Call initial database queries.
require_once("dbcontroller.php");
$table = $tbl_eff;
$orderby = array();
?>

<header class="pageheader jumbotron text-center">
    <h1 class="display-5">Reporting - By Developer</h1>
    <p>To edit any of these values, please go to the appropriate editable page</p>
    <button class="btn btn-primary" data-toggle="collapse" data-target="#filtercontrols"><i class="fa fa-filter">&nbsp;</i>Filters</button>
</header>

    <?php
    $db_handle = new DBController();

    echo filtercontrols($db_handle, $bydate = TRUE, $bydev = TRUE, $byprog = FALSE, $byepic = FALSE, $byus = FALSE, $byadv = FALSE, $orderby);

    // Get filters for weeks for top of table.
    $fsd = $fed = $fspr = $fdev = '';
    if (isset($_POST['startdate'])) {$fsd = $_POST['startdate'];}
    if (isset($_POST['enddate'])) {$fed = $_POST['enddate'];}
    if (isset($_POST['sprintnumber'])) {$fspr = $_POST['sprintnumber'];}
    $startdate = filterprocess_startdate($db_handle, $fsd, $fspr);
    $enddate = filterprocess_enddate($db_handle, $fed, $fspr);
    $filterweeks = " AND weekcommencing >= '" . $startdate . "' AND weekcommencing <= '" . $enddate . "'";
    // Get filters for body of table.
    // Tasks.
    $filtertasks = filterprocess_tasks ($db_handle, 'all', 'all', 'inprogress');
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
    $tasknamelist = taskslist ($db_handle, $tbl_eff, $tbl_bklg, $filtertasks);

    // Get Planned velocities
    $velwkstart = $velwkend = '';
    foreach ($weeks as $k => $v) {
        if ($velwkstart == '') {
            $velwkstart = $weeks[$k]['id'];
        }
        $velwkend = $weeks[$k]['id'];
    }
    $sqlvel = "SELECT * from " .  $tbl_vel . " WHERE weekid >= " . $velwkstart . " AND weekid <= " . $velwkend . $filterdev;
    $velocity = $db_handle->runQuery($sqlvel);


//    echo '<div id="showsql" class="sql alert alert-info collapse">';
//    echo $sqlweeks.'<br>';
//    echo $sqlvel.'<br>';
//    echo $sqltasks;
//    echo '</div>';

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
                $sql = "SELECT * FROM developers WHERE ".$devidsel;
                if ($db_handle->numrows($sql) < 1) {
                    $devname = 'Please select a developer in the Filters panel';
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
                    $sql = "SELECT * from " . $tbl_vel . " WHERE weekid= ".$weeks[$kw]['id'].$filterdev;
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
                        $sql = "SELECT * from " . $table . " WHERE weekid= ".$weeks[$kw]['id'].$filterdev;
                        $eftotallist = $db_handle->runQuery($sql);
                        $eftotal = 0;
                        foreach ($eftotallist as $k=>$v){
                            $eftotal=$eftotal+$eftotallist[$k]['effort'];
                        }
                        $sqlpv = "SELECT plannedvelocity FROM velocity WHERE weekid= ".$weeks[$kw]['id'].$filterdev;
                        $wkvel = $db_handle->runQuery($sqlpv);
                        foreach ($wkvel as $w) {
                            $wkv = $w['plannedvelocity'];
                        }
                        if ($eftotal > $wkv) {
                            $class="table-danger";
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
                include ('includes/reportingbody.php');
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
