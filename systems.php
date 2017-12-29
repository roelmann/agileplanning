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
echo '<div class="page-wrapper container-fluid page-systems">';
include('includes/navbar.php');

// Call initial database queries.
require_once("dbcontroller.php");
$table = 'system'; // Main table.
$db_handle = new DBController(); // Set up database connection.
$sql = "SELECT * from " . $table;
$sys = $db_handle->runQuery($sql);
?>

<!-- Header Title -->
<header class="pageheader jumbotron text-center">
    <h1 class="display-5">Systems</h1>
</header>

    <!-- Main page content -->
    <div class="main-content">
        <table class="table table-striped">
            <!-- Table header row -->
            <thead class="thead-dark">
                <tr>
                    <th class="table-header" width="10%">ID</th>
                    <th class="table-header">System</th>
                    <th class="table-header">Product Owner</th>
                    <th class="table-header">Primary Customer Contact</th>
                </tr>
            </thead>
            <!-- Main body of table -->
            <tbody>
                <?php
                foreach($sys as $k=>$v) {
                ?>
                    <tr class="table-row">
                        <td contenteditable="false">
                            <?php echo $sys[$k]["id"]; ?>
                        </td>
                        <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>', 'system','<?php echo $sys[$k]["id"]; ?>')" onClick="showEdit(this);">
                            <?php echo $sys[$k]["system"]; ?>
                        </td>
                        <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','productowner','<?php echo $sys[$k]["id"]; ?>')" onClick="showEdit(this);">
                            <?php echo $sys[$k]["productowner"]; ?>
                        </td>
                        <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','customercontact','<?php echo $sys[$k]["id"]; ?>')" onClick="showEdit(this);">
                            <?php echo $sys[$k]["customercontact"]; ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan=5>
                        <br><br>
                    </td>
                </tr>
                <tr>
                    <th colspan=5>Add a new system</th>
                </tr>
                <form action="newsys.php?t=system" method="post">
                    <tr class="table-row">
                        <td>
                            <input type="submit" name="submit" value="&#xf0c7;" class="fa">
                        </td>
                        <td>*
                            <input type="text" name="system" value="" />
                        </td>
                        <td>
                            <input type="text" name="productowner" value="" />
                        </td>
                        <td>
                            <input type="text" name="customercontact" value="" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="&#xf0c7;" class="fa">
                        </td>
                    </tr>
                </form>

            </tbody>
        </table>
    </div>
<?php
include('includes/foot.php');
?>
