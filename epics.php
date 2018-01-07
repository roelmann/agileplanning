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
echo '<div class="page-wrapper container-fluid page-epics">';
include('includes/navbar.php');

// Call initial database queries.
require_once("dbcontroller.php");
$table = $tbl_epic; // Main table.
$db_handle = new DBController(); // Set up database connection.
?>

<!-- Header Title -->
<header class="pageheader jumbotron text-center">
    <h1 class="display-5">Epics</h1>
    <button class="btn btn-primary" data-toggle="collapse" data-target="#filtercontrols"><i class="fa fa-filter">&nbsp;</i>Filters</button>
</header>

    <?php
        $orderby=array('id','systemid','deadline','title');
        echo filtercontrols($db_handle, $bydate = FALSE, $bydev = FALSE, $byprog = FALSE, $byepic = FALSE, $byus = FALSE, $byadv = TRUE, $orderby);

        // Process form.
        // If advanced filter set, this overrides others.
        if (isset($_POST['advancedfilter']) && $_POST['advancedfilter'] !== '') {
            $conditioninput = $_POST['advancedfilter'];
            if (strpos($conditioninput,';')) {
                $conditioninputlist = explode($conditioninput,';');
                $condition = $conditioninputlist[0];
            } else {
                $condition = $conditioninput;
            }
        } else {
            // Filter dropdowns.
            if (isset($_POST['filterby'])) {
                if ($_POST['filterby'] == 'none') {
                    $filterby = "> 0";
                } else {
                    $filterby = "=".$_POST['filterby'];
                }
            } else {
                $filterby = "> 0";
            }
            // Order by options.
            if (isset($_POST['orderby'])) {
                $orderby = $_POST['orderby'];
            } else {
                $orderby = 'id';
            }
            // Create condition where not manual advanced.
            $condition = "WHERE systemid " . $filterby . " ORDER BY " . $orderby;
        }
        $sql = "SELECT * from " . $table . " " . $condition;
        echo '<div class="row alert alert-dark">'.$sql.'</div>';
        // Ensure there are rows to report from search.
        if ($db_handle->numRows($sql) < 1 ) {
            echo 'There are no results for that filter.<br>';
            $sql = "SELECT * from " . $table; // Override filters if no results from filtered search.
        }
        $epics = $db_handle->runQuery($sql); // Get epics list, including filters where set.
        ?>
    </div>
    <!-- End filter section -->

    <!-- Main page content -->
    <div class="main-content">
        <table class="table table-striped">
            <!-- Table header row -->
            <thead class="thead-dark sticky-top">
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
            <!-- Main body of table -->
            <tbody>
                <?php
                if (count($epics) >0) {
                    foreach($epics as $k=>$v) { // Loop through epics from filtered search.
                        // Get system names from system id.
                        $sql = "SELECT system FROM system WHERE id = ".$epics[$k]["systemid"];
                        $system = $db_handle->runQuery($sql);
                        foreach ($system as $s) {
                            $systemname=$s['system'];
                        }
                        ?>
                        <tr class="table-row" width="100%">
                            <td contenteditable="false">
                                <?php echo $epics[$k]["id"]; ?> <!-- ID -->
                            </td>
                            <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>', 'systemid','<?php echo $epics[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $epics[$k]["systemid"].':'.$systemname; ?></td>
                            <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','title','<?php echo $epics[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $epics[$k]["title"]; ?></td>
                            <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','description','<?php echo $epics[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $epics[$k]["description"]; ?></td>
                            <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','deadline','<?php echo $epics[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $epics[$k]["deadline"]; ?></td>
                            <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','notes','<?php echo $epics[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $epics[$k]["notes"]; ?></td>
                            <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','icon','<?php echo $epics[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $epics[$k]["icon"]; ?></td>
                        </tr>
                    <?php
                    } // End ForEach in main body of table.
                    ?>
                    <tr>
                        <td colspan=7> <!-- Spacer row -->
                            <p>Note: If editing the System in an existing epic, although the system name is displayed, the value to be entered is the system id number found on the systems page. For new Epics, the dropdown will handle this for you.<br><br></p>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <!-- New Epic form -->
                <tr>
                    <th colspan=7>Add a new Epic</th>
                </tr>
                <form action="newepic.php?t=epic" method="post">
                    <tr class="table-row">
                        <td>
                            <input type="submit" name="submit" value="&#xf0c7;" class="fa">
                        </td>
                        <!-- System -->
                        <td>*
                            <select name="systemid">
                                <?php foreach ($systems as $sys) { ?> <!-- Systems drop down -->
                                    <option value="<?php echo $sys['id'];?>"> <?php echo $sys['system'];?> </option>
                                <?php } ?>
                            </select>
                        </td>
                        <!-- Title -->
                        <td>*
                            <input type="text" name="title" value="" />
                        </td>
                        <!-- Description -->
                        <td>
                            <textarea name="description" rows="5" cols="30"> </textarea>
                        </td>
                        <!-- Deadline date -->
                        <td>*
                            <input type="date" name="deadline" />
                        </td>
                        <!-- Notes -->
                        <td>
                            <textarea name="notes" rows="5" cols="20"> </textarea>
                        </td>
                        <!-- Fontawesome Icon -->
                        <td>
                            <input type="text" name="icon" value="" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <!-- Submit button -->
                            <input type="submit" name="submit" value="&#xf0c7;" class="fa">
                        </td>
                    </tr>
                </form>

            </tbody>
        </table>
    </div>
<?php
include('includes/foot.php');
?>
