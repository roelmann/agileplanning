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
include('includes/head.php');
?>
<div class="page-wrapper container-fluid page-newdev">
    <?php include('includes/navbar.php');

require_once("dbcontroller.php");
$table = $_GET['t'];

if (isset($_POST['submit'])) {
    $db_handle = new DBController();

    // Get form data, making sure it is valid.
    $taskid = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['task']));
    $devid = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['dev']));
    $weekid = 0;
    $effort = 0;
    $error = '';

    // Check to make sure both fields are entered.
    if ($taskid == '' || $devid == '') {
        // Generate error message.
        echo '<p class="alert alert-danger">ERROR: Please fill in all required fields!</p>';
        echo '<p class="text-danger">* Taskid: '.$taskid.'</p>';
        echo '<p class="text-danger">* Devid: '.$devid.'</p>';
        echo '<p class="alert alert-primary">The page will refresh in 10seconds.
                Or click to <a href="effort.php" class="btn btn-primary">Return to Effort list</a></p>';
        // Redirect back to the view page.
        ?>
        <script type="text/javascript">
            location.replace("effort.php");
        </script>
        <?php

    } else {
        // Save the data to the database.
        $sql = "INSERT " . $table . " SET taskid='$taskid', developerid='$devid', weekid=0, effort=0";
        $dev = $db_handle->executeUpdate($sql);

        // Once saved, redirect back to the view page.
        ?>
        <script type="text/javascript">
            location.replace("effort.php");
        </script>
        <?php
    }
}
include('includes/foot.php');
?>
