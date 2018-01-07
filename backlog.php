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
echo '<div class="page-wrapper container-fluid page-backlog">';
include('includes/navbar.php');

// Call initial database queries.
require_once("dbcontroller.php");
$table = 'task'; // Main table.
$db_handle = new DBController(); // Set up database connection.
?>

<!-- Header Title -->
<header class="pageheader jumbotron text-center">
    <h1 class="display-5">Backlog</h1>
    <button class="btn btn-primary" data-toggle="collapse" data-target="#filtercontrols"><i class="fa fa-filter">&nbsp;</i>Filters</button>
</header>
        <?php
        $orderby=array('id','businessvalue','epicid','parent','deadline');
        echo filtercontrols($db_handle, $bydate = FALSE, $bydev = FALSE, $byprog = TRUE, $byepic = TRUE, $byus = TRUE, $byadv = TRUE, $orderby);

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
            // Tasks.
            $fepic = $fpar = $fcom = '';

            if (isset($_POST['epic'])) {$fepic = $_POST['epic'];}
            if (isset($_POST['parent'])) {$fpar = $_POST['parent'];}
            if (isset($_POST['completion'])) {$fcom = $_POST['completion'];}
            $filterby = filterprocess_tasks ($db_handle, $fepic, $fpar, $fcom);

            // Order by dropdown.
            if (isset($_POST['orderby'])) {
                $orderby = $_POST['orderby'];
            } else {
                $orderby = 'id'; // Default order by id.
            }
            $condition = $filterby . " ORDER BY " . $orderby; // Set search condition.
        }
        // Open database connection.
        $sql = "SELECT * from " . $table . " WHERE id>0 " . $condition;
        echo '<div class="row alert alert-dark">'.$sql.'</div>';
        // Ensure there are rows to report from search.
        if ($db_handle->numRows($sql) < 1 ) {
            $sql = "SELECT * from " . $table; // Override filters if no results from filtered search.
        }
        if ($db_handle->numRows($sql) < 1 ) {
            $sql = "SELECT * from " . $table; // Override filters if no results from filtered search.
        }
        $backlog = $db_handle->runQuery($sql); // Get backlog list, including filters where set.
        ?>
    </div>
    <!-- End filter section -->

    <!-- Main page content -->
    <div class="main-content">
        <table class="table table-striped sticky-header">
            <!-- Table header row -->
            <thead class="thead-dark">
                <tr>
                    <th class="table-header" width="4em">ID</th>
                    <th class="table-header" width="5em">Type</th>
                    <th class="table-header" width="25em">EpicID</th>
                    <th class="table-header" width="5em">Parent U/S</th>
                    <th class="table-header">Title</th>
                    <th class="table-header">Progress</th>
                    <th class="table-header">Description</th>
                    <th class="table-header">Deadline</th>
                    <th class="table-header">Notes</th>
                    <th class="table-header" colspan=6>Business Value<br>+M/+Re/+Ri/-Dup/+Ddown</th>
                </tr>
            </thead>
            <!-- Main body of table -->
            <tbody>
                <?php
                if (count($backlog) > 0) {
                    // Calculate business value from other fields and add to backlog array.
                    foreach($backlog as $k=>$v) {
                        $backlog[$k]["businessvalue"] = $backlog[$k]["MoSCoW"]+$backlog[$k]["Releasability"]+$backlog[$k]["Risk"]+$backlog[$k]["DependenciesDownstream"]-$backlog[$k]["DependenciesUpstream"];
                    }

                    foreach($backlog as $k=>$v) { // Loop through all backlog items from filtered search.
                        // Get epic title from epicid in backlog array.
                        $sql = "SELECT title FROM epic WHERE id = ".$backlog[$k]["epicid"];
                        $epic = $db_handle->runQuery($sql);
                        foreach ($epic as $e) {
                            $epictitle=$e['title'];
                        }
                        ?>
                        <!-- Create each row from array -->
                        <tr class="table-row" width="100%">
                            <td contenteditable="false">
                                <?php echo $backlog[$k]["id"]; ?> <!-- ID -->
                            </td>
                            <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>', 'type','<?php echo $backlog[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $backlog[$k]["type"]; ?></td>
                            <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>', 'epicid','<?php echo $backlog[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $backlog[$k]["epicid"].': '.$epictitle; ?></td>
                            <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>', 'parent','<?php echo $backlog[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $backlog[$k]["parent"]; ?></td>
                            <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','title','<?php echo $backlog[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $backlog[$k]["title"]; ?></td>
                            <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','completion','<?php echo $backlog[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $backlog[$k]["completion"]; ?></td>
                            <td class="maxwidth100" contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','description','<?php echo $backlog[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $backlog[$k]["description"]; ?></td>
                            <td contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','deadline','<?php echo $backlog[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $backlog[$k]["deadline"]; ?></td>
                            <td class="maxwidth32" contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','notes','<?php echo $backlog[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $backlog[$k]["notes"]; ?></td>
                            <td class="maxwidth32" contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>','MoSCoW','<?php echo $backlog[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $backlog[$k]["MoSCoW"]; ?></td>
                            <td class="maxwidth32" contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>', 'Releasbility','<?php echo $backlog[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $backlog[$k]["Releasability"]; ?></td>
                            <td class="maxwidth32" contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>', 'Risk','<?php echo $backlog[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $backlog[$k]["Risk"]; ?></td>
                            <td class="maxwidth32" contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>', 'DependenciesUpstream','<?php echo $backlog[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $backlog[$k]["DependenciesUpstream"]; ?></td>
                            <td class="maxwidth32" contenteditable="true" onBlur="saveToDatabase(this, '<?php echo $table; ?>', 'DependenciesDownstream','<?php echo $backlog[$k]["id"]; ?>')" onClick="showEdit(this);"><?php echo $backlog[$k]["DependenciesDownstream"]; ?></td>
                            <td class="maxwidth32" contenteditable="false"><?php echo '<strong>'.$backlog[$k]["businessvalue"].'</strong>'; ?></td>
                        </tr>
                    <?php
                    } // End ForEach in main body of table.
                    ?>
                    <tr> <!-- Spacer row -->
                        <td colspan=15>
                            <br><br>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <!-- New task/user story form -->
                <tr>
                    <th colspan=15>Add a new Task or User Story</th>
                </tr>
                <form action="newtask.php?t=task" method="post">
                    <tr class="table-row">
                        <!-- Submit button -->
                        <td>
                            <input type="submit" name="submit" value="&#xf0c7;" class="fa">
                        </td>
                        <!-- Type -->
                        <td>
                            <select name="type">
                                <option value="UserStory">User Story</option>
                                <option value="Task">Task</option>
                                <option value="SubTask">Sub-Task</option>
                            </select>
                        </td>
                        <!-- Epic ID -->
                        <td>
                            <select name="epicid"> <!-- Epics drop down list -->
                            <?php foreach ($epics as $e) { ?>
                                <option value="<?php echo $e['id'];?>"> <?php echo $e['id'].':'.$e['title'];?> </option>
                            <?php } ?>
                            </select>
                        </td>
                        <!-- Parent -->
                        <td>
                            <select name="parent"> <!-- Parent drop down list -->
                                <option value=0>No Parent UserStory</option>
                                <?php foreach ($userstories as $b) { ?>
                                    <option value="<?php echo $b['id'];?>"> <?php echo $b['id'].':'.$b['title'];?> </option>
                                <?php } ?>
                            </select>
                        </td>
                        <!-- Title -->
                        <td>
                            <input type="text" name="title" value="" />
                        </td>
                        <!-- Progress -->
                        <td>
                            <select name="completion">
                                <option value="ToDo">ToDo: To Do</option>
                                <option value="InDev">InDev: In Development</option>
                                <option value="InDevTest">InDevTest: Inhouse Development Testing</option>
                                <option value="InUserTest">InUserTest: Released for User Testing</option>
                                <option value="Released">Released: Live</option>
                            </select>
                        </td>
                        <!-- Description -->
                        <td>
                            <textarea name="description" rows="5" cols="30"> </textarea>
                        </td>
                        <!-- Deadline -->
                        <td>
                            <input type="text" name="deadline" value="" class="maxwidth100"/>
                        </td>
                        <!-- Notes -->
                        <td>
                            <textarea name="notes" rows="5" cols="20"> </textarea>
                        </td>
                        <!-- Business Value -->
                        <td colspan=6>
                            <input type="text" name="MoSCoW" value="" maxlength="2" class="maxwidth28"/>
                            <input type="text" name="Releasability" value="" maxlength="2" class="maxwidth28"/>
                            <input type="text" name="Risk" value="" maxlength="2" class="maxwidth28"/>
                            <input type="text" name="DependenciesUpstream" value="" maxlength="2" class="maxwidth28"/>
                            <input type="text" name="DependenciesDownstream" value="" maxlength="2" class="maxwidth28"/>&nbsp;<input type="submit" name="submit" value="&#xf0c7;" class="fa">
                        </td>
                    </tr>
                </form>

            </tbody>
        </table>
    </div>
<?php
include('includes/foot.php');
?>
