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
echo '<div class="page-wrapper container-fluid page-newepic">';
include('includes/navbar.php');

// Call initial database queries.
require_once("dbcontroller.php");
$table = $_GET['t'];

if (isset($_POST['submit'])) {
    $db_handle = new DBController();
    // Get form data, making sure it is valid.
    $systemid = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['systemid']));
    $sql = "SELECT system FROM system WHERE id = ".$systemid;
    $system = $db_handle->runQuery($sql);
    foreach ($system as $s) {
        $systemname=$s['system'];
    }
    $title = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['title']));
    $description = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['description']));
    $deadline = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['deadline']));
    $notes = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['notes']));
    $icon = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['icon']));
    $error = '';

    // Check to make sure both fields are entered.
    if ($systemid == '' || $title == '') {
        // Generate error message.
        echo '<p class="alert alert-danger">ERROR: Please fill in all required fields!</p>';
        echo '<p class="text-danger">* System: '.$systemid.':'.$systemname.'</p>';
        echo '<p class="text-danger">* Title: '.$title.'</p>';
        echo '<p>Description: '.$description.'</p>';
        echo '<pclass="text-danger">* Deadline: '.$deadline.'</p>';
        echo '<p>Notes: '.$notes.'</p>';
        echo '<p>icon: '.$icon.'</li>';
        echo '<p class="alert alert-primary">The page will refresh in 10seconds.
                Or click to <a href="epics.php" class="btn btn-primary">Return to Epics list</a></p>';
        // Redirect back to the view page.
        ?>
        <script type="text/javascript">
            location.replace("epics.php");
        </script>
        <?php

    } else {
        // Save the data to the database.
        $sql = "INSERT " . $table . " SET systemid='$systemid', title='$title', description='$description', deadline='$deadline', notes='$notes', icon='$icon'";
        $dev = $db_handle->executeUpdate($sql);

        // Once saved, redirect back to the view page.
        ?>
        <script type="text/javascript">
            location.replace("epics.php");
        </script>
        <?php
    }
}
include('includes/foot.php');
?>
