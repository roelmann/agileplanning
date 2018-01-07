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
echo '<div class="page-wrapper container-fluid page-velocity">';
include('includes/navbar.php');

// Call initial database queries.
require_once("dbcontroller.php");
$table = $tbl_vel;
$db_handle = new DBController();
$orderby = array();
?>

<header class="pageheader jumbotron text-center">
    <h1 class="display-5">Planned velocity</h1>
    <button class="btn btn-primary" data-toggle="collapse" data-target="#filtercontrols"><i class="fa fa-filter">&nbsp;</i>Filters</button>
</header>
    <?php
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
    // Get developers.
    $devs = devslist ($db_handle, $tbl_dev, $devidsel);
    ?>

    <div class="main-content">
        <table class="table table-striped sticky-header">
            <thead class="thead-dark">
                <tr>
                    <th class="table-header">Week Commencing</th>
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
                    <th class="table-header">Sprint</th>
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
            foreach($devs as $k=>$v) {
                $name = $devs[$k]["firstname"].' '.$devs[$k]["lastname"];
            ?>
                <tr class="table-row">
                    <td contenteditable="false"><i class="fa fa-<?php echo $devs[$k]["icon"]; ?>"></i><?php echo $name; ?></td>

                    <?php
                    foreach($weeks as $kw=>$vw) {
                        $db_handle = new DBController();
                        $sql = "SELECT * from " . $table . " WHERE developerid= ".$devs[$k]['id']." AND weekid= ".$weeks[$kw]['id'];
                        if ($db_handle->numrows($sql) < 1) {
                            $sqlinsert = "INSERT INTO ".$table." (developerid,weekid,plannedvelocity) VALUES (".$devs[$k]['id'].",".$weeks[$kw]['id'].",0)";
                            $db_handle->executeUpdate($sqlinsert);
                        }
                        $vel = $db_handle->runQuery($sql);
                        $velid=$vel[0]['id'];
                        $pv=$vel[0]['plannedvelocity'];
                    ?>

                        <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','plannedvelocity','<?php echo $velid; ?>')" onClick="showEdit(this);"><?php echo $pv; ?></td>
                    <?php
                    } ?>
                </tr>
            <?php
            }

        ?>

          </tbody>
        </table>
    </div>
<?php
include('includes/foot.php');
?>
