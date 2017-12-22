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
$table = 'developers';
$db_handle = new DBController();
$sql = "SELECT * from " . $table;
$dev = $db_handle->runQuery($sql);

?>
<header class="pageheader jumbotron text-center">
    <h1 class="display-5">Developers</h1>
</header>
    <div class="main-content">
       <table class="table table-striped">
          <thead class="thead-dark">
              <tr>
                <th class="table-header" width="10%">ID</th>
                <th class="table-header">Firstname</th>
                <th class="table-header">Lastname</th>
                <th class="table-header">Username</th>
                <th class="table-header">Icon</th>

              </tr>
          </thead>
          <tbody>
          <?php
          foreach($dev as $k=>$v) {
          ?>
              <tr class="table-row">
                <td contenteditable="false"><?php echo $dev[$k]["id"]; ?></td>
                <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>', 'firstname','<?php echo $dev[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $dev[$k]["firstname"]; ?></td>
                <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','lastname','<?php echo $dev[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $dev[$k]["lastname"]; ?></td>
                <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','username','<?php echo $dev[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $dev[$k]["username"]; ?></td>
                <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','icon','<?php echo $dev[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $dev[$k]["icon"]; ?>&nbsp;&nbsp;<i class="fa fa-2x fa-<?php echo $dev[$k]["icon"]; ?>"></i></td>
              </tr>
        <?php
        }
        ?>
        <tr><td colspan=5><br><br></td></tr>
        <tr>
            <th colspan=5>Add a new user</th>
        </tr>
        <form action="newdev.php?t=developers" method="post">
            <tr class="table-row">
                <td><input type="submit" name="submit" value="&#xf0c7;" class="fa"></td>
                <td>*<input type="text" name="firstname" value="" /></td>
                <td>*<input type="text" name="lastname" value="" /></td>
                <td>*<input type="text" name="username" value="" /></td>
                <td><input type="text" name="icon" value="" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="&#xf0c7;" class="fa"></td>
            </tr>
        </form>

          </tbody>
        </table>
    </div>
<?php
include('includes/foot.php');
?>