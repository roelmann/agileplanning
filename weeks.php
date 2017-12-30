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
echo '<div class="page-wrapper container-fluid page-weeks">';
include('includes/navbar.php');

// Call initial database queries.
require_once("dbcontroller.php");
$table = 'weeks'; // Main table.
$db_handle = new DBController(); // Set up database connection.
$sql = "SELECT * from " . $table;
$week = $db_handle->runQuery($sql);
?>

<!-- Header Title -->
<header class="pageheader jumbotron text-center">
    <h1 class="display-5">Weeks - Sprint number and Events</h1>
</header>

    <!-- Main page content -->
    <div class="main-content">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="table-header" width="10%">ID</th>
                    <th class="table-header">Week Commencing</th>
                    <th class="table-header">Sprint number</th>
                    <th class="table-header">External Events</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($week) > 0) {
                    foreach($week as $k=>$v) {
                    ?>
                        <tr class="table-row">
                            <td contenteditable="false">
                                <?php echo $week[$k]['id']; ?>
                            </td>
                            <td contenteditable="false">
                                <?php echo $week[$k]['weekcommencing']; ?>
                            </td>
                            <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','sprint','<?php echo $week[$k]['id']; ?>')" onClick="showEdit(this);"><?php echo $week[$k]['sprint']; ?></td>
                            <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','events','<?php echo $week[$k]['id']; ?>')" onClick="showEdit(this);"><?php echo $week[$k]['events']; ?></td>
                        </tr>
                    <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <div><p class="alert alert-info">Adding new weeks in bulk should be done via the database directly.<br><br></p></div>
<?php
include('includes/foot.php');
?>
