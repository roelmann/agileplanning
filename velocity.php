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
echo '<div class="page-wrapper container-fluid page-velocity">';
include('includes/navbar.php');

// Call initial database queries.
require_once("dbcontroller.php");
$table = 'velocity';
$table1 = 'weeks';
$table2 = 'developers';
$db_handle = new DBController();
$sqldevlist = "SELECT * from " . $table2;
$devlist = $db_handle->runQuery($sqldevlist);
?>

<header class="pageheader jumbotron text-center">
    <h1 class="display-5">Planned velocity</h1>
</header>

    <div class="filtercontrols container">
        <form action="" method="post" class="row">
            <div class="filterbydate col">
                <label><strong>Start Date: </strong></label><br>
                <input type="date" name="startdate" min="2018-01-01">
            </div>
            <div class="filterbydate col">
                <label><strong>End Date: </strong></label><br>
                <input type="date" name="enddate" max="2024-12-31">
            </div>
<!--
            <div class="filterbydate col">
                <label><strong>OR Number of weeks: </strong></label><br>
                <input type="number" name="numberofweeks" min="0" max="250">
            </div>
-->
            <div class="filterbydev col">
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
        $startdate = '2018-01-01';
    }
    if (isset($_POST['enddate']) && $_POST['enddate'] !== '') {
        $enddate = $_POST['enddate'];
    } else {
        $enddate = '2048-01-01';
    }
    if (isset($_POST['dev']) && $_POST['dev'] !== 'all') {
        $filterdev = " WHERE id = ". $_POST['dev'];
    } else {
        $filterdev = "";
    }
    $sql1 = "SELECT * from " . $table1 . " WHERE weekcommencing >= '" . $startdate . "' AND weekcommencing <= '" . $enddate . "'";
    $weeks = $db_handle->runQuery($sql1);
    $sql2 = "SELECT * from " . $table2 . $filterdev;
    $devs = $db_handle->runQuery($sql2);
    ?>

    <div class="main-content">
        <table class="table table-striped">
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
