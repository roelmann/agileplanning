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
echo '<div class="page-wrapper container-fluid page-repadv">';
include('includes/navbar.php');

// Call initial database queries.
require_once("dbcontroller.php");
$db_handle = new DBController();

$table = $tbl_eff;
$orderby = array();
?>

<header class="pageheader jumbotron text-center">
    <h1 class="display-5">Reporting - By Various filters</h1>
    <p>To edit any of these values, please go to the appropriate editable page</p>
    <button class="btn btn-primary" data-toggle="collapse" data-target="#filtercontrols"><i class="fa fa-filter">&nbsp;</i>Filters</button>
<!--    <button class="btn btn-info" data-toggle="collapse" data-target="#showsql"><i class="fa fa-code">&nbsp;</i>Show SQL generated</button> -->
</header>

    <?php
    echo filtercontrols($db_handle, $bydate = TRUE, $bydev = TRUE, $byprog = TRUE, $byepic = TRUE, $byus = TRUE, $byadv = FALSE, $orderby);

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
    $tasknamelist = taskslist ($db_handle, $tbl_eff, $tbl_bklg, $filtertasks);

//    echo '<div id="showsql" class="sql alert alert-info collapse">';
//    echo $sqlweeks.'<br>';
//    echo $sqltasks;
//    echo '</div>';

    ?>

    <div class="main-content">
        <table class="table table-striped datatable floatThead-table">
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
