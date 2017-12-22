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
include('includes/head.php');

require_once("dbcontroller.php");
$table = 'weeks';
$db_handle = new DBController();
$sql = "SELECT * from " . $table;
$dev = $db_handle->runQuery($sql);

?>
<header class="pageheader jumbotron text-center">
    <h1 class="display-5">Weeks - Sprint number and Events</h1>
</header>
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
          foreach($dev as $k=>$v) {
          ?>
              <tr class="table-row">
                <td contenteditable="false"><?php echo $dev[$k]["id"]; ?></td>
                <td contenteditable="false"><?php echo $dev[$k]["weekcommencing"]; ?></td>
                <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','sprint','<?php echo $dev[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $dev[$k]["sprint"]; ?></td>
                <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','events','<?php echo $dev[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $dev[$k]["events"]; ?></td>
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