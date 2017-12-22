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
$table = 'epic';
$db_handle = new DBController();
$sql2 = "SELECT * from system";
$systems = $db_handle->runQuery($sql2);
?>
<header class="pageheader jumbotron text-center">
    <h1 class="display-5">Epics</h1>
</header>
    <div class="filtercontrols">
        <form action="" method="post">
            <label>Filter by System: </label>
            <select name="filterby">
                <?php foreach ($systems as $sys) { ?>
                    <option value="<?php echo $sys['id'];?>"> <?php echo $sys['system'];?> </option>
                <?php } ?>
            </select>
            <label>Order by: </label>
            <select name="orderby">
                <option value="id">ID</option>
                <option value="system">System</option>
                <option value="title">Title</option>
                <option value="deadline">Deadline</option>
            </select>
            <label>Advanced: </label>
            <input type="text" name="advancedfilter">
            <input type="submit" value="Go">
        </form>
    </div>
    <?php
        $sql = "SELECT * from " . $table . " WHERE systemid='2'";
        $epics = $db_handle->runQuery($sql);
    ?>
    <div class="main-content">
       <table class="table table-striped">
          <thead class="thead-dark">
              <tr>
                <th class="table-header" width="10%">ID</th>
                <th class="table-header">System</th>
                <th class="table-header">Title</th>
                <th class="table-header">Description</th>
                <th class="table-header">Deadline</th>
                <th class="table-header">Notes</th>
                <th class="table-header">Icon</th>
              </tr>
          </thead>
          <tbody>
          <?php
          foreach($epics as $k=>$v) {
            $sql = "SELECT system FROM system WHERE id = ".$epics[$k]["systemid"];
            $system = $db_handle->runQuery($sql);
            foreach ($system as $s) {
                $systemname=$s['system'];
            }

          ?>
              <tr class="table-row">
                <td contenteditable="false"><?php echo $epics[$k]["id"]; ?></td>
                <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>', 'systemid','<?php echo $epics[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $epics[$k]["systemid"].':'.$systemname; ?></td>
                <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','title','<?php echo $epics[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $epics[$k]["title"]; ?></td>
                <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','description','<?php echo $epics[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $epics[$k]["description"]; ?></td>
                <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','deadline','<?php echo $epics[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $epics[$k]["deadline"]; ?></td>
                <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','notes','<?php echo $epics[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $epics[$k]["notes"]; ?></td>
                <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','icon','<?php echo $epics[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $epics[$k]["icon"]; ?>&nbsp;&nbsp;<i class="fa fa-2x fa-<?php echo $epics[$k]["icon"]; ?>"></i></td>
              </tr>
        <?php
        }
        ?>
        <tr><td colspan=7>Note: If editing the System in an existing epic, although the system name is displayed, the value to be entered is the system id number found on the systems page. For new Epics, the dropdown will handle this for you.<br><br></td></tr>
        <tr>
            <th colspan=7>Add a new Epic</th>
        </tr>
        <form action="newepic.php?t=epic" method="post">
            <tr class="table-row">
                <td><input type="submit" name="submit" value="&#xf0c7;" class="fa"></td>
                <td>*
                    <select name="systemid">
                    <?php foreach ($systems as $sys) { ?>
                        <option value="<?php echo $sys['id'];?>"> <?php echo $sys['system'];?> </option>
                    <?php } ?>
                    </select>
                </td>
                <td>*<input type="text" name="title" value="" /></td>
                <td><input type="text" name="description" value="" /></td>
                <td><input type="text" name="deadline" value="" /></td>
                <td><input type="text" name="notes" value="" /></td>
                <td><input type="text" name="icon" value="" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="&#xf0c7;" class="fa"></td>
            </tr>
        </form>

          </tbody>
        </table>
    </div>
<?php
include('includes/foot.php');
?>